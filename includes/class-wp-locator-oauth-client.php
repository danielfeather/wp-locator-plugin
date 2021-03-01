<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_OAuth_Client {

    protected $authority;
    protected $client_id;
    protected $client_secret;
    protected $audience;
    protected $scope;
    protected $redirect_uri;
    
    public function __construct()
    {
        $this->authority = get_option(WP_LOCATOR_OAUTH_AUTHORITY);
        $this->client_id = get_option(WP_LOCATOR_OAUTH_CLIENT_ID);
        $this->client_secret = get_option(WP_LOCATOR_OAUTH_CLIENT_SECRET);
        $this->audience = get_option(WP_LOCATOR_API_ENDPOINT);
        $this->scope = 'Locations.Read';
        $this->redirect_uri = rtrim(site_url(), '/') . '/wp-locator/oauth2/callback';
    }

    public function get_authorization_url()
    {
        return rtrim($this->authority, '/') . "/authorize?client_id=$this->client_id&audience=$this->audience&scope=$this->scope&state=testing123&response_type=code&redirect_uri=$this->redirect_uri";
    }
    
}