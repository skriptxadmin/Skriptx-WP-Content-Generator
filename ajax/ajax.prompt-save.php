<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (! function_exists('skriptx_congen_prompt_save')) {

    function skriptx_congen_prompt_save()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        // Fixed: Added wp_unslash() and sanitization functions to raw $_POST elements
        $data = [
            'prompt'   => ! empty($_POST['prompt']) ? sanitize_textarea_field(wp_unslash($_POST['prompt'])) : null,
            'language' => ! empty($_POST['language']) ? sanitize_text_field(wp_unslash($_POST['language'])) : null,
            'hours'    => ! empty($_POST['hours']) ? sanitize_text_field(wp_unslash($_POST['hours'])) : null,
            'mins'     => ! empty($_POST['mins']) ? sanitize_text_field(wp_unslash($_POST['mins'])) : null,
        ];

        // ------------------------------------------------
        // Prompt validation
        // ------------------------------------------------
        if (
            ! $data['prompt'] ||
            ! is_string($data['prompt']) ||
            strlen($data['prompt']) < 10 ||
            strlen($data['prompt']) > 500
        ) {
            wp_send_json_error('Invalid prompt specified');
        }

        // ------------------------------------------------
        // Language validation
        // ------------------------------------------------
        if (
            ! $data['language'] ||
            ! is_string($data['language']) ||
            strlen($data['language']) < 3 ||
            strlen($data['language']) > 15
        ) {
            wp_send_json_error('Invalid language specified');
        }

        // ------------------------------------------------
        // Hours validation
        // ------------------------------------------------
        if (! $data['hours'] || ! is_numeric($data['hours'])) {
            wp_send_json_error('Invalid hours specified');
        }

        // ------------------------------------------------
        // Minutes validation
        // ------------------------------------------------
        if (! $data['mins'] || ! is_numeric($data['mins'])) {
            wp_send_json_error('Invalid mins specified');
        }

        $hours = intval($data['hours']);
        $mins  = intval($data['mins']);

        $frequency_in_mins = $hours * 60 + $mins;

        if (! $frequency_in_mins) {
            wp_send_json_error('Invalid frequency slot');
        }

        $data['frequency_in_mins'] = $frequency_in_mins;
        $data['hours']             = $hours;
        $data['mins']              = $mins;
        $data['next_run']          = current_time('mysql');

        global $wpdb;

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

        $table = $wpdb->prefix . 'skriptx_congen_prompts';

        $id = ! empty($_POST['id']) && is_numeric($_POST['id'])
            ? intval($_POST['id'])
            : null;

        if (empty($id)) {

            $wpdb->insert($table, $data);

        } else {

            $wpdb->update(
                $table,
                $data,
                [
                    'id'         => $id,
                    'deleted_at' => null,
                ]
            );
        }

        wp_send_json_success();
    }
}

add_action(
    'wp_ajax_skriptx-congen-prompt-save',
    'skriptx_congen_prompt_save'
);