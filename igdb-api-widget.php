<?php
/*
 *  Plugin Name: IGDB Games Widget
 *  Plugin URI: www.gaming-masters.co.uk
 *  Description: Widget to display Game's name with release date from IGDB
 *  Author: Gaming Masters
 *  Author URI: www.gaming-masters.co.uk
 *  Version: 3.0.1
 *  License: GPLv2 or later
 */


class TSIGDB_Widget extends WP_Widget {

    function __construct() {
        // Instantiate the parent object
        $widget_ops = array(
            'classname'   => 'ts-igdb-widget',
            'description' => __( 'Widget to display IGDB Games List', 'ts_hvrbrd' )
        );
        parent::__construct( 'ts-igdb-widget', __( 'IGDB Gamelist Widget', 'ts_hvrbrd' ),$widget_ops);
        add_action( 'load-widgets.php', array(&$this, 'lxt_colorpicker') );
    }

    function widget( $args, $instance ) {

        extract( $args );

        $title = apply_filters( 'widget_title' , $instance['title'] );

        if($this->validarg($instance['api'])){
             $api = $instance['api'];
        }else{
            $this->message("Damn! Api not connecting.");
            return;
        }
        if ($this->validarg($instance['date_color'])){
            $date_color=$instance['date_color'];
        }else{
            $date_color="#AA2323";
        }

        $hide_platform=$instance['hide_platform'];
        $hide_rating=$instance['hide_rating'];

        $platform=$instance['platform'];
        $limit=$instance['limit'];
        $release_day=(int)$instance['release_day'];
        $game_release=(int)$instance['game_release'];
        $popular=$instance['popular'];
        
        $filters=array();
        $filters['date_color']=$date_color;
        $filters['hide_platform']=$hide_platform;
        $filters['hide_rating']=$hide_rating;
        $filters['limit']=$limit;
        $filters['game_release']=$game_release;
        if($platform) $filters['platform']=$platform;
        if($release_day){
            $date =date("Y-m-d",time());
            if($release_day===1){
                $filters['release_day']=$date;
            }else if($release_day===7){
                $filters['release_day']=$date;
                $date = strtotime("+7 day", time());
                $filters['end_day']=date("Y-m-d",$date);
            }else if($release_day===30){
                $filters['release_day']=$date;
                $date = strtotime("+30 day", time());
                $filters['end_day']=date("Y-m-d",$date);
            }
        }
       
        if($popular) $filters['popular']=$popular;
        require( 'inc/front-end.php' );

    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['api'] = strip_tags($new_instance['api']);
        $instance['platform']=(int)$new_instance['platform'];
        $instance['limit'] = (int)$new_instance['limit'];
        $instance['release_day'] =strip_tags($new_instance['release_day']);
        $instance['game_release'] =strip_tags($new_instance['game_release']);
        $instance['date_color']=strip_tags($new_instance['date_color']);
        $instance['hide_platform']=(int)strip_tags($new_instance['hide_platform']);
        $instance['hide_rating']=(int)strip_tags($new_instance['hide_rating']);
        $instance['popular']=(int)strip_tags($new_instance['popular']);

        return $instance;
    }

    function form( $instance ) {
        $default_instance=array('title' => 'Game List','api'=>'','platform'=>0,'limit'=>10,'release_day'=>0,'date_color'=>'#fffff','hide_platform'=>0,'popular'=>0);
        $instance = wp_parse_args( (array) $instance,$default_instance);
        
        $title = esc_attr($instance['title']);
        $api = esc_attr($instance['api']);
        $platform=esc_attr($instance['platform']);
        $limit=esc_attr($instance['limit']);
        $release_day=esc_attr($instance['release_day']);
        $game_release=esc_attr($instance['game_release']);
        $date_color=esc_attr($instance['date_color']);
        $hide_platform=esc_attr($instance['hide_platform']);
        $hide_rating=esc_attr($instance['hide_rating']);
        $popular=esc_attr($instance['popular']);

        require( 'inc/widget-fields.php' );

    }

