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

    /**
     * @var WP_Locator_Plugin_Admin
     */
    protected $admin;

    /**
     * @var WP_Locator_DCR_Client
     */
    protected $dcr;

    /**
     * @var WP_Locator_OAuth_Client
     */
    public $oauth;

    /**
     * @var WP_Locator_Import_Service
     */
    protected $import_service;

    public function __construct()
    {
        $this->load_dependancies();

        $this->loader->add_action('init', $this, 'register_post_type');

        $this->loader->add_action('admin_menu', $this->admin, 'register_admin_menus');
        $this->loader->add_action('admin_init', $this->admin, 'register_settings');
        $this->loader->add_action('admin_notices', $this->admin, 'show_admin_notices');
        $this->loader->add_filter('manage_location_posts_columns', $this->admin, 'register_post_type_columns');
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'load_scripts');
        $this->loader->add_action('manage_location_posts_custom_column', $this->admin, 'custom_location_columns', 10, 2);

        $this->admin->register_meta();

        $this->loader->add_action('add_meta_boxes', $this->admin, 'add_meta_boxes');
        $this->loader->add_action('admin_post_wp_locator_dcr_register', $this->dcr, 'register');

        $this->loader->add_action('admin_init', $this->admin, 'validate_auth_code');

        $this->register_frontend();

        $this->register_cron_schedules();
        $this->register_cron_actions();

    }

    public function run(){
        $this->loader->run();
    }

    public function load_dependancies(){

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-plugin-loader.php';

        require_once plugin_dir_path(__FILE__) . '/../admin/class-wp-locator-plugin-admin.php';

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-dcr-client.php';

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-oauth-client.php';

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-api-client.php';

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-import-service.php';

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-template-loader.php';

        $this->loader = new WP_Locator_Plugin_Loader();

        $this->dcr = new WP_Locator_DCR_Client();

        $this->oauth = new WP_Locator_OAuth_Client();

        $this->admin = new WP_Locator_Plugin_Admin($this);

        $this->import_service = new WP_Locator_Import_Service();
    }

    public function register_post_type()
    {

        register_post_type('location', [
            'labels' => [
                'name' => __('Locations', 'wp-locator-plugin'),
                'singular_name' => __('Location', 'wp-locator-plugin'),
            ],
            'description' => 'An individual location',
            'has_archive' => true,
            'public' => true,
            'menu_icon' => 'dashicons-location',
            'show_in_rest' => true
        ]);

    }

    public function register_cron_actions()
    {
        $this->loader->add_action('wp_locator_import_service', $this->import_service, 'run');
    }

    public function register_cron_schedules()
    {
        $this->loader->add_filter('cron_schedules', $this, 'cron_schedules');
    }

    public function cron_schedules($schedules)
    {
        $schedules['fifteen_minutes'] = [
            'interval' => 900,
            'display' => esc_html__('Every Fifteen Minutes')
        ];

        return $schedules;
    }

    public function register_frontend()
    {
        $this->loader->add_filter('template_include', WP_Locator_Template_Loader::class, 'template_loader');
        $this->loader->add_action('template_redirect', $this, 'enqueue_scripts');
    }

    public function enqueue_scripts()
    {
        if (is_singular('location') || is_post_type_archive('location')){
            wp_enqueue_style('wp-locator-styles', WP_LOCATOR_PLUGIN_URL . 'public/assets/public.css');
            wp_enqueue_script('wp-locator-script', WP_LOCATOR_PLUGIN_URL . 'public/assets/public.js', null, false, true);
        }

        if (is_singular('location') && $google_api_key = get_option(WP_LOCATOR_GOOGLE_MAPS_API_KEY)){
            wp_enqueue_script('wp-locator-location-map', WP_LOCATOR_PLUGIN_URL . 'public/assets/location-map.js', null, false, true);
            wp_enqueue_script('wp-locator-google-maps-sdk', "https://maps.googleapis.com/maps/api/js?key=$google_api_key&callback=wplocator.initMap", ['wp-locator-location-map'], false, true);
        }
    }

}