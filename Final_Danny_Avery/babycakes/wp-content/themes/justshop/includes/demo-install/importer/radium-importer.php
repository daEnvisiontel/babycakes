<?php

/**
 * Class Radium_Theme_Importer
 *
 * This class provides the capability to import demo content as well as import widgets and WordPress menus
 *
 * @since 2.2.0
 *
 * @category RadiumFramework
 * @package  NewsCore WP
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 *
 */
class Radium_Theme_Importer {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $theme_options_file;

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $widgets;

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $content_demo;

	/**
	 * Flag imported to prevent duplicates
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $flag_as_imported = array();

    /**
     * Holds a copy of the object for easy reference.
     *
     * @since 2.2.0
     *
     * @var object
     */
    private static $instance;

    /**
     * Constructor. Hooks all interactions to initialize the class.
     *
     * @since 2.2.0
     */
    public function __construct() {

        self::$instance = $this;
        
        $this->theme_options_file = $this->demo_files_path . $this->theme_options_file_name;
        $this->widgets = $this->demo_files_path . $this->widgets_file_name;
        $this->content_demo = $this->demo_files_path . $this->content_demo_file_name;
		 
        add_action( 'admin_menu', array($this, 'add_admin') );

    }

	/**
	 * Add Panel Page
	 *
	 * @since 2.2.0
	 */
    public function add_admin() {

        add_submenu_page('themes.php', "Import Demo Data", "Import Demo Data", 'switch_themes', 'radium_demo_installer', array($this, 'demo_installer'));

    }

    /**
     * [demo_installer description]
     *
     * @since 2.2.0
     *
     * @return [type] [description]
     */
    public function demo_installer() {

        ?>
<style type="text/css">
    .tt_import_img {
        background: none;
        border: none;
        cursor:pointer;
    } .tt_import_img:hover {
        opacity: .7;
    }
     .tt_adm_box {
         background-color: #F5FAFD;
         margin:10px 0;
         padding: 4px 10px;
         color: #0C518F;
         border: 3px solid #CAE0F3;
         claer:both;
         width:90%;
         line-height:18px;
     }
     .tt_adm_box_green {
         background-color: #e1ffe2;
         margin:10px 0;
         padding: 4px 10px;
         color: #0C518F;
         border: 3px solid #77d479;
         claer:both;
         width:90%;
         line-height:18px;
     }
</style>
        <div id="icon-tools" class="icon32"><br></div>
        <h2>Import Demo Data</h2>
        <div class="tt_adm_box">
            <p class="tie_message_hint">Please make sure you have imported content.xml already.<br />
                When you import the data following things will happen:</p>

              <ul style="padding-left: 20px;list-style-position: inside;list-style-type: square;}">
                  <li>This step makes no changes to content, like page, posts etc.</li>
                  <li>Sliders, widgets, theme configurations, menus will get imported. Also the other setup will be done on website.</li>
                  <li>Images will be downloaded from our server, these images are from http://Pixabay.com and for demo use only .</li>
                  <li>Please click import only once and wait, it can take a couple of minutes</li>
              </ul>
<p class="tie_message_hint">Important : Before you begin, make sure Visual Composer, Woocommerce, Ultimate addon for VC, WooSidebars plugins are activated, or this process will not finish completely.<br /> Please select which Demo you want to import. (you can switch to other layouts easily by a click on Theme Options too. If you see any errors below, please ignore them, When done, you will see a link below to move to final step.) </p>         </div>
        <form method="post">
            <input type="hidden" name="demononce" value="<?php echo wp_create_nonce('radium-demo-code'); ?>" />
            <button name="demosite" class="tt_import_img" type="submit" value="layout2"><img src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/layout2.png" alt="layout2"><p>Layout2</p></button>
            <button name="demosite" class="tt_import_img" type="submit" value="layout3"><img  src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/layout3.png" alt="layout3"><p>Layout3</p></button>
            <button name="demosite" class="tt_import_img" type="submit" value="layout4"><img src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/layout4.png" alt="layout4"><p>Layout4</p></button>
            <button name="demosite" class="tt_import_img" type="submit" value="layout5"><img src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/layout5.png" alt="layout5"><p>Layout5</p></button>
            <button name="demosite" class="tt_import_img" type="submit" value="layout6"><img src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/layout6.png" alt="layout6"><p>Layout6</p></button>
             <button name="demosite" class="tt_import_img" type="submit" value="layout7"><img src="<?php echo get_template_directory_uri();?>/includes/demo-install/importer/restaurant.png" alt="restaurant"><p>Layout7/Restaurant</p></button>
       </form>
        <?php
		$demosite = isset($_REQUEST['demosite']) ? $_REQUEST['demosite'] : 'nill';
		
        if( 'nill' != $demosite && check_admin_referer('radium-demo-code' , 'demononce')){

            //modify themeoptions file path based on selected layout to import
            $this->theme_options_file = $this->demo_files_path . $demosite .'/'. $this->theme_options_file_name;

            //$this->set_demo_data( $this->content_demo ); //its breaking in middle, so have to manual for now.
            $this->set_demo_theme_options( $this->theme_options_file ); //import before widgets incase we need more sidebars

	        // $this->set_demo_menus(); // we are calling this as a stand alone function.

           // $this->process_widget_import_file( $this->widgets ); // we are calling this as a stand alone function.

            do_action( 'tt_after_import_hook' ); // hooking menu, widgets, slider setup etc here.
            echo '<p class=tt_adm_box_green>Almost done. Final step is to click <strong>Save All Changes</strong> button on next page. &nbsp;<a href="'.admin_url( 'admin.php?page=templatation' ).'">Click to go to next page and save to finish final step.</a></p>';
        }

    }

