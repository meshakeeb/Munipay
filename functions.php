<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @since        1.0.0
 * @package      Munipay
 * @link         http://http://boltmedia.ca
 * @copyright    Copyright (C) 2018, BoltMedia - info@boltmedia.ca
 */

defined( 'ABSPATH' ) || exit;

define( 'MUNIPAY_SANDBOX', true );

/**
 * Start the engine.
 */
get_template_part( 'includes/class-munipay' );
