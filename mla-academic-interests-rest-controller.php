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
	 * Does $user_input match $term?
	 * This function provides a single place to define business logic for what constitutes a match.
	 * e.g. case insensitivity, prioritize beginning of term vs. middle/end, etc.
	 *
	 * @return bool
	 */
	public function _is_match( $user_input, $term ) {
		$match = false;

		if ( false !== strpos( strtolower( $term ), strtolower( $user_input ) ) ) {
			$match = true;
		}

		return $match;
	}

	/**
	 * Get list of terms that match $_GET['q']
	 *
	 * @return WP_REST_Response
	 */
	public function get_terms( $data ) {
		global $mla_academic_interests;

		$params = $data->get_query_params();

		$user_input = $params['q'];

		$all_terms = $mla_academic_interests->mla_academic_interests_list();

		// formatted for select2 consumption
		$matched_terms = [
			'results' => []
		];

		foreach ( $all_terms as $term_id => $term ) {
			if ( $this->_is_match( $user_input, $term ) ) {
				$matched_term = new stdClass;
				$matched_term->id = $term_id;
				$matched_term->text = $term;

				$matched_terms['results'][] = $matched_term;
			}
		}

		$response = new WP_REST_Response;

		$response->set_data( $matched_terms );

		return $response;
	}
}
