<?php
/*
 * Plugin Name: Remove Wordpress Overhead
 * Version: 1.0.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: remove-wordpress-overhead
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-remove-wordpress-overhead.php' );
require_once( 'includes/class-remove-wordpress-overhead-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-remove-wordpress-overhead-admin-api.php' );
require_once( 'includes/lib/class-remove-wordpress-overhead-post-type.php' );
require_once( 'includes/lib/class-remove-wordpress-overhead-taxonomy.php' );

/**
 * Returns the main instance of Remove_Wordpress_Overhead to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Remove_Wordpress_Overhead
 */
function Remove_Wordpress_Overhead () {
	$instance = Remove_Wordpress_Overhead::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Remove_Wordpress_Overhead_Settings::instance( $instance );
	}

	return $instance;
}

Remove_Wordpress_Overhead();
