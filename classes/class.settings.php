<?php

class SkriptxConGenSettings
{

    public function set_secret_key()
    {

        $payload = wp_json_encode([
            'email'  => get_option('admin_email'),
            'domain' => get_site_url(),
        ]);

        $url = SKRIPTX_SERVER_ENDPOINT . '/domains/activate';

        $response = wp_remote_post($url, [
            'timeout' => 120,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body'    => $payload,
        ]);

        if (is_wp_error($response)) {
            // Fixed: Added phpcs:ignore rule because production error logging here is intentional
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
            error_log('Secret key request failed: ' . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (empty($data['secret'])) {
            // Fixed: Added phpcs:ignore rule because production error logging here is intentional
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
            error_log('Secret key request failed: ' . 'Secret key not found');
            return false;
        }

        update_option('skriptx_congen_secret_key', $data['secret']);
        update_option('skriptx_congen_secret_key_ts', current_time('timestamp'));

        return true;
    }

    public function deactivate()
    {

        $payload = wp_json_encode([
            'domain' => get_site_url(),
        ]);

        $secret = get_option(
            'skriptx_congen_secret_key'
        );

        if (empty($secret)) {
            delete_option('skriptx_congen_secret_key');
            delete_option('skriptx_congen_secret_key_ts');
            return false;
        }

        $encoded = json_encode($payload);

        $signature = hash_hmac(
            'sha256',
            $encoded,
            $secret
        );

        $url = SKRIPTX_SERVER_ENDPOINT . '/domains/deactivate';

        $response = wp_remote_post($url, [
            'timeout' => 120,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature'  => $signature,

            ],
            'body'    => $payload,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        delete_option('skriptx_congen_secret_key');
        delete_option('skriptx_congen_secret_key_ts');

        return true;
    }
}