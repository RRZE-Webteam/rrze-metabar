<?php

namespace RRZE\Metabar;

defined('ABSPATH') || exit;

/**
 * Hauptklasse (Main)
 */
class Main
{
    /**
     * Der vollständige Pfad- und Dateiname der Plugin-Datei.
     * @var string
     */
    protected $pluginFile;

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);

        new Metabar();

    }

    /**
     * Enqueue der globale Skripte.
     */
    public function enqueueScripts()
    {
        wp_enqueue_style(
            'rrze-metabar',
            plugins_url('assets/css/rrze-metabar.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_enqueue_script(
            'rrze-metabar',
            plugins_url('assets/js/rrze-metabar.js', plugin()->getBasename()),
            ['jquery'],
            plugin()->getVersion()
        );
    }

    public function adminEnqueueScripts()
    {
        wp_enqueue_style(
            'rrze-metabar-admin',
            plugins_url('assets/css/rrze-metabar-admin.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_enqueue_script(
            'rrze-metabar-admin',
            plugins_url( 'assets/js/rrze-metabar-admin.js', plugin()->getBasename()),
            ['jquery'],
            plugin()->getVersion(),
            ['in_footer' => true,]
        );
    }

}
