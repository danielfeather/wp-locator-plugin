<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '/class-wp-locator-plugin.php';

class WP_Locator_Activator {

    public function activate()
    {
        $plugin = new WP_Locator_Plugin();
        $plugin->register_post_type();
        flush_rewrite_rules();
        if (!wp_next_scheduled( 'wp_locator_import_service')) {
            wp_schedule_event( time(), 'fifteen_minutes', 'wp_locator_import_service' );
        }
    }

}