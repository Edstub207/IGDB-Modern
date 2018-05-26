<!-- Title Field -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:' , 'ts_hvrbrd'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<!-- IGDB Api Key Field -->
<p>
	<label for="<?php echo $this->get_field_id( 'api' ); ?>"><?php esc_html_e( 'API:' , 'ts_hvrbrd'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'api' ); ?>" name="<?php echo $this->get_field_name( 'api' ); ?>" type="text" value="<?php echo esc_attr( $api ); ?>" />
</p>
<!-- Select Platform Field -->
<p>
    <label for="<?php echo $this->get_field_id( 'platform' ); ?>"><?php esc_html_e( 'Platform:' , 'ts_hvrbrd'); ?></label>
    <select name="<?php echo $this->get_field_name('platform'); ?>" id="<?php echo $this->get_field_id('platform'); ?>" class="widefat">
        <option value="0"<?php selected( $platform, '0' ); ?>><?php _e('All'); ?></option>
        <?php 
        	$options=$this->get_platforms(''); 
        	if($this->validarr($options)){
        		foreach($options as $key=>$name){
                    echo '<option value="'.$key.'" '.selected($platform,$key).' >'.$name.'</option>';
                }
        	}
        ?>
    </select>
    
</p> 

<!-- Select Game Release Date -->
<p>
    <label for="<?php echo $this->get_field_id( 'game_release' ); ?>"><?php esc_html_e( 'Game Release Date:' , 'ts_hvrbrd'); ?></label>
    <select name="<?php echo $this->get_field_name('game_release'); ?>" id="<?php echo $this->get_field_id('game_release'); ?>" class="widefat">
        <option value="0"<?php selected( $game_release, '0' ); ?>><?php _e('First Release Date'); ?></option>
        <option value="1"<?php selected( $game_release, '1' ); ?>><?php _e('Latest Release Date'); ?></option>
    </select>
</p> 

<!-- Select Release Day Field -->
<p>
    <label for="<?php echo $this->get_field_id( 'release_day' ); ?>"><?php esc_html_e( 'Release Day:' , 'ts_hvrbrd'); ?></label>
    <select name="<?php echo $this->get_field_name('release_day'); ?>" id="<?php echo $this->get_field_id('release_day'); ?>" class="widefat">
        <option value="0"<?php selected( $release_day, '0' ); ?>><?php _e('All'); ?></option>
        <option value="1"<?php selected( $release_day, '1' ); ?>><?php _e('Current Day'); ?></option>
        <option value="7"<?php selected( $release_day, '7' ); ?>><?php _e('Next 7 days'); ?></option>
        <option value="30"<?php selected( $release_day, '30' ); ?>><?php _e('Next 30 days'); ?></option>
    </select>
</p> 

<!-- No of Games Display -->
<p>
	<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Display Limit:' , 'ts_hvrbrd'); ?></label>
	<input size="4"  id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $limit ); ?>" />
</p>
<script type='text/javascript'>
    jQuery(document).ready(function($) {
        $('.lxt-colorpicker').wpColorPicker();
    });
</script>
<!-- Pick Date Field color -->
<p>
	<label for="<?php echo $this->get_field_id( 'date_color' ); ?>"><?php esc_html_e( 'Pick Date Field color:' , 'ts_hvrbrd'); ?></label>
	<input size="7" class="lxt-colorpicker" id="<?php echo $this->get_field_id( 'date_color' ); ?>" name="<?php echo $this->get_field_name( 'date_color' ); ?>" type="text" value="<?php echo esc_attr( $date_color ); ?>" />
</p>
<!--Hide Platform -->
<p>
    <input class="widefat" id="<?php echo $this->get_field_id( 'hide_platform' ); ?>" name="<?php echo $this->get_field_name( 'hide_platform' ); ?>" type="checkbox" value="1" <?php checked( $hide_platform, 1 ); ?> />
    <label for="<?php echo $this->get_field_id( 'hide_platform' ); ?>"><?php esc_html_e( 'Hide Platform' , 'ts_hvrbrd'); ?></label>
</p>
<!--Hide Rating -->
<p>
    <input class="widefat" id="<?php echo $this->get_field_id( 'hide_rating' ); ?>" name="<?php echo $this->get_field_name( 'hide_rating' ); ?>" type="checkbox" value="1" <?php checked( $hide_rating, 1 ); ?> />
    <label for="<?php echo $this->get_field_id( 'hide_rating' ); ?>"><?php esc_html_e( 'Hide Rating' , 'ts_hvrbrd'); ?></label>
</p>
<!--Popular Games -->
<p>
	<input class="widefat" id="<?php echo $this->get_field_id( 'popular' ); ?>" name="<?php echo $this->get_field_name( 'popular' ); ?>" type="checkbox" value="1" <?php checked( $popular, 1 ); ?> />
	<label for="<?php echo $this->get_field_id( 'popular' ); ?>"><?php esc_html_e( 'Most Popular only' , 'ts_hvrbrd'); ?></label>
</p>
