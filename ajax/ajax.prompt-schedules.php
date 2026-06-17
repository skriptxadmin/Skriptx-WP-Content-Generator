<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompt_schedules')) {

    function skriptx_congen_prompt_schedules()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_schedules';

        $prompt_id = ! empty($_POST['promptId']) && is_numeric($_POST['promptId'])
            ? intval($_POST['promptId'])
            : null;

        if (! $prompt_id) {
            wp_send_json_error('No prompt found');
        }

        // Fixed: Moved prepare inline and substituted $table with %i placeholder
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT status_id, post_id, started_at, completed_at, error_message, created_at
                 FROM %i
                 WHERE prompt_id = %d
                 ORDER BY id DESC",
                $table,
                $prompt_id
            )
        );

        wp_send_json_success($rows);
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompt-schedules',
    'skriptx_congen_prompt_schedules'
);