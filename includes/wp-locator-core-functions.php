<?php

function WP_Locator()
{
    global $wp_locator;
    /**
     * @var $plugin WP_Locator_Plugin
     */
    return $wp_locator;
}

function wp_locator_get_template_part($slug, $name = '')
{
    $template = '';

    if ($name){
        $template = locate_template([
            "{$slug}-{$name}.php",
            WP_Locator()->template_path() . "{$slug}-{$name}"
        ]);
        if (!$template){
            $fallback = WP_Locator()->plugin_path() . "/templates/{$slug}-{$name}.php";
            $template = file_exists($fallback) ? $fallback : '';
        }
    }

    if (!$template){

        $template = locate_template([
            "{$slug}.php",
            WP_Locator()->template_path() . "{$slug}.php"
        ]);

    }

    if (!$template){



    }

    apply_filters('wp_locator_get_template_part', $template, $slug, $name);

    if ($template){
        load_template($template, false);
    }
}
