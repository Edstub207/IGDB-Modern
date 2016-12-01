<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * Let's Review Class
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/inc
 */

if ( ! class_exists( 'Lets_Review' ) ) {
	class Lets_Review {

		/**
	     * Constructor
	     *
	     * @since 1.0.0
	     *
	    */
		public function __construct() {

			$this->name     = "Let's Review";
			$this->version  = '1.2.0';
			$this->slug     = 'lets-review';
			$this->url  	= plugin_dir_url( dirname( __FILE__ ) );
			$this->dir_path = plugin_dir_path( dirname( __FILE__ ) );
			$this->lets_review_loader();
			$this->lets_review_backend();
			$this->lets_review_frontend();
			$this->lets_review_locale();
			add_action( 'admin_init', array( $this, 'lets_review_settings' ) );
			add_action( 'admin_menu', array( $this, 'lets_review_menu_page' ) );
			add_action( 'widgets_init', array( $this, 'lets_review_widget' ) );
			if ( is_admin() ) {
			    add_action( 'load-post.php',     array( $this, 'lets_review_metabox_init' ) );
			    add_action( 'load-post-new.php', array( $this, 'lets_review_metabox_init' ) );
			}
		}

		/**
		 * Let's Review Loader
		 *
		 * @since 1.0.0
		 */
		private function lets_review_loader() {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lets-review-admin.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lets-review-api.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lets-review-metabox.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aqua-resizer.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'frontend/class-lets-review-frontend.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/lets-review-widget.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-lets-review-i18n.php';
		}

		/**
		 * Let's Review Initialize
		 *
		 * @since    1.0.0
		 */
		function lets_review_metabox_init() {
		    new Lets_Review_Metabox( $this->lets_review_get_slug(), $this->lets_review_get_version(), $this->lets_review_get_url() );
		}

		/**
		 * Let's Review Widget
		 *
		 * @since 1.0.0
		 */
		public function lets_review_widget(){

			return register_widget("Lets_Review_Widget");

		}

		/**
		 * Let's Review Backend Loader
		 *
		 * @since    1.0.0
		 */
		private function lets_review_backend() {

			$lets_review_admin = new Lets_Review_Admin( $this->lets_review_get_slug(), $this->lets_review_get_version(), $this->lets_review_get_url() );
			add_action( 'admin_enqueue_scripts', array( $lets_review_admin, 'lets_review_enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $lets_review_admin, 'lets_review_enqueue_styles' ) );

		}

		/**
		 * Let's Review Frontend Loader
		 *
		 * @since    1.0.0
		 */
		private function lets_review_frontend() {

			$lets_review_frontend = new Lets_Review_Frontend( $this->lets_review_get_slug(), $this->lets_review_get_version(), $this->lets_review_get_url() );
			add_filter( 'the_content', array( $lets_review_frontend, 'lets_review_append' ) );
			add_shortcode( 'letsreview', array( $lets_review_frontend, 'lets_review_shortcode' ) );
			add_shortcode( 'letsreviewlist', array( $lets_review_frontend, 'lets_review_shortcode_list' ) );
			add_shortcode( 'letsreviewaffiliate', array( $lets_review_frontend, 'lets_review_shortcode_affiliate' ) );
			add_action( 'wp_enqueue_scripts', array( $lets_review_frontend, 'lets_review_enqueue_scripts_frontend' ) );
			add_action( 'wp_enqueue_scripts', array( $lets_review_frontend, 'lets_review_enqueue_styles_frontend' ) );
			add_action( 'wp_head', array( $lets_review_frontend, 'lets_review_custom_css' ) );

		}

		/**
		 * Let's Review Admin Menu Page
		 *
		 * @since 1.0.0
		 */
		public function lets_review_menu_page(){

			add_menu_page( __( 'Let\'s review', 'lets-review' ), __( 'Let\'s review', 'lets-review' ), 'manage_options', $this->slug, array( $this, 'lets_review_page_loader' ),  esc_url( $this->url  . 'admin/images/lets-review-icon.png' ) );
			add_submenu_page( $this->slug, __( 'Default Options', 'lets-review' ), __( 'Default Options', 'lets-review' ), 'manage_options', $this->slug, array( $this, 'lets_review_page_loader' ) );
			add_submenu_page( $this->slug, __( 'Typography', 'lets-review' ), __( 'Typography', 'lets-review' ), 'manage_options', $this->slug . '-typography', array( $this, 'lets_review_page_loader' ) );
			add_submenu_page( $this->slug, __( 'Extras', 'lets-review' ), __( 'Extras', 'lets-review' ), 'manage_options', $this->slug . '-extras', array( $this, 'lets_review_page_loader' ) );
			add_submenu_page( $this->slug, __( 'Documentation', 'lets-review' ), __( 'Documentation', 'lets-review' ), 'manage_options', $this->slug . '-documentation', array( $this, 'lets_review_page_loader' ) );

		}

		/**
		 * Let's Review Page Loader
		 *
		 * @since 1.0.0
		 */
		public function lets_review_page_loader(){

			global $plugin_page;
			switch ( $plugin_page ) {

				case $this->slug . '-extras' :
					require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/extras.php' );
					break;
				case $this->slug . '-typography' :
					require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/typography.php' );
					break;
				case $this->slug . '-documentation' :
					require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/documentation.php' );
					break;
				default:
					require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pages/defaults.php' );
					break;
			}

		}

		/**
		 * Let's Review Page Settings
		 *
		 * @since 1.0.0
		 */
		public function lets_review_settings() {

			add_settings_section(  
		        'lets_review_set_section_extras', 
		        esc_html__( 'Extra Options', 'lets-review'), 
		        NULL,
		        'lets-review-extras'
		    );

			add_settings_field(
		        'lets_review_gen_lb',
		        esc_html__( 'Load  Let\'s Review Lightbox', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_switch' ),
		        'lets-review-extras',
		        'lets_review_set_section_extras',
		        array(
		            'lets_review_gen_lb',
		            NULL
		        )  
		    );

		    add_settings_field(
		        'lets_review_gen_fa',
		        esc_html__( 'Load FontAwesome files', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_switch' ),
		        'lets-review-extras',
		        'lets_review_set_section_extras',
		        array(
		            'lets_review_gen_fa',
		            NULL
		        )  
		    );

		    add_settings_field(
		        'lets_review_gen_js_min',
		        esc_html__( 'Use Minified Javascript', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_switch' ),
		        'lets-review-extras',
		        'lets_review_set_section_extras',
		        array(
		            'lets_review_gen_js_min',
		            NULL
		        )  
		    );

		    add_settings_field(
		        'lets_review_gen_load_outside_post',
		        esc_html__( 'Show full review outside post', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_switch' ),
		        'lets-review-extras',
		        'lets_review_set_section_extras',
		        array(
		            'lets_review_gen_load_outside_post',
		            esc_html__('If your theme shows all the post content on the homepage, categories, tags, etc, you may not want to output the full review box.', 'lets-review' )
		        )  
		    );

		    register_setting( 'lets-review-settings-extras', 'lets_review_gen_fa', 'intval' );
		    register_setting( 'lets-review-settings-extras', 'lets_review_gen_js_min', 'intval' );
		    register_setting( 'lets-review-settings-extras', 'lets_review_gen_load_outside_post', 'intval' );
			register_setting( 'lets-review-settings-extras', 'lets_review_gen_lb', 'intval' );
			
		    add_settings_section(  
		        'lets_review_set_section_typography', 
		        esc_html__( 'Typography Options', 'lets-review'), 
		        NULL,
		        'lets-review-typography'
		    );

			add_settings_field(
		        'lets_review_gen_type_headings',
		        esc_html__( 'Font: Headings', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_font' ),
		        'lets-review-typography',
		        'lets_review_set_section_typography',
		        array(
		            'lets_review_gen_type_headings',
		        )  
		    );
		    
		    add_settings_field(
		        'lets_review_gen_type_p',
		        esc_html__( 'Font: Parragraphs', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_font' ),
		        'lets-review-typography',
		        'lets_review_set_section_typography',
		        array(
		            'lets_review_gen_type_p',
		        )  
		    );

		    add_settings_field(
		        'lets_review_gen_type_ext',
		        esc_html__( 'Font: Character Sets', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_multiselect' ),
		        'lets-review-typography',
		        'lets_review_set_section_typography',
		        array(
		            'lets_review_gen_type_ext',
		            array( 
			            'latin' => 'Latin',
			            'latin-ext' => 'Latin Extended',
			            'cyrillic' => 'Cyrillic', 
			            'cyrillic-ext' => 'Cyrillic Extended', 
			            'vietnamese' => 'Vietnamese', 
			            'greek' => 'Greek', 
			            'greek-ext' => 'Greek Extended'
		            )
		        )  
		    );

		    register_setting( 'lets-review-settings-type', 'lets_review_gen_type_ext', array( $this, 'lets_review_array_sanitize' ) );
			register_setting( 'lets-review-settings-type', 'lets_review_gen_type_p', 'esc_attr' );
			register_setting( 'lets-review-settings-type', 'lets_review_gen_type_headings', 'esc_attr' );

			add_settings_section(  
		        'lets_review_set_section_defaults', 
		        esc_html__( 'Default Options', 'lets-review'), 
		        array( $this, 'lets_review_settings_section_cb_default' ),
		        'lets-review-defaults'
		    );

			 add_settings_field(
		        'lets_review_gd_format',
		        esc_html__( 'Format', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_radio_img' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_format',
		            array( 'cb-review-format cb-trigger-wrap', 'cb-custom-icon', 4 ),
		            array( 
		            	array( esc_html__( 'Percent', 'lets-review' ), 'lr-percent.png' ), 
		            	array( esc_html__( 'Points', 'lets-review' ), 'lr-points.png' ),
		            	array( esc_html__( 'Stars', 'lets-review' ), 'lr-stars.png' ),
		            	array( esc_html__( 'Custom Icon', 'lets-review' ), 'lr-custom-icon.png' ),
		            	array( esc_html__( 'Custom Image', 'lets-review' ), 'lr-custom-image.png' ),
		            )

		        )  
		    );

			
			 add_settings_field(
		        'lets_review_gd_custom_icon',
		        esc_html__( 'Custom Icon Code', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_text' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_custom_icon',
		            'cb-custom-icon',
		            'cb-hidden-load'
		        )  
		    );

			 add_settings_field(
		        'lets_review_gd_custom_image',
		        esc_html__( 'Custom Icon Image', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_media' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_custom_image',
		        )  
		    );


		    add_settings_field(
		        'lets_review_gd_location',
		        esc_html__( 'Location', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_radio_img' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_location',
		            'cb-review-location',
		            array( 
		            	array( esc_html__( 'Bottom', 'lets-review' ), 'lr-bot.png' ), 
		            	array( esc_html__( 'Top', 'lets-review' ), 'lr-top.png' ),
		            	array( esc_html__( 'Top + Bottom', 'lets-review' ), 'lr-top-bot.png' ),
		            	array( esc_html__( 'Shortcode', 'lets-review' ), 'lr-shortcode.png' ),
		            ),
		        )  
		    );

		    add_settings_field(
		        'lets_review_gd_design',
		        esc_html__( 'Design', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_radio_img' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_design',
		            array( 'cb-review-design cb-trigger-wrap', 'cb-review-animation cb-review-skin', 3 ),
		            array( 
		            	array( esc_html__( 'Minimalist', 'lets-review' ), 'lr-skin-1.png' ), 
		            	array( esc_html__( 'Bold', 'lets-review' ), 'lr-skin-2.png' ),
		            	array( esc_html__( 'Modern', 'lets-review' ), 'lr-skin-3.png' ),
		            )
		        )  
		    );
		    		    
		    add_settings_field(
		        'lets_review_gd_ani',
		        esc_html__( 'Animation Effect', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_radio_img' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_ani',
		            'cb-review-animation',
		            array( 
		            	array( esc_html__( 'Incremental', 'lets-review' ), 'lr-ani-1.gif' ), 
		            	array( esc_html__( 'Fade In', 'lets-review' ), 'lr-ani-2.gif' ),
		            	array( esc_html__( 'None', 'lets-review' ), 'lr-ani-off.png' ),
		            )
		        )  
		    );

		   	add_settings_field(
		        'lets_review_gd_skin',
		        esc_html__( 'Skin Style', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_radio_img' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_skin',
		            'cb-review-skin',
		            array( 
		            	array( esc_html__( 'Light', 'lets-review' ), 'lr-light.png' ), 
		            	array( esc_html__( 'Dark', 'lets-review' ), 'lr-dark.png' ),
		            )
		        )  
		    );	     

			add_settings_field(
		        'lets_review_gd_color',
		        esc_html__( 'Accent Color', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_color' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_gd_color',
		            'cb-review-color',
		        )  
		    ); 

			add_settings_field(
		        'lets_review_criterias',
		        esc_html__( 'Criterias', 'lets-review' ),
		         array( $this, 'lets_review_settings_cb_crits' ),
		        'lets-review-defaults',
		        'lets_review_set_section_defaults',
		        array(
		            'lets_review_criterias',
		            'cb-criteria-block'
		        )  
		    ); 

			register_setting( 'lets-review-settings-gd', 'lets_review_gd_skin', 'intval' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_color', 'esc_html' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_custom_icon', 'esc_html' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_custom_image', 'intval' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_location', 'intval' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_format', 'intval' );
			register_setting( 'lets-review-settings-gd', 'lets_review_criterias', array($this, 'lets_review_criterias_sanitize' ) );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_ani', 'intval' );
			register_setting( 'lets-review-settings-gd', 'lets_review_gd_design', 'intval' );

		}


		/**
		 * Let's Review Settings
		 *
		 * @since 1.0.0
		 */
		public function lets_review_settings_section_cb_default() {

		    ?>
		    <p><?php esc_html_e( 'Set the default settings you want reviews to have when adding a new review.', 'lets-review' ); ?></p>
		    <?php
		}

		public function lets_review_settings_cb_text( $args ) {
		    $lets_review_value = get_option( $args[0] );
		    $lets_review_class = $lets_review_id = NULL;
		    if ( isset(  $args[1] ) ) {
		    	$lets_review_id = $args[1];
		    }

		    if ( isset(  $args[2] ) ) {
		    	$lets_review_class = $args[2];
		    }

		   ?>
		   <div id="<?php echo esc_attr( $lets_review_id ); ?>" class="cb-element cb-text <?php echo esc_attr( $lets_review_class ); ?> <?php echo esc_attr( $lets_review_id ); ?> cb-clearfix">
			    <input type="text" id="<?php echo esc_attr( $args[0] ); ?>" name="<?php echo esc_attr( $args[0] ); ?>" value="<?php echo esc_html( $lets_review_value ); ?>">
			</div>
		    <?php
		}

		public function lets_review_settings_cb_media( $args ) {
		    $lets_review_value = get_option( $args[0] );
		   ?>

		    <div id="cb-custom-image-wrap" class="cb-trigger-block cb-media-cont cb-clearfix">
                <div id="cb-custom-image" class="cb-clearfix">
                    <div class="cb-option cb-clearfix cb-mb">
                        <span class="cb-list-title"><?php esc_html_e( 'Custom Image', 'lets-review' ); ?></span>
                        <div class="cb-desc"><?php esc_html_e( 'Upload/Set an image of a single object. It will output 5 times automatically. Recommended size is 80px x 80px.', 'lets-review' ); ?></div>
                    </div>
                    
                    <?php if ( !empty ( $lets_review_value ) ) { ?>

                        <?php $lets_review_custom_image_img = wp_get_attachment_image_src ( $lets_review_value, 'thumbnail' ); ?>

                            <span data-cb-id="<?php echo intval( $lets_review_value ); ?>" class="cb-gallery-img">
                                <a href="#" class="cb-remove"></a>
                                <img src="<?php echo esc_url( $lets_review_custom_image_img[0] ); ?>" alt="">
                                <input type="hidden" value="<?php echo intval( $lets_review_value ); ?>" name="<?php echo esc_attr( $args[0] ); ?>">
                            </span>

                    <?php } ?>

                </div>

                <a href="#" id="cb-review-custom-image" class="cb-single-image-trigger cb-button" data-cb-dest="cb-custom-image" data-cb-name="<?php echo esc_attr( $args[0] ); ?>"><?php esc_html_e('Select Image', 'lets-review' ); ?></a>
            </div>

		    <?php
		}

		public function lets_review_settings_cb_switch( $args ) {
		    $lets_review_value = get_option( $args[0] );
		    $lets_review_desc =  $args[1];

		    if ( $lets_review_value == NULL ) {
		    	$lets_review_value = 1;
		    }

		    ?>
		    <input type="checkbox" value="1" id="<?php echo esc_attr( $args[0] ); ?>" class="cb-onoff" name="<?php echo esc_attr( $args[0] ); ?>" <?php if ( intval( $lets_review_value ) == 1 ) { ?>checked="checked"<?php } ?>>
		    <?php
		    if ( $lets_review_desc != NULL ) { echo '<p class="description">' . $lets_review_desc .'</p>'; }
		}

		public function lets_review_settings_cb_font( $args ) {
		    $lets_review_value = get_option( $args[0] );
		    $lets_review_google_fonts = $this->lets_review_fonts();

		    if ( $lets_review_value == NULL ) {
		    	$lets_review_value = 'none';
		    }

		    ?>
		    <select id="<?php echo esc_attr( $args[0] ); ?>"  name="<?php echo esc_attr( $args[0] ); ?>" class="cb-select">

		    	<?php foreach ( $lets_review_google_fonts as $value => $font ) { ?>
		    		<option value="<?php echo esc_attr( $font ); ?>" <?php if ( $lets_review_value == $font ) { ?>selected="selected"<?php } ?>><?php echo esc_attr( $font ); ?></option>
		    	<?php } ?>
				
			</select>

		    <?php
		}

		public function lets_review_settings_cb_multiselect( $args ) {
		    $lets_review_value = get_option( $args[0] );

		    if ( $lets_review_value == NULL ) {
		    	$lets_review_value = array();
		    }

		    ?>
		    <select multiple="multiple" id="cb-cat-<?php echo esc_attr( $args[0] ); ?>"  name="<?php echo esc_attr( $args[0] ); ?>[]" class="cb-select cb-select-sol">

		    	<?php foreach ( $args[1] as $value => $name ) { ?>
		    		<option value="<?php echo esc_attr( $value ); ?>" <?php if ( in_array( $value, $lets_review_value ) ) { ?>selected="selected"<?php } ?>><?php echo esc_attr( $name ); ?></option>
		    	<?php } ?>
				
			</select>

		    <?php
		}

		public function lets_review_fonts() {

			return apply_filters( 'lets_review_fonts', 
				array(
					'none' => esc_html__( 'Inherit from Theme', 'lets-review' ),
					'Arvo, serif' => 'Arvo',
					'Lobster, cursive' => 'Lobster',
					'Merriweather, serif' => 'Merriweather',
					'Montserrat, sans-serif' => 'Montserrat',
					'Open Sans, sans-serif' => 'Open Sans',
					'Oswald, sans-serif' => 'Oswald',
					'Pacifico, cursive' => 'Pacifico',
					'Quattrocento, serif' => 'Quattrocento',
					'Raleway, sans-serif' => 'Raleway',
					'Ubuntu, sans-serif' => 'Ubuntu',
				)
			);

		}

		public function lets_review_settings_cb_crits( $args ) {
		    $lets_review_criterias = get_option( $args[0] );
		    $lets_review_crit_counter = 1; 
		    ?>

		    <div class="cb-element cb-clearfix cb-criteria-block">
                <div class="cb-desc cb-mb"><?php esc_html_e( 'Add as many criterias as you like. These will appear in every new review. You will be able to remove or add more of them inside each post if required. You can drag & drop the items around here.', 'lets-review' ); ?></div>
                <ul id="cb-criterias">
                    <?php if ( isset( $lets_review_criterias ) && is_array( $lets_review_criterias ) ) { ?>
                        <?php foreach ( $lets_review_criterias as $lets_review_crit ) { ?>

                            <li id="cb-criteria-<?php echo intval( $lets_review_crit_counter ); ?>" class="ui-state-default cb-list-field cb-criteria-field cb-clearfix" data-cb-score="<?php if ( isset( $lets_review_crit['score'] ) ) { echo intval( $lets_review_crit['score'] ); } ?>" data-cb-crit-title="<?php if ( isset( $lets_review_crit['title'] ) ) { echo esc_html( $lets_review_crit['title'] ); } ?>">
                                <div class="cb-criteria-title">
                                    <span class="cb-list-title"><?php esc_html_e( 'Title', 'lets-review' ); ?></span>
                                    <input type="text" value="<?php if ( isset( $lets_review_crit['title'] ) ) { echo esc_html( $lets_review_crit['title'] ); } ?>" id="cb-criteria-field-<?php echo intval( $lets_review_crit_counter ); ?>" name="lets_review_criterias[<?php echo intval( $lets_review_crit_counter ); ?>][title]" class="cb-input">
                                </div>
                                <div class="cb-criteria-score cb-clearfix">
                                    <span class="cb-list-title"><?php esc_html_e( 'Score', 'lets-review' ); ?></span>
                                    <div class="cb-review-slider cb-slider"></div>
                                    <input type="text" value="<?php if ( isset( $lets_review_crit['score'] ) ) { echo intval( $lets_review_crit['score'] ); } ?>" name="lets_review_criterias[<?php echo intval( $lets_review_crit_counter ); ?>][score]" class="cb-cri-score cb-input" readonly>
                                </div>
                                <a href="#" class="cb-button cb-remove"></a>
                            </li>
                            <?php $lets_review_crit_counter++; ?>

                        <?php } ?>
                    <?php } ?>

                </ul>
            </div>

			<a href="#" id="cb-add-crit" class="cb-button cb-add-new" data-cb-counter="<?php echo intval( $lets_review_crit_counter ); ?>"><?php esc_html_e( 'Add', 'lets-review' ); ?></a>

		    <?php
		}
		
		public function lets_review_settings_cb_color( $args ) {
		    $i = 1;
		    $lets_review_value = ( get_option( $args[0] ) == NULL ) ? '#f8d92f' : get_option( $args[0] );
		    ?>
		    <input type="text" class="lets-review-color" name="<?php echo esc_attr( $args[0] ); ?>" value="<?php echo esc_attr( $lets_review_value ); ?>">
			<div class="lets-review-colorpicker"></div>
			<?php
		}

		public function lets_review_settings_cb_radio_img( $args ) {
		    $i = 1;
		    $lets_review_value = get_option( $args[0] );

		    if ( empty( $lets_review_value ) ) {
		    	$lets_review_value = 1;
		    }

		    if ( is_array( $args[1] ) ) {
		    	$lets_review_attr = $args[1][0];
		    	$lets_review_count = $args[1][2];
		    	$lets_review_data = $args[1][1];
		    } else {
		    	$lets_review_attr = $args[1];
		    	$lets_review_count = NULL;
		    	$lets_review_data = NULL;
		    }

		    ?>

		    <div class="cb-element cb-radio-images <?php echo esc_attr( $lets_review_attr ); ?> cb-clearfix cb-text" data-cb-trigger="<?php echo esc_attr( $lets_review_data ); ?>">

				<?php foreach ( $args[2] as $arg ) { ?>
					<?php if ( $lets_review_count == $i ) { $lets_review_data_trigger = 'cb-trig'; } else { $lets_review_data_trigger = NULL; } ?>

					<div class="cb-radio-images-element cb-clearfix" data-cb-trigger="<?php echo esc_attr( $lets_review_data_trigger ); ?>">
						<span class="cb-radio-title"><?php echo esc_html( $arg[0] ); ?></span>
	                     <label>
	                        <input type="radio" class="cb-input-radio" name="<?php echo esc_attr( $args[0] ); ?>" value="<?php echo intval( $i ); ?>" <?php checked( $i,  $lets_review_value ); ?>>
	                        <img src="<?php echo esc_url( $this->url  . 'admin/images/' . $arg[1] ); ?>" alt="" class="cb-radio-image">
	                    </label>
	                </div>

				<?php $i++; ?>
				<?php } ?>
                
            </div>

			<?php
		}

		/**
		 * Let's Review Criterias Sanitize
		 *
		 * @since 1.0.0
		 */
		public function lets_review_criterias_sanitize( $input ) {
			
			if ( is_array( $input ) ) {
				$lets_review_output = array();
	            foreach ( $input as $keys ) {
	            	$lets_review_arr = array();
	            	foreach ( $keys as $key => $value ) {
	            		$lets_review_arr[$key] = esc_attr( $value );
	            	}
	                $lets_review_output[] = $lets_review_arr;
	            }
	        } else {
	        	$lets_review_output = NULL;
	        }

			return $lets_review_output;
		}

		/**
		 * Let's Review Array Sanitize
		 *
		 * @since 1.0.0
		 */
		public function lets_review_array_sanitize( $input ) {

			if ( is_array( $input ) ) {
				return array_map( 'esc_attr', $input );
			} else {
				return esc_attr( $input );
			}
		}

	
	    /**
		 * Let's Review Translation Loader
		 *
		 * @since 1.0.0
		 */
		public function lets_review_locale() {
			$lets_review_i18n = new Lets_Review_i18n();
			add_action( 'plugins_loaded', array( $lets_review_i18n, 'lets_review_textdomain' ) );

		}

		/**
		 * Return Let's Review version
		 *
		 * @since     1.0.0
		 * @return    string
		 */
		public function lets_review_get_version() {
			return $this->version;
		}

		/**
		 * Return the plugin name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function lets_review_get_name() {
			return $this->name;
		}

		/**
		 * Return the plugin slug.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function lets_review_get_slug() {
			return $this->slug;
		}

		/**
		 * Return Let's Review URL.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function lets_review_get_url() {
			return $this->url;
		}

		/**
		 * Return Let's Review path.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function lets_review_get_path() {
			return $this->dir_path;
		}		
	}
}