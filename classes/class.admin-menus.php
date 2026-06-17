<?php

class SkriptxConGenAdminMenus
{
    public function init(): void
    {
        
        $this->register_admin_menus();
    }

    private function register_admin_menus(): void
    {
        add_menu_page(
            'Skriptx ConGen',
            'Skriptx ConGen',
            'manage_options',
            'skriptx-congen',
            [$this, 'dashboard_page'],
            'dashicons-admin-site',
            25
        );

        add_submenu_page(
            'skriptx-congen',
            'Prompts',
            'Prompts',
            'manage_options',
            'skriptx-congen--prompts',
            [$this, 'prompts_page']
        );

        add_submenu_page(
            'skriptx-congen',
            'Credits',
            'Credits',
            'manage_options',
            'skriptx-congen--credits',
            [$this, 'credits_page']
        );
    }

    public function dashboard_page(): void
    {
        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/dashboard.php';
    }

    public function prompts_page(): void
    {
        $allowed_views = ['list', 'edit', 'create', 'schedules'];

        // Fixed: Added wp_unslash and sanitize_text_field to clear input warnings.
        // Fixed: Added phpcs:ignore rule for NonceVerification since this is a clean GET navigation parameter.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $raw_view = isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : 'list';

        $view = in_array($raw_view, $allowed_views, true) ? $raw_view : 'list';

        switch ($view) {
            case 'schedules':
                require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/prompt-schedules.php';
                break;

            case 'edit':
            case 'create':
                require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/prompt-form.php';
                break;

            default:
                require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/prompt-list.php';
                break;
        }
    }

    public function credits_page(): void
    {
        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/credits.php';
    }
}