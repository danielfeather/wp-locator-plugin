<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Import_Service extends WP_Locator_Api_Client {

    /**
     * @var array Defines the mapping of post_meta to api attributes;
     */
    protected $mapping = [
        '_wp_locator_id' => 'id',
        '_wp_locator_name' => 'name',
        '_wp_locator_display_name' => 'display_name',
        '_wp_locator_location' => 'location',
        '_wp_locator_opening_hours' => 'opening_hours',
        '_wp_locator_contact_details' => 'contact_details',
        '_wp_locator_created_at' => 'created_at',
        '_wp_locator_updated_at' => 'updated_at'
    ];

    public function __construct()
    {
        parent::__construct(new WP_Locator_OAuth_Client());
    }

    public function run()
    {

        foreach ($this->locations() as $location){

            // Construct the post name for the post name in the posts table.
            $location_slug = sanitize_title($location['name'] . '-' . $location['id']);

            // Check for an existing location with the same name as the above generated slug.
            $existing_location = get_page_by_path($location_slug, 'OBJECT', 'location');

            if ($existing_location){

                if ($location['updated_at'] <= get_post_meta($existing_location->ID, '_wp_locator_updated_at', true)){

                    continue;

                }

                $this->update_location($existing_location->ID, $location_slug, $location);
                continue;

            }

            $new_location_id = wp_insert_post([
                'post_name' => $location_slug,
                'post_title' => sanitize_text_field($location['display_name']),
                'post_type' => 'location',
                'post_status' => 'publish'
            ]);

            foreach ($this->mapping as $meta_key => $name){

                add_post_meta($new_location_id, $meta_key, $location[$name]);

            }

        }
    }

    protected function locations()
    {

        $locations = $this->do_api_get('/locations');

        if (!is_array($locations)){
            echo 'Unexpected Response' . PHP_EOL;
            return;
        }

        foreach ($locations as $location){

            yield $location;

        }

    }

    protected function update_location($id, $post_name, $location_attributes)
    {

        wp_update_post([
            'ID' => $id,
            'post_name' => $post_name,
            'post_title' => sanitize_text_field($location_attributes['display_name'])
        ]);

        foreach ($this->mapping as $meta_key => $name){

            add_post_meta($id, $meta_key, $location_attributes[$name]);

        }

    }

    protected function create_location($post_name, $post_title, $attributes)
    {

        $new_location_id = wp_insert_post([
            'post_name' => $post_name,
            'post_title' => sanitize_text_field($attributes['display_name']),
            'post_type' => 'location',
            'post_status' => 'publish'
        ]);

        foreach ($this->mapping as $meta_key => $name){

            add_post_meta($new_location_id, $meta_key, $attributes[$name]);

        }

    }

}