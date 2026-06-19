<?php
if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('skriptx_congen_db_health_check')) {

    function skriptx_congen_db_health_check()
    {
        check_ajax_referer('congen_nonce', 'nonce');

        if (! current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        global $wpdb;

        $db_name = DB_NAME;

       
        // Define schema requirements
        $schema = [
            $wpdb->prefix . 'skriptx_congen_schedules' => [
                "id",
                "prompt_id",
                "post_id",
                "status_id",
                "started_at",
                "completed_at",
                "job_id",
                "expires_at",
                "error_message",
                "created_at",
                "updated_at",
            ],
            $wpdb->prefix . 'skriptx_congen_prompts'   => [
                'id',
                'prompt',
                'language',
                'hours',
                'mins',
                'frequency_in_mins',
                'is_active',
                'generate_image',
                'runs_count',
                'last_run',
                'next_run',
                'deleted_by',
                'deleted_at',
                'created_at',
                'updated_at',
            ],
            $wpdb->prefix . 'skriptx_congen_images'    => [
                'id',
                'schedule_id',
                'status_id',
                'started_at',
                'completed_at',
                'job_id',
                'expires_at',
                'error_message',
                'created_at',
                'updated_at',
            ],
        ];

        $report = [
            'tables'         => [],
            'overall_status' => 'ok',
        ];

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        foreach ($schema as $table => $required_columns) {

            // 1. Check table exists
             // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
            $table_exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*)
                     FROM information_schema.tables
                     WHERE table_schema = %s
                     AND table_name = %s",
                    $db_name,
                    $table
                )
            );

            $table_ok = ((int) $table_exists > 0);

            // 2. Check columns if table exists
            $columns_found = [];

            if ($table_ok) {
 // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                $columns = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT COLUMN_NAME
                         FROM information_schema.columns
                         WHERE table_schema = %s
                         AND table_name = %s",
                        $db_name,
                        $table
                    ),
                    ARRAY_A
                );

                $columns_found = array_column($columns, 'COLUMN_NAME');
            }

            $missing_columns = [];

            foreach ($required_columns as $col) {
                if (! in_array($col, $columns_found)) {
                    $missing_columns[] = $col;
                }
            }

            $status = ($table_ok && empty($missing_columns)) ? 'ok' : 'error';

            if ($status === 'error') {
                $report['overall_status'] = 'error';
            }

            $report['tables'][$table] = [
                'exists'           => $table_ok,
                'required_columns' => $required_columns,
                'found_columns'    => $columns_found,
                'missing_columns'  => $missing_columns,
                'status'           => $status,
            ];
        }

        wp_send_json_success($status);
    }
}

add_action(
    'wp_ajax_skriptx-congen-db-health-check',
    'skriptx_congen_db_health_check'
);
