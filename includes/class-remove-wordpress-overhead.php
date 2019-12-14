<?php
namespace Remove_Wordpress_Overhead;
use Remove_Wordpress_Overhead\Remove_Wordpress_Overhead;

if ( ! defined( 'ABSPATH' ) ) exit;

class Remove_Wordpress_Overhead {

	/**
	 * The single instance of Remove_Wordpress_Overhead.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var	 object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The base prefix.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_base;

	/**
	 * The token.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0', $base = 'wp_plugin_' ) {
		$this->_version = $version;
		$this->_base = $base;
		$this->_token = 'remove_wordpress_overhead';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Remove_Wordpress_Overhead_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		// do the actual removing of stuff
		$this->removeStuff();

		// delete transients on save options page
		add_action( 'load-settings_page_remove_wordpress_overhead_settings', array( $this, 'deleteTransients' ) );

	} // End __construct ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin' . $this->script_suffix . '.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'remove-wordpress-overhead', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
		$domain = 'remove-wordpress-overhead';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Remove_Wordpress_Overhead Instance
	 *
	 * Ensures only one instance of Remove_Wordpress_Overhead is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Remove_Wordpress_Overhead()
	 * @return Main Remove_Wordpress_Overhead instance
	 */
	public static function instance ( $file = '', $version = '1.0.0', $base = 'wp_plugin_' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version, $base );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	/**
	 * Do the removing of stuff
	 * @access	private
	 * @since	 1.0.0
	 * @return	void
	 */
	private function removeStuff() {
		$options = array();
		// get transient with options or set it of not available
		if ( false === $options = get_transient( $this->_base . 'transient_settings' ) ) {
			$options['rsd_link'] = get_option( $this->_base . 'remove_rsd_link' );
			$options['wlwmanifest'] = get_option( $this->_base . 'remove_wlwmanifest_link' );
			$options['feed_links'] = get_option( $this->_base . 'remove_rss_feed_links' );
			$options['next_prev'] = get_option( $this->_base . 'remove_next_prev_links' );
			$options['shortlink'] = get_option( $this->_base . 'remove_shortlink' );
			$options['wp_generator'] = get_option( $this->_base . 'remove_wp_generator' );
			$options['ver'] = get_option( $this->_base . 'remove_version_numbers_from_style_script' );
			$options['emojicons'] = get_option( $this->_base . 'disable_wp_emojicons' );
			$options['json_api'] = get_option( $this->_base . 'disable_json_api' );
			$options['canonical'] = get_option( $this->_base . 'remove_canonical' );
			$options['woo_generator'] = get_option( $this->_base . 'remove_woo_generator' );
			$options['widgets'] = get_option( $this->_base . 'disable_wp_widgets' );
			$options['jquery_migrate'] = get_option( $this->_base . 'remove_jquery_migrate' );
			$options['disable_xmlrpc'] = get_option( $this->_base . 'disable_xmlrpc' );
			$options['remove_block_scripts'] = get_option( $this->_base . 'remove_block_scripts' );
			set_transient( $this->_base . 'transient_settings', $options );
		}

		// remove really simple discovery link
		if ( isset( $options['rsd_link'] ) && 'on' == $options['rsd_link'] ) {
			remove_action( 'wp_head', 'rsd_link' );
		}

		// remove wlwmanifest.xml (needed to support windows live writer)
		if ( isset( $options['wlwmanifest'] ) && 'on' == $options['wlwmanifest'] ) {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}

		// remove rss feed and exta feed links (make sure you add them in yourself if you are using as RSS service
		if ( isset( $options['feed_links'] ) && 'on' == $options['feed_links'] ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}

		// remove the next and previous post links
		if ( isset( $options['next_prev'] ) && 'on' == $options['next_prev'] ) {
			remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		}

		// remove the shortlink url from header
		if ( isset( $options['shortlink'] ) && 'on' == $options['shortlink'] ) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
			remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
		}

		// remove wordpress generator version
		if ( isset( $options['wp_generator'] ) && 'on' == $options['wp_generator'] ) {
			add_filter( 'the_generator', array( $this, 'wp_remove_version' ) );
		}

		// remove ver= after style and script links
		if ( isset( $options['ver'] ) && 'on' == $options['ver'] ) {
			add_filter( 'style_loader_src', array( $this, 'remove_ver_css_js' ), 9999 );
			add_filter( 'script_loader_src', array( $this, 'remove_ver_css_js' ), 9999 );
		}

		// remove emoji styles and script from header
		if ( isset( $options['emojicons'] ) && 'on' == $options['emojicons'] ) {
			add_action( 'init', array( $this, 'disable_wp_emojicons' ) );
		}

