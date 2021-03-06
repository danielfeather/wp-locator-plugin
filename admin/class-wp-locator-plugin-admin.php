<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Plugin_Admin {

    /**
     * @var WP_Locator_Plugin
     */
    protected $plugin;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }


    public function register_settings()
    {

        // API Settings
        add_settings_section('wp-locator-api-settings', null, null, 'wp-locator-api-settings');

        add_settings_field(WP_LOCATOR_API_BASE_URL, 'API Base URL', static function(){
            $value = get_option(WP_LOCATOR_API_BASE_URL);
            echo "<input name=\"" . WP_LOCATOR_API_BASE_URL . "\" type=\"text\" value=\"$value\">";
        }, 'wp-locator-api-settings', 'wp-locator-api-settings');

        register_setting('wp-locator-api-settings', WP_LOCATOR_API_BASE_URL);

        // OAuth Settings
        add_settings_section('wp-locator-oauth-settings', null, null, 'wp-locator-oauth-settings');

        add_settings_field(WP_LOCATOR_OAUTH_AUTHORITY, 'Authority', static function(){
            $value = get_option(WP_LOCATOR_OAUTH_AUTHORITY);
            echo "<input name=\"" . WP_LOCATOR_OAUTH_AUTHORITY . "\" type=\"text\" value=\"$value\">";
        }, 'wp-locator-oauth-settings', 'wp-locator-oauth-settings');

        add_settings_field(WP_LOCATOR_OAUTH_USE_DCR, 'Use DCR (Dynamic Client Registration)', static function(){
            $value = get_option(WP_LOCATOR_OAUTH_USE_DCR);
            echo "<input id=\"" . WP_LOCATOR_OAUTH_USE_DCR . "\" name=\"" . WP_LOCATOR_OAUTH_USE_DCR . "\" type=\"checkbox\" value=\"1\"" . checked($value, 1, false) . ">";
        }, 'wp-locator-oauth-settings', 'wp-locator-oauth-settings');

        add_settings_field(WP_LOCATOR_OAUTH_DCR_ENDPOINT, 'DCR Endpoint', static function(){
            $value = get_option(WP_LOCATOR_OAUTH_DCR_ENDPOINT);
            echo "<input name=\"" . WP_LOCATOR_OAUTH_DCR_ENDPOINT . "\" type=\"text\" value=\"$value\">";
        }, 'wp-locator-oauth-settings', 'wp-locator-oauth-settings');

        add_settings_field(WP_LOCATOR_OAUTH_CLIENT_ID, 'Client ID', static function(){
            $value = get_option(WP_LOCATOR_OAUTH_CLIENT_ID);
            echo "<input id=\"" . WP_LOCATOR_OAUTH_CLIENT_ID . "\" name=\"" . WP_LOCATOR_OAUTH_CLIENT_ID . "\" type=\"text\" value=\"$value\">";
        }, 'wp-locator-oauth-settings', 'wp-locator-oauth-settings');

        add_settings_field(WP_LOCATOR_OAUTH_CLIENT_SECRET, 'Client Secret', static function(){
            $value = get_option(WP_LOCATOR_OAUTH_CLIENT_SECRET);
            echo "<input id=\"" . WP_LOCATOR_OAUTH_CLIENT_SECRET . "\" name=\"" . WP_LOCATOR_OAUTH_CLIENT_SECRET . "\" type=\"text\" value=\"$value\">";
        }, 'wp-locator-oauth-settings', 'wp-locator-oauth-settings');

        register_setting('wp-locator-oauth-settings', WP_LOCATOR_OAUTH_AUTHORITY);
        register_setting('wp-locator-oauth-settings', WP_LOCATOR_OAUTH_USE_DCR);
        register_setting('wp-locator-oauth-settings', WP_LOCATOR_OAUTH_DCR_ENDPOINT);
        register_setting('wp-locator-oauth-settings', WP_LOCATOR_OAUTH_CLIENT_ID);
        register_setting('wp-locator-oauth-settings', WP_LOCATOR_OAUTH_CLIENT_SECRET);
    }

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

    public function load_scripts($hook_suffix)
    {
        if ($hook_suffix === 'toplevel_page_wp-locator-admin'){
            wp_enqueue_script('wp_locator_admin_script', plugin_dir_url(__FILE__) . 'assets/admin.js', [], false, true);
            wp_enqueue_style('wp_locator_admin_style', plugin_dir_url(__FILE__) . 'assets/admin.css');
        }
    }

    public function validate_auth_code()
    {
        if (!isset($_GET['page'])){
            return;
        }

        if ($_GET['page'] !== 'wp-locator-admin'){
            return;
        }

        if (!isset($_GET['code'], $_GET['state'])){
            return;
        }

        $authorization_code = sanitize_text_field($_GET['code']);
        $client_state = sanitize_text_field($_GET['state']);

        if (empty($authorization_code) || empty($client_state)){
            return;
        }

        if (!wp_verify_nonce($client_state)){
            return;
        }

        $response = wp_remote_post(rtrim(get_option(WP_LOCATOR_OAUTH_AUTHORITY), '/') . '/oauth/token', [
            'body' => [
                'client_id' => get_option(WP_LOCATOR_OAUTH_CLIENT_ID),
                'client_secret' => get_option(WP_LOCATOR_OAUTH_CLIENT_SECRET),
                'grant_type' => 'authorization_code',
                'code' => $authorization_code,
                'redirect_uri' => rtrim(admin_url(), '/') . '/admin.php?page=wp-locator-admin'
            ]
        ]);

        $body = json_decode(wp_remote_retrieve_body($response), true);

        update_option(WP_LOCATOR_OAUTH_REFRESH_TOKEN, $body['refresh_token']);
        set_transient(WP_LOCATOR_OAUTH_ACCESS_TOKEN, $body['access_token'], 3600);

    }

    // Loads the HTML for the main WP Locator page.
    public function load_home_page()
    {
        $authorization_url = $this->plugin->oauth->get_authorization_url();
        require plugin_dir_path(__FILE__) . '/views/html-admin-page-home.php';

    }

    public function show_admin_notices()
    {
        if (!$this->has_client_credentials()){
            ?>
                <div class="notice notice-warning">
                    <p>
                        WP Locator: Missing client credentials, please register for client credentials or enter them on the WP Locator Admin page.</p>
                        <form action="<?= admin_url('admin-post.php')?>" method="post">
                            <input type="hidden" name="action" value="wp_locator_dcr_register">
                            <button type="submit" class="button-primary" <?php echo !$this->is_dcr_enabled() ? 'disabled' : null ?>>Register</button>
                        </form>
                        <a href="<?php menu_page_url('wp-locator-admin') ?>" class="button">Enter Credentials</a>
                    </p>
                </div>
            <?php
        }
    }

    /**
     * Checks if the client credentials have been set in the WP Options table.
     * Returns false if there is a problem and the user needs to register or re-register the client.
     * @return bool
     */
    protected function has_client_credentials(){

        if (empty(get_option(WP_LOCATOR_OAUTH_CLIENT_ID)) || empty(get_option(WP_LOCATOR_OAUTH_CLIENT_SECRET))){

            return false;

        }

        return true;
    }

    public function is_dcr_enabled()
    {

        $isEnabled = get_option(WP_LOCATOR_OAUTH_USE_DCR);

        if (!is_numeric($isEnabled)){
            return false;
        }

        if (!$isEnabled){
            return false;
        }

        return true;
    }

    public function has_refresh_token()
    {
        $refresh_token = get_option(WP_LOCATOR_OAUTH_REFRESH_TOKEN);

        if (!$refresh_token){
            return false;
        }

        return true;
    }

    public function get_status()
    {
        if (!$this->has_client_credentials()){
            return 'disconnected';
        }

        if (!$this->has_refresh_token()){
            return 'disconnected';
        }

        return 'connected';
    }

    public function render_status()
    {
        $status = $this->get_status();
        $status_text = $status === 'connected' ? 'Connected' : 'Disconnected';
        $status_color = $status === 'connected' ? 'green-500' : 'red-500';
        ?>
            <div class="mr-auto block flex items-center">
                <div class="relative h-6 w-6 mr-4">
                    <span class="animate-ping-discrete absolute h-full w-full bg-<?= $status_color ?> opacity-75 rounded-full"></span>
                    <span class="block rounded-full bg-<?= $status_color ?> h-full w-full"></span>
                </div>
                <div class="">
                    <span class="text-<?= $status_color ?> font-bold text-lg block"><?= $status_text ?></span>
                    <small class="block text-xs">Last Sync: Never</small>
                </div>
            </div>
        <?php
    }

    public function register_post_type_columns($columns)
    {

        unset($columns['date']);

        $columns['api_id'] = __('API Id', 'wp-locator-plugin');
        $columns['updated_at'] = __('Date Updated', 'wp-locator-plugin');
        $columns['created_at'] = __('Date Created', 'wp-locator-plugin');

        return $columns;

    }

    public function custom_location_columns($column, $post_id)
    {

        switch ($column){
            case 'api_id':
                echo get_post_meta($post_id, '_wp_locator_id', true);
                break;
            case 'updated_at':
                echo date_create(get_post_meta($post_id, '_wp_locator_updated_at', true))->format('d-m-Y H:i:s');
                break;
            case 'created_at':
                echo date_create(get_post_meta($post_id, '_wp_locator_created_at', true))->format('d-m-Y H:i:s');
                break;
        }

    }

    public function register_meta()
    {
        $meta = [
            '_wp_locator_id' => 'id',
            '_wp_locator_name' => 'name',
            '_wp_locator_display_name' => 'display_name',
            '_wp_locator_location' => 'location',
            '_wp_locator_opening_hours' => 'opening_hours',
            '_wp_locator_contact_details' => 'contact_details',
            '_wp_locator_created_at' => 'created_at',
            '_wp_locator_updated_at' => 'updated_at'
        ];

        foreach ($meta as $key){
            register_meta('post', $key, [
                'object_subtype' => 'location'
            ]);
        }
    }

    public function add_meta_boxes()
    {
        add_meta_box('wp-locator-plugin', 'Location Attributes', [$this, 'meta_box_html'], 'location');
    }

    public function meta_box_html()
    {
        $fields = [
            '_wp_locator_id' => 'id',
            '_wp_locator_name' => 'name',
            '_wp_locator_display_name' => 'display_name',
            '_wp_locator_location' => 'location',
            '_wp_locator_opening_hours' => 'opening_hours',
            '_wp_locator_contact_details' => 'contact_details',
            '_wp_locator_created_at' => 'created_at',
            '_wp_locator_updated_at' => 'updated_at'
        ];
        $location_id = get_the_ID();
        require plugin_dir_path(__FILE__) . '/views/html-admin-location-meta-box.php';
    }
}