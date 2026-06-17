<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompt_get')) {

    function skriptx_congen_prompt_get()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_prompts';

        $id = ! empty($_POST['id']) && is_numeric($_POST['id'])
            ? intval($_POST['id'])
            : null;

        if (! $id) {
            wp_send_json_error('No row found');
        }

        // Fixed: Moved prepare inline and substituted $table with %i placeholder
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM %i 
                 WHERE id = %d 
                 AND deleted_at IS NULL",
                $table,
                $id
            )
        );

        wp_send_json_success($row);
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompt-get',
    'skriptx_congen_prompt_get'
);