    public function set_demo_data( $file ) {

	    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

        require_once ABSPATH . 'wp-admin/includes/import.php';

        $importer_error = false;

        if ( !class_exists( 'WP_Importer' ) ) {

            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	
            if ( file_exists( $class_wp_importer ) ){

                require_once($class_wp_importer);

            } else {

                $importer_error = true;

            }

        }

        if ( !class_exists( 'WP_Import' ) ) {

            $class_wp_import = dirname( __FILE__ ) .'/wordpress-importer.php';

            if ( file_exists( $class_wp_import ) ) 
                require_once($class_wp_import);
            else
                $importer_error = true;

        }

        if($importer_error){

            die("Error on import");

        } else {
			
            if(!is_file( $file )){

                echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the Wordpress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

            } else {

               $wp_import = new WP_Import();
               $wp_import->fetch_attachments = true;
               $wp_import->import( $file );

         	}

    	}
    	// Hook after import
    	do_action( 'radium_theme_after_import' );

    }

    //public function set_demo_menus() {}

    public function set_demo_theme_options( $file ) {

    	// File exists?
		if ( ! file_exists( $file ) ) {
			wp_die(
				__( 'Theme options Import file could not be found. Please try again.', 'radium' ),
				'',
				array( 'back_link' => true )
			);
		}

		// Get file contents and decode
		$data = file_get_contents( $file );

		// Decode the JSON from the uploaded file
		$options = json_decode( $data, true );

	    // Make sure the options are saved to the global options collection as well.
		$woo_options = get_option( 'woo_options' );

		$has_updated = false; // If this is set to true at any stage, we update the main options collection.

		// Cycle through data, import settings
		foreach ( (array)$options as $key => $settings ) {

			$settings = maybe_unserialize( $settings ); // Unserialize serialized data before inserting it back into the database.

			// We can run checks using get_option(), as the options are all cached. See wp-includes/functions.php for more information.
			if ( get_option( $key ) != $settings ) {
				update_option( $key, $settings );
			}

			if ( is_array( $woo_options ) ) {
				if ( isset( $woo_options[$key] ) && $woo_options[$key] != $settings ) {
					$woo_options[$key] = $settings;
					$has_updated = true;
				}
			}
		}

		if ( $has_updated == true ) {
			update_option( 'woo_options', $woo_options );
		}

    }
}


