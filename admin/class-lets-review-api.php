<?php 

/**
 *
 * Let's Review API Class
 *
 * @since      1.1.0
 *
 * @package    Let's Review
 * @subpackage lets-review/admin
 */

if ( ! class_exists( 'Lets_Review_API' ) ) {
	class Lets_Review_API {

		/**
		 * Var for Let's Review slug.
		 *
		 * @since    1.1.0
		 */
		private $lets_review_slug;

		/**
		 * Var for Let's Review version.
		 *
		 * @since    1.1.0
		 */
		private $lets_review_version;

		/**
		 * Var for Let's Review URL.
		 *
		 * @since    1.1.0
		 */
		private $lets_review_url;
		

		/**
	     * Admin Constructor
	     *
	     * @since 1.1.0
	     *
	    */
		public function __construct( $lets_review_slug = NULL, $lets_review_version = NULL, $lets_review_url = NULL ) {

			$this->lets_review_slug = $lets_review_slug;
			$this->lets_review_version = $lets_review_version;
			$this->lets_review_url = $lets_review_url;

		}

		/**
		 * Get review final score
		 *
		 * @since    1.1.0
		 */
		public function lets_review_get_final_score( $lets_review_post_id = NULL ) {
			
			if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) != 1 ) {
				return;
			} else {
				$lets_review_frontend = new Lets_Review_Frontend( NULL, NULL, NULL );
				$lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
				$lets_review_ext =  $lets_review_format == 1 ? '<span class="cb-percent-sign">%</span>' : NULL;
				return $lets_review_frontend->lets_review_final_score( $lets_review_post_id, true ) . $lets_review_ext;
			}

		}

		/**
		 * Get review final score subtitle
		 *
		 * @since    1.1.0
		 */
		public function lets_review_get_final_score_subtitle( $lets_review_post_id = NULL ) {
			
			if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) != 1 ) {
				return;
			} else {
				return get_post_meta( $lets_review_post_id, '_lets_review_subtitle', true );
			}

		}
		
		/**
		 * Get review color
		 *
		 * @since    1.1.0
		 */
		public function lets_review_get_color( $lets_review_post_id = NULL ) {

			if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) != 1 ) {
				return;
			} else {
				$lets_review_frontend = new Lets_Review_Frontend( NULL, NULL, NULL );
				return $lets_review_frontend->lets_review_color( $lets_review_post_id );
			}
			

		}

		/**
		 * Get review color
		 *
		 * @since    1.1.0
		 */
		public function lets_review_get_onoff( $lets_review_post_id = NULL ) {

			if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) == 1 ) {
				return true;
			} else { 
				return false;
			}
			
		}
		
	}
}