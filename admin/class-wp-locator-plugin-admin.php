<?php

class WP_Locator_Plugin_Admin {

    public function register_admin_menus(){

        add_menu_page(
            'WP Locator', // visible page name
            'WP Locator', // menu label
            'edit_posts', // required capability
            'wp-locator-admin', // hook/slug of page
            [$this, 'load_home_page'], // function to render the page
            'dashicons-location-alt' // custom icon
        );

    }

    // Loads the HTML for the main WP Locator page.
    public function load_home_page(){

        require plugin_dir_path(__FILE__) . '/views/html-admin-page-home.php';

    }

}