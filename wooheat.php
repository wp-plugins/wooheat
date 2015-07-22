<?php 

/*
Plugin Name: wooHeat! Chilli Heat Rating & Product Sorting
Plugin URI: http://uiux.me
Description: Woocommerce Plugin for adding Heat Ratings to products allowing items to be sorted by their heat value.
Version: 1.1
Author: Ben Parry
Author URI: http://uiux.me
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	add_action( 'woocommerce_product_options_general_product_data', 'wc_woo_heat_field' );
	function wc_woo_heat_field() {

		$heat_ratings = get_option('woo_heat_scale_limit', false);

		if(false && $heat_ratings != false && is_numeric($heat_ratings)) {

				//version 2 - dynamic range
				$options = array();
				for ($i=0; $i < $heat_ratings; $i++) { 
					$options[$i+1] = __( $i+1, 'woocommerce' );
				}

		} else {

			$options = array(
				'1'   	=> __( '1', 'woocommerce' ),
				'2'   	=> __( '2', 'woocommerce' ),
				'3'		=> __( '3', 'woocommerce' ),
				'4'		=> __( '4', 'woocommerce' ),
				'5'		=> __( '5', 'woocommerce' ),
				'6'		=> __( '6', 'woocommerce' ),
				'7'		=> __( '7', 'woocommerce' ),
				'8'		=> __( '8', 'woocommerce' ),
				'9'		=> __( '9', 'woocommerce' ),
				'10'	=> __( '10', 'woocommerce' ),
				'11'	=> __( '11', 'woocommerce' ),
				'12'	=> __( '12', 'woocommerce' ),
				'13'	=> __( '13', 'woocommerce' ),
				'14'	=> __( '14', 'woocommerce' ),
				'15'	=> __( '15', 'woocommerce' )
			);

		}


		woocommerce_wp_select( 
			array( 
				'id'          => 'woo_heat', 
				'label'       => __( 'Heat Rating', 'woocommerce' ), 
				'description' => __( 'Choose a Rating.', 'woocommerce' ),
				'options' => $options
				)
			);
	}

	add_action( 'save_post', 'woo_heat_save_product' );
	function woo_heat_save_product( $product_id ) {
	    // If this is a auto save do nothing, we only save when update button is clicked
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( isset( $_POST['woo_heat'] ) ) {
			if ( is_numeric( $_POST['woo_heat'] ) )
				update_post_meta( $product_id, 'woo_heat', $_POST['woo_heat'] );
		} else delete_post_meta( $product_id, 'woo_heat' );
	}

	add_action( 'woocommerce_single_product_summary', 'woo_heat_show', 5 );
	function woo_heat_show() {
	    global $product;
		// Do not show this on variable products
		if ( $product->product_type <> 'variable' ) {
			$woo_heat_display = get_post_meta( $product->id, 'woo_heat', true );

			if($woo_heat_display > 10) {
				//only show a maximum of 10 heat points on chart
				$heat_rating = 10;
			} else {
				$heat_rating = $woo_heat_display;
			}

			$heat_rating = $heat_rating*10-10;

			if($woo_heat_display > 10) {
				echo '<p class="wooheat-rating">Heat Rating</p>';
				echo get_heat_rating_image($woo_heat_display);
				echo '<div class="wooheat-scale"><span class="wooheat-fire" style="left:'.$heat_rating.'%;"></span></div>';
			} else {
				echo '<p class="wooheat-rating">Heat Rating</p>';
				echo '<div class="wooheat-scale"><span class="wooheat-fire" style="left:'.$heat_rating.'%;"></span></div>';
			}
			
			
		}
	}

	function get_heat_rating_image($heat_rating) {

		$image = '';

		if(is_numeric($heat_rating)) {
			if(get_option('woo_heat_enable_rating_'.$heat_rating.'_image', false) != false) {
				$image = get_option('woo_heat_rating_'.$heat_rating.'_image', 'http://placehold.it/200x50');
				$image = '<img class="wooheat-image" src="'.$image.'" />';
			}
		}

		return $image;

	}

	function get_heat_ratings_and_images() {

		$heat_ratings = array();

		for ($x = 1; $x <= 5; $x++) {
			if(get_option('woo_heat_enable_rating_'.$x.'_image',false) != false ) {
				$key = 10+$x;
				$heat_ratings[$key] = get_option('woo_heat_enable_rating_'.$x.'_image');
				$heat_ratings[$key]['image'] = get_option('woo_heat_rating_'.$x.'_image', 'http://placehold.it/250x50');
			}
		}

		if(is_empty($heat_ratings)) {
			return false;
		}

		return $heat_ratings;
		
	}

	add_action( 'woocommerce_after_shop_loop_item_title', 'woo_heat_show' );

	function woo_heat_add_postmeta_ordering_args( $sort_args ) {
			
		$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		switch( $orderby_value ) {
		
			case 'woo_heat_low':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'asc';
				$sort_args['meta_key'] = 'woo_heat';
				break;

			case 'woo_heat_high':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'dsc';
				$sort_args['meta_key'] = 'woo_heat';
				break;
			
		}
		
		return $sort_args;
	}
	add_filter( 'woocommerce_get_catalog_ordering_args', 'woo_heat_add_postmeta_ordering_args' );

	//sorting

	function woo_heat_add_new_postmeta_orderby( $sortby ) {
		
		$sortby['woo_heat_high'] = __( 'Sort by Heat: Hot to Mild', 'woocommerce' );
		$sortby['woo_heat_low'] = __( 'Sort by Heat: Mild to Hot', 'woocommerce' );
	    
		return $sortby;
	}
	add_filter( 'woocommerce_default_catalog_orderby_options', 'woo_heat_add_new_postmeta_orderby' );
	add_filter( 'woocommerce_catalog_orderby', 'woo_heat_add_new_postmeta_orderby' );


	//settings

	add_action('admin_menu', 'woo_heat_admin_menu');

	function woo_heat_admin_menu() {
		add_menu_page('WooHeat! Plugin Settings', 'wooHeat!', 'administrator', __FILE__, 'woo_heat_settings_page', 'dashicons-dashboard', '105' );
		add_action( 'admin_init', 'woo_heat_settings' );
	}

	function woo_heat_settings() {
		register_setting( 'woo-heat-settings-group', 'woo_heat_scale_limit' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_cold_temperature_colour' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_hot_temperature_colour' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_flame_colour' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_enable_rating_11_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_enable_rating_12_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_enable_rating_13_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_enable_rating_14_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_enable_rating_15_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_rating_11_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_rating_12_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_rating_13_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_rating_14_image' );
		register_setting( 'woo-heat-settings-group', 'woo_heat_rating_15_image' );
	}

	function woo_heat_settings_page() {
		include 'admin/admin-wooheat.php';
	}

	add_action('wp_head','woo_heat_css');

	function woo_heat_css() {

	$cold = get_option('woo_heat_cold_temperature_colour', '#7DB558');
	$hot = get_option('woo_heat_hot_temperature_colour', '#FF1616');
	$flame_colour = get_option('woo_heat_flame_colour', 'white');

	$css='<style>

	.wooheat-rating {padding: 0;margin: 0;}
	.wooheat-image {width:250px;height:50px;margin:2px!important;}
	.wooheat-scale{
		-webkit-border-radius: 6px;-moz-border-radius: 6px;-ms-border-radius: 6px;-o-border-radius: 6px;border-radius: 6px;
		position:relative;width:100%;height:20px;margin-top:4px;margin-bottom:4px;
		background: '.$cold.'; /* Old browsers */
		background: -moz-linear-gradient(left, '.$cold.' 0%, '.$hot.' 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$cold.'), color-stop(100%,'.$hot.')); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(left,  '.$cold.' 0%,'.$hot.' 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(left,  '.$cold.' 0%,'.$hot.' 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(left,  '.$cold.' 0%,'.$hot.' 100%); /* IE10+ */
		background: linear-gradient(to right,  '.$cold.' 0%,'.$hot.' 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$cold.'", endColorstr="'.$hot.'",GradientType=1 ); /* IE6-8 */
	}
	.wooheat-fire {display:block;width:20px;height:20px;background-size:100%;background-image:url("'.plugins_url('img/wooheat-fire-'.$flame_colour.'.png',__FILE__).'");position:absolute;top:0px;}

	</style>';

	print $css;

	}

	// load script to admin
	function wpss_admin_js() { 
	     $siteurl = get_option('siteurl');
	     $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/js/wooheat.js';
	     echo "<script type='text/javascript' src='$url'></script>"; 
	}
	 add_action('admin_head', 'wpss_admin_js');

}

?>