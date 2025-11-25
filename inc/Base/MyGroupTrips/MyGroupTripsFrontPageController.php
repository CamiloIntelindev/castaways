<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyGroupTrips;

 use \Inc\Base\General\BaseController;

 class MyGroupTripsFrontPageController extends BaseController
{
	public function register(){
		add_shortcode('group_trips_front_page', array($this, 'get_group_trips_by_term'));
	}
	
	public function get_group_trips_by_term($atts) {
		$atts = shortcode_atts(array(
			'term' => 'bliss-cruises', // valor por defecto
		), $atts);

		$term_slug = sanitize_title($atts['term']);

		$args = array(
			'post_type'      => 'group-trip',
			'meta_key'       => 'wpcf-start-date',
			'orderby'        => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'group-trip-category',
					'field'    => 'slug',
					'terms'    => $term_slug,
				)
			)
		);

		$posts = get_posts($args);
		$output = '<div class="group_trips-container">';

		foreach ($posts as $post) {
			$post_id            = $post->ID;
			$post_url           = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title             = get_the_title($post_id);
			$start_date        = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date          = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names  = get_the_terms($post_id, 'group-trip-name');
			$destination       = get_the_terms($post_id, 'destination');
			$hosts            = get_the_terms($post_id, 'host-couple');

			// ✅ Validación segura para PHP 8.3
			$start_date_ts = is_numeric($start_date) ? intval($start_date) : 0;
			$end_date_ts   = is_numeric($end_date) ? intval($end_date) : 0;

			if ($start_date_ts > current_time('timestamp')) {
				$output .= '<div class="group_trips-item">';

				$output .= '<div class="group_trips-item-image"><a href="' . esc_url($post_url) . '"><img src="' . esc_url($featured_image_url) . '"></a></div>';

				$output .= '<div class="group_trips-item-title"><a href="' . esc_url($post_url) . '"><h3>' . esc_html($title) . '</h3></a></div>';

				$output .= '<div class="group_trips-item-description-container">';

				// Start date
				$output .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">Start date:</div><div class="group_trips-item-description-content">' . wp_date("F d Y", $start_date_ts) . '</div></div>';

				// End date
				$output .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">End date:</div><div class="group_trips-item-description-content">' . wp_date("F d Y", $end_date_ts) . '</div></div>';

				// Group Trip Name
				$output .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">Group Trip Name</div><div class="group_trips-item-description-content">';
				if ($group_trip_names && !is_wp_error($group_trip_names)) {
					foreach ($group_trip_names as $term) {
						$output .= '<a href="' . esc_url($post_url) . '">' . esc_html($term->name) . '</a>';
					}
				}
				$output .= '</div></div>';

				// Destination
				$output .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">Destinations</div><div class="group_trips-item-description-content">';
				if ($destination && !is_wp_error($destination)) {
					foreach ($destination as $term) {
						$output .= '<a href="' . esc_url($post_url) . '">' . esc_html($term->name) . '</a>';
					}
				}
				$output .= '</div></div>';

				// Hosts
				$output .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">Hosts</div><div class="group_trips-item-description-content">';
				if ($hosts && !is_wp_error($hosts)) {
					foreach ($hosts as $term) {
						$output .= '<a href="' . esc_url($post_url) . '">' . esc_html($term->name) . '</a>';
					}
				}
				$output .= '</div></div>';

				$output .= '</div>'; // .group_trips-item-description-container
				$output .= '</div>'; // .group_trips-item
			}
		}

		$output .= '</div>'; // .group_trips-container

		return $output;
	}


	 
	 	 
}