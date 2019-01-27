<?php
/**
 * User Order
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */
namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Order class.
 */
class Order {

	/**
	 * Order Data array. This is the core order data exposed in APIs since 3.0.0.
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $data = array(
		// Abstract order props.
		'parent_id'            => 0,
		'status'               => '',
		'currency'             => '',
		'version'              => '',
		'prices_include_tax'   => false,
		'date_created'         => null,
		'date_modified'        => null,
		'discount_total'       => 0,
		'discount_tax'         => 0,
		'shipping_total'       => 0,
		'shipping_tax'         => 0,
		'cart_tax'             => 0,
		'total'                => 0,
		'total_tax'            => 0,
		// Order props.
		'customer_id'          => 0,
		'order_key'            => '',
		'billing'              => array(
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
			'country'    => '',
			'email'      => '',
			'phone'      => '',
		),
		'shipping'             => array(
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
			'country'    => '',
		),
		'payment_method'       => '',
		'payment_method_title' => '',
		'transaction_id'       => '',
		'customer_ip_address'  => '',
		'customer_user_agent'  => '',
		'created_via'          => '',
		'customer_note'        => '',
		'date_completed'       => null,
		'date_paid'            => null,
		'cart_hash'            => '',
	);

	/**
	 * Order items will be stored here, sometimes before they persist in the DB.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Get the order if ID is passed, otherwise the order is new and empty.
	 * This class should NOT be instantiated, but the get_order function or new WC_Order_Factory.
	 * should be used. It is possible, but the aforementioned are preferred and are the only.
	 * methods that will be maintained going forward.
	 *
	 * @param int|object|Order $order Order to read.
	 */
	public function __construct( $order = 0 ) {
		parent::__construct( $order );
		if ( is_numeric( $order ) && $order > 0 ) {
			$this->set_id( $order );
		} elseif ( $order instanceof self ) {
			$this->set_id( $order->get_id() );
		} elseif ( ! empty( $order->ID ) ) {
			$this->set_id( $order->ID );
		} else {
			$this->set_object_read( true );
		}
		$this->data_store = WC_Data_Store::load( $this->data_store_name );
		if ( $this->get_id() > 0 ) {
			$this->data_store->read( $this );
		}
	}

	/**
	 * Save data to the database.
	 *
	 * @since 3.0.0
	 * @return int order ID
	 */
	public function save() {
		try {
			$this->maybe_set_user_billing_email();
			if ( $this->data_store ) {
				// Trigger action before saving to the DB. Allows you to adjust object props before save.
				do_action( 'woocommerce_before_' . $this->object_type . '_object_save', $this, $this->data_store );
				if ( $this->get_id() ) {
					$this->data_store->update( $this );
				} else {
					$this->data_store->create( $this );
				}
			}
			$this->save_items();
			$this->status_transition();
		} catch ( Exception $e ) {
			$logger = wc_get_logger();
			$logger->error(
				sprintf( 'Error saving order #%d', $this->get_id() ), array(
					'order' => $this,
					'error' => $e,
				)
			);
			$this->add_order_note( __( 'Error saving order.', 'woocommerce' ) . ' ' . $e->getMessage() );
		}
		return $this->get_id();
	}

	/**
	 * Get date_created.
	 *
	 * @param  string $context View or edit context.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get date_modified.
	 *
	 * @param  string $context View or edit context.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Return the order statuses without wc- internal prefix.
	 *
	 * @param  string $context View or edit context.
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		$status = $this->get_prop( 'status', $context );
		if ( empty( $status ) && 'view' === $context ) {
			// In view context, return the default status if no status has been set.
			$status = apply_filters( 'woocommerce_default_order_status', 'pending' );
		}
		return $status;
	}

	/**
	 * Get shipping_total.
	 *
	 * @param  string $context View or edit context.
	 * @return string
	 */
	public function get_shipping_total( $context = 'view' ) {
		return $this->get_prop( 'shipping_total', $context );
	}

	/**
	 * Gets order grand total. incl. taxes. Used in gateways.
	 *
	 * @param  string $context View or edit context.
	 * @return float
	 */
	public function get_total( $context = 'view' ) {
		return $this->get_prop( 'total', $context );
	}

	/**
	 * Gets order subtotal.
	 *
	 * @return float
	 */
	public function get_subtotal() {
		$subtotal = 0;
		foreach ( $this->get_items() as $item ) {
			$subtotal += $item->get_subtotal();
		}
		return apply_filters( 'woocommerce_order_get_subtotal', (double) $subtotal, $this );
	}

	/**
	 * Alias for get_customer_id().
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_customer_id( $context );
	}

	/**
	 * Get the user associated with the order. False for guests.
	 *
	 * @return WP_User|false
	 */
	public function get_user() {
		return $this->get_user_id() ? get_user_by( 'id', $this->get_user_id() ) : false;
	}

