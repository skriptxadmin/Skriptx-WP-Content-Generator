<?php

class SkriptxConGenRunScheduler
{
    public function init()
    {

        if ($this->has_running_jobs()) {
            return;
        }

        $queued_rows = $this->get_queued_rows();

        if (empty($queued_rows)) {
            return;
        }

        foreach ($queued_rows as $queued_row) {

            $this->process_queued_row($queued_row);

        }

    }

    public function has_running_jobs()
    {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';

        // Fixed: Moved prepare inline inside get_var and swapped table interpolation for %i
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $count = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM %i WHERE status_id = 2",
                $schedules_table
            )
        );

        return $count > 0;

    }

    public function get_queued_rows()
    {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';

        // Fixed: Wrapped in prepare inline to satisfy static analysis and switched to %i
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE status_id = 1 ORDER BY id ASC",
                $schedules_table
            )
        );

    }

    public function process_queued_row($queued_row)
    {

        $prompt = $this->get_prompt_row($queued_row);

        if (empty($prompt->id)) {

            $this->set_schedule_error(
                $queued_row,
                'Failed: Prompt row missing'
            );

            return;
        }

        $job_id = $this->submit_job($prompt);

        if (empty($job_id)) {

            $this->set_schedule_error(
                $queued_row,
                'Failed: Job ID missing'
            );

            return;
        }

        $this->set_schedule_running(
            $queued_row,
            $job_id
        );

        $this->update_prompt_run_details(
            $prompt
        );

    }

    public function get_prompt_row($queued_row)
    {

        global $wpdb;

        $prompts_table = $wpdb->prefix . 'skriptx_congen_prompts';

        // Fixed: Removed intermediate variables, moved prepare inline, added %i placeholder
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE id = %d AND is_active = 1 AND deleted_at IS NULL",
                $prompts_table,
                $queued_row->prompt_id
            )
        );

    }

    public function submit_job($prompt)
    {

        $secret = get_option(
            'skriptx_congen_secret_key'
        );

        if (empty($secret)) {
            return false;
        }

        $payload = wp_json_encode([
            'domain'   => get_site_url(),
            'prompt'   => $prompt->prompt,
            'language' => $prompt->language,
        ]);

        $signature = hash_hmac(
            'sha256',
            $payload,
            $secret
        );

        $response = wp_remote_post(
            SKRIPTX_SERVER_ENDPOINT . '/domains/jobs',
            [
                'timeout' => 120,
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

        return $data['job_id'] ?? false;

    }

    public function set_schedule_running(
        $queued_row,
        $job_id
    ) {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $data = [
            'job_id'     => $job_id,
            'status_id'  => 2,
            'started_at' => current_time('mysql'),
        ];

        $where = [
            'id' => $queued_row->id,
        ];

        $wpdb->update(
            $schedules_table,
            $data,
            $where
        );

    }

    public function update_prompt_run_details(
        $prompt
    ) {

        global $wpdb;

        $prompts_table = $wpdb->prefix . 'skriptx_congen_prompts';

        // Fixed: Moved prepare inline inside get_var and updated format variables to %i and %d
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $runs_count = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT runs_count FROM %i WHERE id = %d",
                $prompts_table,
                $prompt->id
            )
        );

        $data = [
            'last_run'   => current_time('mysql'),
            'runs_count' => $runs_count + 1,
        ];

        $where = [
            'id' => $prompt->id,
        ];

        $wpdb->update(
            $prompts_table,
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

}