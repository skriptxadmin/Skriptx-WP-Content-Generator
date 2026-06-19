<?php
if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('skriptx_congen_create_images_table')) {
    function skriptx_congen_create_images_table()
    {

        global $wpdb;

        $table = $wpdb->prefix . 'skriptx_congen_images';

        $charset_collate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $sql = "CREATE TABLE $table (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    schedule_id BIGINT UNSIGNED NOT NULL,
    status_id TINYINT UNSIGNED NOT NULL DEFAULT 0,
    started_at DATETIME NULL,
    completed_at DATETIME NULL,
    job_id VARCHAR(100) NULL,
    expires_at DATETIME NOT NULL,
    image_url LONGTEXT NULL,
    error_message LONGTEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) $charset_collate;";
        dbDelta($sql);
    }

}

skriptx_congen_create_images_table();
