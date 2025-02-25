<?php

/*
 * Plugin Name:       RRZE Meta Bar
 * Plugin URI:        https://github.com/RRZE-Webteam/rrze-metabar
 * Description:       Add a bar with meta information / links on top of every site of a WordPress multisite environment
 * Version:           1.0.0
 * Requires at least: 6.1
 * Requires PHP:      8.0
 * Author:            RRZE Webteam
 * Author URI:        https://blogs.fau.de/webworking/
 * License:           GNU General Public License v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.de.html
 * Text Domain:       rrze-metabar
 * Domain Path:       /languages
 */

namespace RRZE\Metabar;

defined('ABSPATH') || exit;

const RRZE_PHP_VERSION = '8.0';
const RRZE_WP_VERSION = '6.1';
const RRZE_PLUGIN_FILE = __FILE__;
define('RRZE_METABAR_ROOT', dirname(__FILE__));
const RRZE_METABAR_VERSION = '1.0.0';

// Automatische Laden von Klassen.
spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $base_dir = __DIR__ . '/includes';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Registriert die Plugin-Funktion, die bei Aktivierung des Plugins ausgeführt werden soll.
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
// Registriert die Plugin-Funktion, die ausgeführt werden soll, wenn das Plugin deaktiviert wird.
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');
// Wird aufgerufen, sobald alle aktivierten Plugins geladen wurden.
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');


/**
 * Einbindung der Sprachdateien.
 */
function load_textdomain()
{
    load_plugin_textdomain('rrze-metabar', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}


/**
 * Überprüft die minimal erforderliche PHP- u. WP-Version.
 */
function system_requirements()
{
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle PHP-Version, 2: erforderliche PHP-Version */
        $error = sprintf(__('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'rrze-metabar'), PHP_VERSION, RRZE_PHP_VERSION);
    } elseif (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        /* Übersetzer: 1: aktuelle WP-Version, 2: erforderliche WP-Version */
        $error = sprintf(__('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'rrze-metabar'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    return $error;
}


/**
 * Wird durchgeführt, nachdem das Plugin aktiviert wurde.
 */
function activation()
{
    // Sprachdateien werden eingebunden.
    load_textdomain();

    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if ($error = system_requirements()) {
        deactivate_plugins(plugin_basename(__FILE__), false, true);
        wp_die($error);
    }

    // Ab hier können die Funktionen hinzugefügt werden,
    // die bei der Aktivierung des Plugins aufgerufen werden müssen.
    // Bspw. wp_schedule_event, flush_rewrite_rules, etc.

}


/**
 * Wird durchgeführt, nachdem das Plugin deaktiviert wurde.
 */
function deactivation() {

}


/**
 * Instantiate Plugin class.
 * @return object Plugin
 */
function plugin()
{
    static $instance;
    if (null === $instance) {
        $instance = new Plugin(__FILE__);
    }

    return $instance;
}
/**
 * Wird durchgeführt, nachdem das WP-Grundsystem hochgefahren
 * und alle Plugins eingebunden wurden.
 */
function loaded()
{
    // Sprachdateien werden eingebunden.
    load_textdomain();
    plugin()->loaded();
    // Überprüft die minimal erforderliche PHP- u. WP-Version.
    if ($error = system_requirements()) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_name = $plugin_data['Name'];
        $tag = is_network_admin() ? 'network_admin_notices' : 'admin_notices';
        add_action($tag, function () use ($plugin_name, $error) {
            printf('<div class="notice notice-error"><p>%1$s: %2$s</p></div>', esc_html($plugin_name), esc_html($error));
        });
    } else {
        // Hauptklasse (Main) wird instanziiert.
        $main = new Main(__FILE__);
        $main->onLoaded();
    }
}