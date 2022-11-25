<?php

/**
 * WP REST API custom routes & callbacks.
 *
 * @package WordPress
 * @subpackage critick
 */

declare( strict_types = 1 );

add_action( 'rest_api_init', 'fd_create_route' );
/**
 * Create custom routes & endpoints.
 *
 * @return void
 */
function fd_create_route(): void
{
	register_rest_route( 'jwt-auth/v1', '/fd', [
		[
			'methods'				=> 'GET',
			'permission_callback'	=> function(){
				return is_user_logged_in();
			},
			'callback'				=> 'fd_get_products',
			'args'					=> [
				'limit'	=> [
					'default'	=> 20,
					'required'	=> null
				],
				'page'	=> [
					'default'	=> 1,
					'required'	=> null
				]
			]
		]
	] );
}

/**
 * @param WP_REST_Request $request
 * @return array
 */
function fd_get_products( WP_REST_Request $request ): array
{
	if( empty( $request->get_params() ) )
		return [( new WP_Error( 'no_params', 'No params in request', ['status' => 404] ) )];

	return fd_get_products_from_db( $request->get_params() );
}

/**
 * @param array $params
 * @return array
 */
function fd_get_products_from_db( array $params ): array
{
	global $wpdb;
	$table = $wpdb->prefix . 'posts';

	$query = "SELECT * FROM `" . $table . "` WHERE";
	$limit	= ! empty( $params['limit'] ) ? ( int ) $params['limit'] : 20;
	$page	= ! empty( $params['page'] ) ? ( int ) $params['page'] : 1;
	$offset	= $limit * $page - $limit;
	$query	.= " LIMIT $limit OFFSET $offset";

	return $wpdb->get_results( $wpdb->prepare( $query ) );
}

