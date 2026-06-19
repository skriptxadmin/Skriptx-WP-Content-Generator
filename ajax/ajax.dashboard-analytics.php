<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_dashboard_analytics')) {

    function skriptx_congen_dashboard_analytics()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        // Use %i for identifiers (like table/column names) to clear the Interpolated warning
        $table = $wpdb->prefix . 'skriptx_congen_schedules';

        // Pass the prepared queries directly into the execution method to clear the NotPrepared error
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $result = [
            'queued'  => (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE status_id = %d", $table, 1)
            ),
            'running' => (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE status_id = %d", $table, 2)
            ),
            'success' => (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE status_id = %d", $table, 3)
            ),
            'error'   => (int) $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE status_id = %d", $table, 4)
            ),

            'prompter' => wp_date(
                'Y-m-d H:i:s',
                wp_next_scheduled('skriptx_congen_cron_prompter')
            ),

            'scheduler' => wp_date(
                'Y-m-d H:i:s',
                wp_next_scheduled('skriptx_congen_cron_scheduler')
            ),

            'jobber' => wp_date(
                'Y-m-d H:i:s',
                wp_next_scheduled('skriptx_congen_cron_jobber')
            ),

               'imgmaker' => wp_date(
                'Y-m-d H:i:s',
                wp_next_scheduled('skriptx_congen_cron_imgmaker')
            ),

            'current' => current_time('mysql'),
            'secret'  => get_option('skriptx_congen_secret_key') ? 'Yes' : 'No',
        ];

        wp_send_json_success($result);
    }
}

add_action(
    'wp_ajax_skriptx-congen-dashboard-analytics',
    'skriptx_congen_dashboard_analytics'
);