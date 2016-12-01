<?php 
/**
 *
 * Let's Review Widget
 *
 * @since      1.0.0
 *
 * @package    Let's Review
 * @subpackage lets-review/inc
 */

if ( ! class_exists( 'Lets_Review_Widget' ) ) {
    class Lets_Review_Widget extends WP_Widget {

        /**
         * Constructor
         *
         * @since 1.0.0
         *
        */
        public function __construct() {

            $widget_ops = array( 
                'classname' => 'lets_review_widget',
                'description' => esc_html__( 'Review widget desc', 'lets-review' ),
            );
            parent::__construct( 'lets-review-widget', esc_html__( 'Let\'s Review Widget', 'lets-review' ) , $widget_ops );

        }

        /**
         * Let's Review Final Scores
         *
         * @since 1.0.0
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function lets_review_box( $lets_review_post_id = NULL, $lets_review_size = 'bar' ) {

            $lets_review_final_score = get_post_meta( $lets_review_post_id, '_lets_review_final_score', true );
            $lets_review_type = get_post_meta( $lets_review_post_id, '_lets_review_type', true );
            $lets_review_format = get_post_meta( $lets_review_post_id, '_lets_review_format', true );
            $lets_review_subtitle = get_post_meta( $lets_review_post_id, '_lets_review_subtitle', true );
            $lets_review_color = ( get_post_meta( $lets_review_post_id, '_lets_review_color', true ) == NULL ) ? '#f8d92f' : get_post_meta( $lets_review_post_id, '_lets_review_color', true );

            if ( $lets_review_type == 3 ) {
                $lets_review_final_score = get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true ) ? get_post_meta( $lets_review_post_id, '_lets_review_user_rating', true ) : '0';
            }

            switch ( $lets_review_format ) {
                case 1:
                    $lets_review_final_score_per = $lets_review_final_score;
                    break;
                case 2:
                    $lets_review_final_score_per = $lets_review_final_score * 10;
                    break;
                
                default:
                    $lets_review_final_score_per = $lets_review_final_score * 20;
                    break;
            }



            if ( $lets_review_size == 'big' ) {
            ?>
            <div class="cb-final-score<?php if ( empty( $lets_review_subtitle ) ) { ?> cb-no-sub<?php } ?>">
                <div class="cb-score-bg" style="background: <?php echo esc_attr( $lets_review_color ); ?>;"></div>
                <div class="cb-score cb-format-<?php echo esc_attr( $lets_review_format ); ?>"><?php echo floatval( $lets_review_final_score ); ?></div>
            

            </div>
            <?php
            }

            if ( $lets_review_size == 'small' ) {
            ?>
            <div class="cb-final-score">
                <div class="cb-score-bg" style="background-color:<?php echo esc_attr( $lets_review_color ); ?>; "></div>
                <div class="cb-score cb-format-<?php echo esc_attr( $lets_review_format ); ?>"><?php echo floatval( $lets_review_final_score ); ?></div>
            </div>
            <?php
            }

            if ( $lets_review_size == 'bar' ) {
            ?>
            <div class="cb-score cb-format-<?php echo esc_attr( $lets_review_format ); ?>"><?php echo floatval( $lets_review_final_score ); ?></div>
            <div class="cb-score-bar"><div class="cb-score-overlay" style="width: <?php echo floatval( $lets_review_final_score_per ); ?>%; background-color:<?php echo esc_attr( $lets_review_color ); ?>;"></div></div>
            <?php
            }

            if ( $lets_review_size == 'stars' ) {
            ?>
            <span class="cb-overlay" style="color: <?php echo esc_attr( $lets_review_color ); ?>;"><?php echo str_repeat( '<i class="fa fa-star"></i>', 5 ); ?><span style="width: <?php echo intval( $lets_review_final_score_per ); ?>%;" ></span></span>
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
                $lets_review_order = 'ASC';
                    $lets_review_orderby= 'meta_value_num';
                    break;
                case 3:
                    $lets_review_order = 'DESC';
                    $lets_review_orderby= 'date';
                    break;
                
                default:
                    $lets_review_order = 'DESC';
                    $lets_review_orderby= 'meta_value_num';
                    break;
            }

            switch ( $instance['pubdate'] ) {
                   
                case 2:
                    $lets_review_week = date( 'W' );
                    $lets_review_month = NULL;
                    $lets_review_year = date( 'Y' );
                    break;
                case 3:
                    $lets_review_week = NULL;
                    $lets_review_month = date( 'm', strtotime( '-30 days' ) );
                    $lets_review_year = date( 'Y', strtotime( '-30 days' ) );
                    break;
                
                default:
                    $lets_review_week = $lets_review_year = $lets_review_month = NULL;
                    
                    break;
            }

            if ( $instance['type'] == 1 ) {
                $lets_review_type_filter = '_lets_review_final_score_100';
            } else {
                $lets_review_type_filter = '_lets_review_user_rating';
            }

            $lets_review_qry = new WP_Query( array( 
                'post_type' => array( 'post' ), 
                'posts_per_page' => $instance['count'] + 5,
                'cat' => $instance['category'], 
                'w' => $lets_review_week,                
                'meta_key' => $lets_review_type_filter,
                'orderby' => $lets_review_orderby, 
                'order' => $lets_review_order, 
                'post_status' => 'publish',
                'no_found_rows' => true, 
                'ignore_sticky_posts' => true 
            ) );

            if ( $lets_review_qry->have_posts() ) :

                ?>
                <div class="cb-lets-review-widget cb-clearfix cb-widget-design-base">
                <?php
                    $i = 1;

                    switch ( $instance['design'] ) {
                        case 1:
                            $lets_review_width = $lets_review_height = NULL;
                            break;
                        case 2:
                            $lets_review_width = 360;
                            $lets_review_height = 240;
                            break;
                        case 3:
                            $lets_review_width = 90;
                            $lets_review_height = 60;
                            break;
                         case 4:
                            $lets_review_width = 90;
                            $lets_review_height = 60;
                            break;

                    }

                    while ( $lets_review_qry->have_posts() ) : $lets_review_qry->the_post();
                        global $post;
                        $lets_review_post_id = $post->ID;

                        if ( get_post_meta( $lets_review_post_id, '_lets_review_onoff', true ) != 1 ) {
                            continue;
                        }

                        $lets_review_fi_url = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( $lets_review_post_id ) ), $lets_review_width, $lets_review_height, true ) ? aq_resize( wp_get_attachment_url( get_post_thumbnail_id( $lets_review_post_id ) ), $lets_review_width, $lets_review_height, true ) : wp_get_attachment_url( get_post_thumbnail_id( $lets_review_post_id ) );
                        $lets_review_fi_exist = $lets_review_fi_url ? ' cb-fi-on' : ' cb-fi-off';

                        ?>
                        <article class="cb-widget-post cb-clearfix cb-lr-font-p<?php echo esc_attr( $lets_review_fi_exist ); ?> cb-widget-design-<?php echo intval( $instance['design'] ); ?>">
                            <?php if ( $instance['design'] == 1 ) { ?>
                    
                                <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <?php $this->lets_review_box( $lets_review_post_id, 'bar' );?>
                            <?php } ?>

                            <?php if ( $instance['design'] == 2 ) { ?>
                                <div class="cb-mask">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $lets_review_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                                <?php $this->lets_review_box( $lets_review_post_id, 'big' );?>
                                <a href="<?php the_permalink(); ?>" class="cb-full-overlay"></a>
                                <div class="cb-countdown"><?php echo intval( $i ); ?></div>
                            <?php } ?>

                            <?php if ( $instance['design'] == 3 ) { ?>
                                <div class="cb-mask">
                                    <?php $this->lets_review_box( $lets_review_post_id, 'small' );?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $lets_review_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                                
                            <?php } ?>

                            <?php if ( $instance['design'] == 4 ) { ?>
                                <div class="cb-mask">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $lets_review_fi_url ); ?>" alt="">
                                    </a>
                                </div>
                                <div class="cb-meta">
                                    <h4 class="cb-title cb-lr-font-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <?php $this->lets_review_box( $lets_review_post_id, 'stars' );?>
                                </div>
                                
                            <?php } ?>

                            <?php if ( $instance['design'] > 4 ) { ?>
                                <?php echo apply_filters( 'lets_review_widget_add_design_html_' . intval( $instance['design'] - 4 ), '' ); ?>
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
         * @since 1.0.0
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
            $lets_review_title = ! empty( $instance['title'] ) ? $instance['title'] : '';
            $lets_review_count = ! empty( $instance['count'] ) ? $instance['count'] : 4;
            $lets_review_pubdate = ! empty( $instance['pubdate'] ) ? $instance['pubdate'] : 1;
            $lets_review_category = ! empty( $instance['category'] ) ? $instance['category'] : array(0);
            $lets_review_order = ! empty( $instance['order'] ) ? $instance['order'] : 1;
            $lets_review_skin = ! empty( $instance['skin'] ) ? $instance['skin'] : 1;
            $lets_review_design = ! empty( $instance['design'] ) ? $instance['design'] : 1;
            $lets_review_type = ! empty( $instance['type'] ) ? $instance['type'] : 1;
            $lets_review_categories = get_categories();
            ?>
            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'lets-review' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $lets_review_title ); ?>">
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Amount Reviews:', 'lets-review' ); ?></label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" min="1" max="100" size="3" type="number" value="<?php echo esc_attr( $lets_review_count ); ?>">
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category(s):', 'lets-review' ); ?></label>
            <select multiple="multiple" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" id="cb-cat-<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
                <option value="0" <?php if ( in_array( 0, $lets_review_category ) == true ) {?>selected="selected"<?php } ?>>
                    <?php esc_html_e( 'All Categories', 'lets-review' ); ?>
                </option>
                <?php foreach ( $lets_review_categories as $lets_review_cat ) { ?>
                    <option value="<?php echo esc_attr( $lets_review_cat->term_id ); ?>" <?php if ( in_array( $lets_review_cat->term_id, $lets_review_category ) ) { ?>selected="selected"<?php } ?>> <?php echo ( $lets_review_cat->name ) . ' ( ' . $lets_review_cat->count . ' )'; ?> </option>
                <?php } ?>
            </select>
            </p>
    
            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pubdate' ) ); ?>"><?php esc_html_e( 'Review Date:', 'lets-review' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'pubdate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pubdate' ) ); ?>">
                <option value="1" <?php if ( $lets_review_pubdate == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'All-time', 'lets-review' ); ?></option>
                <option value="2" <?php if ( $lets_review_pubdate == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Past 7 Days', 'lets-review' ); ?></option>
                <option value="3" <?php if ( $lets_review_pubdate == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Last Month', 'lets-review' ); ?></option>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'lets-review' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                <option value="1" <?php if ( $lets_review_order == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Top Scores', 'lets-review' ); ?></option>
                <option value="2" <?php if ( $lets_review_order == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Lowest Scores', 'lets-review' ); ?></option>
                <option value="3" <?php if ( $lets_review_order == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Newest Reviews', 'lets-review' ); ?></option>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'design' ) ); ?>"><?php esc_html_e( 'Design:', 'lets-review' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'design' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'design' ) ); ?>">
                <?php $this->lets_review_widget_add_design( $lets_review_design ); ?>
            </select>
            </p>

            <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Score Type:', 'lets-review' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
                <option value="1" <?php if ( $lets_review_type == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Editor Scores', 'lets-review' ); ?></option>
                <option value="2" <?php if ( $lets_review_type == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Visitor Scores', 'lets-review' ); ?></option>
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
        public function lets_review_widget_add_design( $lets_review_design ) {
            ?>
            <option value="1" <?php if ( $lets_review_design == '1' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Minimal', 'lets-review' ); ?></option>
            <option value="2" <?php if ( $lets_review_design == '2' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Bold', 'lets-review' ); ?></option>
            <option value="3" <?php if ( $lets_review_design == '3' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Simple', 'lets-review' ); ?></option>
            <option value="4" <?php if ( $lets_review_design == '4' ) { echo 'selected="selected"'; } ?>><?php esc_html_e( 'Simple + Stars', 'lets-review' ); ?></option>
            <?php
            $check = apply_filters( 'lets_review_widget_add_design', '' );
            if ( $check != NULL ) {
                if ( is_array( $check ) ) {
                    $i = 5;
                    foreach ( $check as $design ) {

                         ?>
                        <option value="<?php echo intval( $i ); ?>" <?php if ( $lets_review_design == '1' ) { echo 'selected="selected"'; } ?>><?php echo esc_html( $design ); ?></option>
                        <?php
                        $i++;
                    }
                } else {
                    ?>
                    <option value="5" <?php if ( $lets_review_design == '5' ) { echo 'selected="selected"'; } ?>><?php echo esc_html( $check ); ?></option>
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