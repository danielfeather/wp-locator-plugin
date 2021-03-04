<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Import_Service extends WP_Locator_Api_Client {

    public function __construct()
    {
        parent::__construct(new WP_Locator_OAuth_Client());
    }

    public function run()
    {
        $locations = $this->do_api_get('/locations');

        if (!is_array($locations)){
            echo 'Unexpected Response';
        }

        foreach ($locations as $location){

            echo $location['name'] . PHP_EOL;

        }
    }

}