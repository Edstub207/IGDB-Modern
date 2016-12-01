<?php 

/**
 *
 * Let's Review Frontend Class
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/frontend
 */

if ( ! class_exists( 'Lets_Review_Frontend' ) ) {
	class Lets_Review_Frontend {

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
	     * Frontend Constructor
	     *
	     * @since 1.0.0
	     *
	    */
		public function __construct( $lets_review_slug = NULL, $lets_review_version = NULL, $lets_review_url = NULL ) {

			$this->lets_review_slug = $lets_review_slug;
			$this->lets_review_version = $lets_review_version;
			$this->lets_review_url = $lets_review_url;
			add_action( 'wp_ajax_lets_review_a_cb', array( $this, 'lets_review_a_cb' ) ); 
        	add_action( 'wp_ajax_nopriv_lets_review_a_cb', array( $this, 'lets_review_a_cb' ) );

		}

		/**
		 * Load Let's Review scripts frontend
		 *
		 * @since    1.0.0
		 */
		public function lets_review_enqueue_scripts_frontend() {

			wp_enqueue_script( $this->lets_review_slug . '-js-ext', esc_url( $this->lets_review_url  . 'frontend/js/' . $this->lets_review_slug . '-ext.js' ), array( 'jquery' ), $this->lets_review_version, true );

			if ( get_option( 'lets_review_gen_js_min' ) != 1 ) {
				$lets_review_js_ext = '-scripts-source.js';
			} else {
				$lets_review_js_ext = '-scripts.min.js';
			}

			wp_enqueue_script( $this->lets_review_slug . '-js', esc_url( $this->lets_review_url  . 'frontend/js/' . $this->lets_review_slug . $lets_review_js_ext ), array( 'jquery', $this->lets_review_slug . '-js-ext'  ), $this->lets_review_version, true );
        	global $post;
            wp_localize_script( $this->lets_review_slug . '-js', 'letsReview', 
            	array( 
            		'letsReviewTitle' 		=> esc_html__( 'Title', 'lets-review' ),
            		'letsReviewAUrl'   => admin_url( 'admin-ajax.php' ),
            		'letsReviewNonce' => wp_create_nonce( 'lets_review_nonce' ),
            		'letsReviewPostID' => $post->ID,
        		)
            );

            wp_localize_script( $this->lets_review_slug . '-js-ext', 'letsReview', 
            	array( 
            		'letsReviewLb'	=> intval( get_option('lets_review_gen_lb') ),
        		)
            );

		}

		/**
		 * Let's Review Fonts 
		 *
		 * @since    1.0.0
		 */
		public function lets_review_enqueue_styles_frontend() {

			$lets_review_ssl = is_ssl() ? 'https' : 'http';
			$lets_review_font_headings = ( ( get_option('lets_review_gen_type_headings') == NULL ) || ( get_option('lets_review_gen_type_headings') == 'Inherit from Theme' ) ) ? NULL : get_option('lets_review_gen_type_headings');
			$lets_review_font_p = ( ( get_option('lets_review_gen_type_p') == NULL ) || ( get_option('lets_review_gen_type_p') == 'Inherit from Theme' ) ) ? NULL : get_option('lets_review_gen_type_p');
			$lets_review_font_ext = ( get_option('lets_review_gen_type_ext') == NULL ) ? NULL : get_option('lets_review_gen_type_ext');
			$lets_review_font_p_url = $lets_review_font_headings_url = $lets_review_font_ext_url = NULL;

			if ( $lets_review_font_headings != NULL ) {

	            $lets_review_font_headings_url = str_replace(' ', '+', $lets_review_font_headings ) . ':400,700';
	        }

			if ( ( $lets_review_font_p != NULL ) && ( $lets_review_font_p != $lets_review_font_headings ) ) {

	            $lets_review_font_p_url = '|' . str_replace(' ', '+', $lets_review_font_p ) . ':400,700,400italic';
	        }

	        if ( ( $lets_review_font_ext != NULL ) ) {
	        	$lets_review_font_ext_url .= '&subset=';
				foreach ( $lets_review_font_ext as $key => $value) {
					$lets_review_font_ext_url .= $value .',';
				}
				$lets_review_font_ext_url = rtrim( $lets_review_font_ext_url, ',');
	        }


			if ( ( $lets_review_font_p_url != NULL ) || ( $lets_review_font_headings_url != NULL ) ) {
    			wp_enqueue_style( 'montserrat', esc_url( $lets_review_ssl  . '://fonts.googleapis.com/css?family=' . $lets_review_font_headings_url . $lets_review_font_p_url . $lets_review_font_ext_url ) );
    		}

    		if ( is_rtl() ) {
    			$lets_review_ext = '-style-rtl';
    		} else {
    			$lets_review_ext = '-style';
    		}

			wp_enqueue_style( $this->lets_review_slug, esc_url( $this->lets_review_url  . 'frontend/css/' . $this->lets_review_slug . $lets_review_ext . '.css' ), array(), $this->lets_review_version, 'all' );
			if ( intval( get_option('lets_review_gen_fa') ) == 1 ) {
            	wp_enqueue_style( 'fontAwesome', esc_url( $this->lets_review_url  . 'admin/css/font-awesome-4.6.1/css/font-awesome.min.css' ), array(), $this->lets_review_version, 'all' );
            }

		}

		/**
		 * Let's Review Custom CSS
		 *
		 * @since    1.0.0
		 */
		public function lets_review_custom_css() {

			$lets_review_font_headings = ( ( get_option('lets_review_gen_type_headings') == NULL ) || ( get_option('lets_review_gen_type_headings') == 'Inherit from Theme' ) ) ? NULL : get_option('lets_review_gen_type_headings');
			$lets_review_font_p = ( ( get_option('lets_review_gen_type_p') == NULL ) || ( get_option('lets_review_gen_type_p') == 'Inherit from Theme' ) ) ? NULL : get_option('lets_review_gen_type_p');
			$lets_review_output = NULL; 

			if ( $lets_review_font_headings != NULL ) {
				$lets_review_output .= '.cb-lr-font-heading { font-family: ' . $lets_review_font_headings . '; }';
			}

			if ( $lets_review_font_p != NULL ) {
				$lets_review_output .= '.cb-lr-font-p { font-family: ' . $lets_review_font_p . '; }';
			}

			if ( $lets_review_output != NULL ) { echo '<style type="text/css">' . $lets_review_output . '</style><!-- end custom Let\'s Review css -->'; }
		}

		/**
		 * Let's Review User Rating
		 *
		 * @since    1.0.0
		 */
		public function lets_review_user_rating( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}
	        $lets_review_user_rating = get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true );
	        $lets_review_design_ani = get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true );
	        $lets_review_type = get_post_meta( $lets_review_post_id, '_lets_review_type', true );
	        $lets_review_color = $this->lets_review_color( $lets_review_post_id );
	        $lets_review_trigger = $lets_review_icon = NULL;
	        if ( empty( $lets_review_design_ani ) ) { $lets_review_design_ani = 1; }
	        if ( $lets_review_user_rating == NULL ) { $lets_review_user_rating = '0'; $lets_review_score = $lets_review_width = 0; }
            if ( $lets_review_design_ani == '1' ) { $lets_review_trigger = 'cb-trigger-zero'; }
            $lets_review_itemprop_meta = $lets_review_type == '3' ? 'itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"' : '';
            $lets_review_itemprop_span = $lets_review_type == '3' ? 'itemprop="reviewCount"' : '';
            $lets_review_itemprop_value = $lets_review_type == '3' ? 'itemprop="ratingValue"' : '';

			$lets_review_width = $lets_review_user_rating;

            switch ( get_post_meta( $lets_review_post_id, '_lets_review_format', true ) ) {
            	case '1':
            		$lets_review_score = $lets_review_user_rating;
            		$lets_review_icon_output = '<span class="cb-overlay"><span style="width:' . intval( $lets_review_width ) . '%; background-color: ' . $lets_review_color . ';" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
            		break;

            	case '2':
            		$lets_review_score = $lets_review_user_rating / 10;
            		$lets_review_icon_output = '<span class="cb-overlay"><span style="width:' . intval( $lets_review_width ) . '%; background-color: ' . $lets_review_color . ';" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
            		break;
            	case '3':
            		$lets_review_score = $lets_review_user_rating / 20;
            		$lets_review_width_rev = 100 - $lets_review_width;
                	$lets_review_icon_output = '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( '<i class="fa fa-star"></i>', 5 ) . '<span style="width:' . intval( $lets_review_width_rev ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
            		break;
            	case '4':
            		$lets_review_score = $lets_review_user_rating / 20;
            		$lets_review_icon = get_post_meta( $lets_review_post_id, '_lets_review_custom_icon', true );
            		$lets_review_width_rev = 100 - $lets_review_width;
                	$lets_review_icon_output = '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( $lets_review_icon, 5 ) . '<span style="width:' . intval( $lets_review_width_rev ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
            		break;

            	case '5':
                	$lets_review_custom_image = get_post_meta( $post->ID, '_lets_review_custom_image', true );
                	if ( $lets_review_custom_image != NULL ) { 
                    	$lets_review_icon = wp_get_attachment_image ( $lets_review_custom_image, 'thumbnail' );
                    }

                    $lets_review_score = $lets_review_user_rating / 20;
            		$lets_review_width_rev = 100 - $lets_review_width;
                	$lets_review_icon_output = '<span class="cb-overlay">' . str_repeat( $lets_review_icon, 5 ) . '<span style="width:' . intval( $lets_review_width_rev ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
                    break;
            	default:
            		$lets_review_score = $lets_review_user_rating / 20;
            		$lets_review_width_rev = 100 - $lets_review_width;
                	$lets_review_icon_output = '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( '<i class="fa fa-star"></i>', 5 ) . '<span style="width:' . intval( $lets_review_width_rev ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span>';
            		break;
            }
                
			$lets_review_title = esc_html__( 'Reader Rating', 'lets-review');
			$lets_review_user_rating_title = esc_html__( 'Leave Rating', 'lets-review');
			$lets_review_votes_count = get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) : '0';
           	$lets_review_votes_txt =  sprintf( _n( 'Vote', 'Votes', $lets_review_votes_count, 'lets-review' ), $lets_review_votes_count );

            $lets_review_output = '<div class="cb-bar" ' . $lets_review_itemprop_meta . '><span class="cb-criteria" data-cb-txt="' . esc_attr( $lets_review_user_rating_title ) . '">' . $lets_review_title . '<span class="cb-votes-count"><span ' . $lets_review_itemprop_span . '>' . $lets_review_votes_count . '</span> ' . $lets_review_votes_txt . '</span></span><span class="cb-criteria-score" data-cb-score-cache="' . floatval( $lets_review_score ) .'" data-cb-width-cache="' . floatval( $lets_review_width ) .'"  ' . $lets_review_itemprop_value . '>' . $lets_review_score  . '</span>' . $lets_review_icon_output . '</div>';		

            return $lets_review_output;
	    }

	    /**
		 * Let's Review User Rating
		 *
		 * @since    1.0.0
		 */
		public function lets_review_a_cb() {
			
			if ( ! wp_verify_nonce( $_POST['letsReviewNonce'], 'lets_review_nonce' ) ) {
	            die();
	        }

	        $lets_review_score = floatval( $_POST['letsReviewScore'] );
	        $lets_review_post_id = floatval( $_POST['letsReviewPostID'] );

	        $lets_review_votes_cache = ( get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) == NULL ) ? 0 : intval( get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) );
	        $lets_review_score_cache = intval( get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true ) );
	        $lets_review_votes = $lets_review_votes_cache + 1;

	        if ( $lets_review_votes_cache == 1 ) {
	            $lets_review_score = ( intval( $lets_review_score_cache + $lets_review_score ) ) / 2;
	        } elseif ( $lets_review_votes_cache > 1 ) {
	            $lets_review_score_cache_total = $lets_review_score_cache * $lets_review_votes_cache;
	            $lets_review_score = intval( ( $lets_review_score_cache_total + $lets_review_score ) / $lets_review_votes );
	        }
	       
	        if ( isset( $_COOKIE['lets_review_user_rating'] ) ) {
	        	$lets_review_cookie = json_decode($_COOKIE['lets_review_user_rating'], true );
	        	$lets_review_cookie[] = $lets_review_post_id;
	        } else {
	        	$lets_review_cookie = array( $lets_review_post_id );
	        }	        

	        update_post_meta( $lets_review_post_id, '_lets_review_user_rating', intval( $lets_review_score ) );
	        update_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', intval( $lets_review_votes ) );

	        switch ( get_post_meta( $lets_review_post_id, '_lets_review_format', true ) ) {
	        	case 1:
	        		$lets_review_score_output = $lets_review_score;
	        		break;
	        	case 2:
	        		$lets_review_score_output = $lets_review_score / 10;
	        		break;
	        	
	        	default:
	        		$lets_review_score_output = $lets_review_score / 20;
	        		$lets_review_score = 100 - $lets_review_score;
	        		break;
	        }

	        $lets_review_votes_count = get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_user_rating_vote_count', true ) : '0';
           	$lets_review_votes_txt =  sprintf( _n( 'Vote', 'Votes', $lets_review_votes_count, 'lets-review' ), $lets_review_votes_count );
	        	      
        	die( json_encode( array( $lets_review_score, $lets_review_score_output, $lets_review_votes_count . ' ' . $lets_review_votes_txt, $lets_review_cookie ) ) );
		}

		/**
		 * Let's Review Gallery
		 *
		 * @since    1.0.0
		 */
		public function lets_review_media_gallery( $lets_review_post_id = NULL ) {

	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_gallery_imgs = get_post_meta( $lets_review_post_id, '_lets_review_gallery_imgs', true );
	        $lets_review_gallery_title = get_post_meta( $lets_review_post_id, '_lets_review_gallery_title', true );

			if ( ! empty ( $lets_review_gallery_imgs ) ) {

				if ( ! empty ( $lets_review_gallery_title ) ) {
					$lets_review_output = '<div class="cb-gallery-title cb-title cb-block-header cb-lr-font-heading">' . $lets_review_gallery_title . '</diV>';
				}

				$lets_review_output .= '<div id="cb-review-gallery-wrap" class="cb-review-gallery cb-clearfix">';
				foreach ( $lets_review_gallery_imgs as $lets_review_gallery_img ) {
					if ( isset( $lets_review_gallery_img['attachment-id'] ) ) {
						$lets_review_attachment = wp_get_attachment_image_src( $lets_review_gallery_img['attachment-id'], 'full' );				
						$lets_review_img_title = get_the_title($lets_review_gallery_img['attachment-id']) ? ' title="' .get_the_title($lets_review_gallery_img['attachment-id']) . '"' : NULL;
						$lets_review_output .= '<a href="' . esc_url( $lets_review_attachment[0] ) . '" class="lets-review-lightbox cb-lr-img cb-img-ani-1" rel="gal-' . $lets_review_post_id . '"' . $lets_review_img_title . '>' . wp_get_attachment_image( $lets_review_gallery_img['attachment-id'], 'thumbnail' ) . '</a>';
                    }
                }
                $lets_review_output .= '</div>';
            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Featured Image
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_media_fi( $lets_review_post_id = NULL, $lets_review_design = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_fi = get_post_meta( $lets_review_post_id, '_lets_review_fi', true );
	        $lets_review_design = $lets_review_design == NULL ? get_post_meta( $lets_review_post_id, '_lets_review_design', true ) : $lets_review_design;

			if ( ! empty ( $lets_review_fi ) ) {

				switch ( $lets_review_design ) {
					case 3:
						$lets_review_fi_url = aq_resize( wp_get_attachment_url( $lets_review_fi ), 1200, 800, true ) ? aq_resize( wp_get_attachment_url( $lets_review_fi ), 1200, 800, true ) : wp_get_attachment_url( $lets_review_fi );
						$lets_review_output .= '<div class="cb-review-fi" style=" background-image: url( ' . esc_url( $lets_review_fi_url ) . ') ; "></div>'; 
						break;
					
					default:
						$lets_review_attachment = wp_get_attachment_image_src( $lets_review_fi, 'full' );
						$lets_review_img_title = get_the_title( $lets_review_fi ) ? ' title="' .get_the_title( $lets_review_fi ) . '"' : NULL;
						$lets_review_output .= '<div class="cb-review-fi"><a href="' . esc_url( $lets_review_attachment[0] ) . '" class="lets-review-lightbox cb-lr-img cb-img-ani-1" rel="gal-fi-' . $lets_review_post_id . '"' . $lets_review_img_title . '>' . wp_get_attachment_image( $lets_review_fi, 'thumbnail' ) . '</a></div>';
						break;
				}
				

            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Color
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_color( $lets_review_post_id = NULL ) {

	    	$lets_review_color = get_post_meta( $lets_review_post_id, '_lets_review_color', true );
	    	if ( empty( $lets_review_color ) ) { $lets_review_color = ( get_option( 'lets_review_gd_color' ) == NULL ) ? '#f8d92f' : get_option( 'lets_review_gd_color' ); }
	    	return $lets_review_color;
	    }

	    /**
		 * Let's Review Criteria
		 *
		 * @since    1.0.0
		 */
		public function lets_review_criterias( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = $lets_review_icon = NULL;
	        $lets_review_crits = get_post_meta( $lets_review_post_id, '_lets_review_criterias', true );
	        $lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
	        $lets_review_color = $this->lets_review_color( $lets_review_post_id );
	        $lets_review_design_ani = get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true );
	        $lets_review_trigger = NULL;
	        if ( empty( $lets_review_design_ani ) ) { $lets_review_design_ani = 1; }

            if ( $lets_review_design_ani == '1' ) {
            	$lets_review_trigger = 'cb-trigger-zero';
            }

			if ( ! empty ( $lets_review_crits ) ) {

				$lets_review_output = '<div class="cb-criteria-block cb-clearfix">';

				foreach ( $lets_review_crits as $lets_review_crit ) {

					if ( $lets_review_crit['score'] == NULL ) { $lets_review_crit['score'] = '0'; }
                    $lets_review_output .= '<div class="cb-bar"><span class="cb-criteria">' . $lets_review_crit['title'] . '</span>';

                    switch ( $lets_review_format ) {
                    	case '1':
                    		$lets_review_width_score = $lets_review_crit['score'];
                    		$lets_review_output .= '<span class="cb-criteria-score">' . $lets_review_crit['score']  . '</span>';
                    		$lets_review_output .= '<span class="cb-overlay"><span style="width:' . $lets_review_width_score . '%; background-color: ' . $lets_review_color . ';" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    	case '2':
                    		$lets_review_width_score = $lets_review_crit['score'] * 10;
                    		$lets_review_output .= '<span class="cb-criteria-score">' . $lets_review_crit['score']  . '</span>';
                   			$lets_review_output .= '<span class="cb-overlay"><span style="width:' . $lets_review_width_score . '%; background-color: ' . $lets_review_color . ';" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    	case '3':

                    		$lets_review_output .= '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( '<i class="fa fa-star"></i>', 5 ) . '<span style="width:' . intval( 100 - ( $lets_review_crit['score'] * 20 ) ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    	case '4':
                    		$lets_review_output .= '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( get_post_meta( $lets_review_post_id, '_lets_review_custom_icon', true ), 5 ) . '<span style="width:' . intval( 100 - ( $lets_review_crit['score'] * 20 ) ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    	case '5':
	                    	$lets_review_custom_image = get_post_meta( $post->ID, '_lets_review_custom_image', true );
	                    	if ( $lets_review_custom_image != NULL ) { 
	                        	$lets_review_icon = wp_get_attachment_image ( $lets_review_custom_image, 'thumbnail' );
	                        }

	                    	$lets_review_output .= '<span class="cb-overlay">' . str_repeat( $lets_review_icon, 5 ) . '<span style="width:' . intval( 100 - ( $lets_review_crit['score'] * 20 ) ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    		
                    	default:
                    		$lets_review_output .= '<span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( '<i class="fa fa-star"></i>', 5 ) . '<span style="width:' . intval( 100 - ( $lets_review_crit['score'] * 20 ) ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
                    		break;
                    }

                }

                $lets_review_output .= '</div>';
            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Pros
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_pros( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_pros = get_post_meta( $lets_review_post_id, '_lets_review_pros', true );
	        $lets_review_pros_title = get_post_meta( $lets_review_post_id, '_lets_review_pros_title', true );

			if ( ! empty ( $lets_review_pros ) ) {
				$lets_review_output = '<div class="cb-pros">';

				if ( ! empty ( $lets_review_pros_title ) ) {
					$lets_review_output .= '<div class="cb-pros-title cb-block-header cb-clearfix">' . $lets_review_pros_title . '</div>';
				}

				foreach ( $lets_review_pros as $lets_review_pro ) {

					if ( isset( $lets_review_pro['positive'] ) && ( $lets_review_pro['positive'] != NULL ) ) {
                   		$lets_review_output .= '<div class="cb-pros-cons cb-pro">' . $lets_review_pro['positive'] . '</div>';
                   	}

                }
                $lets_review_output .= '</div>';
            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Cons
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_cons( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_cons = get_post_meta( $lets_review_post_id, '_lets_review_cons', true );
	        $lets_review_cons_title = get_post_meta( $lets_review_post_id, '_lets_review_cons_title', true );

			if ( ! empty ( $lets_review_cons ) ) {
				$lets_review_output = '<div class="cb-cons">';
				
				if ( ! empty ( $lets_review_cons_title ) ) {
					$lets_review_output .= '<div class="cb-cons-title cb-block-header cb-clearfix">' . $lets_review_cons_title . '</div>';
				}

				foreach ( $lets_review_cons as $lets_review_con ) {
					if ( isset( $lets_review_con['negative'] ) && ( $lets_review_con['negative'] != NULL ) ) {
                    	$lets_review_output .= '<div class="cb-pros-cons cb-con">' . $lets_review_con['negative'] . '</div>';
                    }

                }
                $lets_review_output .= '</div>';
            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Final Score
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_final_score( $lets_review_post_id = NULL, $lets_review_score_only = false, $lets_review_design = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_final_score = get_post_meta( $lets_review_post_id, '_lets_review_final_score', true );
	        $lets_review_type = get_post_meta( $lets_review_post_id, '_lets_review_type', true );
	        $lets_review_subtitle = get_post_meta( $lets_review_post_id, '_lets_review_subtitle', true );
	        $lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
	        $lets_review_design = $lets_review_design == NULL ? get_post_meta( $lets_review_post_id, '_lets_review_design', true ) : $lets_review_design;
	        $lets_review_color = $this->lets_review_color( $lets_review_post_id );
	        $lets_review_design_ani = get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) : 1;
	        $lets_review_trigger = $lets_review_icon = $lets_review_class = $lets_review_itemprop_meta = NULL;
	        $lets_review_best_rating = 5;
	        $lets_review_style = "background-color: " . $lets_review_color . ";";
            if ( $lets_review_design_ani == '1' ) { $lets_review_trigger = 'cb-trigger-zero'; }
            $lets_review_itemprop = 'itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"';

            if ( $lets_review_type == 3 ) {
            	$lets_review_itemprop = NULL;
				$lets_review_final_score = get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true ) : '-';
			}

	        $lets_review_final_score_cache = $lets_review_final_score;

			if ( $lets_review_final_score != NULL ) {				

				if ( $lets_review_final_score == '-' ) {
					$lets_review_class = ' cb-score--';
				} else { 

					switch ( $lets_review_format ) {
						case 1:
							$lets_review_best_rating = 100;
							break;
						case 2:
						 	if ( $lets_review_type == 3 ) {
						 		$lets_review_final_score = number_format( $lets_review_final_score, 1 ) / 10;
						 	} else {
						 		$lets_review_final_score = number_format( $lets_review_final_score, 1 );
						 	}
						 	$lets_review_final_score = substr( $lets_review_final_score, -2 ) == '.0' ? mb_substr( $lets_review_final_score, 0, -2) : $lets_review_final_score;

							$lets_review_best_rating = 10;
							break;
						case 3:
							if ( $lets_review_type == 3 ) {
								$lets_review_width = 100 -  $lets_review_final_score;
							} else {
								$lets_review_width = 100 - ( $lets_review_final_score * 20 );
							}
							$lets_review_final_score = '<div class="cb-bar"><span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( '<i class="fa fa-star"></i>', 5 ) . '<span style="width:' . intval( $lets_review_width ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
							break;
						case 4:
							if ( $lets_review_type == 3 ) {
								$lets_review_width = 100 -  $lets_review_final_score;
							} else {
								$lets_review_width = 100 - ( $lets_review_final_score * 20 );
							}
	                		$lets_review_icon = get_post_meta( $lets_review_post_id, '_lets_review_custom_icon', true );
							$lets_review_final_score = '<div class="cb-bar"><span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( $lets_review_icon, 5 ) . '<span style="width:' . intval( $lets_review_width ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
							break;
						case 5:
							$lets_review_custom_image = get_post_meta( $post->ID, '_lets_review_custom_image', true );
		                	if ( $lets_review_custom_image != NULL ) { 
		                    	$lets_review_icon = wp_get_attachment_image ( $lets_review_custom_image, 'thumbnail' );
		                    }
		                    if ( $lets_review_type == 3 ) {
								$lets_review_width = 100 -  $lets_review_final_score;
							} else {
								$lets_review_width = 100 - ( $lets_review_final_score * 20 );
							}
		                	$lets_review_final_score = '<div class="cb-bar"><span class="cb-overlay" style="color: ' . $lets_review_color . ';">' . str_repeat( $lets_review_icon, 5 ) . '<span style="width:' . intval( $lets_review_width ) . '%;" class="' . esc_attr( $lets_review_trigger ) . '"></span></span></div>';
		                    break;
					}
				}

				
				 if ( $lets_review_type != 3 ) {
            		$lets_review_itemprop_meta = '<meta itemprop="worstRating" content="0"> <meta itemprop="ratingValue" content="' . floatval( $lets_review_final_score_cache ) . '" /> <meta itemprop="bestRating" content="' . floatval( $lets_review_best_rating ) . '" />';
            	}
				
				switch ( $lets_review_design ) {

					case 3:
						$lets_review_style = "border-color:" . $lets_review_color . ";";
						$lets_review_output = '<div class="cb-score-box" style="' . esc_attr( $lets_review_style ) . '"><div class="cb-score-titles-wrap">';
						$lets_review_output .= '<div class="cb-final-score cb-clearfix" ' . $lets_review_itemprop . '>' . $lets_review_itemprop_meta . ( $lets_review_final_score ) . '</div>';
						break;

					
					default:
						$lets_review_output = '<div class="cb-score-box' . esc_attr( $lets_review_class ) . '" style="' . esc_attr( $lets_review_style ) . '"><div class="cb-score-titles-wrap">';
						$lets_review_output .= '<div class="cb-final-score cb-clearfix" ' . $lets_review_itemprop . '>' . $lets_review_itemprop_meta . ( $lets_review_final_score ) . '</div>';
						break;
				}
				
				if ( ! empty ( $lets_review_subtitle ) ) {
					$lets_review_output .= '<div class="cb-score-subtitle">' . $lets_review_subtitle . '</div>';
	            }
	            $lets_review_output .= '</div></div>';

            }

            if ( $lets_review_score_only == false ) {

            	return $lets_review_output;

            } else {
            	return $lets_review_final_score_cache;
            }
	    }

	    /**
		 * Let's Review Top Review Box
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_top_small_box( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_out_of_5 = NULL;

	    	$lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
            $lets_review_design = get_post_meta( $lets_review_post_id, '_lets_review_design', true ) ?  get_post_meta( $lets_review_post_id, '_lets_review_design', true ) : 1;
            $lets_review_design_ani = get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) : 1;
            $lets_review_design_skin = get_post_meta( $lets_review_post_id, '_lets_review_design_skin', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_design_skin', true ) : 1;
            $lets_review_affiliates = $this->lets_review_affiliates( $lets_review_post_id );
            $lets_review_media_fi = $this->lets_review_media_fi( $lets_review_post_id );

            if ( $lets_review_format > 2 ) {
            	$lets_review_out_of_5 = ' cb-out-of-5';
            }

            if ( $lets_review_media_fi == NULL ) {
            	$lets_review_out_of_5 .= ' cb-no-fi';
            }

	        $lets_review_output = '<div id="cb-review-box-small-' . intval( $lets_review_post_id ) . '" class="cb-skin-base cb-lr-font-p cb-design-' . $lets_review_design . ' cb-skin-version-' . $lets_review_design_skin . $lets_review_out_of_5 . ' cb-score-type-' . $lets_review_format . ' cb-ani-type-' . $lets_review_design_ani .' cb-clearfix cb-review-box-top-fs">';
	        if ( $lets_review_design == 3 ) { 
	        	$lets_review_output .= $lets_review_media_fi;
	        }
	        $lets_review_output .= '<div class="cb-review-block cb-review-pad cb-clearfix">' . $this->lets_review_final_score( $lets_review_post_id ) . '</div>';
	        $lets_review_output .= $lets_review_affiliates ? '<div class="cb-review-block cb-review-pad cb-aff-block cb-clearfix">' . $lets_review_affiliates . '</div>' : NULL;
	        $lets_review_output .= '</div>';
	        

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Affiliate
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_affiliates( $lets_review_post_id = NULL ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_aff_options = get_post_meta( $lets_review_post_id, '_lets_review_aff_buttons', true );
	        $lets_review_aff_title = get_post_meta( $lets_review_post_id, '_lets_review_aff_title', true );
	        $lets_review_color = $this->lets_review_color( $lets_review_post_id );

			if ( ! empty ( $lets_review_aff_options ) ) {

				
				if ( ! empty ( $lets_review_aff_title ) ) {
					$lets_review_output = '<div class="cb-aff-title cb-block-header">' . $lets_review_aff_title . '</diV>';
				}
				$lets_review_output .= '<div class="cb-aff-buttons">';
				foreach ( $lets_review_aff_options as $lets_review_aff_option ) {

                    $lets_review_output .= '<div class="cb-aff-button"><a href="' . do_shortcode( $lets_review_aff_option['url'] ) . '" rel="nofollow" target="_blank">' . $lets_review_aff_option['title'] . '<span class="cb-icon-wrap" style="background-color: ' . esc_attr( $lets_review_color ) . ';"><i class="fa fa-shopping-cart cb-button-ani-1"></i><i class="fa fa-long-arrow-right cb-button-ani-1-hid cb-button-ani-1"></i></span></a></div>';

                }

                $lets_review_output .= '</div>';
            }


            return $lets_review_output;
	    }

	    /**
		 * Let's Review Title
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_title( $lets_review_post_id = NULL, $lets_review_countdown = false ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = NULL;
	        $lets_review_conclusion = get_post_meta( $lets_review_post_id, '_lets_review_conclusion', true );
            $lets_review_title = get_post_meta( $lets_review_post_id, '_lets_review_title', true );

			if ( ! empty ( $lets_review_title ) ) {

                $lets_review_output .= '<div class="cb-review-title cb-lr-font-heading" itemprop="name">';
                $lets_review_output .= $lets_review_countdown == false ? '' : '<div class="cb-countdown">' . $lets_review_countdown . '</div>';
                $lets_review_output .= $lets_review_title . '</div>';

            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Conclusion
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_conclusion( $lets_review_post_id = NULL, $lets_review_conclusion_show_title = true, $lets_review_conclusion_show_read_more = false ) {
	    	global $post;

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	$lets_review_output = $lets_review_itemprop_meta = NULL;
	        $lets_review_conclusion_title = get_post_meta( $lets_review_post_id, '_lets_review_conclusion_title', true );
	        $lets_review_conclusion = get_post_meta( $lets_review_post_id, '_lets_review_conclusion', true );
	        $lets_review_type = get_post_meta( $lets_review_post_id, '_lets_review_type', true );

			if ( ! empty ( $lets_review_conclusion_title ) && ( $lets_review_conclusion_show_title == true ) ) {

                $lets_review_output .= '<div class="cb-review-conclusion-title cb-block-header cb-clearfix">' . $lets_review_conclusion_title . '</div>';

            }

            if ( ! empty ( $lets_review_conclusion ) ) {

            	if ( $lets_review_type != 3 ) {
            		$lets_review_itemprop_meta = 'itemprop="reviewBody"';
            	}

                $lets_review_output .= '<div class="cb-review-conclusion cb-clearfix" ' . $lets_review_itemprop_meta . '>';
                $lets_review_output .= $lets_review_conclusion;
                if ( $lets_review_conclusion_show_title == false ) {
                	$lets_review_output .= '<a href="' . get_permalink( $lets_review_post_id ) . '" class="cb-full-review">' . esc_html__( 'Read full review', 'lets-review' ) . ' <i class="fa fa-long-arrow-right"></i></a>';
                }
                $lets_review_output .= '</div>';

            }

            return $lets_review_output;
	    }

	    /**
		 * Let's Review Review Box
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_review_box( $lets_review_post_id = NULL ) {
	     	global $post;

	     	if ( $lets_review_post_id == NULL ) {
	     		$lets_review_post_id = $post->ID;
	     	}

	    	$lets_review_criterias = $this->lets_review_criterias( $lets_review_post_id );
            $lets_review_cons = $this->lets_review_cons( $lets_review_post_id );
            $lets_review_pros = $this->lets_review_pros( $lets_review_post_id );
            $lets_review_final_score = $this->lets_review_final_score( $lets_review_post_id );
            $lets_review_affiliates = $this->lets_review_affiliates( $lets_review_post_id );
            $lets_review_title = $this->lets_review_title( $lets_review_post_id );
            $lets_review_conclusion = $this->lets_review_conclusion( $lets_review_post_id );
            $lets_review_media_gallery = $this->lets_review_media_gallery( $lets_review_post_id );
            $lets_review_media_fi = $this->lets_review_media_fi( $lets_review_post_id );
            $lets_review_user_rating = $this->lets_review_user_rating( $lets_review_post_id );
            $lets_review_type = get_post_meta( $lets_review_post_id, '_lets_review_type', true );
            $lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
            $lets_review_design = get_post_meta( $lets_review_post_id, '_lets_review_design', true ) ?  get_post_meta( $lets_review_post_id, '_lets_review_design', true ) : 1;
            $lets_review_design_ani = get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_design_ani', true ) : 1;
            $lets_review_design_skin = get_post_meta( $lets_review_post_id, '_lets_review_design_skin', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_design_skin', true ) : 1;            
            $lets_review_color = $this->lets_review_color( $lets_review_post_id );
            $lets_review_location = get_post_meta( $lets_review_post_id, '_lets_review_location', true );
            $lets_review_out_of_5 = $lets_review_style = NULL;

            $lets_review_author_id = get_post_field( 'post_author', $lets_review_post_id );
            if ( $lets_review_format > 2 ) {
            	$lets_review_out_of_5 = ' cb-out-of-5';
            	$lets_review_style = 'style="background-color: ' . $lets_review_color . ';"';
            }

            $lets_review_itemprop = 'http://schema.org/Review';

             if ( $lets_review_media_fi == NULL ) {
            	$lets_review_out_of_5 .= ' cb-no-fi';
            }


            if ( $lets_review_type == 2 ) {
            	$lets_review_user_rating = NULL;
            }

            if ( $lets_review_type == 3 ) {
            	$lets_review_criterias = NULL;
            	$lets_review_itemprop = 'http://schema.org/Product';

            }

            $lets_review_opener = '<div id="cb-review-box-' . intval( $lets_review_post_id ) . '" class="cb-skin-base cb-lr-font-p cb-design-' . $lets_review_design . ' cb-skin-version-' . $lets_review_design_skin . $lets_review_out_of_5 . ' cb-score-type-' . $lets_review_format . ' cb-ani-type-' . $lets_review_design_ani . ' cb-location-' . esc_attr( $lets_review_location ) . ' cb-clearfix" data-cb-pid="' . intval( $lets_review_post_id ) . '" data-cb-score-format="' . intval( $lets_review_format ) . '" data-cb-reviewer="' . intval( $lets_review_type ) . '" itemscope itemtype="' . esc_url( $lets_review_itemprop ) . '">';

            $lets_review_opener .= '<meta itemprop="url" content="' . esc_url( get_permalink( $lets_review_post_id ) ) .'">';
            $lets_review_opener .= '<meta itemprop="name" content="' . esc_attr( get_post_meta( $lets_review_post_id, '_lets_review_title', true ) ) . '">';
            
            
            $lets_review_opener .= '<meta itemprop="description" content="' . esc_attr( get_post_meta( $lets_review_post_id, '_lets_review_conclusion', true ) ) . '">';
            if ( $lets_review_type != 3 ) {
            	$lets_review_opener .= '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="' . esc_attr( get_the_author_meta( 'display_name', $lets_review_author_id ) ) . '"></span>';
            	$lets_review_opener .= '<span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing"><meta itemprop="name" content="' . esc_attr( get_post_meta( $lets_review_post_id, '_lets_review_title', true ) ) . '"></span>';
            	$lets_review_opener .= '<meta itemprop="datePublished" content="' . get_the_date( '', $lets_review_post_id ) . '">';
            }
	        $lets_review_closer = '</div>';

            $lets_review_voted = NULL;
            $cb_tip_title = 'data-cb-tip="' . esc_html__( 'You have already rated', 'lets-review' ) . '"';

            if ( isset( $_COOKIE['lets_review_user_rating'] ) ) {

            	if ( in_array( $lets_review_post_id , json_decode($_COOKIE['lets_review_user_rating'] ) ) ) {
	            	$lets_review_voted = ' cb-rated cb-lr-tip-f';
            	}
            }

            switch ( $lets_review_design ) {
            	case 2:
            		$lets_review_content = ( $lets_review_media_fi || $lets_review_title) ? '<div class="cb-review-block cb-review-pad cb-round-fi cb-title-block cb-clearfix" ' . $lets_review_style . '>' . $lets_review_media_fi . $lets_review_title . '</div>' : NULL;
            		$lets_review_content .= $lets_review_criterias ? '<div class="cb-review-block cb-criteria-block-wrap cb-clearfix">' . $lets_review_criterias . '</div>' : NULL;
					$lets_review_content .= $lets_review_user_rating ? '<div id="cb-user-rating-' . intval( $lets_review_post_id ) . '" class="cb-review-block cb-user-rating-block cb-clearfix' . $lets_review_voted . '" ' . $cb_tip_title . '>' . $lets_review_user_rating . '</div>' : NULL;
					$lets_review_content .= ( $lets_review_pros || $lets_review_cons ) ? '<div class="cb-review-block cb-review-pad cb-pros-cons-block cb-clearfix">' . $lets_review_pros . $lets_review_cons . '</div>' : NULL;
					$lets_review_content .= ( $lets_review_conclusion || $lets_review_final_score ) ? '<div class="cb-review-block cb-review-pad cb-conclusion-block cb-clearfix">' . $lets_review_final_score . $lets_review_conclusion . '</div>' : NULL;
            		$lets_review_content .= $lets_review_media_gallery ? '<div class="cb-review-block cb-review-in-title cb-review-pad cb-gallery-block cb-gallery-block-center cb-clearfix">' . $lets_review_media_gallery . '</div>' : NULL;
            		$lets_review_content .= $lets_review_affiliates ? '<div class="cb-review-block cb-review-pad cb-aff-block cb-clearfix">' . $lets_review_affiliates . '</div>' : NULL;
            		break;
            	case 3:
            	    $lets_review_content = ( $lets_review_title || $lets_review_pros || $lets_review_cons || $lets_review_media_fi || $lets_review_final_score || $lets_review_conclusion ) ? '<div class="cb-review-block cb-review-pad cb-summary-block cb-clearfix">' . $lets_review_title : NULL;
            	    $lets_review_content .= ( $lets_review_media_fi ) ? $lets_review_media_fi : NULL;
            	    $lets_review_content .= $lets_review_conclusion ? '<div class="cb-conclusion-block cb-clearfix">' . $lets_review_conclusion . '</div>' : NULL;
            	    $lets_review_content .= ( $lets_review_pros || $lets_review_cons ) ? '<div class="cb-pros-cons-block cb-clearfix">' . $lets_review_pros . $lets_review_cons . '</div>' : NULL;
            	    $lets_review_content .= ( $lets_review_final_score ) ? '<div class="cb-fi-block cb-clearfix">' . $lets_review_final_score . '</div>' : NULL;
            	    $lets_review_content .= ( $lets_review_title || $lets_review_pros || $lets_review_cons || $lets_review_media_fi || $lets_review_final_score || $lets_review_conclusion) ? '</div>' : NULL;
					$lets_review_content .= $lets_review_media_gallery ? '<div class="cb-review-block cb-review-in-title cb-gallery-block cb-gallery-block-center cb-review-pad-no-top cb-clearfix">' . $lets_review_media_gallery . '</div>' : NULL;
	            	$lets_review_content .= $lets_review_affiliates ? '<div class="cb-review-block cb-review-pad-no-top cb-aff-block cb-clearfix">' . $lets_review_affiliates . '</div>' : NULL;
            		break;
            	default:
            		$lets_review_content = ( $lets_review_media_fi || $lets_review_title) ? '<div class="cb-review-block cb-review-pad cb-round-fi cb-summary-block cb-clearfix">' . $lets_review_media_fi . $lets_review_title . '</div>' : NULL;
	        		$lets_review_content .= ( $lets_review_conclusion || $lets_review_media_gallery ) ? '<div class="cb-review-block cb-review-pad cb-conclusion-block cb-clearfix">' : NULL;
	        		$lets_review_content .= $lets_review_conclusion ? $lets_review_conclusion : NULL;
	        		$lets_review_content .= $lets_review_media_gallery ? '<div class="cb-review-in-title cb-gallery-block cb-clearfix">' . $lets_review_media_gallery . '</div>' : NULL;
	        		$lets_review_content .= ( $lets_review_conclusion || $lets_review_media_gallery ) ? '</div>' : NULL;
            		$lets_review_content .= ( $lets_review_criterias || $lets_review_user_rating ) ? '<div class="cb-review-block cb-criteria-block cb-review-pad cb-clearfix">' . $lets_review_criterias : NULL;
            		$lets_review_content .= $lets_review_user_rating ? '<div id="cb-user-rating-' . intval( $lets_review_post_id ) . '" class="cb-user-rating-block cb-clearfix' . $lets_review_voted . '" ' . $cb_tip_title . '>' . $lets_review_user_rating . '</div>' : NULL;
            		$lets_review_content .= ( $lets_review_criterias || $lets_review_user_rating ) ? '</div>' : NULL;            		
            		$lets_review_content .= ( $lets_review_pros || $lets_review_cons || $lets_review_final_score ) ? '<div class="cb-review-block cb-review-pad cb-score-pros cb-clearfix">' : NULL;
            		$lets_review_content .= ( $lets_review_pros || $lets_review_cons ) ? '<div class="cb-pros-cons-block cb-clearfix">' . $lets_review_pros . $lets_review_cons . '</div>' : NULL;
            		$lets_review_content .= ( $lets_review_final_score ) ? $lets_review_final_score : NULL;
            		$lets_review_content .= ( $lets_review_pros || $lets_review_cons || $lets_review_final_score ) ? '</div>' : NULL;
            		$lets_review_content .= $lets_review_affiliates ? '<div class="cb-review-block cb-review-pad cb-aff-block cb-clearfix">' . $lets_review_affiliates . '</div>' : NULL;         		
            		break;
            }


            return 	$lets_review_opener
            	. apply_filters( 'lets_review_box_start', '' )
            	. $lets_review_content
				. apply_filters( 'lets_review_box_end', '' )
				. $lets_review_closer;

	    }
	    
	   	/**
		 * [lets_review] shortcode output
		 *
		 * @since 1.0.0
		 * @return Review output
		 */
		public function lets_review_shortcode( $atts, $content = NULL ) {
			global $post;

			if ( !isset( $atts['postid'] ) ) {
	    		$atts['postid'] = $post->ID;
	    	}

	        if ( get_post_meta( $atts['postid'], '_lets_review_onoff', true ) != 1 ) {
	    		return;
	    	}

	        return $this->lets_review_review_box( $atts['postid'] );
	    }

	    /**
		 * [lets_review] shortcode list output
		 *
		 * @since 1.2.0
		 * @return Review output
		 */
		public function lets_review_shortcode_list( $atts, $content = NULL ) {

			if ( ! isset( $atts[ 'postid' ] ) ) {
				return;
			}

			$lets_review_design =  isset( $atts[ 'design' ] ) ? $atts[ 'design' ] : 'modern';
			$lets_review_countdown =  isset( $atts[ 'countdown' ] ) ? $atts[ 'countdown' ] : true;
			$lets_review_proscons =  isset( $atts[ 'proscons' ] ) ? $atts[ 'proscons' ] : 'off';
			$lets_review_proscons_attr = $lets_review_proscons == 'on' ? 'cb-pros-cons-on' : 'cb-pros-cons-off';
			switch ( $lets_review_design ) {
				case 'simple':
					$lets_review_design = 1;
					$lets_review_design_attr = 'cb-design-1';
					break;
				
				default:
					$lets_review_design = 3;
					$lets_review_design_attr = 'cb-design-3';
					break;
			}

			$lets_review_output = '<div class="cb-aff-list cb-skin-base ' . esc_attr( $lets_review_design_attr )  . ' clearfix">';

			if ( isset( $atts[ 'title' ] ) ) {
				$lets_review_output .= '<div class="cb-aff-main-title"><h2>' . $atts[ 'title' ] . '</h2></div>' ;
			}

			$lets_reviews_pids = explode( ',',  $atts[ 'postid' ] );
			$i = 1;
			foreach ( $lets_reviews_pids as $lets_review_post_id ) {

				$lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );

				if ( $lets_review_format > 2 ) {
	            	$lets_review_out_of_5 = ' cb-out-of-5';
	            } else {
	            	$lets_review_out_of_5 = NULL;
	            }

				$lets_review_output .= '<div class="cb-list-entry cb-list-entry-' . intval( $i ) . ' ' . $lets_review_out_of_5 . ' cb-review-block clearfix">';

				switch ( $lets_review_design ) {
					case 1:
						$lets_review_output .= $lets_review_countdown == true ? $this->lets_review_title( $lets_review_post_id, $i ) : $this->lets_review_title( $lets_review_post_id );
						$lets_review_output .= $this->lets_review_media_fi( $lets_review_post_id, $lets_review_design );
						$lets_review_output .= '<div class="cb-review-text-summary clearfix">';
						$lets_review_output .= $this->lets_review_conclusion( $lets_review_post_id, false );
						$lets_review_output .= '</div>';
						
				        $lets_review_output .= '<div class="cb-review-summary clearfix">';
				        $lets_review_output .= $this->lets_review_final_score( $lets_review_post_id, false, $lets_review_design );
			            $lets_review_output .= '</div>';

			            $lets_review_output .= '<div class="cb-review-aff-summary ' . esc_attr( $lets_review_proscons_attr ) . '">';
			            if ( $lets_review_proscons == 'on' ) {
							$lets_review_output .= '<div class="cb-pros-cons-block clearfix">';
				            $lets_review_output .= $this->lets_review_cons( $lets_review_post_id );
				            $lets_review_output .= $this->lets_review_pros( $lets_review_post_id );
				            $lets_review_output .= '</div>';
				        }
				        $lets_review_output .= '<div class="cb-review-block cb-review-pad cb-aff-block cb-clearfix">' . $this->lets_review_affiliates( $lets_review_post_id ) . '</div>';
				        $lets_review_output .= '</div>';

						break;
					
					default:
						$lets_review_output .= $this->lets_review_media_fi( $lets_review_post_id, $lets_review_design );
						$lets_review_output .= '<div class="cb-review-text-summary clearfix">';
						$lets_review_output .= $lets_review_countdown == true ? $this->lets_review_title( $lets_review_post_id, $i ) : $this->lets_review_title( $lets_review_post_id );
						$lets_review_output .= $this->lets_review_conclusion( $lets_review_post_id, false );
						if ( $lets_review_proscons == 'on' ) {
							$lets_review_output .= '<div class="cb-pros-cons-block clearfix">';
				            $lets_review_output .= $this->lets_review_cons( $lets_review_post_id );
				            $lets_review_output .= $this->lets_review_pros( $lets_review_post_id );
				            $lets_review_output .= '</div>';
				        }
						$lets_review_output .= '</div>';
				        $lets_review_output .= '<div class="cb-review-summary clearfix">';
				        $lets_review_output .= $this->lets_review_final_score( $lets_review_post_id, false, $lets_review_design );
			            $lets_review_output .= '<div class="cb-review-block cb-review-pad cb-aff-block cb-clearfix">' . $this->lets_review_affiliates( $lets_review_post_id ) . '</div>';
			            $lets_review_output .= '</div>';
						break;
				}
				
	            $lets_review_output .= '</div>';
	            

	            $i++;
			}

	    	$lets_review_output .= '</div>';

	        return $lets_review_output;
	    }

	    /**
		 * [lets_review] shortcode affiliate button
		 *
		 * @since 1.2.0
		 * @return Review output
		 */
		public function lets_review_shortcode_affiliate( $atts, $content = NULL ) {

	    	if ( empty ( $atts ) ) { 
	    		return;
	    	}

	    	$lets_review_color =  isset( $atts[ 'accent' ] ) ? $atts[ 'accent' ] : '#f8d92f';
			$lets_review_url =  isset( $atts[ 'url' ] ) ? $atts[ 'url' ] : '';
			$lets_review_text =  isset( $atts[ 'text' ] ) ? $atts[ 'text' ] : '';
			$lets_review_border =  isset( $atts[ 'border' ] ) ? $atts[ 'border' ] : 'on';
			$lets_review_ta =  isset( $atts[ 'textalign' ] ) ? $atts[ 'textalign' ] : 'center';

			switch ( $lets_review_border ) {
				case 'on':
					$lets_review_border = 'cb-border-on';
					break;
				
				default:
					$lets_review_border = 'cb-border-off';
					break;
			}

			switch ( $lets_review_ta ) {
				case 'center':
					$lets_review_ta = 'cb-aff-ta-center';
					break;
				
				default:
					$lets_review_ta = 'cb-aff-ta-left';
					break;
			}

			$lets_review_output = '<div class="cb-aff-standalone-button cb-skin-base cb-design-2 ' . esc_attr( $lets_review_border ) . ' ' . esc_attr( $lets_review_ta ) . '">';

			if ( isset( $atts[ 'maintitle' ] ) ) {
				$lets_review_output .= '<div class="cb-aff-title cb-block-header">' . $atts[ 'maintitle' ] . '</diV>';
			}

            $lets_review_output .= '<div class="cb-aff-block"><div class="cb-aff-button"><a href="' . esc_url( $lets_review_url ) . '" rel="nofollow" target="_blank">' . $lets_review_text . '<span class="cb-icon-wrap" style="background-color: ' . esc_attr( $lets_review_color ) . ';"><i class="fa fa-shopping-cart cb-button-ani-1"></i><i class="fa fa-long-arrow-right cb-button-ani-1-hid cb-button-ani-1"></i></span></a></div></div>';

            
            $lets_review_output .= '</div>';

            return $lets_review_output;
	        
	    }

	    /**
		 * Let's Review Frontend
		 *
		 * @since    1.0.0
		 */
	    public function lets_review_append( $content, $lets_review_post_id = NULL ) {
	    	global $post, $multipage, $numpages, $page;

	    	if ( is_front_page() &&  ( get_option( 'lets_review_gen_load_outside_post' ) == 0 ) ) {
	    		return $content;
	    	}

	    	if ( $lets_review_post_id == NULL ) {
	    		$lets_review_post_id = $post->ID;
	    	}

	    	if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) != 1 ) {
	    		return $content;
	    	}

            $lets_review_review_box = $this->lets_review_review_box( $lets_review_post_id );
            $lets_review_final_score = $this->lets_review_top_small_box( $lets_review_post_id );
	        $lets_review_location = get_post_meta( $lets_review_post_id, '_lets_review_location', true );

	        if ( $multipage == true ) {

	            if ( $page == $numpages ) {

	                if ( ( $lets_review_location == 1 ) || ( $lets_review_location == 3 ) || ( $lets_review_location == 5 ) ) {
	                    $content = $content . $lets_review_review_box;
	                }
	            }

	            if ( $page == '1' ) {

	                if ( $lets_review_location == 2 )  {
	                    $content = $lets_review_review_box . $content;
	                }

	                if ( $lets_review_location == 3 )  {
	                    $content = $lets_review_final_score . $content;
	                }
	            }

	        } else {

	        	switch ( $lets_review_location ) {
	        		case 2:
	        			$content = $lets_review_review_box . $content;
	        			break;
	        		case 3:
	        			$content = $lets_review_final_score . $content . $lets_review_review_box;
	        			break;
	        		case 4:
	        			break;
	        		case 5:
	        			$content = $lets_review_review_box . $content;
	        			break;
	        		default:
	        			$content = $content . $lets_review_review_box;
	        			break;
	        	}

	        }

	        return $content;
	    }

	}
}