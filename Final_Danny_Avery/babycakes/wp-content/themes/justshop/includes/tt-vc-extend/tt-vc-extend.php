<?php

/*
Extending VC plugin.
@ Templatation.com
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

/*
Removing VC elements.
*/

vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");

/*
Lets call wpb_map function to "register" our custom shortcode within Visual Composer interface.
*/


vc_add_param("vc_button", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style"),
	"param_name" => "tt_style",
	"value" => array(
		"Default" => "default",
		"justshop" => "justshop"
	),
	"description" => __("If you select justshop, button will be flavored as per the justshop theme and the colors you chose above will not make any difference. Feel free to try out, you can always revert back.")
));
/* no longer needed @v5.0 onwards
vc_add_param("vc_separator", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Line"),
	"param_name" => "tt_line",
	"value" => array(
		"Yes" => "yes",
		"No" => "no"
	),
	"description" => __("Do you want to show line or not.")
));
*/
vc_add_param("vc_pie", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style"),
	"param_name" => "tt_style",
	"value" => array(
		"Default" => "default",
		"justshop" => "justshop"
	),
	"description" => __("If you select justshop, Pie bar will be flavored as per the justshop and the colors you chose above will not make any difference. Feel free to try out, you can always revert back.")
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Row ID"),
	"param_name" => "tt_rowid",
	"value" => "",
	"description" => __("Advance Users: If you want to assign ID to this row incase you are developing single scrollable page, please enter ID here. Make sure to name it personally to avoid conflicting. EG: johnaboutus . If you are not sure, please leave this blank.")
));


//Headline shortcode.
vc_map( array(
		"name" => __("Heading"),
		"base" => "TT-headline",
		"category" => __('Added by JS'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display Headline, multiple options.', 'templatation'),
		"icon" => "icon-wpb-ui-separator",
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "nt-underline",
				"heading" => __("Enter Heading"),
				"admin_label" => true,
				"param_name" => "title",
				"value" => "",
				"description" => __("Enter title for the heading.")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Style"),
				"param_name" => "headstyle",
				"value" => array(
					"Select" => "",
					"Plain" => "HDplain",
					"With Bottom Border" => "HDborder",
					"With Background" => "HDbg"
				),
				"description" => __("Headline styles. They are self explained but if you are not sure try them out. You can always revert back.")
			)
		)
) );


//Templatatio Featured products shortcode
if ( is_woocommerce_activated() ) {
vc_map( array(
		"name" => __("Featured Products"),
		"base" => "TT-featuredproducts",
		"category" => __('Added by JS'),
		'admin_enqueue_css' => array(get_template_directory_uri().'/includes/tt-vc-extend/tt-vc-extend.css'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display Featured Products Section.', 'templatation'),
		"icon" => "icon-wpb-tt_woocommerce",
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "nt-underline",
				"admin_label" => true,
				"heading" => __("Enter Heading"),
				"param_name" => "title",
				"value" => "",
				"description" => __("Enter title for the Featured Products section.")
			)
		)
) );
}
//Templatatio Latest products shortcode.
if ( is_woocommerce_activated() ) {
vc_map( array(
		"name" => __("Latest Products"),
		"base" => "TT-latestproducts",
		"category" => __('Added by JS'),
		'admin_enqueue_css' => array(get_template_directory_uri().'/includes/tt-vc-extend/tt-vc-extend.css'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display Latest Products Section.', 'templatation'),
		"icon" => "icon-wpb-tt_woocommerce",
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "nt-underline",
				"heading" => __("Enter Heading"),
				"admin_label" => true,
				"param_name" => "title",
				"value" => "",
				"description" => __("Enter title for the Latest Products section.")
			)
		)
) );
}

