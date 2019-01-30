<?php
/**
 * Abstract Data.
 *
 * Handles generic data interaction which is implemented by
 * the different data store classes.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */
namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Data Class
 */
abstract class Data {

	/**
	 * ID for this object.
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Core data for this object. Name value pairs (name + default value).
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Post object.
	 *
	 * @var WP_Post
	 */
	protected $object = null;

	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'data';

	/**
	 * Getter property.
	 *
	 * @param string $key Key to get.
	 *
	 * @return mixed
	 */
	public function get_meta( $key ) {
		if ( is_null( $this->object ) && isset( $this->defaults[ $key ] ) ) {
			return $this->defaults[ $key ];
		}

		return $this->object->__get( $key );
	}

	/**
	 * Returns the unique ID for this object.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Read post.
	 */
	public function read() {
		if ( $this->get_id() < 1 ) {
			return;
		}

		$this->object = WP_Post::get_instance( $this->get_id() );
	}

	/**
	 * Save should create or update based on object existence.
	 *
	 * @return int
	 */
	public function save() {
		if ( $this->get_id() ) {
			$this->update();
		} else {
			$this->create();
		}

		return $this->get_id();
	}

	/**
	 * Generate html ids with data id.
	 *
	 * @param string $prefix Prefix to add with id.
	 *
	 * @return string
	 */
	public function get_html_id( $prefix ) {
		echo $prefix . '-' . $this->get_id();
	}

	/**
	 * Format price.
	 *
	 * @param float $price Raw price.
	 *
	 * @return string
	 */
	protected function format_price( $price ) {
		if ( empty( $price ) ) {
			return '';
		}

		return '$' . number_format( $price, 2, '.', ',' );
	}
}
