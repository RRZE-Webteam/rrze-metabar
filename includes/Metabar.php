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
        $output = '<div id="rrze-metabar"><nav class="rrze-metabar-content">'
            . '<ul class="rrze-metabar-faublogs-link"><li><a href="https://blogs.fau.de" class="logo-container">'
            . '<img src="' . plugins_url() . '/rrze-metabar/assets/img/logo-faublogs.svg" class="faublogs-logo" alt="FAU-Blogs Logo"><span class="faublogs-text">' . __('FAU-Blogs', 'rrze-metabar'). '</span>'
            . '</a></li></ul>'
            . '<ul class="rrze-metabar-links"><li><a href="' . get_bloginfo('url') . '/kontakt">' . __('Contact', 'rrze-metabar') . '</a></li></ul>'
            . '</nav></div>';
        echo $output;
    }

    public function add_body_class($classes) {
        $classes[] = 'rrze-metabar';
        return array_filter($classes);
    }

}