<?php
if (! defined('ABSPATH')) {
    exit;
}


$skriptx_files = glob(SKRIPTX_CONGEN_PLUGIN_DIR . 'tables/table.*.php');

if (! empty($skriptx_files)) {
    foreach ($skriptx_files as $skriptx_file) {
        require_once $skriptx_file;
    }
}
