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
		$this->action( 'init', 'change_role_names' );
		$this->action( 'after_setup_theme', 'setup_theme', 2 );
		$this->action( 'after_setup_theme', 'register_nav_menus' );
		$this->action( 'wp_enqueue_scripts', 'enqueue' );
		$this->action( 'login_enqueue_scripts', 'login_enqueue' );

		// Logout Link.
		$this->filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );

		// Allow shortcodes in widget text.
		add_filter( 'widget_text', 'do_shortcode' );

		// Authenticate.
		$this->action( 'template_include', 'reports' );
		$this->action( 'template_redirect', 'authenticate_user' );

		// Change email sernder name and email from.
		$this->filter( 'wp_mail_from', 'mail_from' );
		$this->filter( 'wp_mail_from_name', 'mail_from_name' );

		$this->action( 'pre_get_posts', 'set_author_on_archive_page' );

		// Initiate.
		new Disable_Emojis;
		new Registration;
		new Post_Types;
		new Emails;
		new Crons;
		new Ajax;

		// Shortcodes.
		new Profile;
		new Checkout;
		new Check_Form;
		new Review_Order;
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
			'hasBundle'    => false,
		];

		wp_enqueue_style( 'dashicons' );
		foreach ( $styles as $handle => $src ) {
			wp_enqueue_style( $handle, $src, null, munipay()->version );
		}

		wp_enqueue_script( 'bootstrap', $assets . '/vendor/bootstrap/bootstrap.min.js', [ 'jquery' ], '4.2.1', true );
		wp_enqueue_script( 'serializejson', $assets . '/vendor/jquery.serializejson.min.js', [ 'jquery' ], '2.9.0', true );
		if ( is_page( 'enter-checks' ) ) {
			wp_enqueue_style( 'jquery-ui-base', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
		}
		wp_enqueue_script( 'theme', $assets . '/js/theme.js', [ 'jquery' ], '1.0.0', true );

		$current_user = wp_get_current_user();
		$order_id     = $current_user->current_order;
		$order_id     = $order_id ? $order_id : 0;
		wp_localize_script(
			'theme',
			'munipay',
			[
				'endpoint' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'munipay_security_salt' ),
				'orderID'  => $order_id,
				'userID'   => $current_user->ID,
				'users'    => Profile::get_users_choice( true ),
				'l10n'     => [
					'button_update' => esc_html__( 'Update Check Request', 'munipay' ),
				],
			]
		);
	}

	/**
	 * Change default role names.
	 */
	public function change_role_names() {
		global $wp_roles;
		$wp_roles = wp_roles();

		$wp_roles->roles['editor']['name'] = esc_html__( 'Approvers', 'munipay' );
		$wp_roles->role_names['editor']    = esc_html__( 'Approvers', 'munipay' );

		$wp_roles->roles['author']['name'] = esc_html__( 'Requesters', 'munipay' );
		$wp_roles->role_names['author']    = esc_html__( 'Requesters', 'munipay' );
	}

	/**
	 * Enqueue styles on loging page.
	 */
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
		if ( ! is_user_logged_in() || ! in_array( $args->theme_location, [ 'main_navigation', 'footer_navigation' ] ) ) {
			return $items;
		}

		// Report Viewer.
		if ( current_user_can( 'administrator' ) ) {
			$items = '<li class="nav-item"><a class="nav-link" href="' . home_url( '/reports' ) . '">Reports</a></li>' . $items;
		}

		// Logout Link.
		$items .= '<li class="nav-item"><a class="nav-link" href="' . wp_logout_url() . '">Log Out</a></li>';

		return $items;
	}

	/**
	 * Authenticate user.
	 */
	public function authenticate_user() {

		// Exceptions for AJAX, Cron, or WP-CLI requests.
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		if ( isset( $_GET['action'] ) && 'status_update' === $_GET['action'] ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			auth_redirect();
		}
	}

	/**
	 * Include reports.
	 *
	 * @param string $template Current template.
	 */
	public function reports( $template ) {
		global $wp_query;

		if ( is_user_logged_in() && 'reports' === get_query_var( 'name' ) ) {
			$wp_query->is_404 = false;
			$template         = locate_template( [ 'reports.php' ] );
			$this->filter( 'pre_get_document_title', 'report_title' );
		}

		return $template;
	}

	/**
	 * Report page title.
	 *
	 * @return string
	 */
	public function report_title() {
		return 'Reports';
	}

	/**
	 * Email address to send from.
	 *
	 * @param string $from_email Email address to send from.
	 *
	 * @return string
	 */
	public function mail_from( $from_email ) {
		return 'no-reply@munipay.io';
	}

	/**
	 * Name to associate with the “from” email address.
	 *
	 * @param string $from_name Name associated with the "from" email address.
	 *
	 * @return string
	 */
	public function mail_from_name( $from_name ) {
		return 'Munipay';
	}

	/**
	 * Set order author on archive page.
	 *
	 * @param WP_Query $query Current query instance.
	 */
	public function set_author_on_archive_page( $query ) {

		if ( ! is_admin() && $query->is_main_query() && is_post_type_archive() ) {
			if ( ! current_user_can( 'administrator' ) ) {
				$query->set( 'author', get_current_user_id() );
			}

			$query->set( 'orderby', 'date ID' );
		}
	}
}
