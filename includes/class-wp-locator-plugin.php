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
        $this->loader->add_action('init', $this, 'add_auth_callback_rule');
        $this->loader->add_filter('manage_location_posts_columns', $this, 'register_post_type_columns');
        $this->loader->add_action('admin_menu', new WP_Locator_Plugin_Admin(), 'register_admin_menus');
        $this->loader->add_action('template_redirect', $this, 'validate_auth_code');

        $this->loader->add_filter('query_vars', $this, 'register_query_vars');

    }

    public function run(){
        $this->loader->run();
    }

    public function load_dependancies(){

        require_once plugin_dir_path(__FILE__) . '/class-wp-locator-plugin-loader.php';

        require_once plugin_dir_path(__FILE__) . '/../admin/class-wp-locator-plugin-admin.php';

        $this->loader = new WP_Locator_Plugin_Loader();

    }

    public function register_post_type()
    {

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

    public function register_query_vars($query_vars)
    {
        $query_vars[] = 'code';
        return $query_vars;
    }

    public function add_auth_callback_rule()
    {

        add_rewrite_rule('^wp-locator/oauth2/callback', 'index.php', 'top');

    }

    public function validate_auth_code()
    {

        global $wp_query;

        $authorization_code = $wp_query->get('code');
        $client_state = $wp_query->get('state');

        if (!empty($authorization_code)){

            $response = wp_remote_post('https://yourauth0domain/oauth/token', [
                'body' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'grant_type' => 'authorization_code',
                    'code' => $authorization_code,
                    'redirect_uri' => 'http://WP_HOST/wp-locator/oauth2/callback'
                ]
            ]);

            $body = json_decode(wp_remote_retrieve_body($response));

            $api_response = wp_remote_get('http://API_HOST:PORT/api/v1/locations', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $body->access_token
                ]
            ]);

            $api_response_body = json_decode(wp_remote_retrieve_body($api_response));

            wp_send_json($api_response_body);

        }

    }

}