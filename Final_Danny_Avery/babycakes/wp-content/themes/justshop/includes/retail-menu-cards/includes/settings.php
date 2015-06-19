<?php

function rmc_settings_page() {
	
	global $rmc_options,$rmc_settings;
	if ( isset($rmc_options) )
	{
		foreach ($rmc_options as $k=>$v) { 
			if(empty($rmc_options[$v])){ 
			   $rmc_options[$v] = ''; 
			} 
		} 
	}
	if ( isset($rmc_settings) )
	{
		foreach ($rmc_settings as $k=>$v) { 
			if(empty($rmc_settings[$v])){ 
			   $rmc_settings[$v] = ''; 
			} 
		} 
	}
	$looks = array('classic' => 'Classic', 'list' => 'List', 'grid' => 'Grid', 'light' => 'Light');
	$layouts = array('1col' => '1 Column', '2col' => '2 Columns', '3col' => '3 Columns');
	$links = array('none' => 'No link', 'image' => 'Large image', 'post' => 'Post');
	?>
	
	<div class="wrap">
		
		<h2><?php _e('Retail Menu Cards Settings', 'templatation'); ?></h2>
		<?php
		if (!isset($_REQUEST['updated']))
			$_REQUEST['updated'] = false;
		?>
		<?php if (false !== $_REQUEST['updated']) : ?>
		<div class="updated fade"><p><strong><?php _e('Options saved', 'templatation'); ?></strong></p></div>
		<?php endif; ?>
		
		<form method="post" action="options.php">

			<?php settings_fields('rmc_settings_group'); ?>
			
			<p><?php _e('Here you can fine tune your menu cards.', 'templatation'); ?></p>
			
			<h3><?php _e('Display', 'templatation'); ?></h3>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[thumblink]"><?php _e('Thumbnail links to', 'templatation'); ?></label>
						</th>
						<td>
							<select name="rmc_settings[thumblink]">
								<?php foreach ($links as $k => $option) { ?>
									<option value="<?php echo $k; ?>"<?php if ($rmc_options['thumblink'] == $k) { echo ' selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[titlelink]"><?php _e('Title links to', 'templatation'); ?></label>
						</th>
						<td>
							<select name="rmc_settings[titlelink]">
								<?php foreach ($links as $k => $option) { ?>
									<option value="<?php echo $k; ?>"<?php if ($rmc_options['titlelink'] == $k) { echo ' selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e('Thumbnail', 'templatation'); ?>
						</th>
						<td>
							<label for="rmc_settings[nothumb]"><input id="rmc_settings[nothumb]" name="rmc_settings[nothumb]" type="checkbox" value="1" <?php checked('1', $rmc_options['nothumb']); ?> /> <?php _e('Hide the thumbnail', 'templatation'); ?></label>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e('Price', 'templatation'); ?>
						</th>
						<td>
							<label for="rmc_settings[noprice]"><input id="rmc_settings[noprice]" name="rmc_settings[noprice]" type="checkbox" value="1" <?php checked('1', $rmc_options['noprice']); ?> /> <?php _e('Hide the price', 'templatation'); ?></label>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e('Description', 'templatation'); ?>
						</th>
						<td>
							<label for="rmc_settings[nodesc]"><input id="rmc_settings[nodesc]" name="rmc_settings[nodesc]" type="checkbox" value="1" <?php checked('1', $rmc_options['nodesc']); ?> /> <?php _e('Hide the description', 'templatation'); ?></label>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e('Labels', 'templatation'); ?>
						</th>
						<td>
							<label for="rmc_settings[nolabels]"><input id="rmc_settings[nolabels]" name="rmc_settings[nolabels]" type="checkbox" value="1" <?php checked('1', $rmc_options['nolabels']); ?> /> <?php _e('Hide the labels', 'templatation'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h3><?php _e('Currency', 'templatation'); ?></h3>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[currency]"><?php _e('Currency (&dollar; &euro; &pound; &yen; etc.)', 'templatation'); ?></label>
						</th>
						<td>
							<input id="rmc_settings[currency]" name="rmc_settings[currency]" type="text" value="<?php echo $rmc_options['currency']; ?>" value="$" />
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php _e('Position', 'templatation'); ?>
						</th>
						<td>
							<label for="rmc_settings[position]"><input id="rmc_settings[position]" name="rmc_settings[position]" type="checkbox" value="1" <?php checked('1', $rmc_options['position']); ?> /> <?php _e('Put the currency after the price', 'templatation'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h3><?php _e('Styling', 'templatation'); ?></h3>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[look]"><?php _e('Look and feel', 'templatation'); ?></label>
						</th>
						<td>
							<select name="rmc_settings[look]">
								<?php foreach ($looks as $k => $option) { ?>
									<option value="<?php echo $k; ?>"<?php if ($rmc_options['look'] == $k) { echo ' selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[layout]"><?php _e('Layout', 'templatation'); ?></label>
						</th>
						<td>
							<select name="rmc_settings[layout]">
								<?php foreach ($layouts as $k => $option) { ?>
									<option value="<?php echo $k; ?>"<?php if ($rmc_options['layout'] == $k) { echo ' selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="rmc_settings[css]"><?php _e('Custom CSS', 'templatation'); ?></label>
						</th>
						<td>
							<textarea id="rmc_settings[css]" name="rmc_settings[css]" rows="10" cols="50" class="large-text code"><?php echo $rmc_options['css'];?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			
			<!-- save the options -->
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'templatation'); ?>" />
			</p>							
			
		</form>
		
	</div><!-- .wrap -->
		
	<?php
}

// register the plugin settings
function rmc_register_settings() {

	// create whitelist of options
	register_setting('rmc_settings_group', 'rmc_settings');
}
// call register settings function
add_action( 'admin_init', 'rmc_register_settings' );

function rmc_settings_menu() {
	// add settings page
	add_submenu_page('templatation', 'Menu Cards Settings', 'Menu Cards Settings', 'manage_options', 'rmc-settings', 'rmc_settings_page');
}
add_action('admin_menu', 'rmc_settings_menu');

?>