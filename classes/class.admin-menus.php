<?php

class SkriptxConGenAdminMenus
{
    public function init(): void
    {

        $this->register_admin_menus();
    }

    private function register_admin_menus(): void
    {
        $dashboard_hook = add_menu_page(
            'Skriptx ConGen',
            'Skriptx ConGen',
            'manage_options',
            'skriptx-congen',
            [$this, 'dashboard_page'],
            'dashicons-admin-site',
            99
        );

        $prompts_hook = add_submenu_page(
            'skriptx-congen',
            'Prompts',
            'Prompts',
            'manage_options',
            'skriptx-congen--prompts',
            [$this, 'prompts_page']
        );

        $credits_hook = add_submenu_page(
            'skriptx-congen',
            'Credits',
            'Credits',
            'manage_options',
            'skriptx-congen--credits',
            [$this, 'credits_page']
        );

        add_submenu_page(
            'skriptx-congen',
            'Consent',
            'Consent',
            'manage_options',
            'skriptx-congen--consent',
            [$this, 'consent_page']
        );

        add_action("load-$dashboard_hook", [$this, 'maybe_redirect_to_consent']);
        add_action("load-$prompts_hook", [$this, 'validate_prompts_request']);
        add_action("load-$credits_hook", [$this, 'maybe_redirect_to_consent']);
    }
    public function dashboard_page(): void
    {

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/dashboard.php';
    }

    public function maybe_redirect_to_consent(): void
    {
        if (empty(get_option('skriptx_congen_secret_key'))) {

            wp_safe_redirect(
                admin_url('admin.php?page=skriptx-congen--consent')
            );

            exit;
        }
    }

    public function validate_prompts_request(): void
{
    if ( empty( get_option( 'skriptx_congen_secret_key' ) ) ) {
        wp_safe_redirect(
            admin_url( 'admin.php?page=skriptx-congen--consent' )
        );
        exit;
    }

    $view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ?: 'list';

    if ( in_array( $view, [ 'edit', 'schedules' ], true ) ) {

        $id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

        if ( ! $id ) {
            wp_safe_redirect(
                admin_url( 'admin.php?page=skriptx-congen--prompts' )
            );
            exit;
        }
    }
}



    public function consent_page(): void
    {

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/consent.php';
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
        if (empty(get_option('skriptx_congen_secret_key'))) {

            wp_safe_redirect(
                admin_url('admin.php?page=skriptx-congen--consent')
            );

            exit;
        }

        require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'views/credits.php';
    }
}
