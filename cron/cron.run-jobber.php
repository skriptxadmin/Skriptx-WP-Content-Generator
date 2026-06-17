<?php

class SkriptxConGenRunJobber
{
    public function init()
    {

        $this->clear_expired_rows();

        $running_rows = $this->get_running_rows();

        if (empty($running_rows)) {
            return;
        }
        foreach ($running_rows as $running_row) {

            $this->process_running_row($running_row);

        }

    }

    public function clear_expired_rows()
    {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';

        $now = current_time('mysql');

        // Fixed: Placed prepare inline inside get_results, changed $schedules_table to %i
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $expired_rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE status_id = 2 AND completed_at IS NULL AND expires_at < %s",
                $schedules_table,
                $now
            )
        );

        if (empty($expired_rows)) {
            return;
        }

        foreach ($expired_rows as $expired_row) {

            $this->set_schedule_error(
                $expired_row,
                'Failed:Expired'
            );

        }

    }

    public function get_running_rows()
    {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';

        // Fixed: Wrapped in prepare inline to avoid string interpolation sniffer warnings
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE status_id = 2 ORDER BY id ASC",
                $schedules_table
            )
        );

    }

    public function process_running_row($running_row)
    {

        if (empty($running_row->job_id)) {

            $this->set_schedule_error(
                $running_row,
                'Failed: Job ID missing'
            );

            return;
        }

        $job = $this->get_job_status(
            $running_row->job_id
        );

        if (empty($job)) {
            return;
        }

        if (($job['status'] ?? '') === 'running') {
            return;
        }

        if (($job['status'] ?? '') === 'failed') {

            $this->set_schedule_error(
                $running_row,
                $job['message'] ?? 'Failed: Job failed'
            );

            return;
        }

        if (($job['status'] ?? '') !== 'completed') {
            return;
        }

        $this->complete_job(
            $running_row,
            $job
        );

    }

    public function get_job_status($job_id)
    {

        $secret = get_option(
            'skriptx_congen_secret_key'
        );

        if (empty($secret)) {
            return false;
        }

        $payload = wp_json_encode([
            'domain' => get_site_url(),
        ]);

        $signature = hash_hmac(
            'sha256',
            $payload,
            $secret
        );

        $url = SKRIPTX_SERVER_ENDPOINT . '/domains/jobs/' . $job_id;

        $response = wp_remote_post(
            $url,
            [
                'timeout' => 60,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Signature'  => $signature,
                ],
                'body'    => $payload,
            ]
        );

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body(
            $response
        );
        $data = json_decode($body, true);
      
        $signature = $data['signature'] ?? '';

        unset($data['signature']);

        $encoded = json_encode($data);

        $expected = hash_hmac(
            'sha256',
            $encoded,
            get_option('skriptx_congen_secret_key')
        );

        if (! hash_equals($expected, $signature)) {

            $this->set_schedule_error(
                $running_row,
                'Failed: Signature mismatch'
            );

            return;
        }

        return $data;

    }

    public function complete_job(
        $running_row,
        $job
    ) {

        $post_id = $this->create_wp_post(
            $job
        );

        if (is_wp_error($post_id)) {

            $this->set_schedule_error(
                $running_row,
                $post_id->get_error_message()
            );

            return;
        }

        $this->set_schedule_success(
            $running_row,
            $post_id
        );

    }

    public function set_schedule_success(
        $running_row,
        $post_id
    ) {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $data = [
            'post_id'       => $post_id,
            'completed_at'  => current_time('mysql'),
            'error_message' => null,
            'status_id'     => 3,
        ];

        $where = [
            'id' => $running_row->id,
        ];

        $wpdb->update(
            $schedules_table,
            $data,
            $where
        );

    }

    public function set_schedule_error(
        $running_row,
        $reason
    ) {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $data = [
            'status_id'     => 4,
            'completed_at'  => current_time('mysql'),
            'error_message' => $reason,
        ];

        $where = [
            'id' => $running_row->id,
        ];

        $wpdb->update(
            $schedules_table,
            $data,
            $where
        );

    }

    public function create_wp_post($data)
    {

        $title = sanitize_text_field(
            $data['post_title'] ?? ''
        );

        $post_content = wp_kses_post(
            $data['post_content'] ?? ''
        );

        $category_name = sanitize_text_field(
            $data['category'] ?? 'AI News'
        );

        $tags = $data['tags'] ?? [];

        /*
     * Ensure aigenerated category exists
     */
        $aigenerated = term_exists(
            'aigenerated',
            'category'
        );

        if (! $aigenerated) {
            $aigenerated = wp_insert_term(
                'aigenerated',
                'category',
                [
                    'slug' => 'aigenerated',
                ]
            );
        }

        $category_ids = [];

        if (! is_wp_error($aigenerated)) {
            $category_ids[] = (int) $aigenerated['term_id'];
        }

        /*
     * Create AI suggested category
     */
        $category = term_exists(
            $category_name,
            'category'
        );

        if (! $category) {
            $category = wp_insert_term(
                $category_name,
                'category'
            );
        }

        if (! is_wp_error($category)) {
            $category_ids[] = (int) $category['term_id'];
        }

        $post_id = wp_insert_post([
            'post_title'    => $title,
            'post_content'  => $post_content,
            'post_status'   => 'publish',
            'post_type'     => 'post',
            'post_category' => $category_ids,
        ]);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        if (! empty($tags)) {
            wp_set_post_tags(
                $post_id,
                $tags,
                false
            );
        }

        return $post_id;
    }
}