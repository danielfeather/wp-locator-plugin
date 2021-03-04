<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Deactivator {

    public function deactivate()
    {
        wp_clear_scheduled_hook('wp_locator_import_service');
    }

}