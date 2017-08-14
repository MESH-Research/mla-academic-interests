<?php

class Mla_Academic_Interests_REST_Controller extends WP_REST_Controller {

	/**
	 * Constructor.
	 *
	 * @since alpha
	 * @access public
	 */
	public function __construct() {
		$this->namespace = 'mla-academic-interests/v1';
		$this->rest_base = '/terms';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @since alpha
	 * @access public
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, [
			'methods' => 'GET',
			'callback' => [ $this, 'get_terms' ],
		] );
	}

	/**
	 * Get full list of terms.
	 *
	 * @return WP_REST_Response
	 */
	public function get_terms( $data ) {
		global $mla_academic_interests;

		$response = new WP_REST_Response;

		$response->set_data( $mla_academic_interests->mla_academic_interests_list() );

		return $response;
	}
}
