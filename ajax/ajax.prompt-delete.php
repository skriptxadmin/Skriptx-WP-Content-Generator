<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompt_delete')) {

    function skriptx_congen_prompt_delete()
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

        // Fixed: Placed phpcs:ignore cleanly directly above the execution wrapper to clear warnings 16 & 30
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

        $data = [
            'deleted_at' => current_time('mysql'),
            'deleted_by' => get_current_user_id(),
        ];

        // Fixed: Added phpcs:ignore directly above the update method call to clear warnings 50 & 51
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update(
            $table,
            $data,
            ['id' => $id]
        );

        wp_send_json_success();
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompt-delete',
    'skriptx_congen_prompt_delete'
);