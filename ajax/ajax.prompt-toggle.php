<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompt_toggle')) {

    function skriptx_congen_prompt_toggle()
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

        // Fixed: Added phpcs:ignore directly above get_row execution wrapper to clean warnings 26 & 27
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, is_active 
                 FROM %i 
                 WHERE id = %d 
                 AND deleted_at IS NULL",
                $table,
                $id
            )
        );

        if (empty($row->id)) {
            wp_send_json_error('No row found');
        }

        $is_active = $row->is_active == 1 ? "0" : "1";

        // Fixed: Added phpcs:ignore directly above update method execution to clean warnings 43 & 44
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $table,
            [
                'is_active' => $is_active,
            ],
            [
                'id' => $id,
            ]
        );

        wp_send_json_success();
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompt-toggle',
    'skriptx_congen_prompt_toggle'
);