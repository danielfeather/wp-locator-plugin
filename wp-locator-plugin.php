<?php
/*
 * Plugin Name: WP Locator
 * Description: An example used to demonstrate how a plugin can consume on OAuth 2.0 Protected Resource using Dynamic Client Registration (DCR) RFC 7591.
 * Author: Daniel Feather
 * Author URI: https://dfeather.me
 * Text Domain: wp-locator-plugin
 * Requires at least: 5.6
 * Requires PHP: 7.2
 * License: MIT
 */


define('WP_LOCATOR_API_BASE_URL', 'wp-locator-api-base-url');

define('WP_LOCATOR_OAUTH_AUTHORITY', 'wp-locator-oauth-authority');
define('WP_LOCATOR_OAUTH_USE_DCR', 'wp-locator-oauth-use-dcr');
define('WP_LOCATOR_OAUTH_DCR_ENDPOINT', 'wp-locator-oauth-dcr-endpoint');
define('WP_LOCATOR_OAUTH_CLIENT_ID', 'wp-locator-oauth-client-id');
define('WP_LOCATOR_OAUTH_CLIENT_SECRET', 'wp-locator-oauth-client-secret');
define('WP_LOCATOR_OAUTH_REFRESH_TOKEN', 'wp-locator-oauth-refresh-token');

// The access token will be stored in a Wordpress Transient.
define('WP_LOCATOR_OAUTH_ACCESS_TOKEN', 'wp-locator-oauth-access-token');

require_once plugin_dir_path(__FILE__) . '/includes/class-wp-locator-activator.php';
require_once plugin_dir_path(__FILE__) . '/includes/class-wp-locator-deactivator.php';

$activator = new WP_Locator_Activator();
$deactivator = new WP_Locator_Deactivator();

require_once plugin_dir_path(__FILE__) . '/includes/class-wp-locator-plugin.php';

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

register_activation_hook(__FILE__, [$activator, 'activate']);
register_deactivation_hook(__FILE__, [$deactivator, 'deactivate']);

$plugin = new WP_Locator_Plugin();
$plugin->run();