<?php
/**
 *
 * @package WordPress
 * @subpackage templatation_Testimonials
 * @author Matty
 * @since 1.0.0
 */

require_once( 'classes/class-woothemes-testimonials.php' );
require_once( 'woothemes-testimonials-template.php' );
require_once( 'classes/class-woothemes-widget-testimonials.php' );
global $templatation_testimonials;
$templatation_testimonials = new templatation_Testimonials( __FILE__ );
?>