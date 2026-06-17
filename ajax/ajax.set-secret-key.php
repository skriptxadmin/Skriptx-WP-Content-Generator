<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_set_secret_key')) {

    function skriptx_congen_set_secret_key()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.settings.php';

        $settings = new SkriptxConGenSettings();

        $result = $settings->set_secret_key();

        if (! $result) {
            wp_send_json_error('Secret key not generated');
        }

        wp_send_json_success('Secret key generated successfully');
    }
}

add_action(
    'wp_ajax_skriptx-congen-set-secret-key',
    'skriptx_congen_set_secret_key'
);