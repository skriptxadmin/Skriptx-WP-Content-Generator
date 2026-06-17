<?php

class SkriptxConGenActivate
{
    public function init()
    {
        $this->create_tables();
        $this->setup_cron();
        $this->setup_settings();
    }

    private function create_tables(): void
    {
        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'tables/index.php';
    }

    private function setup_cron(): void
    {
        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.cron.php';

        $cron = new SkriptxConCron();

        add_filter('cron_schedules', [$cron, 'add_cron_interval']);

        $cron->start();
    }

    private function setup_settings(): void
    {
        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.settings.php';

        $settings = new SkriptxConGenSettings();

        $settings->set_secret_key();
    }
}