		// disable json api and remove link from header
		if ( isset( $options['json_api'] ) && 'on' == $options['json_api'] ) {
			add_action( 'after_setup_theme', array( $this, 'remove_json_api' ) );
			add_action( 'after_setup_theme', array( $this, 'disable_json_api' ) );
		}

		// remove canonical link
		if ( isset( $options['canonical'] ) && 'on' == $options['canonical'] ) {
			remove_action( 'embed_head', 'rel_canonical' );
			add_filter( 'wpseo_canonical', '__return_false' );
		}

		// remove woocommerce generator version
		if ( isset( $options['woo_generator'] ) && 'on' == $options['woo_generator'] ) {
			remove_action( 'wp_head','wc_generator_tag' );
		}

		// disable wp widgets
		if ( isset( $options['widgets'] ) && '' != $options['widgets'] && is_array( $options['widgets'] ) ) {
			// unregister widgets
			add_action( 'widgets_init', array( $this, 'unregister_default_widgets' ), 11 );
		}

		// remove jQuery Migrate script
		if ( isset( $options['jquery_migrate'] ) && 'on' == $options['jquery_migrate'] ) {
			add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ), 9999 );
		}

		// disable XML-RPC
		if ( isset( $options['disable_xmlrpc'] ) && 'on' == $options['disable_xmlrpc'] ) {
			add_action( 'wp_default_scripts', array( $this, 'disable_xmlrpc' ), 9999 );
		}

		// remove Gutenberg scrips
		if ( isset( $options['remove_block_scripts'] ) && 'on' == $options['remove_block_scripts'] ) {
			add_action( 'wp_default_scripts', array( $this, 'remove_block_scripts' ), 9999 );
		}

	}

	/**
	 * Remove JSON API links from header
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function remove_json_api () {
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
	}

	/**
	 * Disable JSON API
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function disable_json_api () {
		add_filter( 'json_enabled', '__return_false' );
		add_filter( 'json_jsonp_enabled', '__return_false' );
		add_filter( 'rest_enabled', '__return_false' );
		add_filter( 'rest_jsonp_enabled', '__return_false' );
	}

	/**
	 * Unregister WP Widgets
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function unregister_default_widgets() {
		$options['widgets'] = get_option( $this->_base . 'disable_wp_widgets' );
		foreach( $options['widgets'] as $widget ) {
			unregister_widget( $widget );
		}
	}

	/**
	 * Delete settings transient on save options page
	 * @access	public
	 * @since	1.0.0
	 * @return	void
	 */
	public function deleteTransients() {
		if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
			delete_transient( $this->_base . 'transient_settings' );
		}
	}

	/**
	 * Remove WP generator link
	 * @access	public
	 * @since	1.1.0
	 * @return	void
	 */
	public function wp_remove_version() {
		return '';
	}

	/**
	 * Remove version numbers at the end of css and js files
	 * @access	public
	 * @since	1.1.0
	 * @return	void
	 */
	public function remove_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Disable WP emojicons
	 * @access	public
	 * @since	1.1.0
	 * @return	void
	 */
	public function disable_wp_emojicons() {
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojicons_tinymce' ) );
	}

	/**
	 * Disable WP emojicons from TinyMCE
	 * @access	public
	 * @since	1.1.0
	 * @return	void
	 */
	public function disable_emojicons_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	/**
	 * Remove jQuery Migrate script
	 * Code from: https://dotlayer.com/what-is-migrate-js-why-and-how-to-remove-jquery-migrate-from-wordpress/
	 * @access	public
	 * @since	1.4.0
	 * @return	void
	 */
	public function remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];

			if ( $script->deps ) { // Check whether the script has any dependencies
				$script->deps = array_diff( $script->deps, array(
					'jquery-migrate'
				) );
			}
		}
	}

	/**
	 * Disable XML-RPC methods that require authentication
	 * @access	public
	 * @since	1.4.0
	 * @return	void
	 */
	public function disable_xmlrpc( $scripts ) {
		add_filter( 'xmlrpc_enabled', '__return_false' );
	}

	/**
	 * Remove all scripts and styles added by Gutenberg
	 * @access	public
	 * @since	1.5.0
	 * @return	void
	 */
	public function remove_block_scripts( $scripts ) {
		add_action( 'wp_enqueue_scripts', array( self::$_instance, 'remove_block_scripts_action' ) );
		remove_action( 'enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles' );
	}

	/**
	 * Dequeue all scripts and styles added by Gutenberg
	 * @access	public
	 * @since	1.5.0
	 * @return	void
	 */
	public function remove_block_scripts_action() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wc-block-style' );
	}

}
