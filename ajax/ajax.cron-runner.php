<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if (!function_exists('skriptx_congen_cron_runner')) {

    function skriptx_congen_cron_runner() {
        wp_send_json_success([
            'message' => 'Cron runner executed'
        ]);
    }
}

add_action('wp_ajax_skriptx-congen-cron-runner', 'skriptx_congen_cron_runner');
add_action('wp_ajax_nopriv_skriptx-congen-cron-runner', 'skriptx_congen_cron_runner');