/**
 * add_widget_to_sidebar Import sidebars
 * @param  string $sidebar_slug    Sidebar slug to add widget
 * @param  string $widget_slug     Widget slug
 * @param  string $count_mod       position in sidebar
 * @param  array  $widget_settings widget settings
 *
 * @since 2.2.0
 *
 * @return null
 */
function add_widget_to_sidebar($sidebar_slug, $widget_slug, $count_mod, $widget_settings = array()){

    $sidebars_widgets = get_option('sidebars_widgets');

    if(!isset($sidebars_widgets[$sidebar_slug]))
       $sidebars_widgets[$sidebar_slug] = array('_multiwidget' => 1);

    $newWidget = get_option('widget_'.$widget_slug);

    if(!is_array($newWidget))
        $newWidget = array();

    $count = count($newWidget)+1+$count_mod;
    $sidebars_widgets[$sidebar_slug][] = $widget_slug.'-'.$count;

    $newWidget[$count] = $widget_settings;

    update_option('sidebars_widgets', $sidebars_widgets);
    update_option('widget_'.$widget_slug, $newWidget);

}

/**
 * Available widgets
 *
 * Gather site's widgets into array with ID base, name, etc.
 * Used by export and import functions.
 *
 * @since 2.2.0
 *
 * @global array $wp_registered_widget_updates
 * @return array Widget information
 */
function available_widgets() {

    global $wp_registered_widget_controls;

    $widget_controls = $wp_registered_widget_controls;

    $available_widgets = array();

    foreach ( $widget_controls as $widget ) {

        if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

            $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
            $available_widgets[$widget['id_base']]['name'] = $widget['name'];

        }

    }

    return apply_filters( 'radium_theme_import_widget_available_widgets', $available_widgets );

}


/**
 * Process import file
 *
 * This parses a file and triggers importation of its widgets.
 *
 * @since 2.2.0
 *
 * @param string $file Path to .wie file uploaded
 * @global string $widget_import_results
 */
function process_widget_import_file() {
	$file=get_template_directory() .'/includes/demo-install/demo-files/widgets.json';

    // File exists?
    if ( ! file_exists( $file ) ) {
        wp_die(
            __( 'Widget Import file could not be found. Please try again.', 'radium' ),
            '',
            array( 'back_link' => true )
        );
    }

    // Get file contents and decode
    $data = file_get_contents( $file );
    $data = json_decode( $data );

    // Delete import file
    //unlink( $file );

    // Import the widget data
    // Make results available for display on import/export page
    import_widgets( $data );

}


/**
 * Import widget JSON data
 *
 * @since 2.2.0
 * @global array $wp_registered_sidebars
 * @param object $data JSON widget data from .wie file
 * @return array Results array
 */
