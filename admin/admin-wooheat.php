<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">

	<h2>wooHeat! Settings</h2>

	<form method="post" action="options.php">

	    <?php settings_fields( 'woo-heat-settings-group' ); ?>
	    <?php do_settings_sections( 'woo-heat-settings-group' ); ?>

	    <table class="form-table">
<!-- 	        <tr valign="top">
		        <th scope="row">Heat Scale Top Value</th>
		        <td>
			        <input type="number" name="woo_heat_scale_limit" value="<?php echo esc_attr( get_option('woo_heat_scale_limit') ); ?>" />
					<p>You must enter a number</p>
		        </td>
	        </tr> -->
	        <tr>
	        	<th scope="row">Cold Colour</th>
		        <td>
			        <input type="text" name="woo_heat_cold_temperature_colour" value="<?php echo esc_attr( get_option('woo_heat_cold_temperature_colour') ); ?>" />
					<p>You must enter valid HEX colour code, eg. #7DB558</p>
		        </td>
	        </tr>
	        <tr>
	        	<th scope="row">Hot Colour</th>
		        <td>
			        <input type="text" name="woo_heat_scale_limit" value="<?php echo esc_attr( get_option('woo_heat_hot_temperature_colour') ); ?>" />
					<p>You must enter valid HEX colour code, eg. #FF1616</p>
		        </td>
	        </tr>
	    </table>
	    
	    <?php submit_button(); ?>

	</form>

</div>