<?php
namespace Remove_Wordpress_Overhead;
use Remove_Wordpress_Overhead\Remove_Wordpress_Overhead_Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

class Remove_Wordpress_Overhead_Settings {

	/**
	 * The single instance of Remove_Wordpress_Overhead_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var	 string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var	 array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = $this->parent->_base;

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Remove WP Overhead', 'remove-wordpress-overhead' ) , __( 'Remove WP Overhead', 'remove-wordpress-overhead' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'remove-wordpress-overhead' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['standard'] = array(
			'title'					=> __( 'Settings', 'remove-wordpress-overhead' ),
			'description'			=> __( 'Check the boxes to remove stuff and keep your HTML and WP clean and website fast.', 'remove-wordpress-overhead' ),
			'fields'				=> array(
				array(
					'id' 			=> 'remove_rsd_link',
					'label'			=> __( 'Remove RSD / EditURI Link <a href="https://en.wikipedia.org/wiki/Really_Simple_Discovery" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://www.site.com/xmlrpc.php?rsd" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_wlwmanifest_link',
					'label'			=> __( 'Remove WLW Manifest Link <a href="https://msdn.microsoft.com/en-us/library/bb463265.aspx" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://www.site.com/wp-includes/wlwmanifest.xml" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_rss_feed_links',
					'label'			=> __( 'Remove RSS Feed Links <a href="https://en.wikipedia.org/wiki/RSS" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="alternate" type="application/rss+xml" title="Site name &raquo; (Comments) Feed" href="http://www.site.com/(comments)/feed" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_next_prev_links',
					'label'			=> __( 'Remove Next &amp; Prev Post Links <a href="https://support.google.com/webmasters/answer/1663744?hl=en" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="prev|prev" title="Some Post Title" href="http://www.site.com/some-slug/" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_shortlink',
					'label'			=> __( 'Remove Shortlink URL <a href="http://microformats.org/wiki/rel-shortlink" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="shortlink" href="http://www.site.com/some-slug/" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_wp_generator',
					'label'			=> __( 'Remove WP Generator Meta', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;meta name="generator" content="WordPress x.x.x" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_version_numbers_from_style_script',
					'label'			=> __( 'Remove Version Numbers from Style and Script Links', 'remove-wordpress-overhead' ),
					'description'	=> __( 'src="http://www.site.com/js/some-script.js?ver=x.x.x and src="http://www.site.com/css/some-style.css?ver=x.x.x<br>Important: this might cause cache busting not to work anymore', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'disable_wp_emojicons',
					'label'			=> __( 'Disable WP Emoji <a href="https://en.support.wordpress.com/emoji/" target="_blank"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( 'Emoji CSS styles and javascript in header', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'disable_json_api',
					'label'			=> __( 'Disable JSON API', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="https://api.w.org/" href="http://www.site.com/wp-json/" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_canonical',
					'label'			=> __( 'Remove Canonical URL <a href="https://support.google.com/webmasters/answer/139066?hl=en"><i class="dashicons dashicons-editor-help"></i></a>', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;link rel="canonical" href="http://www.site.com/some-url" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_woo_generator',
					'label'			=> __( 'Remove WooCommerce Generator Meta', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;meta name="generator" content="WooCommerce x.x.x" /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_jquery_migrate',
					'label'			=> __( 'Remove jQuery Migrate', 'remove-wordpress-overhead' ),
					'description'	=> __( '&lt;script type=\'text/javascript\' src=\'http://www.site.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=x.x.x\'&gt;&lt;/script&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'disable_xmlrpc',
					'label'			=> __( 'Disable XML-RPC Methods', 'remove-wordpress-overhead' ),
					'description'	=> __( 'Disable XML-RPC methods requiring authentication.', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'remove_block_scripts',
					'label'			=> __( 'Remove Gutenberg block scripts', 'remove-wordpress-overhead' ),
					'description'	=> __( 'If you do not use the Gutenberg editor, this will remove all css and scripts added by Gutenberg. Like:<br>&lt;link rel=\'stylesheet\' id=\'wp-block-library-css\'  href=\'https://www.site.com/wp/wp-includes/css/dist/block-library/style.css?ver=x.x.x\' type=\'text/css\' media=\'all\' /&gt;<br>&lt;link rel=\'stylesheet\' id=\'wc-block-style-css\'  href=\'https://www.site.com/app/plugins/woocommerce/packages/woocommerce-blocks/build/style.css?ver=xxx\' type=\'text/css\' media=\'all\' /&gt;', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'disable_wp_widgets',
					'label'			=> __( 'WP Widgets<br><small>Check which widgets you want to disable</small>', 'remove-wordpress-overhead' ),
					'description'	=> __( 'Check which widgets you want to disable', 'remove-wordpress-overhead' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'WP_Widget_Archives' => '1. Archives&nbsp;&nbsp;&nbsp;', 'WP_Widget_Calendar' => '2. Calendar&nbsp;&nbsp;&nbsp;', 'WP_Widget_Categories' => '3. Categories&nbsp;&nbsp;&nbsp;', 'WP_Widget_Links' => '4. Links&nbsp;&nbsp;&nbsp;', 'WP_Widget_Meta' => '5. Meta&nbsp;&nbsp;&nbsp;', 'WP_Nav_Menu_Widget' => '6. Nav Menu&nbsp;&nbsp;&nbsp;', 'WP_Widget_Pages' => '7. Pages&nbsp;&nbsp;&nbsp;', 'WP_Widget_Recent_Comments' => '8. Recent Comments&nbsp;&nbsp;&nbsp;', 'WP_Widget_Recent_Posts' => '9. Recent Posts&nbsp;&nbsp;&nbsp;', 'WP_Widget_RSS' => '10. RSS&nbsp;&nbsp;&nbsp;', 'WP_Widget_Search' => '11. Search&nbsp;&nbsp;&nbsp;', 'WP_Widget_Tag_Cloud' => '12. Tag Cloud&nbsp;&nbsp;&nbsp;', 'WP_Widget_Text' => '13. Text&nbsp;&nbsp;&nbsp;' )
				),
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		$html .= '<div class="remove-wordpress-overhead_slide_selectall"><input type="checkbox" id="' . $this->parent->_token . '_selectall"></input><label for="' . $this->parent->_token . '_selectall"></label> Select all</div>';
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Remove WP Overhead Settings' , 'remove-wordpress-overhead' ) . '</h2>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {

					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'remove-wordpress-overhead' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main Remove_Wordpress_Overhead_Settings Instance
	 *
	 * Ensures only one instance of Remove_Wordpress_Overhead_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Remove_Wordpress_Overhead()
	 * @return Main Remove_Wordpress_Overhead_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
