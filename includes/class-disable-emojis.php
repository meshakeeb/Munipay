<?php
/**
 * Disable Emojis
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
 * Disable_Emojis class.
 */
class Disable_Emojis {

	use Hooker;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->action( 'init', 'init', 2 );
	}

	/**
	 * Disable the emoji's
	 */
	public function init() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		$this->filter( 'tiny_mce_plugins', 'tinymce' );
		$this->filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );
	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param array $plugins
	 *
	 * @return array Difference betwen the two arrays
	 */
	public function tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, [ 'wpemoji' ] );
		}

		return [];
	}

	/**
	 * Remove emoji CDN hostname from DNS prefetching hints.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for.
	 *
	 * @return array Difference betwen the two arrays.
	 */
	public function remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			// Strip out any URLs referencing the WordPress.org emoji location
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ( $urls as $key => $url ) {
				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[ $key ] );
				}
			}
		}

		return $urls;
	}
}
