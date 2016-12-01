/*
 Plugin Name: IDGB Games Widget Modern
 Plugin URI: www.gaming-masters.co.uk
 Description: Widget to display Game name with release date from IGDB
 Author: Gaming Masters
 Author URI: www.gaming-masters.co.uk
 Version: 2.0.1
 License: GPLv2 or later
 */
/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */

if ( ! class_exists( 'igdb_widget_modern_Widget' ) ) {
    class igdb_widget_modern_Widget extends WP_Widget {

        /**
         * Constructor
         *
         * @since 2.0.0
         *
        */
        public function __construct() {

            $widget_ops = array( 
                'classname' => 'igdb_widget_modern_widget',
                'description' => esc_html__( 'IGDB_Widget widget desc', 'IGDB_Widget' ),
            );
            parent::__construct( 'IGDB-Widget', esc_html__( 'IGDB_Widget', 'IGDB_Widget' ) , $widget_ops );

        }

        /**
         * IGDB_Widget Final Scores
         *
         * @since 2.0.0
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function igdb_widget_modern_box( $igdb_widget_modern_post_id = NULL, $igdb_widget_modern_size = 'bar' ) {

            $igdb_widget_modern_final_score = get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_final_score', true );
            $igdb_widget_modern_type = get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_type', true );
            $igdb_widget_modern_format = get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_format', true );
            $igdb_widget_modern_subtitle = get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_subtitle', true );
            $igdb_widget_modern_color = ( get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_color', true ) == NULL ) ? '#f8d92f' : get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_color', true );

            if ( $igdb_widget_modern_type == 3 ) {
                $igdb_widget_modern_final_score = get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_user_rating', true ) ? get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_user_rating', true ) : '0';
            }

            switch ( $igdb_widget_modern_format ) {
                case 1:
                    $igdb_widget_modern_final_score_per = $igdb_widget_modern_final_score;
                    break;
                case 2:
                    $igdb_widget_modern_final_score_per = $igdb_widget_modern_final_score * 10;
                    break;
                
                default:
                    $igdb_widget_modern_final_score_per = $igdb_widget_modern_final_score * 20;
                    break;
            }



            if ( $igdb_widget_modern_size == 'big' ) {
            ?>
            <div class="cb-final-score<?php if ( empty( $igdb_widget_modern_subtitle ) ) { ?> cb-no-sub<?php } ?>">
                <div class="cb-score-bg" style="background: <?php echo esc_attr( $igdb_widget_modern_color ); ?>;"></div>
                <div class="cb-score cb-format-<?php echo esc_attr( $igdb_widget_modern_format ); ?>"><?php echo floatval( $igdb_widget_modern_final_score ); ?></div>
            

            </div>
            <?php
            }

            if ( $igdb_widget_modern_size == 'small' ) {
            ?>
            <div class="cb-final-score">
                <div class="cb-score-bg" style="background-color:<?php echo esc_attr( $igdb_widget_modern_color ); ?>; "></div>
                <div class="cb-score cb-format-<?php echo esc_attr( $igdb_widget_modern_format ); ?>"><?php echo floatval( $igdb_widget_modern_final_score ); ?></div>
            </div>
            <?php
            }

            if ( $igdb_widget_modern_size == 'bar' ) {
            ?>
            <div class="cb-score cb-format-<?php echo esc_attr( $igdb_widget_modern_format ); ?>"><?php echo floatval( $igdb_widget_modern_final_score ); ?></div>
            <div class="cb-score-bar"><div class="cb-score-overlay" style="width: <?php echo floatval( $igdb_widget_modern_final_score_per ); ?>%; background-color:<?php echo esc_attr( $igdb_widget_modern_color ); ?>;"></div></div>
            <?php
            }

            if ( $igdb_widget_modern_size == 'stars' ) {
            ?>
            <span class="cb-overlay" style="color: <?php echo esc_attr( $igdb_widget_modern_color ); ?>;"><?php echo str_repeat( '<i class="fa fa-star"></i>', 5 ); ?><span style="width: <?php echo intval( $igdb_widget_modern_final_score_per ); ?>%;" ></span></span>
            <?php
            }
        }       

        /**
         * Front-end display of widget.
         *
         * @since 1.0.0
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {

            echo $args['before_widget'];
            
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            }

            switch ( $instance['order'] ) {
                case 2:
                $igdb_widget_modern_order = 'ASC';
                    $igdb_widget_modern_orderby= 'meta_value_num';
                    break;
                case 3:
                    $igdb_widget_modern_order = 'DESC';
                    $igdb_widget_modern_orderby= 'date';
                    break;
                
                default:
                    $igdb_widget_modern_order = 'DESC';
                    $igdb_widget_modern_orderby= 'meta_value_num';
                    break;
            }

            switch ( $instance['pubdate'] ) {
                   
                case 2:
                    $igdb_widget_modern_week = date( 'W' );
                    $igdb_widget_modern_month = NULL;
                    $igdb_widget_modern_year = date( 'Y' );
                    break;
                case 3:
                    $igdb_widget_modern_week = NULL;
                    $igdb_widget_modern_month = date( 'm', strtotime( '-30 days' ) );
                    $igdb_widget_modern_year = date( 'Y', strtotime( '-30 days' ) );
                    break;
                
                default:
                    $igdb_widget_modern_week = $igdb_widget_modern_year = $igdb_widget_modern_month = NULL;
                    
                    break;
            }

            if ( $instance['type'] == 1 ) {
                $igdb_widget_modern_type_filter = '_igdb_widget_modern_final_score_100';
            } else {
                $igdb_widget_modern_type_filter = '_igdb_widget_modern_user_rating';
            }

            $igdb_widget_modern_qry = new WP_Query( array( 
                'post_type' => array( 'post' ), 
                'posts_per_page' => $instance['count'] + 5,
                'cat' => $instance['category'], 
                'w' => $igdb_widget_modern_week,                
                'meta_key' => $igdb_widget_modern_type_filter,
                'orderby' => $igdb_widget_modern_orderby, 
                'order' => $igdb_widget_modern_order, 
                'post_status' => 'publish',
                'no_found_rows' => true, 
                'ignore_sticky_posts' => true 
            ) );

            if ( $igdb_widget_modern_qry->have_posts() ) :

                ?>
                <div class="cb-IGDB-Widget cb-clearfix cb-widget-design-base">
                <?php
                    $i = 1;

                    switch ( $instance['design'] ) {
                        case 1:
                            $igdb_widget_modern_width = $igdb_widget_modern_height = NULL;
                            break;
                        case 2:
                            $igdb_widget_modern_width = 360;
                            $igdb_widget_modern_height = 240;
                            break;
                        case 3:
                            $igdb_widget_modern_width = 90;
                            $igdb_widget_modern_height = 60;
                            break;
                         case 4:
                            $igdb_widget_modern_width = 90;
                            $igdb_widget_modern_height = 60;
                            break;

                    }

                    while ( $igdb_widget_modern_qry->have_posts() ) : $igdb_widget_modern_qry->the_post();
                        global $post;
                        $igdb_widget_modern_post_id = $post->ID;

                        if ( get_post_meta( $igdb_widget_modern_post_id, '_igdb_widget_modern_onoff', true ) != 1 ) {
                            continue;
                        }

                        $igdb_widget_modern_fi_url = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( $igdb_widget_modern_post_id ) ), $igdb_widget_modern_width, $igdb_widget_modern_height, true ) ? aq_resize( wp_get_attachment_url( get_post_thumbnail_id( $igdb_widget_modern_post_id ) ), $igdb_widget_modern_width, $igdb_widget_modern_height, true ) : wp_get_attachment_url( get_post_thumbnail_id( $igdb_widget_modern_post_id ) );
                        $igdb_widget_modern_fi_exist = $igdb_widget_modern_fi_url ? ' cb-fi-on' : ' cb-fi-off';

                        ?>
                        <article class="cb-widget-post cb-clearfix cb-lr-font-p<?php echo esc_attr( $igdb_widget_modern_fi_exist ); ?> cb-widget-design-<?php echo intval( $instance['design'] ); ?>">
                            <?php if ( $instance['design'] == 1 ) { ?>
                    
                                <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <?php $this->igdb_widget_modern_box( $igdb_widget_modern_post_id, 'bar' );?>
                            <?php } ?>

                            <?php if ( $instance['design'] == 2 ) { ?>
                                <div class="cb-mask">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $igdb_widget_modern_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                                <?php $this->igdb_widget_modern_box( $igdb_widget_modern_post_id, 'big' );?>
                                <a href="<?php the_permalink(); ?>" class="cb-full-overlay"></a>
                                <div class="cb-countdown"><?php echo intval( $i ); ?></div>
                            <?php } ?>

                            <?php if ( $instance['design'] == 3 ) { ?>
                                <div class="cb-mask">
                                    <?php $this->igdb_widget_modern_box( $igdb_widget_modern_post_id, 'small' );?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $igdb_widget_modern_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                                
                            <?php } ?>

                            <?php if ( $instance['design'] == 4 ) { ?>
                                <div class="cb-mask">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $igdb_widget_modern_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <?php $this->igdb_widget_modern_box( $igdb_widget_modern_post_id, 'stars' );?>
                                </div>
                                
                            <?php } ?>

                            <?php if ( $instance['design'] > 4 ) { ?>
                                <?php echo apply_filters( 'igdb_widget_modern_widget_add_design_html_' . intval( $instance['design'] - 4 ), '' ); ?>
                            <?php } ?>
                        </article>
                        
                        <?php if ( $i ==  $instance['count'] ) { break; } ?>
                        <?php $i++; ?>

                    <?php endwhile; ?>
                </div>

                <?php
                echo $args['after_widget'];
                wp_reset_postdata();

            endif;
        }

        /**
         * Back-end widget form.
         *
         * @since 2.0.0
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
            $igdb_widget_modern_title = ! empty( $instance['title'] ) ? $instance['title'] : '';
            $igdb_widget_modern_count = ! empty( $instance['count'] ) ? $instance['count'] : 4;
            $igdb_widget_modern_pubdate = ! empty( $instance['pubdate'] ) ? $instance['pubdate'] : 1;
            $igdb_widget_modern_category = ! empty( $instance['category'] ) ? $instance['category'] : array(0);
            $igdb_widget_modern_order = ! empty( $instance['order'] ) ? $instance['order'] : 1;
            $igdb_widget_modern_skin = ! empty( $instance['skin'] ) ? $instance['skin'] : 1;
            $igdb_widget_modern_design = ! empty( $instance['design'] ) ? $instance['design'] : 1;
            $igdb_widget_modern_type = ! empty( $instance['type'] ) ? $instance['type'] : 1;
            $igdb_widget_modern_categories = get_categories();
            ?>
            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'IGDB_Widget' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $igdb_widget_modern_title ); ?>">
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Amount IGDB_Widgets:', 'IGDB_Widget' ); ?></label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" min="1" max="100" size="3" type="number" value="<?php echo esc_attr( $igdb_widget_modern_count ); ?>">
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category(s):', 'IGDB_Widget' ); ?></label>
            <select multiple="multiple" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" id="cb-cat-<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
                <option value="0" <?php if ( in_array( 0, $igdb_widget_modern_category ) == true ) {?>selected="selected"<?php } ?>>
                    <?php esc_html_e( 'All Categories', 'IGDB_Widget' ); ?>
                </option>
                <?php foreach ( $igdb_widget_modern_categories as $igdb_widget_modern_cat ) { ?>
                    <option value="<?php echo esc_attr( $igdb_widget_modern_cat->term_id ); ?>" <?php if ( in_array( $igdb_widget_modern_cat->term_id, $igdb_widget_modern_category ) ) { ?>selected="selected"<?php } ?>> <?php echo ( $igdb_widget_modern_cat->name ) . ' ( ' . $igdb_widget_modern_cat->count . ' )'; ?> </option>
                <?php } ?>
            </select>
            </p>
    
            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pubdate' ) ); ?>"><?php esc_html_e( 'IGDB_Widget Date:', 'IGDB_Widget' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'pubdate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pubdate' ) ); ?>">
                <option value="1" <?php if ( $igdb_widget_modern_pubdate == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'All-time', 'IGDB_Widget' ); ?></option>
                <option value="2" <?php if ( $igdb_widget_modern_pubdate == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Past 7 Days', 'IGDB_Widget' ); ?></option>
                <option value="3" <?php if ( $igdb_widget_modern_pubdate == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Last Month', 'IGDB_Widget' ); ?></option>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'IGDB_Widget' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                <option value="1" <?php if ( $igdb_widget_modern_order == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Top Scores', 'IGDB_Widget' ); ?></option>
                <option value="2" <?php if ( $igdb_widget_modern_order == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Lowest Scores', 'IGDB_Widget' ); ?></option>
                <option value="3" <?php if ( $igdb_widget_modern_order == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Newest IGDB_Widgets', 'IGDB_Widget' ); ?></option>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'design' ) ); ?>"><?php esc_html_e( 'Design:', 'IGDB_Widget' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'design' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'design' ) ); ?>">
                <?php $this->igdb_widget_modern_widget_add_design( $igdb_widget_modern_design ); ?>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Score Type:', 'IGDB_Widget' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
                <option value="1" <?php if ( $igdb_widget_modern_type == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Editor Scores', 'IGDB_Widget' ); ?></option>
                <option value="2" <?php if ( $igdb_widget_modern_type == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Visitor Scores', 'IGDB_Widget' ); ?></option>
            </select>
            </p>


            <?php 
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function igdb_widget_modern_widget_add_design( $igdb_widget_modern_design ) {
            ?>
            <option value="1" <?php if ( $igdb_widget_modern_design == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Minimal', 'IGDB_Widget' ); ?></option>
            <option value="2" <?php if ( $igdb_widget_modern_design == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Bold', 'IGDB_Widget' ); ?></option>
            <option value="3" <?php if ( $igdb_widget_modern_design == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Simple', 'IGDB_Widget' ); ?></option>
            <option value="4" <?php if ( $igdb_widget_modern_design == '4' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Simple + Stars', 'IGDB_Widget' ); ?></option>
            <?php
            $check = apply_filters( 'igdb_widget_modern_widget_add_design', '' );
            if ( $check != NULL ) {
                if ( is_array( $check ) ) {
                    $i = 5;
                    foreach ( $check as $design ) {

                         ?>
                        <option value="<?php echo intval( $i ); ?>" <?php if ( $igdb_widget_modern_design == '1' ) { echo 'selected="selected"'; } ?>><?php echo esc_html( $design ); ?></option>
                        <?php
                        $i++;
                    }
                } else {
                    ?>
                    <option value="5" <?php if ( $igdb_widget_modern_design == '5' ) { echo 'selected="selected"'; } ?>><?php echo esc_html( $check ); ?></option>
                    <?php
                }
            }
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? intval( $new_instance['count'] ) : 4;
            $instance['design'] = ( ! empty( $new_instance['design'] ) ) ? intval( $new_instance['design'] ) : 1;
            $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? intval( $new_instance['order'] ) : 1;
            $instance['skin'] = ( ! empty( $new_instance['skin'] ) ) ? intval( $new_instance['skin'] ) : 1;
            $instance['pubdate'] = ( ! empty( $new_instance['pubdate'] ) ) ? intval( $new_instance['pubdate'] ) : 1;
            $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? ( $new_instance['category'] ) : array(0);
            $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? ( $new_instance['type'] ) : 1;

            return $instance;
        }
    }
}