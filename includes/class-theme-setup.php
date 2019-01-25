<?php
/**
 * The Theme Setup
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */
namespace Munipay;

use Munipay\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Theme_Setup class.
 */
class Theme_Setup {

	use Hooker;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->action( 'after_setup_theme', 'setup_theme', 2 );
		$this->action( 'after_setup_theme', 'register_nav_menus' );
		$this->action( 'wp_enqueue_scripts', 'enqueue' );

		// Add contact methods for author page.
		$this->filter( 'user_contactmethods', 'add_user_contact_methods' );

		// Allow shortcodes in widget text.
		add_filter( 'widget_text', 'do_shortcode' );
	}

	/**
	 * Setup theme
	 */
	public function setup_theme() {

		/**
		 * Content Width
		 */
		if ( ! isset( $content_width ) ) {
			$content_width = apply_filters( 'munipay_content_width', 700 );
		}

		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Next, use a find and replace
		 * to change 'munipay' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'munipay', MUNIPAY_PATH . '/languages' );

		/**
		 * Theme Support
		 */
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Switch default core markup for search form, comment form, and comments.
		// to output valid HTML5.
		add_theme_support(
			'html5',
			[
				'search-form',
				'gallery',
				'caption',
			]
		);

		// Post formats.
		add_theme_support(
			'post-formats',
			[
				'gallery',
				'image',
				'link',
				'quote',
				'video',
				'audio',
				'status',
				'aside',
			]
		);

		// Add theme support for Custom Logo.
		add_theme_support(
			'custom-logo',
			[
				'width'       => 180,
				'height'      => 60,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);

		// Customize Selective Refresh Widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue() {
		$assets = munipay()->assets();
		$styles = [
			'google-fonts' => '//fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900',
			'bootstrap'    => $assets . '/vendors/bootstrap/bootstrap.min.css',
			'font-awesome' => $assets . '/vendors/font-awesome/css/font-awesome.min.css',
			'theme'        => $assets . '/css/theme.css',
		];

		foreach ( $styles as $handle => $src ) {
			wp_enqueue_style( $handle, $src, null, munipay()->version );
		}

		wp_enqueue_script( 'like_post', get_template_directory_uri() . '/js/post-like.js', '1.0', 1 );
		wp_localize_script(
			'like_post',
			'ajax_var',
			array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ajaxnonce' ),
			)
		);
	}

	/**
	 * Register navigation menus.
	 */
	public function register_nav_menus() {
		register_nav_menus( [ 'main_navigation' => __( 'Main Navigation', 'munipay' ) ] );
	}

	/**
	 * Modifies user contact methods and adds some more social networks.
	 *
	 * @param  array $fields The profile fields.
	 * @return array The profile fields with additional contact methods.
	 */
	public function add_user_contact_methods( $fields ) {
		$fields['author_email']    = 'Email (Author Page)';
		$fields['author_facebook'] = 'Facebook (Author Page)';
		$fields['author_twitter']  = 'Twitter (Author Page)';
		$fields['author_linkedin'] = 'LinkedIn (Author Page)';
		$fields['author_dribble']  = 'Dribble (Author Page)';
		$fields['author_gplus']    = 'Google+ (Author Page)';
		$fields['author_whatsapp'] = 'WhatsApp (Author Page)';
		$fields['author_custom']   = 'Custom Message (Author Page)';

		return $fields;
	}
}
