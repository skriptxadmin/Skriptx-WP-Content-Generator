<?php

class SkriptxConGenEnqueueAssets
{
    private string $version = SKRIPTX_CONGEN_VERSION;

    public function init(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'handle']);
    }

    public function handle($hook): void
    {
        $allowed_hooks = [
            'toplevel_page_skriptx-congen',
            'skriptx-congen_page_skriptx-congen--prompts',
            'skriptx-congen_page_skriptx-congen--credits',
        ];

        if (! in_array($hook, $allowed_hooks, true)) {
            return;
        }

        $this->enqueue_styles();
        $this->enqueue_scripts($hook);
    }

    public function enqueue_styles(): void
    {
        wp_enqueue_style(
            'congen-style',
            SKRIPTX_CONGEN_PLUGIN_URL . 'styles/style.css',
            [],
            $this->version
        );
    }

    public function enqueue_scripts($hook): void
    {
        wp_enqueue_script(
            'congen-script',
            SKRIPTX_CONGEN_PLUGIN_URL . 'scripts/script.js',
            ['jquery'],
            $this->version,
            true
        );

        $this->enqueue_page_scripts($hook);

        wp_localize_script(
            'congen-script',
            'skriptxcongen',
            [
                'ajax_url'  => admin_url('admin-ajax.php'),
                'admin_url' => admin_url('admin.php'),
                'nonce'     => wp_create_nonce('congen_nonce'),
                'svgs'      => [
                    'edit'     => $this->get_svg('edit'),
                    'testPipe' => $this->get_svg('test-pipe'),
                    'trash'    => $this->get_svg('trash'),
                    'list'     => $this->get_svg('list'),
                ],
            ]
        );
    }

    private function enqueue_page_scripts($hook): void
    {
        $map = [
            'toplevel_page_skriptx-congen'                => [
                'congen-dashboard' => 'dashboard.js',
            ],

            'skriptx-congen_page_skriptx-congen--prompts' => [
                'congen-prompt-form'      => 'prompt-form.js',
                'congen-prompt-list'      => 'prompt-list.js',
                'congen-prompt-schedules' => 'prompt-schedules.js',
            ],

            'skriptx-congen_page_skriptx-congen--credits' => [
                'congen-credits' => 'credits.js',
            ],
        ];

        if (! isset($map[$hook])) {
            return;
        }

        foreach ($map[$hook] as $handle => $file) {
            wp_enqueue_script(
                $handle,
                SKRIPTX_CONGEN_PLUGIN_URL . 'scripts/' . $file,
                ['congen-script'],
                $this->version,
                true
            );
        }
    }

    public function get_svg($file)
    {
        $path = SKRIPTX_CONGEN_PLUGIN_DIR . 'svgs/' . $file . '.svg';

        if (! file_exists($path)) {
            return null;
        }

        return file_get_contents($path);
    }
}
