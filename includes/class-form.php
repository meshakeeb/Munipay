<?php
/**
 * User Form
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Form class.
 */
class Form {

	/**
	 * Display errors.
	 *
	 * @param WP_Error $errors Error instance.
	 */
	public static function display_errors( $errors ) {
		if ( empty( $errors->errors ) ) {
			return;
		}
		?>
		<div class="alert alert-<?php echo 'success' === $errors->get_error_code() ? 'success' : 'danger'; ?>" role="alert">
			<ul class="list-unstyled mb-0">
				<?php echo '<li>' . join( '</li><li>', $errors->get_error_messages() ) . '</li>'; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render text field.
	 *
	 * @param array $args Field args.
	 * @param bool  $only Only input.
	 */
	public static function text( $args = [], $only = false ) {
		$args = wp_parse_args(
			$args,
			[
				'name'        => '',
				'id'          => '',
				'type'        => 'text',
				'class'       => 'form-control',
				'value'       => '',
				'title'       => '',
				'placeholder' => '',
			]
		);

		$args['name']        = empty( $args['name'] ) ? $args['id'] : $args['name'];
		$args['placeholder'] = empty( $args['placeholder'] ) ? $args['title'] : $args['placeholder'];
		$args['placeholder'] = str_replace( '*', '', $args['placeholder'] );

		$title = $args['title'];
		unset( $args['title'] );

		$input = sprintf( '<input%s>', self::attributes_to_string( $args ) );

		if ( $only ) {
			echo $input;
			return;
		}
		?>
		<div class="col">
			<?php echo $input; ?>
			<small class="form-text pl-2 text-muted"><?php echo $title; ?></small>
		</div>
		<?php
	}

	/**
	 * Render select field.
	 *
	 * @param array $args Field args.
	 * @param bool  $only Only input.
	 */
	public static function select( $args = [], $only = false ) {
		$args = wp_parse_args(
			$args,
			[
				'name'        => '',
				'id'          => '',
				'class'       => 'form-control',
				'value'       => '',
				'title'       => '',
				'placeholder' => '',
			]
		);

		$args['name']        = empty( $args['name'] ) ? $args['id'] : $args['name'];
		$args['placeholder'] = empty( $args['placeholder'] ) ? $args['title'] : $args['placeholder'];

		$title   = $args['title'];
		$value   = $args['value'];
		$options = $args['options'];
		unset( $args['title'], $args['options'], $args['value'] );

		// Input.
		$input = sprintf( '<select%s>', self::attributes_to_string( $args ) );
		foreach ( $options as $key => $label ) {
			$input .= sprintf(
				'<option value="%1$s"%3$s>%2$s</option>',
				$key,
				$label,
				selected( $value, $key, false )
			);
		}
		$input .= '</select>';

		if ( $only ) {
			echo $input;
			return;
		}
		?>
		<div class="col">
			<?php echo $input; ?>
			<small class="form-text pl-2 text-muted"><?php echo $title; ?></small>
		</div>
		<?php
	}

	/**
	 * Render file field.
	 *
	 * @param array $args Field args.
	 */
	public static function file( $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'name'        => '',
				'id'          => '',
				'type'        => 'file',
				'class'       => 'custom-file-input',
				'value'       => '',
				'title'       => '',
				'placeholder' => '',
			]
		);

		$args['name']        = empty( $args['name'] ) ? $args['id'] : $args['name'];
		$args['placeholder'] = empty( $args['placeholder'] ) ? $args['title'] : $args['placeholder'];

		$title = $args['title'];
		unset( $args['title'] );
		?>
		<div class="col">
			<div class="custom-file">
				<input<?php echo self::attributes_to_string( $args ); ?>>
				<label class="custom-file-label" for="<?php echo $args['id']; ?>"><?php esc_html_e( 'Choose file...', 'munipay' ); ?></label>
			</div>
			<small class="form-text pl-2 text-muted"><?php echo $title; ?></small>
		</div>
		<?php
	}

	/**
	 * Generate html attribute string for array.
	 *
	 * @param  array  $attributes Contains key/value pair to generate a string.
	 * @param  string $prefix     If you want to append a prefic before every key.
	 * @return string
	 */
	public static function attributes_to_string( $attributes = [], $prefix = '' ) {

		// Early Bail!
		if ( empty( $attributes ) ) {
			return false;
		}

		$out = '';
		foreach ( $attributes as $key => $value ) {
			if ( true === $value || false === $value ) {
				$value = $value ? 'true' : 'false';
			}

			$out .= ' ' . esc_html( $prefix . $key );
			$out .= empty( $value ) ? '' : sprintf( '="%s"', esc_attr( $value ) );
		}

		return $out;
	}
}
