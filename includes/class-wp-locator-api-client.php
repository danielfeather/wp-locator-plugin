<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Api_Client {

    /**
     * @var WP_Locator_OAuth_Client
     */
    protected $oauth_client;

    public function __construct($outh_client)
    {
        $this->api_base_url = rtrim(get_option(WP_LOCATOR_API_BASE_URL), '/');
        $this->oauth_client = $outh_client;
    }

    public function do_api_get($endpoint)
    {
        if (!$endpoint){
            return false;
        }

        if (!is_string($endpoint)){
            return false;
        }

        $trimmed_endpoint = ltrim($endpoint, '/');

        $response = wp_remote_get("{$this->api_base_url}/$trimmed_endpoint", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->oauth_client->get_access_token()
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

        return $json_body;
    }

}