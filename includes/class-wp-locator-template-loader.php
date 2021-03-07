<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Template_Loader {

    public static function template_loader($template)
    {
        $default_file = self::get_template_loader_default_file();

        if (!$default_file){
            return $template;
        }

        return WP_LOCATOR_PLUGIN_PATH . 'templates/' . $default_file;
    }

    public static function get_template_loader_default_file()
    {
        $default_file = '';

        if (is_singular('location')){
            $default_file = 'single-location.php';
        }

        if (is_post_type_archive('location')){
            $default_file = 'archive-location.php';
        }

        return $default_file;
    }

}