<?php
if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('skriptx_congen_create_prompts_table')) {
    function skriptx_congen_create_prompts_table()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_prompts';

        $charset_collate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE $table (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    prompt LONGTEXT NOT NULL,
    language VARCHAR(100) DEFAULT 'English',
    hours INT UNSIGNED NOT NULL,
    mins INT UNSIGNED NOT NULL,
    frequency_in_mins INT UNSIGNED NOT NULL,
    is_active TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    runs_count INT UNSIGNED NOT NULL DEFAULT 0,
    last_run DATETIME NULL,
    next_run DATETIME,
    deleted_by INT UNSIGNED NULL,
    deleted_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) $charset_collate;";

        dbDelta($sql);

    }
}

skriptx_congen_create_prompts_table();
