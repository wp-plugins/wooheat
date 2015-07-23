<?php if ( ! defined( 'ABSPATH' ) ) exit;

wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');
wp_enqueue_script( 'media-upload');

?>

<div class="wrap">

	<h2>wooHeat! Settings</h2>

	<form method="post" action="options.php" enctype="multipart/form-data">

	    <?php settings_fields( 'woo-heat-settings-group' ); ?>
	    <?php do_settings_sections( 'woo-heat-settings-group' ); ?>

	    <table class="form-table">
	    	<tr>
	        	<th scope="row">Display 'Heat Rating' Label?</th>
		        <td>
			        <label for="woo_heat_enable_label"><input name="woo_heat_enable_label" id="woo_heat_enable_label" type="checkbox" value="1" <?php checked( '1', get_option( 'woo_heat_enable_label', true) ); ?> /> Enable</label>
		        </td>
	        </tr>
	    	<tr>
	        	<th scope="row">Display rating chart?</th>
		        <td>
			        <label for="woo_heat_enable_scale"><input name="woo_heat_enable_scale" id="woo_heat_enable_scale" type="checkbox" value="1" <?php checked( '1', get_option( 'woo_heat_enable_scale', true) ); ?> /> Enable</label>
		        </td>
	        </tr>
	        <tr>
	        	<th scope="row">Cold Colour</th>
		        <td>
			        <input type="text" name="woo_heat_cold_temperature_colour" placeholder="<?php echo get_option('woo_heat_cold_temperature_colour', '#7DB558') ?>" value="<?php echo esc_attr( get_option('woo_heat_cold_temperature_colour', '#7DB558' ) ); ?>" />
					<p>You must enter valid HEX colour code, eg. #7DB558</p>
		        </td>
	        </tr>
	        <tr>
	        	<th scope="row">Hot Colour</th>
		        <td>
			        <input type="text" name="woo_heat_hot_temperature_colour" placeholder="<?php echo get_option('woo_heat_hot_temperature_colour', '#FF1616') ?>" value="<?php echo esc_attr( get_option('woo_heat_hot_temperature_colour', '#FF1616') ); ?>" />
					<p>You must enter valid HEX colour code, eg. #FF1616</p>
		        </td>
	        </tr>
	         <tr>
	        	<th scope="row">Flame Colour</th>
		        <td>
		        	<select name="woo_heat_flame_colour" id="woo_heat_flame_colour">
		        		<option value="white" <?php if(get_option('woo_heat_flame_colour', '') == 'white') { echo 'selected'; } ?>>White</option>
		        		<option value="orange" <?php if(get_option('woo_heat_flame_colour', '') == 'orange') { echo 'selected'; } ?>>Orange</option>
		        	</select>
		        </td>
	        </tr>
			<tr>
	        	<th scope="row">Display Scoville rating?</th>
		        <td>
			        <label for="woo_heat_enable_scoville"><input name="woo_heat_enable_scoville" id="woo_heat_enable_scoville" type="checkbox" value="1" <?php checked( '1', get_option( 'woo_heat_enable_scoville', true ) ); ?> /> Enable</label>
		        </td>
	        </tr>

	    </table>

		<h3>wooHeat! Custom Images</h3>
	    <p>The wooHeat! rating system goes up to 15. Everything that is rated over 10 can have a special image that can be displayed instead of the usual heat graph</p>
		<p>If you want to use a custom image, please ensure the dimensions match 200px wide by 50px high.</p>

		<?php $extra = array(11, 12, 13, 14, 15); ?>

		<table class="wp-list-table widefat fixed">
			<?php foreach($extra as $rating) { ?>
			<tr>
				<td class="manage-column column-author">
					Heat Rating <?php echo $rating; ?>
				</td>
				<td class="manage-column column-author">
					<label for="woo_heat_enable_rating_<?php echo $rating; ?>_image"><input name="woo_heat_enable_rating_<?php echo $rating; ?>_image" id="woo_heat_enable_rating_<?php echo $rating; ?>_image" type="checkbox" value="1" <?php checked( '1', get_option( 'woo_heat_enable_rating_'.$rating.'_image' ) ); ?> /> Enable</label>
				</td>
				<td>
					<?php if(get_option('woo_heat_rating_'.$rating.'_image', '') != '' ) { ?>
					<img src="<?php echo get_option('woo_heat_rating_'.$rating.'_image') ?>" alt="" />
					<?php } else { ?>
					<img src="http://placehold.it/200x50" alt="" />
					<?php } ?>
				</td>
				<td>
					<label for="upload_image">
						<input id="woo_heat_rating_<?php echo $rating; ?>_image" type="text" size="36" name="woo_heat_rating_<?php echo $rating; ?>_image" value="<?php echo get_option('woo_heat_rating_'.$rating.'_image'); ?>" />
						<input data-image="woo_heat_rating_<?php echo $rating; ?>_image" class="upload_image_button" type="button" value="Upload Image" />
						<br />Enter an URL or upload an image for the banner.
					</label>
				</td>
			</tr>
			<?php } ?>
		</table>

	    <?php submit_button(); ?>

	</form>

</div>