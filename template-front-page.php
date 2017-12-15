<?php
/**
 *
 * Template Name: Front Page
 *
 * Description: Custom page template for showcasing the brand on the front of the site
 *
 * @author Brubaker Design Services
 * @link    http://design.brubakerministries.org
 *
 */

// Add landing page body class to the head.
add_filter( 'body_class', 'genesis_sample_add_body_class' );
function genesis_sample_add_body_class( $classes ) {

	$classes[] = 'front-page';

	return $classes;

}

// Add Custom content
add_action( 'genesis_entry_content', 'bds_front_page_content' );

function bds_front_page_content() {
  ?>

  <!-- Add custom HTML code here to build the page -->

  <?php
}

// Run the Genesis loop.
genesis();
