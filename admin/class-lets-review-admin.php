<?php 

/**
 *
 * Let's Review Admin Class
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/admin
 */

if ( ! class_exists( 'Lets_Review_Admin' ) ) {
	class Lets_Review_Admin {

		/**
		 * Var for Let's Review slug.
		 *
		 * @since    1.0.0
		 */
		private $lets_review_slug;

		/**
		 * Var for Let's Review version.
		 *
		 * @since    1.0.0
		 */
		private $lets_review_version;

		/**
		 * Var for Let's Review URL.
		 *
		 * @since    1.0.0
		 */
		private $lets_review_url;

		/**
	     * Admin Constructor
	     *
	     * @since 1.0.0
	     *
	    */
		public function __construct( $lets_review_slug, $lets_review_version, $lets_review_url ) {

			$this->lets_review_slug = $lets_review_slug;
			$this->lets_review_version = $lets_review_version;
			$this->lets_review_url = $lets_review_url;

		}

		/**
		 * Load Let's Review scripts for backend
		 *
		 * @since    1.0.0
		 */
		public function lets_review_enqueue_scripts( $pagenow ) {

			if ( ( $pagenow == 'post.php' ) || ( $pagenow == 'post-new.php' ) || ( strpos( $pagenow, $this->lets_review_slug ) !== false ) || ( $pagenow == 'widgets.php' ) ) {
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-slider');
				
	        	wp_enqueue_media();
				wp_enqueue_script( $this->lets_review_slug . '-js-ext', esc_url( $this->lets_review_url  . 'admin/js/' . $this->lets_review_slug . '-admin-ext.js' ), array( 'jquery' ), $this->lets_review_version, true );
				wp_enqueue_script( $this->lets_review_slug . '-js', esc_url( $this->lets_review_url  . 'admin/js/' . $this->lets_review_slug . '-admin.js' ), array( 'jquery', $this->lets_review_slug . '-js-ext', 'jquery-ui-core'  ), $this->lets_review_version, true );
	            wp_localize_script( $this->lets_review_slug . '-js', 'letsReview', 
	            	array( 
	            		'cbTitle' 		=> esc_html__( 'Title', 'lets-review' ), 
	            		'cbScoreTitle' 	=> esc_html__( 'Score', 'lets-review' ), 
	        			'cbAdd' 		=> esc_html__( 'Add', 'lets-review' ),
	        			'cbMediaButton' => esc_html__( 'Insert', 'lets-review' ),
	        			'cbMediaSTitle' => esc_html__( 'Select Image', 'lets-review' ),
	        			'cbMediaTitle' 	=> esc_html__( 'Select or Upload Images', 'lets-review' ),
	        			'cbUrlTitle' 	=> esc_html__( 'Affiliate URL', 'lets-review' ),

	        		)
	            );

			}

		}

		/**
		 * Load Let's Review stylesheet for backend
		 *
		 * @since    1.0.0
		 */
		public function lets_review_enqueue_styles( $pagenow ) {

			if ( ( $pagenow == 'post.php' ) || ( $pagenow == 'post-new.php' ) ||  ( strpos( $pagenow, $this->lets_review_slug ) !== false ) || ( $pagenow == 'widgets.php' ) ) {
				$lets_review_ssl = is_ssl() ? 'https' : 'http';
	    		wp_enqueue_style( 'montserrat', esc_url( $lets_review_ssl . '://fonts.googleapis.com/css?family=Montserrat:400,700') );

	    		if ( is_rtl() ) {
	    			$lets_review_ext = '-admin-rtl';
	    		} else {
	    			$lets_review_ext = '-admin';
	    		}
	    		
				wp_enqueue_style( $this->lets_review_slug, esc_url( $this->lets_review_url  . 'admin/css/' . $this->lets_review_slug .  $lets_review_ext . '.css' ), array(), $this->lets_review_version, 'all' );
				if ( intval( get_option('lets_review_gen_fa') ) == 1 ) {
	            	wp_enqueue_style( 'fontAwesome', esc_url( $this->lets_review_url  . 'admin/css/font-awesome-4.6.1/css/font-awesome.min.css' ), array(), $this->lets_review_version, 'all' );
	            }
			}

		}
		
	}
}