function import_widgets( $data ) {

    global $wp_registered_sidebars;

    // Have valid data?
    // If no data or could not decode
    if ( empty( $data ) || ! is_object( $data ) ) {
	    wp_die(
		    __( 'Widget import data could not be read. Please try a different file.', 'radium' ),
		    '',
		    array( 'back_link' => true )
	    );
    }

    // Hook before import
    $data = apply_filters( 'radium_theme_import_widget_data', $data );

    // Get all available widgets site supports
    $available_widgets = available_widgets();

    // Get all existing widget instances
    $widget_instances = array();
    foreach ( $available_widgets as $widget_data ) {
	    $widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
    }

    // Begin results
    $results = array();

    // Loop import data's sidebars
    foreach ( $data as $sidebar_id => $widgets ) {

	    // Skip inactive widgets
	    // (should not be in export file)
	    if ( 'wp_inactive_widgets' == $sidebar_id ) {
		    continue;
	    }

	    // Check if sidebar is available on this site
	    // Otherwise add widgets to inactive, and say so
	    if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		    $sidebar_available    = true;
		    $use_sidebar_id       = $sidebar_id;
		    $sidebar_message_type = 'success';
		    $sidebar_message      = '';
	    } else {
		    $sidebar_available    = false;
		    $use_sidebar_id       = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
		    $sidebar_message_type = 'error';
		    $sidebar_message      = __( 'Sidebar does not exist in theme (using Inactive)', 'radium' );
	    }

	    // Result for sidebar
	    $results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
	    $results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
	    $results[ $sidebar_id ]['message']      = $sidebar_message;
	    $results[ $sidebar_id ]['widgets']      = array();

	    // Loop widgets
	    foreach ( $widgets as $widget_instance_id => $widget ) {

		    $fail = false;

		    // Get id_base (remove -# from end) and instance ID number
		    $id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
		    $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

		    // Does site support this widget?
		    if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
			    $fail                = true;
			    $widget_message_type = 'error';
			    $widget_message      = __( 'Site does not support widget', 'radium' ); // explain why widget not imported
		    }

		    // Filter to modify settings before import
		    // Do before identical check because changes may make it identical to end result (such as URL replacements)
		    $widget = apply_filters( 'radium_theme_import_widget_settings', $widget );

		    // Does widget with identical settings already exist in same sidebar?
		    if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

			    // Get existing widgets in this sidebar
			    $sidebars_widgets = get_option( 'sidebars_widgets' );
			    $sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // check Inactive if that's where will go

			    // Loop widgets with ID base
			    $single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
			    foreach ( $single_widget_instances as $check_id => $check_widget ) {

				    // Is widget in same sidebar and has identical settings?
				    if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

					    $fail                = true;
					    $widget_message_type = 'warning';
					    $widget_message      = __( 'Widget already exists', 'radium' ); // explain why widget not imported

					    break;

				    }

			    }

		    }

		    // No failure
		    if ( ! $fail ) {

			    // Add widget instance
			    $single_widget_instances   = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
			    $single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
			    $single_widget_instances[] = (array) $widget; // add it

			    // Get the key it was given
			    end( $single_widget_instances );
			    $new_instance_id_number = key( $single_widget_instances );

			    // If key is 0, make it 1
			    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
			    if ( '0' === strval( $new_instance_id_number ) ) {
				    $new_instance_id_number                             = 1;
				    $single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
				    unset( $single_widget_instances[0] );
			    }

			    // Move _multiwidget to end of array for uniformity
			    if ( isset( $single_widget_instances['_multiwidget'] ) ) {
				    $multiwidget = $single_widget_instances['_multiwidget'];
				    unset( $single_widget_instances['_multiwidget'] );
				    $single_widget_instances['_multiwidget'] = $multiwidget;
			    }

			    // Update option with new widget
			    update_option( 'widget_' . $id_base, $single_widget_instances );

			    // Assign widget instance to sidebar
			    $sidebars_widgets                      = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
			    $new_instance_id                       = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
			    $sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id; // add new instance to sidebar
			    update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

			    // Success message
			    if ( $sidebar_available ) {
				    $widget_message_type = 'success';
				    $widget_message      = __( 'Imported', 'radium' );
			    } else {
				    $widget_message_type = 'warning';
				    $widget_message      = __( 'Imported to Inactive', 'radium' );
			    }

		    }

		    // Result for widget instance
		    $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
		    $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = $widget->title ? $widget->title : __( 'No Title', 'radium' ); // show "No Title" if widget instance is untitled
		    $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
		    $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

	    }

    }

    // Hook after import
    do_action( 'radium_theme_import_widget_after_import' );

    // Return results
    return apply_filters( 'radium_theme_import_widget_results', $results );
}
function tt_set_demo_menus(){

    // Menus to Import and assign - you can remove or add as many as you want
    $main_menu = get_term_by('name', 'Main Menu', 'nav_menu');

    set_theme_mod( 'nav_menu_locations', array(
            'primary-menu' => $main_menu->term_id
        )
    );

}

