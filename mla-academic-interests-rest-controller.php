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
	 * Get list of terms that match $_GET['q']
	 *
	 * @return WP_REST_Response
	 */
	public function get_terms( $data ) {
		global $mla_academic_interests;

		$start_time = microtime();

		$params = $data->get_query_params();

		$user_input = $params['q'];

		$response = new WP_REST_Response;

		$cache_key = 'mla_academic_interests_terms_' . sanitize_title( $user_input );

		$matched_terms = wp_cache_get( $cache_key );

		if ( ! $matched_terms ) {

			$all_terms = $mla_academic_interests->mla_academic_interests_list();

			$matched_terms = [];

			// populate array of matches
			foreach ( $all_terms as $term_id => $term ) {
				if ( false !== strpos( strtolower( $term ), strtolower( $user_input ) ) ) {
					$matched_term = new stdClass;
					$matched_term->id = $term_id;
					$matched_term->text = $term;

					$matched_terms[] = $matched_term;
				}
			}

			// prioritize matches with the first letters of a term by moving them to the front of the array
			foreach ( $matched_terms as $i => $matched_term ) {
				if ( 0 === strpos( strtolower( $matched_term->text ), strtolower( $user_input ) ) ) {
					unset( $matched_terms[ $i ] );
					array_unshift( $matched_terms, $matched_term );
				}
			}

			// put exact matches above everything else
			foreach ( $matched_terms as $i => $matched_term ) {
				if ( strtolower( $matched_term->text ) === strtolower( $user_input ) ) {
					unset( $matched_terms[ $i ] );
					array_unshift( $matched_terms, $matched_term );
				}
			}

			wp_cache_set( $cache_key, $matched_terms );

		}

		// formatted for select2 consumption
		$response->set_data( [
			'results' => $matched_terms,
			//'time' => microtime() - $start_time, // for debugging
		] );

		return $response;
	}
}
