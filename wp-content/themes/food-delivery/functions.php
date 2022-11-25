<?php

/**
 * Theme functions.
 *
 * @package WordPress
 * @subpackage critick
 */

declare( strict_types = 1 );

add_action( 'after_setup_theme', 'critick_load_theme_dependencies' );
/**
 * Theme dependencies.
 */
function critick_load_theme_dependencies(): void
{
	// Please place all custom functions declarations in this file.
	require_once( 'theme-functions/theme-functions.php' );
	// Please place all custom WP REST API routes in this file.
	require_once( 'theme-functions/api.php' );
}

add_action( 'init', 'critick_init_theme' );
/**
 * Theme initialization.
 */
function critick_init_theme(): void
{
	// Remove extra styles and default SVG tags.
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

	// Enable post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Custom image sizes.
	// add_image_size( 'full-hd', 1920, 0, 1 );
}

add_action( 'wp_enqueue_scripts', 'critick_inclusion_enqueue' );
/**
 * Enqueue styles and scripts.
 */
function critick_inclusion_enqueue(): void
{
	// Remove Gutenberg styles on front-end.
	if( ! is_admin() ){
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
	}
}

