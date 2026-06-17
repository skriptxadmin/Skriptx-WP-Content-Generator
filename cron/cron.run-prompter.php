<?php

class SkriptxConGenRunPrompter
{
    public function init()
    {

        $this->queue_due_prompts();

    }

    public function queue_due_prompts()
    {

        global $wpdb;

        $prompts_table = $wpdb->prefix . 'skriptx_congen_prompts';

        $now = current_time('mysql');

        // Fixed: Moved prepare inline inside get_results and substituted $prompts_table with %i placeholder
       // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $prompts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE next_run <= %s AND is_active = 1 AND deleted_at IS NULL",
                $prompts_table,
                $now
            )
        );

        if (empty($prompts)) {
            return;
        }

        foreach ($prompts as $prompt) {

            $this->create_queue_row($prompt);

            $this->update_next_run($prompt);

        }

    }

    public function create_queue_row($prompt)
    {

        global $wpdb;

        $schedules_table = $wpdb->prefix . 'skriptx_congen_schedules';
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $now = current_time('mysql');

        $expires_at = $this->get_expiry_timestamp($prompt);

        $data = [
            'prompt_id'  => $prompt->id,
            'status_id'  => 1,
            'created_at' => $now,
            'expires_at' => $expires_at,
        ];

        $wpdb->insert($schedules_table, $data);

    }

    public function get_expiry_timestamp($prompt)
    {

        $dt = new DateTime(current_time('mysql'));

        $minutes = (int) $prompt->frequency_in_mins + 5;

        $dt->modify("+{$minutes} minutes");

        return $dt->format('Y-m-d H:i:s');

    }

    public function update_next_run($prompt)
    {

        global $wpdb;

        $prompts_table = $wpdb->prefix . 'skriptx_congen_prompts';
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $dt = new DateTime(current_time('mysql'));

        $dt->modify('+' . (int) $prompt->frequency_in_mins . ' minutes');

        $data = [
            'next_run' => $dt->format('Y-m-d H:i:s'),
        ];

        $where = [
            'id' => $prompt->id,
        ];

        $wpdb->update($prompts_table, $data, $where);

    }

}