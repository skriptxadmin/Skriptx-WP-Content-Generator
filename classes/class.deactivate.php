<?php

class SkriptxConGenDeactivate
{

    public function init()
    {

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.cron.php';

        $cron = new SkriptxConCron();

        $cron->stop();

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.settings.php';

        $settings = new SkriptxConGenSettings;

        $settings->deactivate();

          update_option(
            'skriptx_congen_db_version', null
        );

    }

}