    //Helpful Functions

    //Function to make IGDB Database Api call
    protected function get_api_result($url,$api){
        require_once 'unirest.php';

        $response = Unirest\Request::get($url,
            array(
                "X-Mashape-Key" => $api,
                "Accept" => "application/json"
            )
        );
        if($response->code!=200) return '';
        return $response->raw_body;
    }

    //Function to get Get Games Platform List
    public function get_platforms($api){
        if ( false === ( $platforms = get_transient( 'ts_platforms_list' ) ) ) {
            if(empty( $api )) return '';
            $platforms=array();
            $limit=40;
            $offset=40;
            $response=$this->get_api_result("https://igdbcom-internet-game-database-v1.p.mashape.com/platforms/?fields=name&limit=40",$api);
            if(empty( $response ) ){ return ''; }
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

    public function ts_get_igdb_data($api,$url="https://igdbcom-internet-game-database-v1.p.mashape.com/games/?fields=name,summary,popularity,rating,first_release_date,cover,release_dates.platform,release_dates.date"){

        $response=$this->get_api_result($url,$api);
        if(!$this->validarr($response)){
            return '';
        }else{
            $response=json_decode($response);
            $platforms=($this->get_platforms($api));
            $games=array();
            $x=0;
            foreach($response as $game){
                
                $temp=array();
                $temp['name']=$game->name;
                if($this->validarg($game->rating)){
                    $rating=(float)$game->rating;
                    $rating/=10;
                    $temp['rating']=number_format($rating, 1, '.', '');
                }else{
                    $temp['rating']='N/A';
                }
                if(isset($game->cover)){
                    $temp['image']='https://res.cloudinary.com/igdb/image/upload/t_logo_med/'.$game->cover->cloudinary_id.'.jpg';
                    $temp['hdimage']='https://res.cloudinary.com/igdb/image/upload/'.$game->cover->cloudinary_id.'.jpg';
                }else{
                    $temp['image']='https://res.cloudinary.com/igdb/image/upload/t_logo_med/nocover_qhhlj6.jpg';
                    $temp['hdimage']='https://res.cloudinary.com/igdb/image/upload/nocover_qhhlj6.jpg';
                }
                $temp['first_release_date']=date('d-F-Y',substr($game->first_release_date,0,-3));
              
                    $games_dates=array();
                    $gn=0;
                   
                    foreach($game->release_dates as $release){
                        $games_dates[$gn]=Array(
                            'platform' => $release->platform,
                            'release' => $release->date
                        );
                        ++$gn;
                    }

                    $games_dates=$this->array_sort($games_dates,'platform',SORT_ASC);
                   
                    $games_uni=$this->unique_multidim_array($games_dates,'platform');
                    

                    $first_release=0;
                    $latest_release=0;
                    $platform=0;
                  
                    foreach($games_uni as $game_uni){
                        $platform=$game_uni['platform'];
                        $first_release=$game_uni['release'];
                        $latest_release=$game_uni['release'];
                         foreach($games_dates as $games_date){
                          if($games_date['platform']==$platform):
                           
                            if($games_date['release']<$first_release){
                              $first_release=$games_date['release'];
                            }
                            if($games_date['release']>$latest_release){
                              $latest_release=$games_date['release'];
                            }
                          endif;
                        }
                        $temp['release'][$platform]=array(
                            'platform'=> $platforms[$platform],
                            'first_release' => $first_release,
                            'latest_release' => $latest_release 
                        );
                               
                    }

                $games[$x++]=$temp;
            }

            return $games;
        }

    }

    public function format_output($api,$filters=array()){
            
            $url="https://igdbcom-internet-game-database-v1.p.mashape.com/games/?fields=name,summary,popularity,rating,first_release_date,cover,release_dates.platform,release_dates.date";
            
            $filter="";
            
            $limit=(int)$filters['limit'];
            if($limit>0 && $limit<11){
                $filter="&limit=".$limit;
            }else{
              $filter="&limit=10";
            }
            if(isset($filters['platform'])){
                $filter .="&filter[release_dates.platform][eq]=".$filters['platform'];
            }
            
            if(isset($filters['release_day'])){
            	if(isset($filters['end_day'])){
            		$filter.="&filter[release_dates.date][gte]=".$filters['release_day'];
                    $filter.="&filter[release_dates.date][lte]=".$filters['end_day'];
                }else{
                	$filter.="&filter[release_dates.date][eq]=".$filters['release_day'];
                }
            }
            
            if(isset($filters['popular'])){
                $filter .="&order=popularity:desc";
            }else{
                $filter .="&order=release_dates.date:desc";
            }
            

            $url=$url.$filter;
            

            $games=$this->ts_get_igdb_data($api,$url);
            if(!$this->validarr($games)){
              

                $this->message("Damn, no upcoming releases have been found. Sorry!!");
                return '';
            }
            $gm=0;
            

            echo '<ul class="game-list-view">';
            $gm=0;
            foreach($games as $game){
                $sno='<span class="game-sno" style="background-color:'.$filters['date_color'].'!important;">'.++$gm.'</span>';
                $rating='<span class="game-rating" style="background-color:'.$filters['date_color'].'!important;">'.$game['rating'].'</span>';
                //$img = '<img src="'.$game['hdimage'].'" class="game-img">';
                $name='<h3 style="display: inline-block; margin-left: 8px; width: 80%;"><span class="lxt-gamen">'.$game['name'].'<span></h3>';
                if($filters['hide_rating']){
                    $gamediv='<div class="lxt-game" style="background:url('.$game['hdimage'].')">'.$sno.$name.'</div>';
                 }else{
                    $gamediv='<div class="lxt-game" style="background:url('.$game['hdimage'].')">'.$sno.$rating.$name.'</div>';
                }

                $release='';
                $platforms='';
                if($filters['platform']>0){
                    $platform_id=$filters['platform'];
                    if($filters['game_release']){
                        $release=$game['release'][$platform_id]['latest_release'];
                        $release=date('d-F-Y',substr($release,0,-3));
                    }else{
                        $release=$game['release'][$platform_id]['first_release'];
                        $release=date('d-F-Y',substr($release,0,-3));
                    }
                }else{
                    $release=$game['first_release_date'];
                }
                $releases ='<div class="release_dates"><span class="relase_date" style="background-color:'.$filters['date_color'].'!important;"> '.$release.'</span></div>';
                foreach($game['release'] as $release){
                    if($this->validarg($release['platform']))
                    $platforms .='<span class="platform cat-links" style="background-color:'.$filters['date_color'].'!important;">'.$release['platform'].'</span>';
                }
                
                if($filters['hide_platform']){
                    echo '<li class="nplt cwp-popular-review">'.$gamediv.$releases.'</li>';
                }else{
                    echo '<li class="cwp-popular-review">'.$gamediv.$releases.'<div class="gplatform">'.$platforms.'</div></li>';
                }

            }
            echo '</ul>';
            
    }

    public function array_sort($array, $on, $order=SORT_ASC){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    public function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    } 

    public function message($error){
        echo "<strong style='color: red;padding:2px;'><small>{$error}</small></strong>";
    }
    
    public function validarg($elem){
        if (strlen(trim(strip_tags($elem))) == 0) return false;
        else return true;
    }

    public function validarr($elem){
        if ( is_array($elem) && (count($elem) >0) ) return true;
        if(is_string($elem)){
            $elem=str_replace('[','',$elem);
            $elem=str_replace(']','',$elem);
            if(strlen(trim(strip_tags($elem))) == 0) return false;
            else return true; 
        }
        return false;
    }

    public function lxt_colorpicker() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }
}

function wptreehouse_badges_register_widgets() {
    register_widget( 'TSIGDB_Widget' );
}

add_action( 'widgets_init', 'wptreehouse_badges_register_widgets' );
