<?php 		
	echo $before_widget;
	if (! empty( $instance['title'] ) ){	echo $before_title . $title . $after_title; }	
?>
<div class="ts_igdb_out_widget">
	<style>
        .ts_igdb_out_widget li{
            border-bottom: 1px solid #ddd;
			padding: 15px 0px !important;
        }
    	.platform.cat-links,.release_dates .relase_date{
    		background-color: #aa2323;
			color: #ffffff;
			font-size: 10px;
			padding: 3px 10px;
        	display: inline-block;
        	margin: 0px;
        }
        .release_dates { text-align: center; padding:3px 0px;}
        .release_dates .relase_date{background-color: #81d742; width:100%;}
        li.cwp-popular-review {
            max-width: 250px;
        }
        img.game-img{
            width: 100%;
            height: 130px;
            max-width: 100%;
        }
        ul.game-list-view{
            list-style: none;
        }
        span.lxt-gamen {    background: #615e5e;}
        .lxt-game h3 {    color: white;    text-align: center;    width: 100% !important;    text-transform: uppercase;    font-family: Montserrat, "Helvetica Neue", sans-serif;    margin-left: 0px !important;    padding: 70px 20px 0px 20px;}
        span.game-rating {    padding: 15px;    background: #aa2323;    float: right;    font-size: 19px;    color: #FFF;    font-weight: 600;}
        span.game-sno {    background: white; padding: 14px 17px; font-size: 19px; font-weight: 600; float: left;}
        .lxt-game {
            height: 180px;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            max-width: 300px;
            width: 100%;
        }
        /*.gplatform {    display: none;}*/
    </style>
	<?php $this->format_output($api,$filters); ?>
</div>
<?php echo $after_widget; ?>
