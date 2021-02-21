<?php

class WP_Locator_Plugin {

    public function init(){

        $this->register_admin();

    }

    public function register_admin(){

        add_menu_page(
            'WP Locator', // visible page name
            'WP Locator', // menu label
            'edit_posts', // required capability
            'wp-locator-admin', // hook/slug of page
            $this->load_home_page(), // function to render the page
            'dashicons-location-alt' // custom icon
        );

    }

    public function load_home_page(){

        return require 'includes/admin/views/html-admin-page-home.php';

    }

}