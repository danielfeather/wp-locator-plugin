<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Plugin {

    protected $name = 'wp-locator-plugin';

    /**
     * @var WP_Locator_Plugin_Loader
     */
    protected $loader;

    public function __construct()
    {
        $this->load_dependancies();

        $this->loader->add_action('init', $this, 'register_post_type');
        $this->loader->add_filter('manage_location_posts_columns', $this, 'register_post_type_columns');
        $this->loader->add_action('admin_menu', new WP_Locator_Plugin_Admin(), 'register_admin_menus');
    }

    public function run(){
        $this->loader->run();
    }

    public function load_dependancies(){

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-plugin-loader.php';

        require_once plugin_dir_path(__FILE__) . '/../admin/class-wp-locator-plugin-admin.php';

        $this->loader = new WP_Locator_Plugin_Loader();

    }

    public function register_post_type(){

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

    }

    public function register_post_type_columns($columns)
    {

        unset($columns['date']);

        $columns['updated_at'] = __('Date Updated', 'wp-locator-plugin');
        $columns['Created At'] = __('Date Created', 'wp-locator-plugin');

        return $columns;

    }

}