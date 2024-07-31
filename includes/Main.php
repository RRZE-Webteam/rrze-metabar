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
     * Variablen Werte zuweisen.
     * @param string $pluginFile Pfad- und Dateiname der Plugin-Datei
     */
    public function __construct($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);

        //$settings = new Settings($this->pluginFile);
        //$settings->onLoaded();
        new Metabar();

    }

    /**
     * Enqueue der globale Skripte.
     */
    public function enqueueScripts()
    {
        wp_enqueue_style(
            'rrze-metabar',
            plugins_url('assets/css/rrze-metabar.css', plugin_basename($this->pluginFile)),
            [],
            RRZE_METABAR_VERSION
        );
        wp_enqueue_script(
            'rrze-metabar',
            plugins_url('assets/js/rrze-metabar.js', plugin_basename($this->pluginFile)),
            ['jquery'],
            RRZE_METABAR_VERSION
        );
    }

    public function adminEnqueueScripts()
    {
        wp_enqueue_style(
            'rrze-metabar-admin',
            plugins_url('assets/css/rrze-metabar-admin.css', plugin_basename($this->pluginFile)),
            [],
            RRZE_METABAR_VERSION
        );
        wp_enqueue_script(
            'rrze-metabar-admin',
            plugins_url( 'assets/js/rrze-metabar-admin.js', plugin_basename($this->pluginFile) ),
            ['jquery'],
            RRZE_METABAR_VERSION,
            ['in_footer' => true,]
        );
    }

}
