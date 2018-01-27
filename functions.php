<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */


/* ===== Table of Contents =====
– Original Sample Theme Functions
	– Additional Core Functions
– Enqueue Scripts & Styles
– Site Structure
	– Site Header
	– Site Navigation
	– Site Inner
	– Site Footer
– Other Stuff
	– Image Sizes
*/




/* ========== Original Sample Theme Functions ========== */

	// Start the engine.
	include_once( get_template_directory() . '/lib/init.php' );

	// Setup Theme.
	include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

	// Set Localization (do not remove).
	add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
	function genesis_sample_localization_setup(){
		load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );
	}

	// Add the helper functions.
	include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

	// Add Image upload and Color select to WordPress Theme Customizer.
	require_once( get_stylesheet_directory() . '/lib/customize.php' );

	// Include Customizer CSS.
	include_once( get_stylesheet_directory() . '/lib/output.php' );

	// Add WooCommerce support.
	include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

	// Add the required WooCommerce styles and Customizer CSS.
	include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

	// Add the Genesis Connect WooCommerce notice.
	include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

	// Child theme (do not remove).
	define( 'CHILD_THEME_NAME', 'Genesis Sample' );
	define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
	define( 'CHILD_THEME_VERSION', '2.3.1' );

	// Add HTML5 markup structure.
	add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

	// Add Accessibility support.
	add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

	// Add viewport meta tag for mobile browsers.
	add_theme_support( 'genesis-responsive-viewport' );

	// Add support for custom background.
	add_theme_support( 'custom-background' );

	/* ===== Additional Core Functions ===== */

		add_filter( 'theme_page_templates', 'genesis_sample_remove_genesis_page_templates' );
		/**
		 * Remove Genesis Page Templates.
		 *
		 * @author Bill Erickson
		 * @link http://www.billerickson.net/remove-genesis-page-templates
		 *
		 * @param array $page_templates
		 * @return array
		 */
		function genesis_sample_remove_genesis_page_templates( $page_templates ) {
			unset( $page_templates['page_archive.php'] );
			unset( $page_templates['page_blog.php'] );
			return $page_templates;
		}

		add_action( 'genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes' );
		/**
		 * Remove Metaboxes
		 * This removes unused or unneeded metaboxes from Genesis > Theme Settings.
		 * See /genesis/lib/admin/theme-settings for all metaboxes.
		 *
		 * @author Bill Erickson
		 * @link http://www.billerickson.net/code/remove-metaboxes-from-genesis-theme-settings/
		 */
		function genesis_sample_remove_metaboxes( $_genesis_theme_settings_pagehook ) {
			remove_meta_box( 'genesis-theme-settings-blogpage', $_genesis_theme_settings_pagehook, 'main' );
		}




/* ========== Enqueue Scripts & Styles ========== */

	add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
	function genesis_sample_enqueue_scripts_styles() {

		wp_enqueue_style( 'genesis-sample-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700|Roboto+Slab:300,400,700', array(), CHILD_THEME_VERSION );
		wp_enqueue_style( 'child-styles', get_stylesheet_directory_uri() . '/css/main.css' );
		wp_enqueue_style( 'dashicons' );

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'genesis-sample-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
		wp_localize_script(
			'genesis-sample-responsive-menu',
			'genesis_responsive_menu',
			genesis_sample_responsive_menu_settings()
		);
	}