	/**
	 * Set order status.
	 *
	 * @since 3.0.0
	 * @param string $new_status    Status to change the order to. No internal wc- prefix is required.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual_update Is this a manual order status change?.
	 * @return array
	 */
	public function set_status( $new_status, $note = '', $manual_update = false ) {
		$result = parent::set_status( $new_status );
		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from'   => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'     => $result['to'],
				'note'   => $note,
				'manual' => (bool) $manual_update,
			);
			if ( $manual_update ) {
				do_action( 'woocommerce_order_edit_status', $this->get_id(), $result['to'] );
			}
			$this->maybe_set_date_paid();
			$this->maybe_set_date_completed();
		}
		return $result;
	}

	/**
	 * Remove item from the order.
	 *
	 * @param int $item_id Item ID to delete.
	 * @return false|void
	 */
	public function remove_item( $item_id ) {
		$item      = $this->get_item( $item_id, false );
		$items_key = $item ? $this->get_items_key( $item ) : false;
		if ( ! $items_key ) {
			return false;
		}
		// Unset and remove later.
		$this->items_to_delete[] = $item;
		unset( $this->items[ $items_key ][ $item->get_id() ] );
	}

	/**
	 * Get an order item object, based on its type.
	 *
	 * @since  3.0.0
	 * @param  int  $item_id ID of item to get.
	 * @param  bool $load_from_db Prior to 3.2 this item was loaded direct from WC_Order_Factory, not this object. This param is here for backwards compatility with that. If false, uses the local items variable instead.
	 * @return WC_Order_Item|false
	 */
	public function get_item( $item_id, $load_from_db = true ) {
		if ( $load_from_db ) {
			return WC_Order_Factory::get_order_item( $item_id );
		}
		// Search for item id.
		if ( $this->items ) {
			foreach ( $this->items as $group => $items ) {
				if ( isset( $items[ $item_id ] ) ) {
					return $items[ $item_id ];
				}
			}
		}
		// Load all items of type and cache.
		$type = $this->data_store->get_order_item_type( $this, $item_id );
		if ( ! $type ) {
			return false;
		}
		$items = $this->get_items( $type );
		return ! empty( $items[ $item_id ] ) ? $items[ $item_id ] : false;
	}

	/**
	 * Adds an order item to this order. The order item will not persist until save.
	 *
	 * @since 3.0.0
	 * @param WC_Order_Item $item Order item object (product, shipping, fee, coupon, tax).
	 * @return false|void
	 */
	public function add_item( $item ) {
		$items_key = $this->get_items_key( $item );
		if ( ! $items_key ) {
			return false;
		}
		// Make sure existing items are loaded so we can append this new one.
		if ( ! isset( $this->items[ $items_key ] ) ) {
			$this->items[ $items_key ] = $this->get_items( $item->get_type() );
		}
		// Set parent.
		$item->set_order_id( $this->get_id() );
		// Append new row with generated temporary ID.
		$item_id = $item->get_id();
		if ( $item_id ) {
			$this->items[ $items_key ][ $item_id ] = $item;
		} else {
			$this->items[ $items_key ][ 'new:' . $items_key . count( $this->items[ $items_key ] ) ] = $item;
		}
	}

	/**
	 * Calculate shipping total.
	 *
	 * @since 2.2
	 * @return float
	 */
	public function calculate_shipping() {
		$shipping_total = 0;
		foreach ( $this->get_shipping_methods() as $shipping ) {
			$shipping_total += $shipping->get_total();
		}
		$this->set_shipping_total( $shipping_total );
		$this->save();
		return $this->get_shipping_total();
	}

	/**
	 * Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
	 *
	 * @since 2.2
	 * @param  bool $and_taxes Calc taxes if true.
	 * @return float calculated grand total.
	 */
	public function calculate_totals( $and_taxes = true ) {
		do_action( 'woocommerce_order_before_calculate_totals', $and_taxes, $this );
		$cart_subtotal     = 0;
		$cart_total        = 0;
		$fee_total         = 0;
		$shipping_total    = 0;
		$cart_subtotal_tax = 0;
		$cart_total_tax    = 0;
		// Sum line item costs.
		foreach ( $this->get_items() as $item ) {
			$cart_subtotal += round( $item->get_subtotal(), wc_get_price_decimals() );
			$cart_total    += round( $item->get_total(), wc_get_price_decimals() );
		}
		// Sum shipping costs.
		foreach ( $this->get_shipping_methods() as $shipping ) {
			$shipping_total += round( $shipping->get_total(), wc_get_price_decimals() );
		}
		$this->set_shipping_total( $shipping_total );
		// Sum fee costs.
		foreach ( $this->get_fees() as $item ) {
			$amount = $item->get_amount();
			if ( 0 > $amount ) {
				$item->set_total( $amount );
				$max_discount = round( $cart_total + $fee_total + $shipping_total, wc_get_price_decimals() ) * -1;
				if ( $item->get_total() < $max_discount ) {
					$item->set_total( $max_discount );
				}
			}
			$fee_total += $item->get_total();
		}
		// Calculate taxes for items, shipping, discounts. Note; this also triggers save().
		if ( $and_taxes ) {
			$this->calculate_taxes();
		}
		// Sum taxes.
		foreach ( $this->get_items() as $item ) {
			$cart_subtotal_tax += $item->get_subtotal_tax();
			$cart_total_tax    += $item->get_total_tax();
		}
		$this->set_discount_total( $cart_subtotal - $cart_total );
		$this->set_discount_tax( $cart_subtotal_tax - $cart_total_tax );
		$this->set_total( round( $cart_total + $fee_total + $this->get_shipping_total() + $this->get_cart_tax() + $this->get_shipping_tax(), wc_get_price_decimals() ) );
		do_action( 'woocommerce_order_after_calculate_totals', $and_taxes, $this );
		$this->save();
		return $this->get_total();
	}

	/**
	 * Get line subtotal - this is the cost before discount.
	 *
	 * @param object $item Item to get total from.
	 * @param bool   $inc_tax (default: false).
	 * @param bool   $round (default: true).
	 * @return float
	 */
	public function get_line_subtotal( $item, $inc_tax = false, $round = true ) {
		$subtotal = 0;
		if ( is_callable( array( $item, 'get_subtotal' ) ) ) {
			if ( $inc_tax ) {
				$subtotal = $item->get_subtotal() + $item->get_subtotal_tax();
			} else {
				$subtotal = $item->get_subtotal();
			}
			$subtotal = $round ? round( $subtotal, wc_get_price_decimals() ) : $subtotal;
		}
		return apply_filters( 'woocommerce_order_amount_line_subtotal', $subtotal, $this, $item, $inc_tax, $round );
	}
	/**
	 * Calculate item cost - useful for gateways.
	 *
	 * @param object $item Item to get total from.
	 * @param bool   $inc_tax (default: false).
	 * @param bool   $round (default: true).
	 * @return float
	 */
	public function get_item_total( $item, $inc_tax = false, $round = true ) {
		$total = 0;
		if ( is_callable( array( $item, 'get_total' ) ) && $item->get_quantity() ) {
			if ( $inc_tax ) {
				$total = ( $item->get_total() + $item->get_total_tax() ) / $item->get_quantity();
			} else {
				$total = floatval( $item->get_total() ) / $item->get_quantity();
			}
			$total = $round ? round( $total, wc_get_price_decimals() ) : $total;
		}
		return apply_filters( 'woocommerce_order_amount_item_total', $total, $this, $item, $inc_tax, $round );
	}

	/**
	 * Checks the order status against a passed in status.
	 *
	 * @param array|string $status Status to check.
	 * @return bool
	 */
	public function has_status( $status ) {
		return apply_filters( 'woocommerce_order_has_status', ( is_array( $status ) && in_array( $this->get_status(), $status, true ) ) || $this->get_status() === $status, $this, $status );
	}

	/**
	 * Returns if an order has been paid for based on the order status.
	 *
	 * @since 2.5.0
	 * @return bool
	 */
	public function is_paid() {
		return apply_filters( 'woocommerce_order_is_paid', $this->has_status( wc_get_is_paid_statuses() ), $this );
	}

	/**
	 * Checks if an order needs payment, based on status and order total.
	 *
	 * @return bool
	 */
	public function needs_payment() {
		$valid_order_statuses = apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $this );
		return apply_filters( 'woocommerce_order_needs_payment', ( $this->has_status( $valid_order_statuses ) && $this->get_total() > 0 ), $this, $valid_order_statuses );
	}

	/**
	 * See if the order needs processing before it can be completed.
	 *
	 * Orders which only contain virtual, downloadable items do not need admin
	 * intervention.
	 *
	 * Uses a transient so these calls are not repeated multiple times, and because
	 * once the order is processed this code/transient does not need to persist.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public function needs_processing() {
		$transient_name   = 'wc_order_' . $this->get_id() . '_needs_processing';
		$needs_processing = get_transient( $transient_name );
		if ( false === $needs_processing ) {
			$needs_processing = 0;
			if ( count( $this->get_items() ) > 0 ) {
				foreach ( $this->get_items() as $item ) {
					if ( $item->is_type( 'line_item' ) ) {
						$product = $item->get_product();
						if ( ! $product ) {
							continue;
						}
						$virtual_downloadable_item = $product->is_downloadable() && $product->is_virtual();
						if ( apply_filters( 'woocommerce_order_item_needs_processing', ! $virtual_downloadable_item, $product, $this->get_id() ) ) {
							$needs_processing = 1;
							break;
						}
					}
				}
			}
			set_transient( $transient_name, $needs_processing, DAY_IN_SECONDS );
		}
		return 1 === absint( $needs_processing );
	}
}
