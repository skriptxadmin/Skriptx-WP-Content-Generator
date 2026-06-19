<?php
if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('skriptx_congen_factory_reset')) {

    function skriptx_congen_factory_reset()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        $tables = [
            $wpdb->prefix . 'skriptx_congen_prompts',
            $wpdb->prefix . 'skriptx_congen_schedules',
            $wpdb->prefix . 'skriptx_congen_images',
        ];

// phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange

       foreach ( $tables as $table ) {

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
            // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.SchemaChange
    $wpdb->query(
        $wpdb->prepare(
            'DROP TABLE IF EXISTS %i',
            $table
        )
    );
}

// phpcs:enable WordPress.DB.DirectDatabaseQuery.SchemaChange

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'tables/index.php';

        wp_send_json_success();
    }
}

add_action(
    'wp_ajax_skriptx-congen-factory-reset',
    'skriptx_congen_factory_reset'
);
