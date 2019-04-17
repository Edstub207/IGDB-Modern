<?php
/*
 *  Plugin Name: IGDB Games Widget
 *  Plugin URI: http://edstub.co.uk/
 *  Description: Widget to display Game's name with release date from IGDB
 *  Author: Eddie Stubbington
 *  Author URI: http://edstub.co.uk/
 *  Version: 2.5.2.1
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
	// If the API Key is invalid return with this message
        if($this->validarg($instance['api'])){
             $api = $instance['api'];
        }else{
            $this->message("No results found. Please enter an IGDB API Key");
            return;
        }

		//If a colour isn't picked by the user use this default colour

        if ($this->validarg($instance['date_color'])){
            $date_color=$instance['date_color'];
        }else{
            $date_color="#AA2323";
        }
	// Create the variables/instances for different options on the wordpress backend
        $hide_platform=$instance['hide_platform'];
        $hide_rating=$instance['hide_rating'];
        $hide_counter=$instance['hide_counter'];
        $hide_release=$instance['hide_release'];
		$hide_cover=$instance['hide_cover'];



		$franchises=$instance['franchises'];
        $companies=$instance['companies'];
        $genres=$instance['genres'];
        $platform=$instance['platform'];
        $limit=$instance['limit'];
        $release_day=(int)$instance['release_day'];
        $game_release=(int)$instance['game_release'];
        $popular=$instance['popular'];
        
        $filters=array();
        $filters['date_color']=$date_color;
        $filters['hide_platform']=$hide_platform;
        $filters['hide_counter']=$hide_counter;
        $filters['hide_release']=$hide_release;
		$filters['hide_cover']=$hide_cover;



        $filters['hide_rating']=$hide_rating;
        $filters['limit']=$limit;
        $filters['game_release']=$game_release;
        if($platform) $filters['platform']=$platform;
		if($franchises) $filters['franchises']=$franchises;
        if($companies) $filters['companies']=$companies;
        if($genres) $filters['genres']=$genres;
        if($release_day){
          //$date =date("d-F-Y",time() * 1000);
		  if($release_day===1){
                $filters['release_day']=strtotime("today", time());
				$filters['end_day']=strtotime("+1 day", time());
            }else if($release_day===7){
                $filters['release_day']=strtotime("today", time());
                $filters['end_day']=strtotime("+7 day", time());
			}else if($release_day===14){
                $filters['release_day']=strtotime("today", time());
                $filters['end_day']=strtotime("+14 day", time());
			}else if($release_day===21){
                $filters['release_day']=strtotime("today", time());
                $filters['end_day']=strtotime("+21 day", time());
			}else if($release_day===30){
                $filters['release_day']=strtotime("today", time());
                $filters['end_day']=strtotime("+30 day", time());
            }else if($release_day===365){
                $filters['release_day']=strtotime("today", time());
                $filters['end_day']=strtotime("+365 day", time());
            }
        }
        
        if($popular) {
			$filters['popular']=$popular;
			if($game_release===0) {
				$filters['popular_asc']=$popular;
			} else {
				$filters['popular_desc']=$popular;
			}
		} else if($game_release===1) {
			$filters['release_desc']=$game_release;
		}	
		
		
        require( 'inc/front-end.php' );

    }
    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['api'] = strip_tags($new_instance['api']);
        $instance['platform']=(int)$new_instance['platform'];
		$instance['franchises']=(int)$new_instance['franchises'];
        $instance['companies']=(int)$new_instance['companies'];
        $instance['genres']=(int)$new_instance['genres'];
        $instance['limit'] = (int)$new_instance['limit'];
        $instance['release_day'] =strip_tags($new_instance['release_day']);
        $instance['game_release'] =strip_tags($new_instance['game_release']);
        $instance['date_color']=strip_tags($new_instance['date_color']);
        $instance['hide_platform']=(int)strip_tags($new_instance['hide_platform']);
        $instance['hide_rating']=(int)strip_tags($new_instance['hide_rating']);
        $instance['popular']=(int)strip_tags($new_instance['popular']);
        $instance['hide_counter']=(int)strip_tags($new_instance['hide_counter']);
        $instance['hide_release']=(int)strip_tags($new_instance['hide_release']);
		$instance['hide_cover']=(int)strip_tags($new_instance['hide_cover']);



        return $instance;
    }

	//Default Options for the widgets settings
    function form( $instance ) {
        $default_instance=array('title' => 'Game List','api'=>'','platform'=>0,'limit'=>10,'franchises'=>0,'companies'=>0,'genres'=>0,'release_day'=>0,'date_color'=>'#fffff','hide_platform'=>0,'popular'=>0,'hide_counter'=>0,'hide_release'=>0,'hide_cover'=>0);
        $instance = wp_parse_args( (array) $instance,$default_instance);
        
        $title = esc_attr($instance['title']);
        $api = esc_attr($instance['api']);
        $platform=esc_attr($instance['platform']);
		$franchises=esc_attr($instance['franchises']);
        $genres=esc_attr($instance['genres']);
        $companies=esc_attr($instance['companies']);
        $limit=esc_attr($instance['limit']);
        $release_day=esc_attr($instance['release_day']);
        $game_release=esc_attr($instance['game_release']);
        $date_color=esc_attr($instance['date_color']);
        $hide_platform=esc_attr($instance['hide_platform']);
        $hide_rating=esc_attr($instance['hide_rating']);
        $popular=esc_attr($instance['popular']);
        $hide_counter=esc_attr($instance['hide_counter']);
        $hide_release=esc_attr($instance['hide_release']);
		$hide_cover=esc_attr($instance['hide_cover']);




        require( 'inc/widget-fields.php' );

    }

    //Helpful Functions

    //Function to make IGDB Database Api call
    protected function get_api_result($url,$api){
        require_once 'Unirest.php';

        $response = Unirest\Request::get($url,
            array(
                "user-key" => $api,
                "Accept" => "application/json"
            )
        );
        if($response->code!=200) return '';
        return $response->raw_body;
    }

    //Function to get Get Games Platform List
		public function get_platforms($api){
        if ( false === ( $platforms = get_transient( 'ts_platforms_list' ) ) ) {
            if(empty( $api )) return 'error';
            $platforms=array();
            $response=$this->get_api_result("https://api-v3.igdb.com/platforms/?fields=name&limit=50",$api);
            if(empty( $response ) ){ return ''; }
            $response=json_decode($response);
            $loop=3;
            
            for($i=0;$i<$loop;$i++){
                $num=60;
                $offset=$num * $i;
                $temp=$this->get_api_result("https://api-v3.igdb.com/platforms/?fields=name&limit=50&offset=".$offset,$api);
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

	   //Function to get Get Games franchises List

		public function get_franchises($api){
        if ( false === ( $franchises = get_transient( 'ts_franchises_list' ) ) ) {
            if(empty( $api )) return 'error';
            $franchises=array();
            $response=$this->get_api_result("https://api-v3.igdb.com/franchises/?fields=name&limit=50",$api);
            if(empty( $response ) ){ return ''; }
            $response=json_decode($response);
            $loop=3;
            
            for($i=0;$i<$loop;$i++){
                $num=500;
                $offset=$num * $i;
                $temp=$this->get_api_result("https://api-v3.igdb.com/franchises/?fields=name&limit=50&offset=".$offset,$api);
                $temp=json_decode($temp);
                $response=array_merge($response,$temp);
            }
            
            
            foreach($response as $res){
                $franchises[$res->id]=$res->name;
            }
            
            set_transient( 'ts_franchises_list', $franchises, 1 * WEEK_IN_SECONDS );
        }
        return $franchises;
    }


//Function to get Get Games companies List
		public function get_companies($api){
        if ( false === ( $companies = get_transient( 'ts_companies_list' ) ) ) {
            if(empty( $api )) return 'error';
            $companies=array();
            $response=$this->get_api_result("https://api-v3.igdb.com/companies/?fields=name&limit=50",$api);
            if(empty( $response ) ){ return ''; }
            $response=json_decode($response);
            $loop=3;
            
            for($i=0;$i<$loop;$i++){
                $num=500;
                $offset=$num * $i;
                $temp=$this->get_api_result("https://api-v3.igdb.com/companies/?fields=name&limit=50&offset=".$offset,$api);
                $temp=json_decode($temp);
                $response=array_merge($response,$temp);
            }
            
            
            foreach($response as $res){
                $companies[$res->id]=$res->name;
            }
            
            set_transient( 'ts_companies_list', $companies, 1 * WEEK_IN_SECONDS );
        }
        return $companies;
    }

//Function to get Get genres List
		public function get_genres($api){
        if ( false === ( $genres = get_transient( 'ts_genres_list' ) ) ) {
            if(empty( $api )) return 'error';
            $genres=array();
            $response=$this->get_api_result("https://api-v3.igdb.com/genres/?fields=name&limit=50",$api);
            if(empty( $response ) ){ return ''; }
            $response=json_decode($response);
            $loop=3;
            
            for($i=0;$i<$loop;$i++){
                $num=60;
                $offset=$num * $i;
                $temp=$this->get_api_result("https://api-v3.igdb.com/genres/?fields=name&limit=50&offset=".$offset,$api);
                $temp=json_decode($temp);
                $response=array_merge($response,$temp);
            }
            
            
            foreach($response as $res){
                $genres[$res->id]=$res->name;
            }
            
            set_transient( 'ts_genres_list', $genres, 1 * WEEK_IN_SECONDS );
        }
        return $genres;
    }

    public function ts_get_igdb_data($api,$url="https://api-v3.igdb.com/games/?fields=name,summary,franchises,genres,popularity,rating,involved_companies.company.*,first_release_date,cover.*,release_dates.platform,release_dates.date&filter[first_release_date][exists]=1"){

        $response=$this->get_api_result($url,$api);
        if(!$this->validarr($response)){
            return '';
        }else{
            $response=json_decode($response);
            $platforms=($this->get_platforms($api));
			$franchises=($this->get_franchises($api));
            $genres=($this->get_genres($api));
			$companies=($this->get_companies($api));
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
                    $temp['image']='https://images.igdb.com/igdb/image/upload/t_logo_med/'.$game->cover->image_id.'.jpg';
                    $temp['hdimage']='https://images.igdb.com/igdb/image/upload/t_logo_med_2x/'.$game->cover->image_id.'.jpg'; 
                }else{
                    $temp['image']='https://images.igdb.com/igdb/image/upload/t_logo_med/nocover_qhhlj6.jpg';
                    $temp['hdimage']='https://images.igdb.com/igdb/image/upload/t_logo_med_2x/nocover_qhhlj6.jpg';
                }

				$temp['first_release_date']=date('d-F-Y',$game->first_release_date);            
                    $games_dates=array();
                    $gn=0;
                   
                    foreach($game->release_dates as $release){
                        $games_dates[$gn]=Array(
                            'platform' => $release->platform,
							'genres' => $release->genres,
							'companies' => $release->companies,
							'franchises' => $release->franchises,
                            'release' => $release->date
                        );
                        ++$gn;
                    }

                    $games_dates=$this->array_sort($games_dates,'platform',SORT_ASC);
                   
                    $games_uni=$this->unique_multidim_array($games_dates,'platform');
                    

                    $first_release=0;
                    $latest_release=0;
                    $platform=0;
				    $franchises=0;
					$genres=0;
				    $companies=0;




                  
                    foreach($games_uni as $game_uni){
                        $platform=$game_uni['platform'];
						$franchises=$game_uni['franchises'];
						$companies=$game_uni['companies'];
						$genres=$game_uni['genres'];
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
            
            $url="https://api-v3.igdb.com/games/?fields=name,summary,genres.*,popularity,rating,involved_companies.company.*,franchises.name,first_release_date,cover.*,release_dates.platform,release_dates.date&filter[first_release_date][exists]=1";
            
            $filter="";
            
            $limit=(int)$filters['limit'];
            if($limit>0 && $limit<51){
                $filter="&limit=".$limit;
            }else{
              $filter="&limit=51";
            }
            if(isset($filters['platform'])){
                $filter .="&filter[release_dates.platform][eq]=".$filters['platform'];
            }
            if(isset($filters['companies'])){
                $filter .="&filter[involved_companies.company][eq]=".$filters['companies'];
            }
             if(isset($filters['franchises'])){
                $filter .="&filter[franchises][eq]=".$filters['franchises'];
            } 
            if(isset($filters['genres'])){
				$filter .="&filter[genres][eq]=".$filters['genres'];
				}
			if(isset($filters['release_day'])){
                if(isset($filters['end_day'])){
                    $filter.="&filter[first_release_date][gte]=".$filters['release_day'];
                    $filter.="&filter[first_release_date][lte]=".$filters['end_day'];
                }else{
                	$filter.="&filter[first_release_date][eq]=".$filters['release_day'];
                }
            }
            
            if(isset($filters['popular'])){
				if(isset($filters['popular_asc'])) {
                $filter .="&order=popularity:asc";
				}
				if(isset($filters['popular_desc'])) {
					$filter.="&order=popularity:desc";
				}
            }else if(isset($filters['release_desc'])){
                $filter .="&order=first_release_date: desc";
            } else {
				$filter .="&order=first_release_date:asc";
			}
            

            $url=$url.$filter;
            

            $games=$this->ts_get_igdb_data($api,$url);
            if(!$this->validarr($games)){
              

                $this->message("Damn, no upcoming game releases have been found. Sorry!!");
                return '';
            }
            $gm=0;
            

            echo '<ul class="game-list-view">';
            $gm=0;
            foreach($games as $game){
            
               if($filters['hide_counter']){
               
                }else{
            $sno='<span class="game-sno" style="background-color:'.$filters['date_color'].'!important;">'.++$gm.'</span>';
                }
                $rating='<span class="game-rating" style="background-color:'.$filters['date_color'].'!important;">'.$game['rating'].'</span>';
                //$img = '<img src="'.$game['hdimage'].'" class="game-img">';
                $name='<h3 style="display: inline-block; margin-left: 8px; width: 80%;"><span class="lxt-gamen">'.$game['name'].'<span></h3>';




                if($filters['hide_rating']){
					if($filters['hide_cover']) {
					$gamediv='<div class="lxt-game">'.$sno.$name.'</div>';
					} else {
                    $gamediv='<div class="lxt-game" style="background:url('.$game['hdimage'].')>'.$sno.$name.'</div>';
					}
                 }else if ($filters['hide_cover']){
					$gamediv='<div class="lxt-game">'.$sno.$rating.$name.'</div>';
				} else {
                    $gamediv='<div class="lxt-game" style="background:url('.$game['hdimage'].')">'.$sno.$rating.$name.'</div>';
                }

                $release='';
                $platforms='';

                if($filters['platform']>0){
                    $platform_id=$filters['platform'];
                    if($filters['game_release']){
                        $release=$game['release'][$platform_id]['latest_release'];
                        $release=date('d-F-Y', $release);
                    }else{
                        $release=$game['release'][$platform_id]['first_release'];
                        $release=date('d-F-Y', $release);
                    }
                }else{
                    $release=$game['first_release_date'];
                }
                
				$franchises='';

                if($filters['franchises']>0){
                    $franchises_id=$filters['franchises'];
                    if($filters['game_release']){
                        $release=$game['release'][$franchises_id]['latest_release'];
                        $release=date('d-F-Y', $release);
                    }else{
                        $release=$game['release'][$franchises_id]['first_release'];
                        $release=date('d-F-Y', $release);
                    }
                }else{
                    $release=$game['first_release_date'];
                }

					$companies='';

                if($filters['companies']>0){
                    $companies_id=$filters['companies'];
                    if($filters['game_release']){
                        $release=$game['release'][$companies_id]['latest_release'];
                        $release=date('d-F-Y', $release);
                    }else{
                        $release=$game['release'][$companies_id]['first_release'];
                        $release=date('d-F-Y', $release);
                    }
                }else{
                    $release=$game['first_release_date'];
                }
					$genres='';

				if($filters['genres']>0){
                    $companies_id=$filters['genres'];
                    if($filters['game_release']){
                        $release=$game['release'][$genres_id]['latest_release'];
                        $release=date('d-F-Y', $release);
                    }else{
                        $release=$game['release'][$genres_id]['first_release'];
                        $release=date('d-F-Y', $release);
                    }
                }else{
                    $release=$game['first_release_date'];
                }

				if($filters['hide_release']){

						$releases ='<div class="release_dates"></div>';
						foreach($game['release'] as $release){
                    	if($this->validarg($release['platform']))
                    	$platforms .='<span class="platform cat-links" style="background-color:'.$filters['date_color'].'!important;">'.$release['platform'].'</span>';
                }

  					 }else{

						$releases ='<div class="release_dates"><span class="relase_date" style="background-color:'.$filters['date_color'].'!important;"> '.$release.'</span></div>';
						foreach($game['release'] as $release){
                    	if($this->validarg($release['platform']))
                    	$platforms .='<span class="platform cat-links" style="background-color:'.$filters['date_color'].'!important;">'.$release['platform'].'</span>';
                }
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
