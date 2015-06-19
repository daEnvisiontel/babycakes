<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*---------------------------------------------------------------------------------*/
/* Product category widget */
/*---------------------------------------------------------------------------------*/
class Woo_Product_Categories extends WP_Widget {
	var $settings = array( 'categories_per_page' );

	function Woo_Product_Categories() {
		$widget_ops = array( 'description' => 'Display product categories (use in the homepage widget region only, will not showup in sidebars)' );
		parent::WP_Widget( false, __( 'JS - Product Categories Loop', 'templatation' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $args, EXTR_SKIP );
		extract( $instance, EXTR_SKIP );
		global $woocommerce_loop;

			echo $before_widget;
				echo '<div class="woocommerce-columns-3">';
	    		echo do_shortcode( '[product_categories columns="3" parent="0" number="' . apply_filters( 'categories_per_page', $categories_per_page, $instance, $this->id_base ) . '"]' );
				echo '</div>';
			echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		$new_instance = $this->woo_enforce_defaults( $new_instance );
		return $new_instance;
	}

	function woo_enforce_defaults( $instance ) {
		$defaults = $this->woo_get_settings();
		$instance = wp_parse_args( $instance, $defaults );
		$instance['categories_per_page'] = strip_tags( $instance['categories_per_page'] );
		if ( '' == $instance['categories_per_page'] ) {
			$instance['categories_per_page'] = __( '4', 'templatation' );
		}
		return $instance;
	}

	/**
	 * Provides an array of the settings with the setting name as the key and the default value as the value
	 * This cannot be called get_settings() or it will override WP_Widget::get_settings()
	 */
	function woo_get_settings() {
		// Set the default to a blank string
		$settings = array_fill_keys( $this->settings, '' );
		// Now set the more specific defaults
		return $settings;
	}

	function form($instance) {
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $instance, EXTR_SKIP );
?>
		<p>
			<label for="<?php echo $this->get_field_id('categories_per_page'); ?>"><?php _e('Number of product categories to display (Multiples of 3 recommended):','templatation'); ?></label>
			<input type="number" name="<?php echo $this->get_field_name('categories_per_page'); ?>" value="<?php echo esc_attr( $categories_per_page ); ?>" size="3" style="width:40px;" id="<?php echo $this->get_field_id('categories_per_page'); ?>" />
		</p>

<?php
	}
}

register_widget( 'Woo_Product_Categories' );
