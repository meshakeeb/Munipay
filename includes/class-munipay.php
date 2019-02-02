<?php
/**
 * The Munipay bootstrap loader.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

/**
 * The main theme class.
 */
final class Munipay {

	/**
	 * Theme Version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Holds various class instances.
	 *
	 * @var array
	 */
	private $container = [];

	/**
	 * The single instance of the class.
	 *
	 * @var Munipay
	 */
	protected static $instance = null;

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param  string $prop Property to check.
	 * @return bool
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 * Magic get method.
	 *
	 * @param  string $prop Property to get.
	 * @return mixed Property value or NULL if it does not exists
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}
		return $this->{$prop};
	}

	/**
	 * Magic set method.
	 *
	 * @param mixed $prop  Property to set.
	 * @param mixed $value Value to set.
	 */
	public function __set( $prop, $value ) {
		if ( property_exists( $this, $prop ) ) {
			$this->$prop = $value;
			return;
		}
		$this->container[ $prop ] = $value;
	}

	/**
	 * Magic call method.
	 *
	 * @param  string $name      Method to call.
	 * @param  array  $arguments Arguments to pass when calling.
	 * @return mixed Return value of the callback.
	 */
	public function __call( $name, $arguments ) {
		$hash = [
			'theme_dir'    => MUNIPAY_PATH,
			'theme_uri'    => MUNIPAY_URL,
			'includes_dir' => MUNIPAY_PATH . '/includes',
			'assets'       => MUNIPAY_URL . '/assets',
		];
		if ( isset( $hash[ $name ] ) ) {
			return $hash[ $name ];
		}
		return call_user_func_array( $name, $arguments );
	}

	/**
	 * Main Munipay instance.
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @see munipay()
	 * @return Munipay
	 */
	public static function get() {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof Munipay ) ) {
			self::$instance = new Munipay;
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Instantiate the plugin.
	 */
	private function setup() {
		// Define constants.
		$this->define_constants();

		// instantiate classes.
		$this->instantiate();

		// Loaded action.
		do_action( 'munipay_loaded' );
	}

	/**
	 * Define the plugin constants.
	 */
	private function define_constants() {
		define( 'MUNIPAY_VERSION', $this->version );
		define( 'MUNIPAY_PATH', get_template_directory() );
		define( 'MUNIPAY_URL', get_template_directory_uri() );
	}

	/**
	 * Include the required files and initialize .
	 */
	private function instantiate() {
		include MUNIPAY_PATH . '/vendor/autoload.php';
		new \Munipay\Theme_Setup;
	}
}

/**
 * Main instance of Munipay.
 *
 * Returns the main instance of Munipay to prevent the need to use globals.
 *
 * @return Munipay
 */
function munipay() {
	return Munipay::get();
}


// Kick it off!
munipay();
