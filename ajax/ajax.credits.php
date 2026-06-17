<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_credits')) {

    function skriptx_congen_credits()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $secret = get_option('skriptx_congen_secret_key');

        if (empty($secret)) {
            wp_send_json_error('Missing Secret key');
        }

        $payload = wp_json_encode([
            'domain' => get_site_url(),
        ]);

        $signature = hash_hmac(
            'sha256',
            $payload,
            $secret
        );

        $url = SKRIPTX_SERVER_ENDPOINT . '/domains/credits';

        $response = wp_remote_post(
            $url,
            [
                'timeout' => 120,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Signature'  => $signature,
                ],
                'body' => $payload,
            ]
        );

        if (is_wp_error($response)) {
            wp_send_json_error('Error fetching credits');
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        wp_send_json_success($data);
    }
}

add_action('wp_ajax_skriptx-congen-credits', 'skriptx_congen_credits');