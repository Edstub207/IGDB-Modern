<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * Let's Review internationalization Class
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/includes
 */

if ( ! class_exists( 'Lets_Review_i18n' ) ) {
	class Lets_Review_i18n {

		/**
		 * Let's Review Translation
		 *
		 * @since    1.0.0
		 */
		public function lets_review_textdomain() {

			load_plugin_textdomain( 'lets-review', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/');

		}
	
	}
}