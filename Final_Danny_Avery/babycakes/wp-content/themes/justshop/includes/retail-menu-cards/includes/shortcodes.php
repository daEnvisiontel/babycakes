<?php

function rmc_display_menu($atts, $content = null) {
	
	global $rmc_options;
	
	extract(shortcode_atts(array(
		'menu' => '',
		'heading' => '',
		'thumblink' => '',
		'titlelink' => '',
		'layout' => '',
		'look' => ''
	), $atts));


	if ($look == '') {
		$rmc_look = $rmc_options['look'];
	} else {
		$rmc_look = $look;
	}
	
	if ($layout == '') {
		$rmc_layout = $rmc_options['layout'];
	} else {
		$rmc_layout = $layout;
	}
	
	if ($thumblink == '') {
		$rmc_thumblink = $rmc_options['thumblink'];
	} else {
		$rmc_thumblink = $thumblink;
	}
	
	if ($titlelink == '') {
		$rmc_titlelink = $rmc_options['titlelink'];
	} else {
		$rmc_titlelink = $titlelink;
	}
	
	// if there's no menu attribute, get top level cards only
	if ($menu) $args = 'include=' . $menu;
	else $args = 'parent=0&include=' . $menu;
	
	// get cards
	$cards = get_terms('rmc_menu_card', $args);
	
	// if there are cards ...
	if (!empty($cards)) {
		
		$content .= '<div class="rmc-menu-wrap rmc-menu-' . $rmc_look . ' rmc-menu-' . $rmc_layout . '">';
		
		// parse cards and ...
		foreach ($cards as $i => $card) {
			$content .= '<div class="rmc-menu rmc-menu-' . $card->term_id . '">';
			if ($heading != 'no') $content .= '<h2>' . $card->name . '</h2>';
			$description = term_description($card->term_id, 'rmc_menu_card');
			$content .= $description;	
			
			// ... get subcards of the current card
			$subcards = get_terms('rmc_menu_card', 'child_of=' . $card->term_id);
			
			// if current card has subcards ...
			if (!empty($subcards)) {
				// ... parse subcards and list items from the subcard
				foreach ($subcards as $j => $subcard) {
					if ($heading != 'no') $content .= '<h3>' . $subcard->name . '</h3>';
					$description = term_description($subcard->term_id, 'rmc_menu_card');
					$content .= $description;								
					$content .= rmc_list_items($subcard, $rmc_thumblink, $rmc_titlelink);					
				}
			// if thre are no subcards, list items from the card
			} else {
				$content .= rmc_list_items($card, $rmc_thumblink, $rmc_titlelink);
			}		
			
			$content .= '</div>'; // .rmc-menu
		}
		$content .= '</div>';
	}	
	
	return $content;	
}


// this function lists items from a given card
function rmc_list_items($card, $thumblink, $titlelink) {
	
	global $rmc_options;
	
	// get all items from the given card
	$items = get_posts('posts_per_page=-1&post_type=rmc_menu_item&rmc_menu_card=' . $card->slug . '&orderby=date&order=ASC');
	global $post;
	$temp = $post;
	
	// if there are items in this card ...
	if ($items) {
		$content  ='';
		$content .= '<ul>';
		
		// ... parse items and ...
		foreach ($items as $post) {
			setup_postdata($post);
			
			// ... get description and price for the current item
			$description = get_post_meta($post->ID, 'rmc_description', true);
			$price = get_post_meta($post->ID, 'rmc_price', true);
			$labels = get_the_terms($post->ID, 'rmc_menu_label');
			
			// ... display item thumb, title and details
			$content .= '<li><div>';
				
				// if thumbnail is not hidden
				if (!$rmc_options['nothumb']) {
			
					// if thumbnail should be a link ...
					if ($thumblink != 'none') {
						// ... show thumb with link to large image or to post
						if ($thumblink == 'image') {
							$thumburl = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
							$thumburl = $thumburl[0];
						} else $thumburl = get_post_permalink();
						$content .= '<a rel="lightbox" href="' . $thumburl . '" title="' . get_the_title() . '">' . get_the_post_thumbnail($post->ID, array(100,100)) . '</a>';
					} else {
						// ... otherwise just show thumbnail
						$content .= get_the_post_thumbnail($post->ID, 'thumbnail');
					}
					
				}
				
				// create title with or without link
				if ($titlelink != 'none') {
					if ($titlelink == 'image') {
						$titleurl = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
						$titleurl = $titleurl[0];
					} else $titleurl = get_post_permalink();
					$title = '<a href="' . $titleurl . '">' . get_the_title() . '</a>';
				} else $title = get_the_title();
				
				// show title and price
				$content .= '<div class="head">';						
				$content .= '<p class="title">' . $title . '</p>';
				// if price is not hidden and there's a price added, display price
				if ((!$rmc_options['noprice']) and ($price != '')) {
					if ($rmc_options['position'] == 1) $price = $price . $rmc_options['currency'];
					else $price = $rmc_options['currency'] . $price;
					$content .= '<p class="price">' . $price . '</p>';
				}
				$content .= '</div>';
				
				// if description is not hidden and there's a description added, display description						
				if ((!$rmc_options['nodesc']) and ($description != '')) $content .= '<p class="description">' . $description . '</p>';
				
				// show labels, if any	
				if ((!$rmc_options['nolabels']) and !empty($labels)) {
					$content .= '<p class="labels">';
					foreach ($labels as $label)
						$content .= '<span class="rmc-label-' . $label->slug . '">' . $label->name . '</span>';
					$content .= '</p>';
				}			
			$content .= '</div></li>';		
		}
		$content .= '</ul>';
	}
	return $content;
}

?>