/* ========== Site Structure ========== */

	// Define structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'menu-secondary',
		'footer-widgets',
		'footer'
	) );

	// Unregister content/sidebar/sidebar layout setting.
	genesis_unregister_layout( 'content-sidebar-sidebar' );

	// Unregister sidebar/sidebar/content layout setting.
	genesis_unregister_layout( 'sidebar-sidebar-content' );

	// Unregister sidebar/content/sidebar layout setting.
	// genesis_unregister_layout( 'sidebar-content-sidebar' );

	// Unregister secondary sidebar.
	// unregister_sidebar( 'sidebar-alt' );

	// Move secondary Sidebar inside content-sidebar-wrap
	remove_action( 'genesis_sidebar_alt', 'genesis_get_sidebar_alt' );
	add_action( 'genesis_before_content', 'genesis_get_sidebar_alt' );

	/* ===== Site Header ===== */

		/**
			* Add Custom Logo support
			* Replaces the logo appearing as CSS background with an inline image
			*
			* To add a logo, use the Wordpress Theme Logo feature found in the Customizer
			*
			* @author @_srikat
			* @link https://sridharkatakam.com/theme-logo-genesis/
			*
			*/

		// Add support for custom logo.
		add_theme_support( 'custom-logo', array(
		'width'       => 600,
		'height'      => 160,
		'flex-width' => true,
		'flex-height' => true,
		) );

		add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );
		/**
		 * Add an image inline in the site title element for the logo
		 *
		 * @param string $title Current markup of title.
		 * @param string $inside Markup inside the title.
		 * @param string $wrap Wrapping element for the title.
		 *
		 * @author @_AlphaBlossom
		 * @author @_neilgee
		 * @author @_JiveDig
		 * @author @_srikat
		 */
		function custom_header_inline_logo( $title, $inside, $wrap ) {
			// If the custom logo function and custom logo exist, set the logo image element inside the wrapping tags.
			if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
				$inside = sprintf( '<span class="screen-reader-text">%s</span>%s', esc_html( get_bloginfo( 'name' ) ), get_custom_logo() );
			} else {
				// If no custom logo, wrap around the site name.
				$inside	= sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), esc_html( get_bloginfo( 'name' ) ) );
			}

			// Build the title.
			$title = genesis_markup( array(
				'open'    => sprintf( "<{$wrap} %s>", genesis_attr( 'site-title' ) ),
				'close'   => "</{$wrap}>",
				'content' => $inside,
				'context' => 'site-title',
				'echo'    => false,
				'params'  => array(
					'wrap' => $wrap,
				),
			) );

			return $title;
		}

		add_filter( 'genesis_attr_site-description', 'custom_add_site_description_class' );
		/**
		 * Add class for screen readers to site description.
		 * This will keep the site description markup but will not have any visual presence on the page
		 * This runs if there is a logo image set in the Customizer.
		 *
		 * @param array $attributes Current attributes.
		 *
		 * @author @_neilgee
		 * @author @_srikat
		 */
		function custom_add_site_description_class( $attributes ) {
			if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
				$attributes['class'] .= ' screen-reader-text';
			}

			return $attributes;
		}

		// Remove Header Right widget area.
		unregister_sidebar( 'header-right' );

	/* ===== Site Navigation ===== */

		// Define our responsive menu settings.
		function genesis_sample_responsive_menu_settings() {

			$settings = array(
				'mainMenu'         => __( 'Menu', 'genesis-sample' ),
				'menuIconClass'    => 'dashicons-before dashicons-menu',
				'subMenu'          => __( 'Submenu', 'genesis-sample' ),
				'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
				'menuClasses'      => array(
					'combine' => array(
						'.nav-primary',
						'.nav-header',
					),
					'others'  => array(),
				),
			);

			return $settings;

		}

		// Rename primary and secondary navigation menus.
		add_theme_support( 'genesis-menus', array(
			'primary'   => __( 'Primary Navigation Menu', 'genesis-sample' ),
			'secondary' => __( 'Secondary Navigation Menu', 'genesis-sample' ),
		) );

		// Reposition primary navigation menu to inside the header
		// remove_action( 'genesis_after_header', 'genesis_do_nav' );
		// add_action( 'genesis_header', 'genesis_do_nav' );

	/* ===== Site Inner ===== */

		// Add single post navigation.
		add_action( 'genesis_after_entry', 'genesis_prev_next_post_nav' );
		add_action( 'genesis_after_loop', 'genesis_adjacent_entry_nav' );

		// Add support for after entry widget.
		// add_theme_support( 'genesis-after-entry-widget-area' );

	/* ===== Site Footer ===== */

		// Add support for footer widgets.
		// add_theme_support( 'genesis-footer-widgets', 1 );

		add_filter( 'genesis_footer_creds_text', 'genesis_sample_footer_creds_filter' );
		/**
		 * Change Footer text.
		 *
		 * @link  https://my.studiopress.com/documentation/customization/shortcodes-reference/footer-shortcode-reference/
		 */
		function genesis_sample_footer_creds_filter( $creds ) {
			$creds = 'Designed by <a href="http://design.brubakerministries.org" target="_blank" alt="Brubaker Design Services">Brubaker Design Services</a><div class="admin-login">[footer_loginout]</div>[footer_copyright before="Copyright "] – ';
			$creds = $creds . get_bloginfo('name');

			return $creds;
		}




/* ========== Other Stuff ========== */

	/* ===== Images Sizes ===== */

		// Add Image Sizes.
		add_image_size( 'featured-image', 720, 400, TRUE );

		// Modify size of the Gravatar in the author box.
		add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
		function genesis_sample_author_box_gravatar( $size ) {
			return 150;
		}

		// Modify size of the Gravatar in the entry comments.
		add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
		function genesis_sample_comments_gravatar( $args ) {
			$args['avatar_size'] = 60;
			return $args;
		}
