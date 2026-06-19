<?php

class SkriptxConCron
{

    // ========================================================
    // Jobber (1 minute)
    // ========================================================

    public $cron_jobber_title = 'skriptx_congen_1_minutes';

    public $cron_jobber_interval = 60;

    public $cron_jobber_description = 'Every 1 minute';

    public $cron_jobber_event = 'skriptx_congen_cron_jobber';

    // ========================================================
// Image Maker (2 minutes)
// ========================================================

public $cron_imgmaker_title = 'skriptx_congen_2_minutes';

public $cron_imgmaker_interval = 120;

public $cron_imgmaker_description = 'Every 2 minutes';

public $cron_imgmaker_event = 'skriptx_congen_cron_imgmaker';

    // ========================================================
    // Scheduler (3 minutes)
    // ========================================================

    public $cron_scheduler_title = 'skriptx_congen_3_minutes';

    public $cron_scheduler_interval = 180;

    public $cron_scheduler_description = 'Every 3 minutes';

    public $cron_scheduler_event = 'skriptx_congen_cron_scheduler';

    // ========================================================
    // Prompter (5 minutes)
    // ========================================================

    public $cron_prompter_title = 'skriptx_congen_5_minutes';

    public $cron_prompter_interval = 300;

    public $cron_prompter_description = 'Every 5 minutes';

    public $cron_prompter_event = 'skriptx_congen_cron_prompter';

    public function init()
    {

        add_filter('cron_schedules', [$this, 'add_cron_interval']);

        add_action($this->cron_jobber_event, [$this, 'run_jobber_event']);
        add_action($this->cron_scheduler_event, [$this, 'run_scheduler_event']);
        add_action($this->cron_prompter_event, [$this, 'run_prompter_event']);
        add_action($this->cron_imgmaker_event,[$this, 'run_imgmaker_event']);

    }

    public function add_cron_interval($schedules)
    {

        $schedules[$this->cron_jobber_title] = [
            'interval' => $this->cron_jobber_interval,
            'display'  => $this->cron_jobber_description,
        ];

        $schedules[$this->cron_scheduler_title] = [
            'interval' => $this->cron_scheduler_interval,
            'display'  => $this->cron_scheduler_description,
        ];

        $schedules[$this->cron_prompter_title] = [
            'interval' => $this->cron_prompter_interval,
            'display'  => $this->cron_prompter_description,
        ];

        $schedules[$this->cron_imgmaker_title] = [
    'interval' => $this->cron_imgmaker_interval,
    'display'  => $this->cron_imgmaker_description,
];

        return $schedules;

    }

    public function start()
    {

        if (! wp_next_scheduled($this->cron_jobber_event)) {

            wp_schedule_event(
                time(),
                $this->cron_jobber_title,
                $this->cron_jobber_event
            );

        }

        if (! wp_next_scheduled($this->cron_scheduler_event)) {

            wp_schedule_event(
                time(),
                $this->cron_scheduler_title,
                $this->cron_scheduler_event
            );

        }

        if (! wp_next_scheduled($this->cron_prompter_event)) {

            wp_schedule_event(
                time(),
                $this->cron_prompter_title,
                $this->cron_prompter_event
            );

        }

        if (! wp_next_scheduled($this->cron_imgmaker_event)) {

    wp_schedule_event(
        time(),
        $this->cron_imgmaker_title,
        $this->cron_imgmaker_event
    );

}

    }

    public function stop()
    {

        wp_clear_scheduled_hook($this->cron_jobber_event);
        wp_clear_scheduled_hook($this->cron_scheduler_event);
        wp_clear_scheduled_hook($this->cron_prompter_event);
        wp_clear_scheduled_hook($this->cron_imgmaker_event);

    }

    public function run_imgmaker_event()
{
    require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'cron/cron.run-imgmaker.php';

    $cron = new SkriptxConGenRunImgMaker();

    $cron->init();
}

    public function run_jobber_event()
    {

        // $file    = SKRIPTX_CONGEN_PLUGIN_DIR . 'test/jobber.txt';
        // $content = current_time('mysql') . PHP_EOL;

        // file_put_contents($file, $content, FILE_APPEND);

        // You will tell me which file to load
 require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'cron/cron.run-jobber.php';

        $cron = new SkriptxConGenRunJobber();

        $cron->init();

        
    }

    public function run_scheduler_event()
    {

        // $file    = SKRIPTX_CONGEN_PLUGIN_DIR . 'test/scheduler.txt';
        // $content = current_time('mysql') . PHP_EOL;

        // file_put_contents($file, $content, FILE_APPEND);

        // You will tell me which file to load

         require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'cron/cron.run-scheduler.php';

        $cron = new SkriptxConGenRunScheduler();

        $cron->init();

    }

    public function run_prompter_event()
    {

        // $file    = SKRIPTX_CONGEN_PLUGIN_DIR . 'test/prompter.txt';
        // $content = current_time('mysql') . PHP_EOL;

        // file_put_contents($file, $content, FILE_APPEND);

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'cron/cron.run-prompter.php';

        $cron = new SkriptxConGenRunPrompter();

        $cron->init();

        // You will tell me which file to load

    }

}
