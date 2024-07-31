<?php

namespace RRZE\Metabar;

defined('ABSPATH') || exit;

class Metabar {

    public function __construct() {
        add_filter('body_class', [$this, 'add_body_class']);
        add_action('wp_body_open', [$this, 'render_metabar'], 0);
        add_action('wp_footer', [$this, 'render_metabar'], 1000); // Back-compat for themes not using `wp_body_open`.
    }

    public function render_metabar() {
        $output = '<div id="rrze-metabar"><div class="rrze-metabar-content">';
        $output .= '<a href="https://blogs.fau.de" class="logo-container"><img src="' . plugins_url() . '/rrze-metabar/assets/img/logo-faublogs.svg" class="faublogs-logo" alt="FAU-Blogs Logo"/>' . __('FAU-Blogs', RRZE_METABAR_TEXTDOMAIN). '</a>' . "Moin!";
        $output .= '</div></div>';
        //var_dump($this->get_svg_icon('logo-faublogs.svg'));
        echo $output;
    }

    public function add_body_class($classes) {
        $classes[] = 'rrze-metabar';
        return array_filter($classes);
    }

    private function get_svg_icon($filename) {
        $path = RRZE_METABAR_ROOT . '/assets/img/';
        $file = $path . $filename;
        if (!file_exists($file)) {
            return '';
        } else {
            return file_get_contents($file);
        }
    }

}