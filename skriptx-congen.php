<?php
/**
 * Plugin Name: Skriptx Content Generator
 * Plugin URI: https://congen.skriptx.com
 * Description: Automatically generate and publish AI-powered WordPress content on a schedule.
 * Version: 2.0.2
 * Author: Skriptx
 * Author URI: https://skriptx.com
 * License: GPLv2 or later
 * Text Domain: skriptx-content-generator
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// ========================================================
// Constants
// ========================================================
define('SKRIPTX_CONGEN_VERSION', '2.0.1');
define('SKRIPTX_CONGEN_DB_VERSION', '2.0.1');
define('SKRIPTX_CONGEN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SKRIPTX_CONGEN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SKRIPTX_SERVER_ENDPOINT', 'https://congen.skriptx.com/wpgateway');

// ========================================================
// Core Plugin File Includes
// ========================================================
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.admin-menus.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.enqueue-assets.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.activate.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.deactivate.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.uninstall.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.ajax.php';
require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'classes/class.cron.php';

// ========================================================
// Bootstrap Class
// ========================================================
final class Skriptx_ConGen_Bootstrap
{
    private static ?self $instance = null;

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks(): void
    {
        // Admin menus
        add_action('admin_menu', [$this, 'admin_menu']);

        (new SkriptxConGenEnqueueAssets())->init();

        // AJAX
        (new SkriptxConGenAjax())->init();

        // Cron
        (new SkriptxConCron())->init();
    }

    public function admin_menu(): void
    {
        (new SkriptxConGenAdminMenus())->init();
    }

  
}

// ========================================================
// Activation / Deactivation / Uninstall
// ========================================================
register_activation_hook(__FILE__, function () {
    (new SkriptxConGenActivate())->init();
});

register_deactivation_hook(__FILE__, function () {
    (new SkriptxConGenDeActivate())->init();
});

// register_uninstall_hook(__FILE__, function () {
//     (new SkriptxConGenUninstall())->init();
// });

// ========================================================
// Start Plugin
// ========================================================
Skriptx_ConGen_Bootstrap::instance();