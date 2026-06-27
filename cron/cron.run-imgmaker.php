<?php

class SkriptxConGenRunImgMaker
{
    public function init()
    {
        $this->expire_jobs();
        $has_running_jobs = $this->has_running_jobs();
        if (! $has_running_jobs) {
            $this->process_queued_job();
        }
    }

    private function expire_jobs()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_images';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->query(
            $wpdb->prepare(
                "
                UPDATE %i
                SET
                    status_id = %d,
                    completed_at = %s,
                    error_message = %s
                WHERE
                    status_id = %d
                    AND completed_at IS NULL
                    AND started_at IS NOT NULL
                    AND expires_at < %s
                ",
                $table,
                4,
                current_time('mysql'),
                'Failed:Expired',
                2,
                current_time('mysql')
            )
        );
    }

    private function has_running_jobs()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_images';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $row = $wpdb->get_row(
            $wpdb->prepare(
                "
                SELECT *
                FROM %i
                WHERE
                    status_id = %d
                    AND started_at is NOT NULL
                    AND completed_at IS NULL
                    AND job_id IS NOT NULL
                    AND expires_at > %s
                ORDER BY id ASC
                LIMIT 1
                ",
                $table,
                2,
                current_time('mysql')
            )
        );

        return ! empty($row->id);

    }

    private function process_queued_job()
    {
         global $wpdb;

        $table_images    = $wpdb->prefix . 'skriptx_congen_images';
        $table_schedules = $wpdb->prefix . 'skriptx_congen_schedules';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $image_row = $wpdb->get_row(
            $wpdb->prepare(
                "
                SELECT *
                FROM %i
                WHERE status_id = %d
                ORDER BY id ASC
                LIMIT 1
                ",
                $table_images,
                1
            )
        );

        if (empty($image_row)) {
            return;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $schedule = $wpdb->get_row(
            $wpdb->prepare(
                "
                SELECT *
                FROM %i
                WHERE id = %d
                LIMIT 1
                ",
                $table_schedules,
                $image_row->schedule_id
            )
        );

        if (empty($schedule)) {
            $this->fail_job(
                $image_row->id,
                'Failed:Missing schedule'
            );
            return;
        }

        if (empty($schedule->post_id)) {
            $this->fail_job(
                $image_row->id,
                'Failed:Missing post id'
            );
            return;
        }

        $post = get_post($schedule->post_id);

        if (empty($post)) {
            $this->fail_job(
                $image_row->id,
                'Failed:Missing post'
            );
            return;
        }

        if (empty($post->post_title)) {
            $this->fail_job(
                $image_row->id,
                'Failed:Missing post title'
            );
            return;
        }

        if (has_post_thumbnail($post->ID)) {
            $this->complete_job($image_row->id);
            return;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $table_images,
            [
                'status_id'  => 2,
                'started_at' => current_time('mysql'),
            ],
            [
                'id' => $image_row->id,
            ],
            [
                '%d',
                '%s',
            ],
            [
                '%d',
            ]
        );

        $image_url = $this->generate_image($post->post_title);

        if (empty($image_url)) {
            $this->fail_job(
                $image_row->id,
                'Failed:Missing Image Url'
            );
            return;
        }

        $attached = $this->attach_featured_image(
            $image_url,
            $post->ID,
            $post->post_title
        );

        if (! $attached) {

            $this->fail_job(
                $image_row->id,
                'Failed:Unable to attach image'
            );

            return;
        }

        $this->complete_job($image_row->id);

    }

    private function attach_featured_image(
        $image_url,
        $post_id,
        $title
    ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_sideload_image(
            $image_url,
            $post_id,
            $title,
            'id'
        );

        if (is_wp_error($attachment_id)) {
            return false;
        }

        set_post_thumbnail(
            $post_id,
            $attachment_id
        );

        return true;
    }

    private function complete_job($id)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_images';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $table,
            [
                'status_id'    => 3,
                'completed_at' => current_time('mysql'),
            ],
            [
                'id' => $id,
            ],
            [
                '%d',
                '%s',
            ],
            [
                '%d',
            ]
        );
    }

    private function fail_job($id, $message)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_images';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $table,
            [
                'status_id'     => 4,
                'completed_at'  => current_time('mysql'),
                'error_message' => $message,
            ],
            [
                'id' => $id,
            ],
            [
                '%d',
                '%s',
                '%s',
            ],
            [
                '%d',
            ]
        );
    }

    public function generate_image($post_title)
    {

        $secret = get_option(
            'skriptx_congen_secret_key'
        );

        if (empty($secret)) {
            return false;
        }

        if (mb_strlen($post_title) > 500) {
            $post_title = mb_substr($post_title, 0, 500);
        }

        $payload = wp_json_encode([
            'domain' => get_site_url(),
            'title'  => $post_title,
        ]);

        $signature = hash_hmac(
            'sha256',
            $payload,
            $secret
        );

        // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
set_time_limit(0);

        $response = wp_remote_post(
            SKRIPTX_SERVER_ENDPOINT . '/domains/images',
            [
                'timeout' => 180,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Signature'  => $signature,
                ],
                'body'    => $payload,
            ]
        );
        $status_code = wp_remote_retrieve_response_code($response);

        if ($status_code !== 200) {
            return false;
        }

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body(
            $response
        );

        $data = json_decode(
            $body,
            true
        );

        $signature = $data['signature'] ?? '';

        unset($data['signature']);

        $encoded = json_encode($data);

        $expected = hash_hmac(
            'sha256',
            $encoded,
            $secret
        );

        if (! hash_equals($expected, $signature)) {

            return false;
        }

        return $data['image_url'] ?? false;

    }

}
