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

require_once __DIR__ . '/includes/class-wp-locator-plugin.php';

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$plugin = new WP_Locator_Plugin();
$plugin->init();