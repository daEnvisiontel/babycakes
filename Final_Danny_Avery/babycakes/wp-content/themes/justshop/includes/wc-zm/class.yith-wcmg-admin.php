<?php
/**
 * Admin class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Zoom Magnifier
 * @version 1.0.0
 */

if ( !defined( 'YITH_WCMG' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCMG_Admin' ) ) {
    /**
     * Admin class. 
	 * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WCMG_Admin {
		/**
		 * Plugin options
		 * 
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public $options = array();

        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version;

        /**
         * Various links
         *
         * @var string
         * @access public
         * @since 1.0.0
         */
    
    	/**
		 * Constructor
		 * 
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct( $version ) {
			$this->options = $this->_initOptions();
            $this->version = $version;

			//Actions
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
            add_filter( 'plugin_action_links_' . plugin_basename( dirname(__FILE__) . '/init.php' ), array( $this, 'action_links' ) );
			
			add_action( 'woocommerce_settings_tabs_yith_wcmg', array( $this, 'print_plugin_options' ) );
			add_action( 'woocommerce_update_options_yith_wcmg', array( $this, 'update_options' ) );
			add_action( 'woocommerce_admin_field_slider', array( $this, 'admin_fields_slider' ) );
            add_action( 'woocommerce_admin_field_picker', array( $this, 'admin_fields_picker' ) );
            add_action( 'woocommerce_admin_field_banner', array( $this, 'admin_fields_banner' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_fields_image_deps' ) );

            add_action( 'woocommerce_update_option_slider', array( $this, 'admin_update_option' ) );
            add_action( 'woocommerce_update_option_picker', array( $this, 'admin_update_option' ) );

			//Filters
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab_woocommerce' ) );
			add_filter( 'woocommerce_catalog_settings', array( $this, 'add_catalog_image_size' ) );


            // YITH WCMG Loaded
            do_action( 'yith_wcmg_loaded' );
		}
		
		
		/**
		 * Init method:
		 *  - default options
		 * 
		 * @access public
		 * @since 1.0.0
		 */
		public function init() {
			$this->_default_options();
		}
		
		
        /**
         * Update plugin options.
         * 
         * @return void
         * @since 1.0.0
         */
        public function update_options() {
            foreach( $this->options as $option ) {
                woocommerce_update_options( $option );   
            }
        }
		
		
		/**
		 * Add Magnifier's tab to Woocommerce -> Settings page
		 * 
		 * @access public
		 * @param array $tabs
		 * 
		 * @return array
		 */
		public function add_tab_woocommerce($tabs) {
            $tabs['yith_wcmg'] = __('Magnifier', 'templatation');
            
            return $tabs;
		}
		
		
		/**
		 * Add Zoom Image size to Woocommerce -> Catalog
		 * 
		 * @access public
		 * @param array $settings
		 * 
		 * @return array
		 */
		public function add_catalog_image_size( $settings ) {
		    $tmp = $settings[ count($settings)-1 ];
		    unset( $settings[ count($settings)-1 ] );
			
			$settings[] = 	array(
				'name' => __( 'Catalog Zoom Images', 'templatation' ),
				'desc' 		=> __('The size of images used within the magnifier box', 'templatation'),
				'id' 		=> 'woocommerce_magnifier_image',
				'css' 		=> '',
				'type' 		=> 'image_width',
				'default' 	=> array( 
									'width' => 600,
									'height' => 600,
									'crop' => true
								),
				'std' 		=> array( 
									'width' => 600,
									'height' => 600,
									'crop' => true
								),
				'desc_tip'	=>  true
			);                                  
			$settings[] = $tmp;
			return $settings;
		}
		
		
        /**
         * Print all plugin options.
         * 
         * @return void
         * @since 1.0.0
         */
        public function print_plugin_options() {
            $links = apply_filters( 'yith_wcmg_tab_links', array(
                '<a href="#yith_wcmg_general">' . __( 'General Settings', 'templatation' ) . '</a>',
                '<a href="#yith_wcmg_magnifier">' . __( 'Magnifier', 'templatation' ) . '</a>',
                '<a href="#yith_wcmg_slider">' . __( 'Slider', 'templatation' ) . '</a>'
            ) );

            ?>

            <div class="subsubsub_section">
                <ul class="subsubsub">
                    <li>
                        <?php echo implode( ' | </li><li>', $links ) ?>
                    </li>
                </ul>
                <br class="clear" />
                
                <?php foreach( $this->options as $id => $tab ) : ?>
                <!-- tab #<?php echo $id ?> -->
                <div class="section" id="yith_wcmg_<?php echo $id ?>">
                    <?php woocommerce_admin_fields( $this->options[$id] ) ?>
                </div>
                <?php endforeach ?>
            </div>
            <?php
        }


		/**
		 * Initialize the options
		 * 
		 * @access protected
		 * @return array
		 * @since 1.0.0
		 */
		protected function _initOptions() {
			$options = array(
				'general' => array(
	                array(
	                	'name' => __( 'General Settings', 'templatation' ), 
	                	'type' => 'title', 
	                	'desc' => '', 
	                	'id' => 'yith_wcmg_general' 
					),
	                
	                array(
	                    'name' => __( 'Enable YITH Magnifier', 'templatation' ),
	                    'desc' => __( 'Enable the plugin or use the Woocommerce default product image.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_enable_plugin',
	                    'std'  => 'yes',
	                    'default' => 'yes',
	                    'type' => 'checkbox'
	                ),
	                
	                array(
	                    'name' => __( 'Forcing Zoom Image sizes', 'templatation' ),
	                    'desc' => __( 'If disabled, you will able to customize the sizes of Zoom Images. Please disable at your own risk; the magnifier should not properly work with unproportioned image sizes.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_force_sizes',
	                    'std'  => 'yes',
	                    'default' => 'yes',
	                    'type' => 'checkbox'
	                ),
	                
					array( 'type' => 'sectionend', 'id' => 'yith_wcmg_general_end' )
				),
				'magnifier' => array(
	                array(
	                	'name' => __( 'Magnifier Settings', 'templatation' ), 
	                	'type' => 'title', 
	                	'desc' => '', 
	                	'id' => 'yith_wcmg_magnifier' 
					),
					
					array(
						'name' => __( 'Zoom Area Width', 'templatation' ), 
						'desc' => __( 'The width of magnifier box (default: auto)', 'templatation' ),
						'id'   => 'yith_wcmg_zoom_width',
						'std'  => 'auto',
						'default' => 'auto',
						'type' => 'text',
					),
					
					array(
						'name' => __( 'Zoom Area Height', 'templatation' ), 
						'desc' => __( 'The height of magnifier box (default: auto)', 'templatation' ),
						'id'   => 'yith_wcmg_zoom_height',
						'std'  => 'auto',
						'default' => 'auto',
						'type' => 'text',
					),
					
					array(
						'name' => __( 'Zoom Area Position', 'templatation' ), 
						'desc' => __( 'The magnifier position', 'templatation' ),
						'id'   => 'yith_wcmg_zoom_position',
						'std'  => 'right',
						'default' => 'right',
						'type' => 'select',
						'options' => array(
							'right'  	=> __( 'Right', 'templatation' ),
							'inside' => __( 'Inside', 'templatation' )
						)
					),
					
					array(
						'name' => __( 'Zoom Area Mobile Position', 'templatation' ), 
						'desc' => __( 'The magnifier position with mobile devices (iPhone, Android, etc.)', 'templatation' ),
						'id'   => 'yith_wcmg_zoom_mobile_position',
						'std'  => 'default',
						'default' => 'inside',
						'type' => 'select',
						'options' => array(
							'default'  	=> __( 'Default', 'templatation' ),
							'inside'    => __( 'Inside', 'templatation' ),
							'disable'   => __( 'Disable', 'templatation' )
						)
					),
					
					array(
						'name' => __( 'Loading label', 'templatation' ), 
						'desc' => '',
						'id'   => 'yith_wcmg_loading_label',
						'std'  => __('Loading...', 'templatation' ),
						'default'  => __('Loading...', 'templatation' ),
						'type' => 'text',
					),
/*
					array(
						'name' => __( 'Tint', 'templatation' ), 
						'desc' => '',
						'id'   => 'yith_wcmg_tint',
						'std'  => '',
						'default' => '',
						'type' => 'picker',
					),

					array(
						'name' => __( 'Tint Opacity', 'templatation' ), 
						'desc' => '',
						'id'   => 'yith_wcmg_tint_opacity',
						'std'  => 0.5,
						'default'  => 0.5,
						'type' => 'slider',
						'min'  => 0,
						'max'  => 1,
						'step' => .1
					),
*/
					array(
						'name' => __( 'Lens Opacity', 'templatation' ), 
						'desc' => '',
						'id'   => 'yith_wcmg_lens_opacity',
						'std'  => 0.5,
						'default'  => 0.5,
						'type' => 'slider',
						'min'  => 0,
						'max'  => 1,
						'step' => .1
					),
/*
					array(
						'name' => __( 'Smoothness', 'templatation' ), 
						'desc' => '',
						'id'   => 'yith_wcmg_smooth',
						'std'  => 3,
						'default'  => 3,
						'type' => 'slider',
						'min'  => 1,
						'max'  => 5,
						'step' => 1
					),
*/
	                array(
	                    'name' => __( 'Blur', 'templatation' ),
	                    'desc' => __( 'Add a blur effect to the small image on mouse hover.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_softfocus',
	                    'std'  => 'no',
	                    'default' => 'no',
	                    'type' => 'checkbox'
	                ),

					array( 'type' => 'sectionend', 'id' => 'yith_wcmg_magnifier_end' )
				),
/*				'slider' => array(
	                array(
	                	'name' => __( 'Slider Settings', 'templatation' ), 
	                	'type' => 'title', 
	                	'desc' => '', 
	                	'id' => 'yith_wcmg_slider' 
					),

                    array(
                        'name' => __( 'Enable Slider', 'templatation' ),
                        'desc' => __( 'Enable Thumbnail slider.', 'templatation' ),
                        'id'   => 'yith_wcmg_enableslider',
                        'std'  => 'yes',
                        'default'  => 'yes',
                        'type' => 'checkbox'
                    ),

                    array(
                        'name' => __( 'Enable Slider Responsive', 'templatation' ),
                        'desc' => __( 'The option fits the thumbnails within the available space. Disable it if you want to manage by yourself the thumbnails (eg. add margins, paddings, etc.)', 'templatation' ),
                        'id'   => 'yith_wcmg_slider_responsive',
                        'std'  => 'yes',
                        'default'  => 'yes',
                        'type' => 'checkbox'
                    ),

					array(
						'name' => __( 'Items', 'templatation' ), 
						'desc' => __( 'Number of items to show', 'templatation' ),
						'id'   => 'yith_wcmg_slider_items',
						'std'  => 3,
						'default' => 3,
						'type' => 'slider',
						'min'  => 1,
						'max'  => 10,
						'step' => 1
					),
					
	                array(
	                    'name' => __( 'Circular carousel', 'templatation' ),
	                    'desc' => __( 'Determines whether the carousel should be circular.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_slider_circular',
	                    'std'  => 'yes',
	                    'default'  => 'yes',
	                    'type' => 'checkbox'
	                ),
					
	                array(
	                    'name' => __( 'Infinite carousel', 'templatation' ),
	                    'desc' => __( 'Determines whether the carousel should be infinite. Note: It is possible to create a non-circular, infinite carousel, but it is not possible to create a circular, non-infinite carousel.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_slider_infinite',
	                    'std'  => 'yes',
	                    'default'  => 'yes',
	                    'type' => 'checkbox'
	                ),
*/
/*
	                array(
	                    'name' => __( 'Slider direction', 'templatation' ),
	                    'desc' => __( 'The direction to scroll the carousel.', 'templatation' ), 
	                    'id'   => 'yith_wcmg_slider_direction',
	                    'std'  => 'yes',
	                    'default' => 'yes',
	                    'type' => 'select',
	                    'options' => array(
							'left' => __('Left', 'templatation' ),
							'right' => __('Right', 'templatation' )
						)
	                ),

					array( 'type' => 'sectionend', 'id' => 'yith_wcmg_slider_end' )
				)
*/
			);
			
			return apply_filters('yith_wcmg_tab_options', $options);
		}


		/**
		 * Default options
		 *
		 * Sets up the default options used on the settings page
		 *
		 * @access protected
		 * @return void
		 * @since 1.0.0
		 */
		protected function _default_options() {
			foreach ($this->options as $section) {
				foreach ( $section as $value ) {
			        if ( isset( $value['std'] ) && isset( $value['id'] ) ) {
			        	if ( $value['type'] == 'image_width' ) {
			        		add_option($value['id'].'_width', $value['std']);
			        		add_option($value['id'].'_height', $value['std']);
			        	} else {
			        		add_option($value['id'], $value['std']);
			        	}
			        }
		        }
		    }
		}
		

		/**
		 * Create new Woocommerce admin field: slider
		 * 
		 * @access public
		 * @param array $value
		 * @return void 
		 * @since 1.0.0
		 */
		public function admin_fields_slider( $value ) {
				$slider_value = ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) ? 
									esc_attr( stripslashes( get_option($value['id'] ) ) ) :
									esc_attr( $value['std'] );
									
            	?><tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
					</th>
                    <td class="forminp">
                    	<div id="<?php echo esc_attr( $value['id'] ); ?>_slider" class="yith_woocommerce_slider" style="width: 300px; float: left;"></div>
                    	<div id="<?php echo esc_attr( $value['id'] ); ?>_value" class="yith_woocommerce_slider_value ui-state-default ui-corner-all"><?php echo $slider_value ?></div>
                    	<input name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" type="hidden" value="<?php echo $slider_value ?>" /> <?php echo $value['desc']; ?></td>
                </tr>
                

                
                <script>
                jQuery(document).ready(function($){
                	$('#<?php echo esc_attr( $value['id'] ); ?>_slider').slider({
                		min: <?php echo $value['min'] ?>,
                		max: <?php echo $value['max'] ?>,
                		step: <?php echo $value['step'] ?>,
                		value: <?php echo $slider_value ?>,
			            slide: function( event, ui ) {
			                $( "#<?php echo esc_attr( $value['id'] ); ?>" ).val( ui.value );
			                $( "#<?php echo esc_attr( $value['id'] ); ?>_value" ).text( ui.value );
			            }
                	});
                });
                </script>
                
                <?php
		}


		/**
		 * Create new Woocommerce admin field: picker
		 * 
		 * @access public
		 * @param array $value
		 * @return void 
		 * @since 1.0.0
		 */
		public function admin_fields_picker( $value ) {
				$picker_value = ( get_option( $value['id'] ) !== false && get_option( $value['id'] ) !== null ) ? 
									esc_attr( stripslashes( get_option($value['id'] ) ) ) :
									esc_attr( $value['std'] );
									
            	?><tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo $value['name']; ?></label>
					</th>
                    <td class="forminp">
						<div class="color_box"><strong><?php echo $value['name']; ?></strong>
							<input name="<?php echo esc_attr( $value['id'] ) ?>" id="<?php echo esc_attr( $value['id'] ) ?>" type="text" value="<?php echo $picker_value ?>" class="colorpick" /> <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ) ?>" class="colorpickdiv"></div>
						</div> <?php echo $value['desc']; ?></td>
                </tr>
                <?php
		}

        /**
         * Save the admin field: slider
         *
         * @access public
         * @param mixed $value
         * @return void
         * @since 1.0.0
         */
        public function admin_update_option($value) {
            update_option( $value['id'], woocommerce_clean($_POST[$value['id']]) );
        }

		/**
		 * Create new Woocommerce admin field: image deps
		 * 
		 * @access public
		 * @param array $value
		 * @return void 
		 * @since 1.0.0
		 */
		public function admin_fields_image_deps( $value ) {
			global $woocommerce; 
			
			$force = get_option('yith_wcmg_force_sizes') == 'yes';
			
			if( $force ) {
				$value['desc'] = 'These values ​​are automatically calculated based on the values ​​of the Single product. If you\'d like to customize yourself the values, please disable the "Forcing Zoom Image sizes" in "Magnifier" tab.';
			}
			
            if( $force && isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] == 'woocommerce_settings' && $_GET['tab'] == 'catalog' ): ?>
				<script>
    			jQuery(document).ready(function($){
    				$('#woocommerce_magnifier_image-width, #woocommerce_magnifier_image-height, #woocommerce_magnifier_image-crop').attr('disabled', 'disabled'); 
    				
    				$('#shop_single_image_size-width, #shop_single_image_size-height').on('keyup', function(){
    					var value = parseInt( $(this).val() );
    					var input = (this.id).indexOf('width') >= 0 ? 'width' : 'height';
    					
    					if( !isNaN(value) ) {
							$('#woocommerce_magnifier_image-' + input).val( value * 2 ); 
        				}
        			});

        			$('#shop_single_image_size-crop').on('change', function(){
        				if( $(this).is(':checked') ) {
        					$('#woocommerce_magnifier_image-crop').attr('checked', 'checked');
        				} else {
        					$('#woocommerce_magnifier_image-crop').removeAttr('checked');
        				}
        			});
        			
                	$('#mainform').on('submit', function(){
                        $(':disabled').removeAttr('disabled');
                    });
        		});
        		</script>
	        <?php endif; 
		}


		/**
		 * Enqueue admin styles and scripts
		 * 
		 * @access public
		 * @return void 
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {
            wp_enqueue_script( 'jquery-ui' ); 
            wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-mouse' );
    		wp_enqueue_script( 'jquery-ui-slider' );
			
			wp_enqueue_style( 'yith_wcmg_admin', YITH_WCMG_URL . 'assets/css/admin.css' );
		}


        /**
         * action_links function.
         *
         * @access public
         * @param mixed $links
         * @return void
         */
        public function action_links( $links ) {

            $plugin_links = array(
                '<a href="' . admin_url( 'admin.php?page=woocommerce_settings&tab=yith_wcmg' ) . '">' . __( 'Settings', 'templatation' ) . '</a>',
                '<a href="' . $this->doc_url . '">' . __( 'Docs', 'templatation' ) . '</a>',
            );

            return array_merge( $plugin_links, $links );
        }
    }
}
