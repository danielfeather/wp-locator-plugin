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


define('WP_LOCATOR_API_ENDPOINT', 'wp-locator-api-endpoint');

define('WP_LOCATOR_OAUTH_AUTHORITY', 'wp-locator-oauth-authority');
define('WP_LOCATOR_OAUTH_USE_DCR', 'wp-locator-oauth-use-dcr');
define('WP_LOCATOR_OAUTH_DCR_ENDPOINT', 'wp-locator-oauth-dcr-endpoint');
define('WP_LOCATOR_OAUTH_CLIENT_ID', 'wp-locator-oauth-client-id');
define('WP_LOCATOR_OAUTH_CLIENT_SECRET', 'wp-locator-oauth-client-secret');

require_once __DIR__ . '/includes/class-wp-locator-plugin.php';

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$plugin = new WP_Locator_Plugin();
$plugin->run();