function tt_import_rev(){ // Import Revslider
	global $wpdb;

	if( class_exists('UniteFunctionsRev') ) { // if revslider is activated
	$rev_directory = get_template_directory() . '/includes/demo-install/demo-files/revsliders/'; // revsliders data dir

	foreach( glob( $rev_directory . '*.zip' ) as $filename ) { // get all files from revsliders data dir
		$filename = basename($filename);
		$rev_files[] = get_template_directory() . '/includes/demo-install/demo-files/revsliders/' . $filename ;
	}

	foreach( $rev_files as $rev_file ) {
			$filepath = $rev_file;
			$zip = new ZipArchive;
			$importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);

			if($importZip === true){ //true or integer. If integer, its not a correct zip file

				//check if files all exist in zip
				$slider_export = $zip->getStream('slider_export.txt');
				$custom_animations = $zip->getStream('custom_animations.txt');
				$dynamic_captions = $zip->getStream('dynamic-captions.css');
				$static_captions = $zip->getStream('static-captions.css');

				//if(!$slider_export)  UniteFunctionsRev::throwError("slider_export.txt does not exist!");
				//if(!$custom_animations)  UniteFunctionsRev::throwError("custom_animations.txt does not exist!");
				//if(!$dynamic_captions) UniteFunctionsRev::throwError("dynamic-captions.css does not exist!");
				//if(!$static_captions)  UniteFunctionsRev::throwError("static-captions.css does not exist!");

				$content = '';
				$animations = '';
				$dynamic = '';
				$static = '';

				while (!feof($slider_export)) $content .= fread($slider_export, 1024);
				if($custom_animations){ while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024); }
				if($dynamic_captions){ while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024); }
				if($static_captions){ while (!feof($static_captions)) $static .= fread($static_captions, 1024); }

				fclose($slider_export);
				if($custom_animations){ fclose($custom_animations); }
				if($dynamic_captions){ fclose($dynamic_captions); }
				if($static_captions){ fclose($static_captions); }

				//check for images!

			}else{ //check if fallback
				//get content array
				$content = @file_get_contents($filepath);
			}

			if($importZip === true){ //we have a zip
				$db = new UniteDBRev();

				//update/insert custom animations
				$animations = @unserialize($animations);
				if(!empty($animations)){
					foreach($animations as $key => $animation){ //$animation['id'], $animation['handle'], $animation['params']
						$exist = $db->fetch(GlobalsRevSlider::$table_layer_anims, "handle = '".$animation['handle']."'");
						if(!empty($exist)){ //update the animation, get the ID
							if($updateAnim == "true"){ //overwrite animation if exists
								$arrUpdate = array();
								$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
								$db->update(GlobalsRevSlider::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));

								$id = $exist['0']['id'];
							}else{ //insert with new handle
								$arrInsert = array();
								$arrInsert["handle"] = 'copy_'.$animation['handle'];
								$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

								$id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
							}
						}else{ //insert the animation, get the ID
							$arrInsert = array();
							$arrInsert["handle"] = $animation['handle'];
							$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

							$id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
						}

						//and set the current customin-oldID and customout-oldID in slider params to new ID from $id
						$content = str_replace(array('customin-'.$animation['id'], 'customout-'.$animation['id']), array('customin-'.$id, 'customout-'.$id), $content);
					}
					//dmp(__("animations imported!",REVSLIDER_TEXTDOMAIN));
				}else{
					//dmp(__("no custom animations found, if slider uses custom animations, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
				}

				//overwrite/append static-captions.css
				if(!empty($static)){
					if(isset( $updateStatic ) && $updateStatic == "true"){ //overwrite file
						RevOperations::updateStaticCss($static);
					}else{ //append
						$static_cur = RevOperations::getStaticCss();
						$static = $static_cur."\n".$static;
						RevOperations::updateStaticCss($static);
					}
				}
				//overwrite/create dynamic-captions.css
				//parse css to classes
				$dynamicCss = UniteCssParserRev::parseCssToArray($dynamic);

				if(is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0){
					foreach($dynamicCss as $class => $styles){
						//check if static style or dynamic style
						$class = trim($class);

						if((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
							strpos($class," ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
							strpos($class,".tp-caption") === false || // everything that is not tp-caption
							(strpos($class,".") === false || strpos($class,"#") !== false) || // no class -> #ID or img
							strpos($class,">") !== false){ //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
							continue;
						}

						//is a dynamic style
						if(strpos($class, ':hover') !== false){
							$class = trim(str_replace(':hover', '', $class));
							$arrInsert = array();
							$arrInsert["hover"] = json_encode($styles);
							$arrInsert["settings"] = json_encode(array('hover' => 'true'));
						}else{
							$arrInsert = array();
							$arrInsert["params"] = json_encode($styles);
						}
						//check if class exists
						$result = $db->fetch(GlobalsRevSlider::$table_css, "handle = '".$class."'");

						if(!empty($result)){ //update
							$db->update(GlobalsRevSlider::$table_css, $arrInsert, array('handle' => $class));
						}else{ //insert
							$arrInsert["handle"] = $class;
							$db->insert(GlobalsRevSlider::$table_css, $arrInsert);
						}
					}
					//dmp(__("dynamic styles imported!",REVSLIDER_TEXTDOMAIN));
				}else{
					//dmp(__("no dynamic styles found, if slider uses dynamic styles, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
				}
			}

			$content = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $content); //clear errors in string

			$arrSlider = @unserialize($content);
			if(empty($arrSlider))
				UniteFunctionsRev::throwError("Wrong export slider file format! This could be caused because the ZipArchive extension is not enabled.");

			$sliderParams = $arrSlider["params"];

			if(isset($sliderParams["background_image"]))
				$sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath($sliderParams["background_image"]);

			$json_params = json_encode($sliderParams);

			//new slider
			$arrInsert = array();
			$arrInsert["params"] = $json_params;
			$arrInsert["title"] = UniteFunctionsRev::getVal($sliderParams, "title","Slider1");
			$arrInsert["alias"] = UniteFunctionsRev::getVal($sliderParams, "alias","slider1");
			$sliderID = $wpdb->insert(GlobalsRevSlider::$table_sliders,$arrInsert);
			$sliderID = $wpdb->insert_id;

			//-------- Slides Handle -----------

			//create all slides
			$arrSlides = $arrSlider["slides"];

			$alreadyImported = array();

			foreach($arrSlides as $slide){

				$params = $slide["params"];
				$layers = $slide["layers"];

				//convert params images:
				if(isset($params["image"])){
					//import if exists in zip folder
					if(trim($params["image"]) !== ''){
						if($importZip === true){ //we have a zip, check if exists
							$image = $zip->getStream('images/'.$params["image"]);
							if(!$image){
								echo $params["image"].' not found!<br>';
							}else{
								if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]])){
									$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$params["image"], $sliderParams["alias"].'/');

									if($importImage !== false){
										$alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]] = $importImage['path'];

										$params["image"] = $importImage['path'];
									}
								}else{
									$params["image"] = $alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]];
								}
							}
						}
					}
					$params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
				}

				//convert layers images:
				foreach($layers as $key=>$layer){
					if(isset($layer["image_url"])){
						//import if exists in zip folder
						if(trim($layer["image_url"]) !== ''){
							if($importZip === true){ //we have a zip, check if exists
								$image_url = $zip->getStream('images/'.$layer["image_url"]);
								if(!$image_url){
									echo $layer["image_url"].' not found!<br>';
								}else{
									if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]])){
										$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$layer["image_url"], $sliderParams["alias"].'/');

										if($importImage !== false){
											$alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]] = $importImage['path'];

											$layer["image_url"] = $importImage['path'];
										}
									}else{
										$layer["image_url"] = $alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]];
									}
								}
							}
						}
						$layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
						$layers[$key] = $layer;
					}
				}

				//create new slide
				$arrCreate = array();
				$arrCreate["slider_id"] = $sliderID;
				$arrCreate["slide_order"] = $slide["slide_order"];
				$arrCreate["layers"] = json_encode($layers);
				$arrCreate["params"] = json_encode($params);

				$wpdb->insert(GlobalsRevSlider::$table_slides,$arrCreate);
			//}
		}
	}
	}

}

?>