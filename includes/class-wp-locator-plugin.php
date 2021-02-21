<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Plugin {

    public function init(){

        $this->register_post_type();
        $this->register_admin();

    }

    public function register_admin(){

        add_action('admin_menu', function (){
            add_menu_page(
                'WP Locator', // visible page name
                'WP Locator', // menu label
                'edit_posts', // required capability
                'wp-locator-admin', // hook/slug of page
                [$this, 'load_home_page'], // function to render the page
                'dashicons-location-alt' // custom icon
            );
        });

    }

    // Loads the HTML for the main WP Locator page.
    public function load_home_page(){

        require __DIR__ . '/admin/views/html-admin-page-home.php';

    }

    public function register_post_type(){

        add_action('init', function (){

            register_post_type('location', [
                'labels' => [
                    'name' => __('Locations', 'wp-locator-plugin'),
                    'singular_name' => __('Location', 'wp-locator-plugin'),
                ],
                'description' => 'An individual location',
                'public' => true,
                'menu_icon' => 'dashicons-location',
                'show_in_rest' => true
            ]);

        });

        add_filter('manage_location_posts_columns', static function ($columns){

            unset($columns['date']);

            $columns['updated_at'] = __('Date Updated', 'wp-locator-plugin');
            $columns['Created At'] = __('Date Created', 'wp-locator-plugin');

            return $columns;

        });
    }

}