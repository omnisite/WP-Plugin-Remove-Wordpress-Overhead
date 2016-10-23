<?php
/*
 * Plugin Name: Remove Wordpress Overhead
 * Version: 1.1.0
 * Plugin URI: https://github.com/omnisite/WP-Plugin-Remove-Wordpress-Overhead
 * Description: Remove overhead from the <head> HTML and disable widgets you don't use
 * Author: Omnisite
 * Author URI: http://www.omnisite.nl
 * Requires at least: 3.9
 * Tested up to: 4.6
 *
 * Text Domain: remove-wordpress-overhead
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Omnisite
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Remove_Wordpress_Overhead\Remove_Wordpress_Overhead;
use Remove_Wordpress_Overhead\Remove_Wordpress_Overhead_Settings;

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
	$instance = Remove_Wordpress_Overhead::instance( __FILE__, '1.0.0', 'rwo_' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Remove_Wordpress_Overhead_Settings::instance( $instance );
	}

	return $instance;
}

Remove_Wordpress_Overhead();
