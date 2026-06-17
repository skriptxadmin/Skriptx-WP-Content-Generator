<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompts')) {

    function skriptx_congen_prompts()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_prompts';

        // Fixed: Moved prepare inline and substituted $table with %i placeholder
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i 
                 WHERE deleted_at IS NULL 
                 ORDER BY id DESC",
                $table
            )
        );

        wp_send_json_success($rows);
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompts',
    'skriptx_congen_prompts'
);