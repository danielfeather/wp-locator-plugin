<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_DCR_Client {

    protected $dcr_endpoint;

    public function __construct()
    {
        $this->dcr_endpoint = get_option(WP_LOCATOR_OAUTH_DCR_ENDPOINT);
    }


    public function register()
    {
        $request_data = [
            'client_name' => 'WP Locator on ' . get_bloginfo(),
            'redirect_uris' => [
                rtrim(site_url(), '/') . '/wp-locator/oauth2/callback'
            ]
        ];

        $response = wp_safe_remote_post(get_option(WP_LOCATOR_OAUTH_DCR_ENDPOINT), [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => wp_json_encode($request_data)
        ]);

        $response_body = wp_remote_retrieve_body($response);

        $json_response_body = json_decode($response_body);

        update_option(WP_LOCATOR_OAUTH_CLIENT_ID, $json_response_body->client_id);
        update_option(WP_LOCATOR_OAUTH_CLIENT_SECRET, $json_response_body->client_secret);

        wp_send_json($json_response_body);
    }

    public function deregister()
    {

    }

}