//Templatatio Carousel products shortcode.
if ( is_woocommerce_activated() ) {
vc_map( array(
		"name" => __("Carousel Products"),
		"base" => "TT-carouselproducts",
		"category" => __('Added by JS'),
		'admin_enqueue_css' => array(get_template_directory_uri().'/includes/tt-vc-extend/tt-vc-extend.css'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display products in Carousel, BETA. Currently only shows Tabs.', 'templatation'),
		"icon" => "icon-wpb-tt_woocommerce",
		"params" => array(
			array(
				"type" => "dropdown",
				"heading" => __("Number of products"),
				"param_name" => "number_of_products",
				"value" => array(
					"2" => "2",
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"6" => "6",
					"7" => "7",
					"8" => "8",
					"9" => "9",
					"10" => "10",
				),
				"description" => __("Columns for products. Recommended : 3 if you have sidebar , 4 if page is full width. Default : 4")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Columns"),
				"param_name" => "columns",
				"value" => array(
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"2" => "2"
				),
				"description" => __("Columns for products. Recommended : 3 if you have sidebar , 4 if page is full width. Default : 4")
			),
		)
) );
}

//Templatation products categories shortcode. extending woocommerce's [product_categories] SC [TT-productcategories] Shortcode
if ( is_woocommerce_activated() ) {
vc_map( array(
		"name" => __("Product Categories"),
		"base" => "TT-productcategories",
		"category" => __('Added by JS'),
		'admin_enqueue_css' => array(get_template_directory_uri().'/includes/tt-vc-extend/tt-vc-extend.css'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display product categories loop.', 'templatation'),
		"icon" => "icon-wpb-tt_woocommerce",
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __("Number of Products"),
				"param_name" => "number",
				"value" => "",
				"description" => __("Enter Number of product to display. Enter only number please , eg 4, 6  Recommended : 4 for full width page")
			),
/*			array(
				"type" => "dropdown",
				"heading" => __("Order by"),
				"param_name" => "orderby",
				"value" => array(
					"title" => "title",
					"name" => "name",
					"date" => "date"
				),
				"description" => __("What parameter do you want products to order by.")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order"),
				"param_name" => "order",
				"value" => array(
					"ASC" => "ASC",
					"DESC" => "DESC"
				),
				"description" => __("Order products in ascending order or descending order ?")
			),
*/			array(
				"type" => "dropdown",
				"heading" => __("Columns"),
				"param_name" => "columns",
				"value" => array(
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"2" => "2"
				),
				"description" => __("Columns for products. Recommended : 3")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Hide Empty"),
				"param_name" => "hide_empty",
				"value" => array(
					"1" => "1",
					"0" => "0"
				),
				"description" => __("Do you want to hide empty categories. 1 for yes, 0 for no. Recommended : 1.")
			),
			array(
				"type" => "textfield",
				"heading" => __("Parent"),
				"param_name" => "parent",
				"value" => "0",
				"description" => __("Set the parent paramater to 0 to only display top level categories. Recommended : Leave blank.")
			),
			array(
				"type" => "textfield",
				"heading" => __("Ids"),
				"param_name" => "orderby",
				"value" => "",
				"description" => __("Display only categories with these ids. Give ids separated by comma. eg : 11,12,13.")
			)
		)
) );
}

//Templatation products category shortcode. extending woocommerce's [product_category] SC [TT-productcategory] Shortcode
if ( is_woocommerce_activated() ) {
vc_map( array(
		"name" => __("List Products"),
		"base" => "TT-productcategory",
		"category" => __('Added by JS'),
		'admin_enqueue_css' => array(get_template_directory_uri().'/includes/tt-vc-extend/tt-vc-extend.css'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Show multiple products in a category by slug.', 'templatation'),
		"icon" => "icon-wpb-tt_woocommerce",
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __("Per page"),
				"param_name" => "number",
				"value" => "12",
				"description" => __("Enter of Products per page.")
			),
/*			array(
				"type" => "dropdown",
				"heading" => __("Order by"),
				"param_name" => "orderby",
				"value" => array(
					"title" => "title",
					"name" => "name",
					"date" => "date"
				),
				"description" => __("What parameter do you want products to order by.")
			),
			array(
				"type" => "dropdown",
				"heading" => __("Order"),
				"param_name" => "order",
				"value" => array(
					"ASC" => "ASC",
					"DESC" => "DESC"
				),
				"description" => __("Order products in ascending order or descending order ?")
			),
*/			array(
				"type" => "dropdown",
				"heading" => __("Columns"),
				"param_name" => "columns",
				"value" => array(
					"3" => "3",
					"4" => "4",
					"5" => "5",
					"2" => "2"
				),
				"description" => __("Columns for products. Recommended : 3 if you have sidebar , 4 if page is full width.")
			),
			array(
				"type" => "textfield",
				"heading" => __("Category"),
				"param_name" => "category",
				"value" => "all",
				"description" => __("Enter slug of category that you want to show products of. ( Do not leave blank.)")
			)
		)
) );
}

//Testimonial shortcode
vc_map( array(
		"name" => __("Testimonial"),
		"base" => "templatation_testimonials",
		"category" => __('Added by JS'),
  	    "wrapper_class" => "clearfix",
	    "description" => __('Display Testimonials, data fetched from Testimonial custom post type.', 'templatation'),
		"icon" => "icon-wpb-layout_sidebar",
		"params" => array(
			array(
				"type" => "Checkbox",
				"class" => "",
				"heading" => __("Heading"),
				"param_name" => "title",
				"value" => "",
				"admin_label" => true,
				"description" => __("For heading , please use Heading component on this Visual composer.")
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Number of Testimonials"),
				"param_name" => "limit",
				"value" => "",
				"description" => __("Number of Testimonials to display. Enter only numbers eg 3, 4 etc")
			),
/*			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("List all ?"),
				"param_name" => "before", // manipulating this before args to add this demanded feature.
				"value" => array(
					"No" => "false",
					"Yes" => "true"
				),
				"description" => __("By default, testimonials are shown as a slider, select yes if you want to display all as blocks. Make sure there is enough space horizontally.")
			),*/
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Display Author"),
				"param_name" => "display_author",
				"value" => array(
					"Yes" => "true",
					"No" => "false"
				),
				"description" => __("")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Display Avatar"),
				"param_name" => "display_avatar",
				"value" => array(
					"Yes" => "true",
					"No" => "false"
				),
				"description" => __("")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Display URL"),
				"param_name" => "display_url",
				"value" => array(
					"Yes" => "true",
					"No" => "false"
				),
				"description" => __("")
			)

		)
) );


require_once ( 'easy-tables-vc/vc-table-manager.php' );