<?php 
/**
 *
 * Let's Review Metabox Class
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/admin
 */

if ( ! class_exists( 'Lets_Review_Metabox' ) ) {
    class Lets_Review_Metabox {
        
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
         * Constructor
         *
         * @since 1.0.0
         *
        */
        public function __construct( $lets_review_slug, $lets_review_version, $lets_review_url ) {

            $this->lets_review_slug = $lets_review_slug;
            $this->lets_review_version = $lets_review_version;
            $this->lets_review_url = $lets_review_url;
            add_action( 'add_meta_boxes', array( $this, 'lets_review_metabox_add' ) );
            add_action( 'save_post', array( $this, 'lets_review_save' ) );

        }
     
        /**
         * Let's Review Meta Box Constructor
         *
         * @since 1.0.0
         * @param WP_Post $post The post object.
         */
        public function lets_review_metabox_add( $post ) {

             add_meta_box(
                'lets-review-metabox',
                esc_html__( 'Let\'s Review Options', 'lets-review' ),
                array( $this, 'lets_review_metabox_callback' ),
                array( 'post', 'page'),
                'advanced',
                'high'
            );

            $lets_review_cpts = get_post_types( array( 'public'   => true, '_builtin' => false ) );

            if ( ! empty( $lets_review_cpts ) ) {
                foreach ( $lets_review_cpts as $lets_review_cpt ) {
                    add_meta_box(
                        'lets-review-metabox',
                        esc_html__( 'Let\'s Review Options', 'lets-review' ),
                        array( $this, 'lets_review_metabox_callback' ),
                        $lets_review_cpt,
                        'advanced',
                        'high'
                    );
                }
                 
            }
           
        }
     
        /**
         * Let's Review Meta Box Save
         *
         * @since 1.0.0
         * @param int $post_id The ID of the post being saved.
         */
        public function lets_review_save( $post_id ) {
            
            // Important checks before saving
            // 1. Check if nonce exists 2. Verify nonce 3. Check if post is autosaving 4. Check quick edit doesn't affect custom fields 5. Check user has right permissions
            if ( 
                ( ! isset( $_POST['lets_review_metabox_nonce'] ) )
                || ( ! wp_verify_nonce( $_POST['lets_review_metabox_nonce'], 'lets_review_metabox' ) )
                || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
                || ( defined('DOING_AJAX') && DOING_AJAX )
                || ( ! current_user_can( 'edit_posts' ) )
                ) {
                return;
            }
     
            $lets_review_onoff = $_POST['lets_review_onoff'];
            $lets_review_title = $_POST['lets_review_title'];
            $lets_review_subtitle = $_POST['lets_review_subtitle'];
            $lets_review_conclusion = $_POST['lets_review_conclusion'];
            $lets_review_conclusion_title = $_POST['lets_review_conclusion_title'];
            $lets_review_pros = $_POST['lets_review_pros'];
            $lets_review_cons = $_POST['lets_review_cons'];
            $lets_review_crits = $_POST['lets_review_criterias'];
            $lets_review_aff_options = $_POST['lets_review_aff_buttons'];
            $lets_review_aff_title = $_POST['lets_review_aff_title'];
            $lets_review_final_score = $_POST['lets_review_final_score'];
            $lets_review_final_score_100 = $_POST['lets_review_final_score_100'];
            $lets_review_final_score_override = $_POST['lets_review_final_score_override'];
            $lets_review_location = $_POST['lets_review_location'];
            $lets_review_format = $_POST['lets_review_format'];
            $lets_review_gallery_imgs = $_POST['lets_review_gallery_imgs'];
            $lets_review_gallery_title = $_POST['lets_review_gallery_title'];
            $lets_review_custom_image = $_POST['lets_review_custom_image'];
            $lets_review_fi = $_POST['lets_review_fi'];
            $lets_review_custom_icon = $_POST['lets_review_custom_icon'];
            $lets_review_type = $_POST['lets_review_type'];
            $lets_review_design_skin = $_POST['lets_review_design_skin'];
            $lets_review_design = $_POST['lets_review_design'];
            $lets_review_color = $_POST['lets_review_color'];
            $lets_review_design_ani = $_POST['lets_review_design_ani'];
            $lets_review_cons_title = $_POST['lets_review_cons_title'];
            $lets_review_pros_title = $_POST['lets_review_pros_title'];

            update_post_meta( $post_id, '_lets_review_onoff', sanitize_text_field( $lets_review_onoff ) );
            update_post_meta( $post_id, '_lets_review_title', sanitize_text_field( $lets_review_title ) );
            update_post_meta( $post_id, '_lets_review_subtitle', sanitize_text_field( $lets_review_subtitle ) );
            update_post_meta( $post_id, '_lets_review_conclusion_title', sanitize_text_field( $lets_review_conclusion_title ) );
            update_post_meta( $post_id, '_lets_review_conclusion', $lets_review_conclusion );
            update_post_meta( $post_id, '_lets_review_criterias', $lets_review_crits );
            update_post_meta( $post_id, '_lets_review_aff_buttons', $lets_review_aff_options );
            update_post_meta( $post_id, '_lets_review_aff_title', sanitize_text_field( $lets_review_aff_title ) );
            update_post_meta( $post_id, '_lets_review_pros', $lets_review_pros );
            update_post_meta( $post_id, '_lets_review_cons', $lets_review_cons );
            update_post_meta( $post_id, '_lets_review_gallery_imgs', $lets_review_gallery_imgs );
            update_post_meta( $post_id, '_lets_review_gallery_title', sanitize_text_field( $lets_review_gallery_title ) );
            update_post_meta( $post_id, '_lets_review_final_score', floatval( $lets_review_final_score ) );
            update_post_meta( $post_id, '_lets_review_final_score_100', intval( $lets_review_final_score_100 ) );
            update_post_meta( $post_id, '_lets_review_final_score_override', esc_attr( $lets_review_final_score_override ) );
            update_post_meta( $post_id, '_lets_review_location', intval( $lets_review_location ) );
            update_post_meta( $post_id, '_lets_review_format', intval( $lets_review_format ) );
            update_post_meta( $post_id, '_lets_review_custom_image', intval( $lets_review_custom_image ) );
            update_post_meta( $post_id, '_lets_review_fi', intval( $lets_review_fi ) );
            update_post_meta( $post_id, '_lets_review_custom_icon', $lets_review_custom_icon);
            update_post_meta( $post_id, '_lets_review_type', intval( $lets_review_type ) );
            update_post_meta( $post_id, '_lets_review_design_skin', intval( $lets_review_design_skin ) );
            update_post_meta( $post_id, '_lets_review_design', intval( $lets_review_design ) );
            update_post_meta( $post_id, '_lets_review_design_ani', intval( $lets_review_design_ani ) );
            update_post_meta( $post_id, '_lets_review_color', esc_attr( $lets_review_color ) );
            update_post_meta( $post_id, '_lets_review_cons_title', sanitize_text_field( $lets_review_cons_title ) );
            update_post_meta( $post_id, '_lets_review_pros_title', sanitize_text_field( $lets_review_pros_title ) );

        }
     
        /**
         * Let's Review Meta Box Callback
         * 
         * @since 1.0.0
         *
         * @param WP_Post $post The post object.
         * 
         */
        public function lets_review_metabox_callback( $post ) {
     
            wp_nonce_field( 'lets_review_metabox', 'lets_review_metabox_nonce' );

            $lets_review_onoff = get_post_meta( $post->ID, '_lets_review_onoff', true );
            $lets_review_conclusion = get_post_meta( $post->ID, '_lets_review_conclusion', true );
            $lets_review_conclusion_title = get_post_meta( $post->ID, '_lets_review_conclusion_title', true );
            $lets_review_subtitle = get_post_meta( $post->ID, '_lets_review_subtitle', true );
            $lets_review_title = get_post_meta( $post->ID, '_lets_review_title', true );
            $lets_review_crits = get_post_meta( $post->ID, '_lets_review_criterias', true );
            $lets_review_aff_options = get_post_meta( $post->ID, '_lets_review_aff_buttons', true );
            $lets_review_aff_title = get_post_meta( $post->ID, '_lets_review_aff_title', true );
            $lets_review_pros = get_post_meta( $post->ID, '_lets_review_pros', true );
            $lets_review_cons = get_post_meta( $post->ID, '_lets_review_cons', true );
            $lets_review_final_score = get_post_meta( $post->ID, '_lets_review_final_score', true );
            $lets_review_final_score_100 = get_post_meta( $post->ID, '_lets_review_final_score_100', true );
            $lets_review_final_score_override = get_post_meta( $post->ID, '_lets_review_final_score_override', true );
            $lets_review_gallery_imgs = get_post_meta( $post->ID, '_lets_review_gallery_imgs', true );
            $lets_review_gallery_title = get_post_meta( $post->ID, '_lets_review_gallery_title', true );
            $lets_review_location = get_post_meta( $post->ID, '_lets_review_location', true );
            $lets_review_format = get_post_meta( $post->ID, '_lets_review_format', true );
            $lets_review_custom_image = get_post_meta( $post->ID, '_lets_review_custom_image', true );
            $lets_review_fi = get_post_meta( $post->ID, '_lets_review_fi', true );
            $lets_review_custom_icon = get_post_meta( $post->ID, '_lets_review_custom_icon', true );
            $lets_review_type = get_post_meta( $post->ID, '_lets_review_type', true );
            $lets_review_design_skin = get_post_meta( $post->ID, '_lets_review_design_skin', true );
            $lets_review_design = get_post_meta( $post->ID, '_lets_review_design', true );
            $lets_review_design_ani = get_post_meta( $post->ID, '_lets_review_design_ani', true );
            $lets_review_design_color = get_post_meta( $post->ID, '_lets_review_color', true );
            $lets_review_cons_title = get_post_meta( $post->ID, '_lets_review_cons_title', true );
            $lets_review_pros_title = get_post_meta( $post->ID, '_lets_review_pros_title', true );

            $lets_review_crit_counter = $lets_review_pro_counter = $lets_review_con_counter = $lets_review_imgs_counter = $lets_review_aff_counter = 1;

            $lets_review_max_score = 5;
            $lets_review_max_step = 0.1;

            if ( empty( $lets_review_crits ) && ( $lets_review_final_score == 0 ) ) { $lets_review_crits = ( get_option('lets_review_criterias') == NULL ) ? NULL : get_option('lets_review_criterias'); }
            if ( empty( $lets_review_custom_icon ) ) { $lets_review_custom_icon = ( get_option('lets_review_gd_custom_icon') == NULL ) ? NULL : get_option('lets_review_gd_custom_icon'); }
            if ( empty( $lets_review_custom_image ) ) { $lets_review_custom_image = ( get_option('lets_review_gd_custom_image') == NULL ) ? NULL : get_option('lets_review_gd_custom_image'); }
            if ( empty( $lets_review_format ) ) { $lets_review_format = ( get_option( 'lets_review_gd_format' ) == NULL ) ? 1 : get_option( 'lets_review_gd_format' ); }
            if ( empty( $lets_review_location ) ) { $lets_review_location = ( get_option( 'lets_review_gd_location' ) == NULL ) ? 1 : get_option( 'lets_review_gd_location' ); }
            if ( empty( $lets_review_design ) ) { $lets_review_design = ( get_option( 'lets_review_gd_design' ) == NULL ) ? 1 : get_option( 'lets_review_gd_design' ); }
            if ( empty( $lets_review_design_ani ) ) { $lets_review_design_ani = ( get_option( 'lets_review_gd_ani' ) == NULL ) ? 1 : get_option( 'lets_review_gd_ani' ); }
            if ( empty( $lets_review_design_color ) ) { $lets_review_design_color = ( get_option( 'lets_review_gd_color' ) == NULL ) ? '#f8d92f' : get_option( 'lets_review_gd_color' ); }
            
            if ( empty( $lets_review_final_score_override ) ) { 
                $lets_review_final_score_override = 'off'; 
            } else { 
                $lets_review_final_score = $lets_review_final_score_override; 
                $lets_review_final_score_100 = $lets_review_final_score_override; 
            }

            if ( empty( $lets_review_type ) ) { $lets_review_type = 1; }
            if ( empty( $lets_review_design_skin ) ) { $lets_review_design_skin = 1; }

            switch ( $lets_review_format ) {
                case '1':
                    $lets_review_max_score = 100;
                    $lets_review_max_step = 1;
                    break;
                case '2':
                    $lets_review_max_score = 10;
                    break;
            }

            ?>
            <label for="lets_review_onoff">
                <?php esc_html_e( 'Enable Review', 'lets-review' ); ?>
            </label>

            <input type="checkbox" value="1" id="cb-review-onoff" name="lets_review_onoff" <?php if ( intval( $lets_review_onoff ) == 1 ) { ?>checked="checked"<?php } ?>>
            <div id="cb-review-wrap" class="cb-clearfix cb-main-wrap<?php if ( intval( $lets_review_onoff ) != 1 ) { ?> cb-hidden<?php } ?>">
                <ul id="cb-tabs" class="cb-metabox-tabs cb-clearfix">
                    <li class="nav-tab nav-tab-active"><a href="#" class="nav-tab-active" data-cb-href="fotomag-tab-1"><?php esc_html_e( 'General', 'lets-review' ); ?></a></li>
                    <li class="nav-tab"><a href="#" data-cb-href="fotomag-tab-2"><?php esc_html_e( 'Fields', 'lets-review' ); ?></a></li>
                    <li class="nav-tab"><a href="#" data-cb-href="fotomag-tab-3"><?php esc_html_e( 'Design', 'lets-review' ); ?></a></li>
                    <li class="nav-tab"><a href="#" data-cb-href="fotomag-tab-4"><?php esc_html_e( 'Media', 'lets-review' ); ?></a></li>
                    <li class="nav-tab"><a href="#" data-cb-href="fotomag-tab-5"><?php esc_html_e( 'Affiliate', 'lets-review' ); ?></a></li>
                </ul>
                <div id="cb-tab-content" class="cb-tab-content-block">
                    <div id="fotomag-tab-1" class="cb-tab-content-area cb-clearfix">
                        <div class="cb-element cb-element-hw cb-clearfix cb-text">
                            <h2><?php esc_html_e( 'Review Main Title', 'lets-review' ); ?></h2>
                            <div class="cb-option cb-with-desc cb-clearfix">
                                <input class="cb-input" type="text" id="cb-review-title" name="lets_review_title" value="<?php echo esc_attr( $lets_review_title ); ?>">
                                <div class="cb-desc"><?php esc_html_e( 'Optional title to show at the top of the review box', 'lets-review' ); ?></div>
                            </div>
                        </div>

                        <h2><?php esc_html_e( 'Review Type', 'lets-review' ); ?></h2>
                        <div class="cb-review-type cb-element cb-clearfix">
                            
                            <select name="lets_review_type" id="cb-select-type" class="cb-select">
                                <option value="1" <?php if ( $lets_review_type == 1 ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Editor Review + Visitor Ratings', 'lets-review' ); ?></option>
                                <option value="2" <?php if ( $lets_review_type == 2 ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Editor Review', 'lets-review' ); ?></option>
                                <option value="3" <?php if ( $lets_review_type == 3 ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Visitor Reviews', 'lets-review' ); ?></option>
                            </select>
                        </div>


                        <h2><?php esc_html_e( 'Review Location', 'lets-review' ); ?></h2>
                        <?php // LOCATION RADIO BLOCK OUTPUT; ?>
                        <div class="cb-element cb-radio-images cb-review-location cb-trigger-wrap cb-clearfix cb-text">
                            
                            <div class="cb-radio-images-element">
                                 <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Bottom', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_location" value="1" <?php if ( $lets_review_location == 1 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-bot.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Top', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_location" value="2" <?php if ( $lets_review_location == 2 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-top.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Top + Bottom', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_location" value="3" <?php if ( $lets_review_location == 3 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-top-bot.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Top Half-Width', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_location" value="5" <?php if ( $lets_review_location == 5 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-top-half.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element cb-trigger" data-cb-trigger="cb-trigger-sc">

                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Shortcode', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_location" value="4" <?php if ( $lets_review_location == 4 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-shortcode.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div id="cb-trigger-sc" class="cb-trigger-block <?php if ( $lets_review_location != 4 ) { ?>cb-hidden<?php } ?> cb-clearfix">
                                <div class="cb-option cb-clearfix">
                                    <span class="cb-list-title"><?php esc_html_e( 'Shortcode Instructions', 'lets-review' ); ?></span>
                                    <div class="cb-desc"><?php esc_html_e( 'Put the following shortcode somewhere in the post content area: [letsreview].', 'lets-review' ); ?></div>
                                    <div class="cb-desc"><?php esc_html_e( 'If you want to add a post review from a different post, you can use [letsreview postid="123"] and replace the 123 with the id of that post.', 'lets-review' ); ?></div>
                                    <div class="cb-desc"><?php esc_html_e( 'For more info, please check the documentation -> Shortcode section.', 'lets-review' ); ?></div>
                                </div>
                            </div>

                        </div>

                        <?php // STYLE RADIO BLOCK OUTPUT; ?>
                        <h2><?php esc_html_e( 'Review Style', 'lets-review' ); ?></h2>
                        <div class="cb-element cb-radio-images cb-review-format cb-trigger-wrap cb-trigger-wrap-slider cb-clearfix cb-text">
                            
                            <div class="cb-radio-images-element">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Percent', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_format" value="1" <?php if ( $lets_review_format == 1 )  { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-percent.png" alt="" class="cb-radio-image cb-style-option">
                                </label>
                            </div>

                            <div class="cb-radio-images-element">
                                 <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Points', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_format" value="2" <?php if ( $lets_review_format == 2 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-points.png" alt="" class="cb-radio-image cb-style-option">
                                </label>
                                
                            </div>

                            <div class="cb-radio-images-element">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Stars', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_format" value="3" <?php if ( $lets_review_format == 3 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-stars.png" alt="" class="cb-radio-image cb-style-option">
                                </label>
                            </div>

                            <div class="cb-radio-images-element cb-trigger" data-cb-trigger="cb-custom-icon">
                                 <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Custom Icon', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_format" value="4" <?php if ( $lets_review_format == 4 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-custom-icon.png" alt="" class="cb-radio-image cb-style-option">
                                </label>
                            </div>

                            <div class="cb-radio-images-element cb-trigger" data-cb-trigger="cb-custom-image">
                                 <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Custom Image', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_format" value="5" <?php if ( $lets_review_format == 5 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-custom-image.png" alt="" class="cb-radio-image cb-style-option">
                                </label>
                            </div>

                            <?php // CUSTOM ICON OUTPUT; ?>
                            <div id="cb-custom-icon" class="cb-trigger-block <?php if ( $lets_review_format != 4 ) { ?>cb-hidden<?php } ?> cb-clearfix">
                                <div class="cb-option cb-with-desc cb-clearfix cb-clearfix">
                                    <span class="cb-list-title"><?php esc_html_e( 'Custom Icon Code', 'lets-review' ); ?></span>
                                    <input type="text" value="<?php echo esc_html( $lets_review_custom_icon ); ?>" name="lets_review_custom_icon"  class="cb-input">
                                    <div class="cb-desc"><?php esc_html_e( 'Enter the HTML icon code. A list of available icons can be found at https://fortawesome.github.io/Font-Awesome/icons/', 'lets-review' ); ?></div>
                                </div>
                            </div>

                            <?php // CUSTOM IMAGES BLOCK OUTPUT; ?>
                            <div id="cb-custom-image" class="cb-trigger-block cb-media-cont <?php if ( $lets_review_format != 5 ) { ?>cb-hidden<?php } ?> cb-clearfix">
                                <div id="cb-custom-image" class="cb-clearfix">
                                    <div class="cb-option cb-clearfix cb-mb">
                                        <span class="cb-list-title"><?php esc_html_e( 'Custom Image', 'lets-review' ); ?></span>
                                        <div class="cb-desc"><?php esc_html_e( 'Upload/Set an image of a single object. It will output 5 times automatically. Recommended size is 80px x 80px.', 'lets-review' ); ?></div>
                                    </div>
                                    
                                    <?php if ( !empty ( $lets_review_custom_image ) ) { ?>

                                        <?php $lets_review_custom_image_img = wp_get_attachment_image_src ( $lets_review_custom_image, 'thumbnail' ); ?>

                                            <span data-cb-id="<?php echo intval( $lets_review_custom_image ); ?>" class="cb-gallery-img">
                                                <a href="#" class="cb-remove"></a>
                                                <img src="<?php echo esc_url( $lets_review_custom_image_img[0] ); ?>" alt="">
                                                <input type="hidden" value="<?php echo intval( $lets_review_custom_image ); ?>" name="lets_review_custom_image">
                                            </span>

                                    <?php } ?>

                                </div>

                                <a href="#" id="cb-review-custom-image" class="cb-single-image-trigger cb-button" data-cb-dest="cb-custom-image" data-cb-name="lets_review_custom_image"><?php esc_html_e('Select Image', 'lets-review'); ?></a>
                            </div>

                        </div>
                        
                        <div class="cb-element cb-element-hw cb-clearfix cb-text">
                            <h2><?php esc_html_e( 'Final Score Subtitle', 'lets-review' ); ?></h2>
                            <div class="cb-option cb-with-desc cb-clearfix">
                                <input class="cb-input" type="text" id="cb-review-subtitle" name="lets_review_subtitle" value="<?php echo esc_attr( $lets_review_subtitle ); ?>">
                                <div class="cb-desc"><?php esc_html_e( 'Optional text under final score', 'lets-review' ); ?></div>
                            </div>
                        </div>

                        <div class="cb-element cb-clearfix cb-text">
                            <h2><?php esc_html_e( 'Conclusion', 'lets-review' ); ?></h2>
                            <div class="cb-option cb-element cb-clearfix">
                                <span class="cb-list-title"><?php esc_html_e( 'Conclusion heading', 'lets-review' ); ?></span>
                                <input class="cb-input" type="text" id="cb-review-conclusion-title" name="lets_review_conclusion_title" value="<?php echo esc_attr( $lets_review_conclusion_title ); ?>">
                            </div>

                            <div class="cb-option cb-element cb-clearfix">
                                <span class="cb-list-title"><?php esc_html_e( 'Conclusion content', 'lets-review' ); ?></span>
                                <textarea rows="4" cols="50" class="cb-input" type="text" id="cb-review-conclusion" name="lets_review_conclusion"><?php echo esc_attr( $lets_review_conclusion ); ?></textarea>
                            </div>
                        </div>

                    </div>                
                    <div id="fotomag-tab-2" class="cb-hidden cb-tab-content-area cb-clearfix">
                        <?php // CRITERIA BLOCK OUTPUT; ?>
                        <div class="cb-element cb-clearfix cb-criteria-block">
                            <h2><?php esc_html_e( 'Criterias', 'lets-review' ); ?></h2>
                            <div class="cb-desc cb-mb"><?php esc_html_e( 'Add as many criterias as you like. You can drag & drop the items too.', 'lets-review' ); ?></div>
                            <ul id="cb-criterias" data-cb-max="<?php echo intval( $lets_review_max_score ); ?>" data-cb-step="<?php echo floatval( $lets_review_max_step ); ?>" data-cb-format="cb-type-<?php echo intval( $lets_review_format ); ?>">
                                <?php if ( isset( $lets_review_crits ) && is_array( $lets_review_crits ) ) { ?>
                                    <?php foreach ( $lets_review_crits as $lets_review_crit ) { ?>

                                        <li id="cb-criteria-<?php echo intval( $lets_review_crit_counter ); ?>" class="ui-state-default cb-list-field cb-criteria-field cb-clearfix" data-cb-score="<?php if ( isset( $lets_review_crit['score'] ) ) { echo floatval( $lets_review_crit['score'] ); } ?>" data-cb-crit-title="<?php if ( isset( $lets_review_crit['title'] ) ) { echo esc_html( $lets_review_crit['title'] ); } ?>">
                                            <div class="cb-criteria-title">
                                                <span class="cb-list-title"><?php esc_html_e( 'Title', 'lets-review' ); ?></span>
                                                <input type="text" value="<?php if ( isset( $lets_review_crit['title'] ) ) { echo esc_html( $lets_review_crit['title'] ); } ?>" id="cb-criteria-field-<?php echo intval( $lets_review_crit_counter ); ?>" name="lets_review_criterias[<?php echo intval( $lets_review_crit_counter ); ?>][title]" class="cb-input">
                                            </div>
                                            <div class="cb-criteria-score cb-clearfix">
                                                <span class="cb-list-title"><?php esc_html_e( 'Score', 'lets-review' ); ?></span>
                                                <div class="cb-review-slider cb-slider"></div>
                                                <input type="text" value="<?php if ( isset( $lets_review_crit['score'] ) ) { echo floatval( $lets_review_crit['score'] ); } ?>" name="lets_review_criterias[<?php echo intval( $lets_review_crit_counter ); ?>][score]" class="cb-cri-score cb-input" readonly>
                                            </div>
                                            <a href="#" class="cb-button cb-remove"></a>
                                        </li>
                                        <?php $lets_review_crit_counter++; ?>

                                    <?php } ?>
                                <?php } ?>

                            </ul>

                            <div class="cb-clearfix cb-final-score cb-list-field cb-criteria-field cb-mb">
                                <div class="cb-criteria-title">
                                    <span class="cb-list-title cb-big-title"><?php esc_html_e( 'Final Score', 'lets-review' ); ?></span>
                                </div>
              
                                <div class="cb-criteria-score cb-clearfix">
                                    <div class="cb-review-slider cb-slider cb-exclude"></div>
                                    <input type="text" value="<?php if ( $lets_review_final_score != NULL ) { echo floatval( $lets_review_final_score ); } ?>" name="lets_review_final_score" id="cb-final-score"  class="cb-cri-score cb-input" readonly>
                                </div>

                            </div>
                            <input type="hidden" value="<?php if ( $lets_review_final_score_100 != NULL ) { echo floatval( $lets_review_final_score_100 ); } ?>" name="lets_review_final_score_100" id="cb-final-score-100"  readonly>
                            <input type="hidden" value="<?php echo esc_attr( $lets_review_final_score_override ); ?>" name="lets_review_final_score_override" id="cb-final-score-override"  readonly>

                            <a href="#" id="cb-add-crit" class="cb-button cb-add-new" data-cb-counter="<?php echo intval( $lets_review_crit_counter ); ?>"><?php esc_html_e( 'Add', 'lets-review' ); ?></a>
                        </div>

                        <?php // PRO BLOCK OUTPUT; ?>
                        <div class="cb-element cb-clearfix cb-pros-block">
                            <h2><?php esc_html_e( 'Positives', 'lets-review' ); ?></h2>
                            <div class="cb-option cb-element cb-clearfix">
                                <span class="cb-list-title"><?php esc_html_e( 'Optional positive heading', 'lets-review' ); ?></span>
                                <input class="cb-input" type="text" id="cb-pros-title" name="lets_review_pros_title" value="<?php echo esc_attr( $lets_review_pros_title ); ?>">
                            </div>
                            <div class="cb-desc cb-mb"><?php esc_html_e( 'Add as many positive points as you like. You can drag & drop the items too.', 'lets-review' ); ?></div>
                            <ul id="cb-pros" class="cb-pros-wrap cb-mb">
                                
                                <?php if ( isset( $lets_review_pros ) && is_array( $lets_review_pros ) ) { ?>
                                    <?php foreach ( $lets_review_pros as $lets_review_pro ) { ?>

                                        <li id="cb-pro-<?php echo intval( $lets_review_pro_counter ); ?>" class="ui-state-default cb-list-field cb-clearfix">
                                            <div class="cb-pro-title">
                                                <span class="cb-list-title"><?php esc_html_e( 'Title', 'lets-review' ); ?></span>
                                                <input type="text" value="<?php if ( isset( $lets_review_pro['positive'] ) ) { echo esc_html( $lets_review_pro['positive'] ); } ?>" id="cb-pro-field-<?php echo intval( $lets_review_pro_counter ); ?>" name="lets_review_pros[<?php echo intval( $lets_review_pro_counter ); ?>][positive]"  class="cb-input">
                                            </div>
                                            
                                            <a href="#" class="cb-button cb-remove"></a>
                                        </li>
                                        <?php $lets_review_pro_counter++; ?>

                                    <?php } ?>
                                <?php } ?>

                            </ul>
                            <a href="#" id="cb-add-pro" class="cb-button cb-add-new" data-cb-counter="<?php echo intval( $lets_review_pro_counter ); ?>"><?php esc_html_e( 'Add', 'lets-review' ); ?></a>
                        </div>

                        <?php // CONS BLOCK OUTPUT; ?>
                        <div class="cb-element cb-clearfix cb-cons-block">
                            <h2><?php esc_html_e( 'Negatives', 'lets-review' ); ?></h2>
                            <div class="cb-option cb-element cb-clearfix">
                                <span class="cb-list-title"><?php esc_html_e( 'Optional negative heading', 'lets-review' ); ?></span>
                                <input class="cb-input" type="text" id="cb-conss-title" name="lets_review_cons_title" value="<?php echo esc_attr( $lets_review_cons_title ); ?>">
                            </div>
                            <div class="cb-desc cb-mb"><?php esc_html_e( 'Add as many negative points as you like. You can drag & drop the items too.', 'lets-review' ); ?></div>
                            <ul id="cb-cons" class="cb-cons-wrap cb-mb">
                                <?php if ( isset( $lets_review_cons ) && is_array( $lets_review_cons ) ) { ?>
                                    <?php foreach ( $lets_review_cons as $lets_review_con ) { ?>

                                        <li id="cb-con-<?php echo intval( $lets_review_con_counter ); ?>" class="ui-state-default cb-list-field cb-clearfix">
                                            <div class="cb-con-title">
                                                <span class="cb-list-title"><?php esc_html_e( 'Title', 'lets-review' ); ?></span>
                                                <input type="text" value="<?php if ( isset( $lets_review_con['negative'] ) ) { echo esc_html( $lets_review_con['negative'] ); } ?>" id="cb-con-field-<?php echo intval( $lets_review_con_counter ); ?>" name="lets_review_cons[<?php echo intval( $lets_review_con_counter ); ?>][negative]"  class="cb-input">
                                            </div>
                                            
                                            <a href="#" class="cb-button cb-remove"></a>
                                        </li>
                                        <?php $lets_review_con_counter++; ?>

                                    <?php } ?>
                                <?php } ?>

                            </ul>
                            <a href="#" id="cb-add-con" class="cb-button cb-add-new" data-cb-counter="<?php echo intval( $lets_review_con_counter ); ?>"><?php esc_html_e( 'Add', 'lets-review' ); ?></a>
                        </div>

                    </div>
                    <div id="fotomag-tab-4" class="cb-hidden cb-tab-content-area cb-clearfix">
                        
                        <div class="cb-review-fi cb-clearfix cb-mb">
                            <h2><?php esc_html_e( 'Main Image', 'lets-review' ); ?></h2>
                            <div id="cb-review-fi-cont" class="cb-media-cont cb-mb cb-clearfix">
                                 <div class="cb-desc"><?php esc_html_e( 'Add an image thumbnail to appear in the review area', 'lets-review' ); ?></div>
                                <?php if ( !empty ( $lets_review_fi ) ) { ?>

                                    <?php $lets_review_fi_id = wp_get_attachment_image_src ( $lets_review_fi, 'thumbnail' ); ?>

                                        <span data-cb-id="<?php echo intval( $lets_review_fi ); ?>" class="cb-gallery-img">
                                            <a href="#" class="cb-remove"></a>
                                            <img src="<?php echo esc_url( $lets_review_fi_id[0] ); ?>" alt="">
                                            <input type="hidden" value="<?php echo intval( $lets_review_fi ); ?>" name="lets_review_fi">
                                        </span>

                                <?php } ?>

                            </div>

                            <a href="#" id="cb-review-fi" class="cb-single-image-trigger cb-button" data-cb-dest="cb-review-fi-cont" data-cb-name="lets_review_fi"><?php esc_html_e('Select Image', 'lets-review'); ?></a>
                        </div>
 
                        <div class="cb-gallery cb-clearfix">
                            <h2><?php esc_html_e( 'Image Gallery', 'lets-review' ); ?></h2>

                            <div class="cb-option cb-element cb-clearfix">
                                <span class="cb-list-title"><?php esc_html_e( 'Optional gallery heading', 'lets-review' ); ?></span>
                                <input class="cb-input" type="text" name="lets_review_gallery_title" value="<?php echo esc_attr( $lets_review_gallery_title ); ?>">
                            </div>

                            <div id="cb-gallery-cont" class="cb-media-cont cb-mb cb-media-cont-multi cb-clearfix">
                                <div class="cb-desc"><?php esc_html_e( 'Add a gallery of images that open in a lightbox slideshow', 'lets-review' ); ?></div>
                                <?php if ( !empty ( $lets_review_gallery_imgs ) ) { ?>

                                    <?php foreach ( $lets_review_gallery_imgs as $lets_review_gallery_id ) { ?>

                                    <?php $lets_review_img_src = wp_get_attachment_image_src ( $lets_review_gallery_id['attachment-id'], 'thumbnail' ); ?>

                                        <span data-cb-id="<?php echo intval( $lets_review_gallery_id['attachment-id'] ); ?>" class="cb-gallery-img">
                                            <a href="#" class="cb-remove"></a>
                                            <img src="<?php echo esc_url( $lets_review_img_src[0] ); ?>" alt="">
                                            <input type="hidden" value="<?php echo intval( $lets_review_gallery_id['attachment-id'] ); ?>" name="lets_review_gallery_imgs[<?php echo intval( $lets_review_imgs_counter ); ?>][attachment-id]">
                                        </span>

                                        <?php $lets_review_imgs_counter++; ?>

                                    <?php } ?>
                                <?php } ?>

                            </div>
                            <a href="#" id="cb-review-media" class="cb-gallery-trigger cb-button" data-cb-counter="<?php if ( !empty ( $lets_review_gallery_imgs ) ) {  echo intval( count( $lets_review_gallery_imgs ) ); } else { echo intval( $lets_review_imgs_counter ); } ?>"><?php esc_html_e('Add/Edit', 'lets-review'); ?></a>
                        </div>

                    </div>
                    <div id="fotomag-tab-3" class="cb-hidden cb-tab-content-area cb-clearfix">
                        <h2><?php esc_html_e( 'Design Selection', 'lets-review' ); ?></h2>

                        <div class="cb-element cb-radio-images cb-trigger-wrap cb-review-skin cb-clearfix cb-text">
                            
                            <div class="cb-radio-images-element" data-cb-trigger="cb-trigger-skin">
                                 <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Minimalist', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_design" value="1" <?php if ( $lets_review_design == 1 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-skin-1.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element" data-cb-trigger="cb-trigger-skin">
                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Bold', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_design" value="2" <?php if ( $lets_review_design == 2 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-skin-2.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div class="cb-radio-images-element">

                                <label>
                                    <span class="cb-radio-title"><?php esc_html_e( 'Modern', 'lets-review' ); ?></span>
                                    <input type="radio" class="cb-input-radio" name="lets_review_design" value="3" <?php if ( $lets_review_design == 3 ) { ?>checked="checked"<?php } ?>>
                                    <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-skin-3.png" alt="" class="cb-radio-image">
                                </label>
                            </div>

                            <div id="cb-trigger-skin" class="cb-trigger-block <?php if ( $lets_review_design == 3 ) { ?>cb-hidden<?php } ?> cb-clearfix">
                                <h2><?php esc_html_e( 'Skin Style', 'lets-review' ); ?></h2>
                        
                                 <div class="cb-element cb-radio-images cb-review-skin cb-clearfix cb-text">
                                    
                                    <div class="cb-radio-images-element">
                                         <label>
                                            <span class="cb-radio-title"><?php esc_html_e( 'Light', 'lets-review' ); ?></span>
                                            <input type="radio" class="cb-input-radio" name="lets_review_design_skin" value="1" <?php if ( $lets_review_design_skin == 1 ) { ?>checked="checked"<?php } ?>>
                                            <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-light.png" alt="" class="cb-radio-image">
                                        </label>
                                    </div>

                                    <div class="cb-radio-images-element">
                                        <label>
                                            <span class="cb-radio-title"><?php esc_html_e( 'Dark', 'lets-review' ); ?></span>
                                            <input type="radio" class="cb-input-radio" name="lets_review_design_skin" value="2" <?php if ( $lets_review_design_skin == 2 ) { ?>checked="checked"<?php } ?>>
                                            <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-dark.png" alt="" class="cb-radio-image">
                                        </label>
                                    </div>

                                </div>


                                <h2><?php esc_html_e( 'Animation Type', 'lets-review' ); ?></h2>

                                <div class="cb-element cb-radio-images cb-review-ani cb-clearfix cb-text">
                                    
                                    <div class="cb-radio-images-element">
                                         <label>
                                            <span class="cb-radio-title"><?php esc_html_e( 'Incremental', 'lets-review' ); ?></span>
                                            <input type="radio" class="cb-input-radio" name="lets_review_design_ani" value="1" <?php if ( $lets_review_design_ani == 1 ) { ?>checked="checked"<?php } ?>>
                                            <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-ani-1.gif" alt="" class="cb-radio-image">
                                        </label>
                                    </div>

                                    <div class="cb-radio-images-element">
                                        <label>
                                            <span class="cb-radio-title"><?php esc_html_e( 'Fade In', 'lets-review' ); ?></span>
                                            <input type="radio" class="cb-input-radio" name="lets_review_design_ani" value="2" <?php if ( $lets_review_design_ani == 2 ) { ?>checked="checked"<?php } ?>>
                                            <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-ani-2.gif" alt="" class="cb-radio-image">
                                        </label>
                                    </div>

                                    <div class="cb-radio-images-element">

                                        <label>
                                            <span class="cb-radio-title"><?php esc_html_e( 'None', 'lets-review' ); ?></span>
                                            <input type="radio" class="cb-input-radio" name="lets_review_design_ani" value="3" <?php if ( $lets_review_design_ani == 3 ) { ?>checked="checked"<?php } ?>>
                                            <img src="<?php echo esc_url( $this->lets_review_url ); ?>admin/images/lr-ani-off.png" alt="" class="cb-radio-image">
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        
                        <h2><?php esc_html_e( 'Accent Color', 'lets-review' ); ?></h2>
                        <div class="cb-review-color cb-element cb-clearfix">
                            
                            <input type="text" class="lets-review-color" name="lets_review_color" value="<?php echo esc_attr( $lets_review_design_color ); ?>">
                            <div class="lets-review-colorpicker"></div>
                        </div>

                        

                    </div>
                    <div id="fotomag-tab-5" class="cb-hidden cb-tab-content-area cb-clearfix">
                        <h2><?php esc_html_e( 'Affiliate Title', 'lets-review' ); ?></h2>
                        <div class="cb-option cb-with-desc cb-clearfix cb-clearfix cb-mb">
                            <input class="cb-input" type="text" name="lets_review_aff_title" value="<?php echo esc_attr( $lets_review_aff_title ); ?>">
                            <div class="cb-desc"><?php esc_html_e( 'Optional title to show above affiliate buttons', 'lets-review' ); ?></div>
                        </div>
                        <h2><?php esc_html_e( 'Affiliate Buttons', 'lets-review' ); ?></h2>
                        <div class="cb-desc cb-mb"><?php esc_html_e( 'Add as many affiliate/buy links you like. You can drag & drop the items too.', 'lets-review' ); ?></div>
                        <ul id="cb-aff-buttons"  class="cb-clearfix cb-mb">
                            <?php if ( isset( $lets_review_aff_options ) && is_array( $lets_review_aff_options ) ) { ?>
                                <?php foreach ( $lets_review_aff_options as $lets_review_aff_option ) { ?>

                                    <li id="cb-aff-option-<?php echo intval( $lets_review_aff_counter ); ?>" class="ui-state-default cb-list-field cb-affiliate-field cb-clearfix">
                                        <div class="cb-aff-option-title cb-list-hw">
                                            <span class="cb-list-title"><?php esc_html_e( 'Title', 'lets-review' ); ?></span>
                                            <input type="text" value="<?php if ( isset( $lets_review_aff_option['title'] ) ) { echo esc_html( $lets_review_aff_option['title'] ); } ?>" id="cb-aff-option-field-<?php echo intval( $lets_review_aff_counter ); ?>" name="lets_review_aff_buttons[<?php echo intval( $lets_review_aff_counter ); ?>][title]"  class="cb-input">
                                        </div>
                                        <div class="cb-aff-option-url cb-list-hw cb-list-hw-2">
                                            <span class="cb-list-title"><?php esc_html_e( 'Affiliate URL', 'lets-review' ); ?></span>
                                            <input type="text" value="<?php if ( isset( $lets_review_aff_option['url'] ) ) { echo esc_url_raw( $lets_review_aff_option['url'] ); } ?>" id="cb-aff-option-field-<?php echo intval( $lets_review_aff_counter ); ?>" name="lets_review_aff_buttons[<?php echo intval( $lets_review_aff_counter ); ?>][url]"  class="cb-input">
                                        </div>
                                        <a href="#" class="cb-button cb-remove"></a>
                                    </li>
                                    <?php $lets_review_aff_counter++; ?>

                                <?php } ?>
                            <?php } ?>

                        </ul>
                        <a href="#" id="cb-add-aff" class="cb-button cb-add-new" data-cb-counter="<?php echo intval( $lets_review_aff_counter ); ?>"><?php esc_html_e( 'Add', 'lets-review' ); ?></a>
                    </div>
                </div> 
            </div> 

            
            <?php

        }
    }
}