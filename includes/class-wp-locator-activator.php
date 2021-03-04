<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Activator {

    public function activate()
    {
        if (!wp_next_scheduled( 'wp_locator_import_service')) {
            wp_schedule_event( time(), 'fifteen_minutes', 'wp_locator_import_service' );
        }
    }

}