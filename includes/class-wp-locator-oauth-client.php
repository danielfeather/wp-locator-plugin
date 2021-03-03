<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_OAuth_Client {

    protected $authority;
    protected $client_id;
    protected $client_secret;
    protected $refresh_token;
    protected $access_token;
    protected $audience;
    protected $scope;
    protected $redirect_uri;
    protected $token_expiration;

    public function __construct()
    {
        $this->authority = get_option(WP_LOCATOR_OAUTH_AUTHORITY);
        $this->client_id = get_option(WP_LOCATOR_OAUTH_CLIENT_ID);
        $this->client_secret = get_option(WP_LOCATOR_OAUTH_CLIENT_SECRET);
        $this->refresh_token = get_option(WP_LOCATOR_OAUTH_REFRESH_TOKEN);
        $this->access_token = get_transient(WP_LOCATOR_OAUTH_ACCESS_TOKEN);
        $this->audience = 'http://localhost:3000/';
        $this->scope = 'Locations.Read offline_access';
        $this->redirect_uri = rtrim(site_url(), '/') . '/wp-locator/oauth2/callback';
        $this->token_expiration = 3600;
    }

    public function get_authorization_url()
    {
        return rtrim($this->authority, '/') . "/authorize?client_id=$this->client_id&audience=$this->audience&scope=$this->scope&state=testing123&response_type=code&redirect_uri=$this->redirect_uri";
    }

    public function get_access_token()
    {
        // 1. Check if there is an existing token stored in a transient
        $access_token = get_transient(WP_LOCATOR_OAUTH_ACCESS_TOKEN);

        if (!$access_token){

            $new_access_token = $this->refresh_token();

            if (!$new_access_token){
                return false;
            }

            set_transient(WP_LOCATOR_OAUTH_ACCESS_TOKEN, $new_access_token, 3600);

            return $new_access_token;

        }

        return $access_token;

    }

    public function refresh_token()
    {
        if (!$this->refresh_token){
            return false;
        }

        if (!$this->client_id || !$this->client_secret ){
            return false;
        }

        $response = wp_remote_post(rtrim(get_option(WP_LOCATOR_OAUTH_AUTHORITY), '/') . '/oauth/token', [
            'body' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refresh_token
            ]
        ]);

        if (is_wp_error($response)){
            return false;
        }

        $response_status_code = wp_remote_retrieve_response_code($response);

        if ($response_status_code > 200){
            return false;
        }

        $raw_body = wp_remote_retrieve_body($response);

        $json_body = json_decode($raw_body, true);

        if (!$json_body){
            return false;
        }

        return $json_body['access_token'];
    }
    
}