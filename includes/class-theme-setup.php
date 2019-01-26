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
		$this->action( 'login_enqueue_scripts', 'login_enqueue' );

		// Add contact methods for author page.
		$this->filter( 'user_contactmethods', 'add_user_contact_methods' );

		// Logout Link
		$this->filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );

		// Allow shortcodes in widget text.
		add_filter( 'widget_text', 'do_shortcode' );

		// Authenticate.
		$this->action( 'template_redirect', 'authenticate_user' );

		// Initiate.
		new Disable_emojis;
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
			'bootstrap'    => $assets . '/vendor/bootstrap/bootstrap.min.css',
			'font-awesome' => $assets . '/vendor/font-awesome/css/font-awesome.min.css',
			'theme'        => $assets . '/css/theme.css',
		];

		foreach ( $styles as $handle => $src ) {
			wp_enqueue_style( $handle, $src, null, munipay()->version );
		}
	}

	public function login_enqueue() {
		$this->enqueue();
		wp_enqueue_style( 'theme-login', munipay()->assets() . '/css/theme-login.css', null, munipay()->version );
	}

	/**
	 * Register navigation menus.
	 */
	public function register_nav_menus() {
		register_nav_menus(
			[
				'main_navigation'   => __( 'Main Navigation', 'munipay' ),
				'footer_navigation' => __( 'Footer Navigation', 'munipay' ),
			]
		);
	}

	/**
	 * Add logout link to both location.
	 *
	 * @param string   $items The HTML list content for the menu items.
	 * @param stdClass $args  An object containing wp_nav_menu() arguments.
	 */
	public function add_loginout_link( $items, $args ) {
		if ( is_user_logged_in() && in_array( $args->theme_location, [ 'main_navigation', 'footer_navigation' ] ) ) {
			$items .= '<li class="nav-item"><a class="nav-link" href="' . wp_logout_url() . '">Log Out</a></li>';
		}

		return $items;
	}

	/**
	 * Authenticate user.
	 */
	public function authenticate_user() {

		// Exceptions for AJAX, Cron, or WP-CLI requests
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			auth_redirect();
		}
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
