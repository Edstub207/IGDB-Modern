<?php
/*
 Plugin Name: IDGB Games Widget
 Plugin URI: www.gaming-masters.co.uk
 Description: Widget to display Game name with release date from IGDB
 Author: Gaming Masters
 Author URI: www.gaming-masters.co.uk
 Version: 1.1.1
 License: GPLv2 or later
 */
/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */

class TSIGDBWidget extends WP_Widget {
	
	public function __construct()
	{
		$widget_ops = array('classname' => 'ts_igdb_widget', 'description' => __('Widget to display IGDB Games List','ts_hvrbrd'));

		$control_ops = array('id_base' => 'ts-igdb-widget');
        parent::__construct( 'ts-igdb-widget', __('IGDB Widget','ts_hvrbrd'), $widget_ops, $control_ops);
        
	}
	
	public function widget($args, $instance)
	{
		

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Games List' : $instance['title'], $instance, $this->id_base );

		$api = ! empty( $instance['api'] ) ? $instance['api'] : '';
		$platform = ! empty( $instance['platform'] ) ? $instance['platform'] : '';
		$show_platform = ! empty( $instance['show_platform'] ) ? $instance['show_platform'] : 'false';
		$current_day = ! empty( $instance['current_day'] ) ? $instance['current_day'] : true;
		$limit = ! empty( $instance['limit'] ) ? $instance['limit'] : 10;
		$popular = ! empty( $instance['popular'] ) ? $instance['popular'] :  true;
        
		$data=array();
        if(''!=$platform){
            $data['platform']=$platform;
        }
        
        $data['show_platform']=$show_platform;
        
        
        if($current_day){
            $data['current_day']=date('Y-m-d');
        }
        if($limit){
            $data['limit']=$limit;
        }

	if($popular){
		$data['popular']=$popular;
	}
        
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
<div class="ts_igdb_out_widget">
    <style>
        .ts_igdb_out_widget li{
            border-bottom: 1px solid #ddd;
padding: 15px 0px !important;
        }
    .platform.cat-links,.release_dates .relase_date{background-color: #aa2323;
border-radius: 3px;
color: #ffffff;
font-size: 10px;
padding: 3px 10px;
        display: inline-block;margin-right: 2px;margin-bottom: 2px;}
        .release_dates{text-align: center;}
        .release_dates .relase_date{background-color: #81d742;}
    </style>
    <?php echo $this->format_output($api,$data) ?></div>
		<?php
		echo $args['after_widget'];
		
	}
	
	public function update($new_instance, $old_instance)
	{ 
        $instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['api'] = ( $new_instance['api'] );
		$instance['platform'] = ( $new_instance['platform'] );
		$instance['show_platform'] = ( $new_instance['show_platform'] );
		$instance['current_day'] = ( $new_instance['current_day'] );
		$instance['limit'] = ( $new_instance['limit'] );
		$instance['popular'] = ( $new_instance['popular'] );
        
		
		
		return $instance;
		
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','api'=>'','show_platform'=>'yes','platform'=>'','current_day'=>false,'popular'=>false,'limit'=>10 ) );
		$title = sanitize_text_field( $instance['title'] );
		$api = $instance['api'] ;
		$platform = $instance['platform'] ;
		$show_platform = $instance['show_platform'] ;
		$current_day = $instance['current_day'] ;
		$limit = $instance['limit'] ;
		$popular = $popular['popular'] ;
        
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('api'); ?>"><?php _e('IGDB.com API Key:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('api'); ?>" name="<?php echo $this->get_field_name('api'); ?>" type="text" value="<?php echo esc_attr($api); ?>" /></p>
        <p>





<label for="<?php echo $this->get_field_id('platform'); ?>"><?php _e('Select Individual Platform:'); ?></label>
            <select name="<?php echo $this->get_field_name('platform'); ?>">
                <option value="">--</option>
                <?php $options=$this->get_platforms('');?>
                <?php if(''!=$options):?>
                <?php 
                    foreach($options as $key=>$name){
                        echo '<option value="'.$key.'" '.selected($platform,$key).' >'.$name.'</option>';
                    }
                ?>
                <?php endif;?>
            </select>
        </p>


        <p><label for="<?php echo $this->get_field_id('current_day'); ?>"><?php _e('Current Day Only:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('current_day'); ?>" name="<?php echo $this->get_field_name('current_day'); ?>" type="checkbox" value="true" <?php checked($current_day,"true")?>/></p>


        <p><label for="<?php echo $this->get_field_id('show_platform'); ?>"><?php _e('Hide Platforms:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_platform'); ?>" name="<?php echo $this->get_field_name('show_platform'); ?>" type="checkbox" value="no" <?php checked($show_platform,"no")?>/></p>
        

<p><label for="<?php echo $this->get_field_id('popular'); ?>"><?php _e('Most Popular only:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('popular'); ?>" name="<?php echo $this->get_field_name('popular'); ?>" type="checkbox" value="true" <?php checked($popular,"true")?>/></p>








           
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit results:'); ?></label>
            <select name="<?php echo $this->get_field_name('limit'); ?>">
                <?php 
                    for($i=1;$i<11;$i++){
                        echo '<option value="'.$i.'" '.selected($limit,$i).' >'.$i.'</option>';
                    }
                ?>
            </select>
        </p>
		<?php
	}
    protected function get_api_result($url,$api){
        require_once 'Unirest.php';
        $response=Unirest\Request::get($url,
          array(
            "X-Mashape-Key" => $api
          )
        );
        if($response->code!=200) return '';
        return $response->raw_body;
    }
    public function ts_get_igdb_data($api,$url="https://igdbcom-internet-game-database-v1.p.mashape.com/games/?fields=name,release_dates.platform,release_dates.date,cover&order=release_dates.date:asc"){
        
        if(''==$api) return 'API key is required.';
        $platforms=($this->get_platforms($api));
        $response=$this->get_api_result($url,$api);
        $response=json_decode($response);
        $games=array();
        
        foreach($response as $game){
            $temp=array();
            $temp['name']=$game->name;
            foreach($game->release_dates as $release){
                $temp['release'][]=array('platform'=>$platforms[$release->platform],'month'=>date('F',substr($release->date,0,-3)),'year'=>date('Y',substr($release->date,0,-3)),'day'=>date('d',substr($release->date,0,-3)),'format_date'=>date('dS F, Y',substr($release->date,0,-3)),'date'=>date('d-F-Y',substr($release->date,0,-3)));
            }
            if(isset($game->cover)){
                $temp['image']='https://images.igdb.com/igdb/image/upload/t_logo_med/'.$game->cover->cloudinary_id.'.jpg';
            }else{
                $temp['image']='https://images.igdb.com/igdb/image/upload/t_logo_med/nocover_qhhlj6.jpg';
            }
            $games[]=$temp;
        }
        
        
        return ($games);
    }
    
    
    public function get_platforms($api){
        if ( false === ( $platforms = get_transient( 'ts_platforms_list' ) ) ) {
            if(''==$api) return '';
            $platforms=array();
            
            $limit=40;
            $offset=40;
            $response=$this->get_api_result("https://igdbcom-internet-game-database-v1.p.mashape.com/platforms/?fields=name&limit=40",$api);
            $response=json_decode($response);
            
            $loop=floor(($response[0]->id)/40);
            
            for($i=1;$i<$loop;$i++){
                $offset=$offset * $i;
                $temp=$this->get_api_result("https://igdbcom-internet-game-database-v1.p.mashape.com/platforms/?fields=name&limit=40&offset=".$offset,$api);
                $temp=json_decode($temp);
                $response=array_merge($response,$temp);
            }
            
            foreach($response as $res){
                $platforms[$res->id]=$res->name;
            }
            
            set_transient( 'ts_platforms_list', $platforms, 1 * WEEK_IN_SECONDS );
        }
        return $platforms;
    }
    
    public function format_output($api,$filters=array()){
        $url="https://igdbcom-internet-game-database-v1.p.mashape.com/games/?fields=name,release_dates.platform,release_dates.date,cover&order=release_dates.date:desc";
        $filter='';
        if(!empty($filters)){
            if(isset($filters['platform'])){
                $filter .='&filter[release_dates.platform][eq]='.$filters['platform'];
            }
            if(isset($filters['current_day'])){
                $filter .='&filter[release_dates.date][eq]='.$filters['current_day'];
            }
 	    if(isset($filters['popular'])){
                $filter .='&filter[name.popularity][eq]='.$filters['popular'];
            }
            if(isset($filters['limit'])){
                $filter .='&limit='.$filters['limit'];
            }

            
        }
        if(''!=$filter){
            $url .=$filter;
        }
        $data=$this->ts_get_igdb_data($api,$url);
        $out='<ul>';
        
        foreach($data as $game){
            
            $img = '<img src="'.$game['image'].'" style="max-width:50px;max-height:100px;float:left;margin-bottom:0;">';
            $name='<h5 style="display: inline-block; margin-left: 8px; width: 80%;">'.$game['name'].'</h5>';
            $releases='';
            $platforms='';
/* The date below has been modified so that the date isn't shown while fixing a few issues. Original date was 1 September 2016 */
            $prev='1-September-2018';
            $day='';
            foreach($game['release'] as $release){
                if(strtotime($release['date'])>strtotime($prev)){
                    $releases ='<div class="release_dates"><span class="relase_date"> '.$day=$release['day'].' '.$release['month'].'  '.$release['year'].'</span></div>';
                }
                $platforms .='<span class="platform cat-links">'.$release['platform'].'</span>';
            }
 
/* This is the chart code ready to be fixed in a future update;

  $new='<div class="cwp-review-chart absolute" style="top: 40%;">
				<div class="cwp-review-percentage cwp_easyPieChart" data-percent="90" style="width: 40px; height: 40px; line-height: 40px;"><span style="color: rgb(141, 193, 83);border: 6px solid;">'.$day.'</span></div></div>';
	
*/
            if('yes'==$filters['show_platform']){
                $out .='<li class="cwp-popular-review">'.$releases.$img.$name.$new.'<div class="">'.$platforms.'</div></li>';
            }else{
                $out .='<li class="cwp-popular-review">'.$releases.$img.$name.$new.'</li>';
            }
        }
       
        $out .='</ul>';
        return $out;
    }
}

add_action( 'widgets_init', 'ts_widgets_init' );
function ts_widgets_init(){
    register_widget( 'TSIGDBWidget' );
    
}
