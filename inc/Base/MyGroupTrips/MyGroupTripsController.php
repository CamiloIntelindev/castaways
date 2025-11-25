<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyGroupTrips;

 use \Inc\Base\General\BaseController;

 class MyGroupTripsController extends BaseController
{
	public function register(){
		add_action( 'init', array( $this, 'register_group_trip' ) );
		add_action(	'init', array($this, 'register_taxomonies'));
		add_shortcode('start_date', array($this, 'get_start_date'));
		add_shortcode('end_date', array($this, 'get_end_date'));
		add_shortcode('group_trips', array($this, 'get_group_trips'));
		
		add_action('wp_ajax_get_all_posts',array($this, 'get_group_trips_all'));
		add_action('wp_ajax_nopriv_get_all_posts', array($this, 'get_group_trips_all'));
		
		
		add_shortcode('bliss_cruises', array($this, 'get_group_trips_bliss_cruises'));
		add_shortcode('nude_cruises', array($this, 'get_group_trips_nude_cruises'));
		add_shortcode('group-trips-calendar', array($this, 'get_group_trips_calendar'));
		add_shortcode('group_trips_calendar_sidebar', array($this, 'get_group_trips_calendar_sidebar'));
		add_shortcode('get_group_trips_categories_list', array($this, 'get_group_trips_categories_link'));
		add_shortcode('group_trips_vanilla_cruises', array($this, 'get_group_trips_vanilla_cruises'));
		
		
		add_action('wp_ajax_get_posts_by_taxonomy',array($this, 'group_trips_by_taxonomy'));
		add_action('wp_ajax_nopriv_get_posts_by_taxonomy', array($this, 'group_trips_by_taxonomy'));
		
		add_action('wp_ajax_get_posts_by_destination',array($this, 'group_trips_by_destination'));
		add_action('wp_ajax_nopriv_get_posts_by_destination', array($this, 'group_trips_by_destination'));

		add_action('wp_ajax_get_posts_by_host_couple',array($this, 'group_trips_by_host_couple'));
		add_action('wp_ajax_nopriv_get_posts_by_host_couple', array($this, 'group_trips_by_host_couple'));

		
		
		add_shortcode('menu_hosted_group_trips', array($this, 'get_menu_hosted_group_trips'));
		add_shortcode('menu_hosted_group_trips_and_resorts', array($this, 'get_menu_hosted_group_trips_and_resorts'));
		add_shortcode( 'menu_exotic_group_trips', array($this, 'get_menu_exotic_trips_and_resorts') );
		
		add_action('wp_head', array($this, 'agregar_meta_noindex_archives'), 1);
		
		add_shortcode('group_trips_exclude', [$this, 'get_group_trips_exclude_terms_shortcode']);

		// Cache invalidation hooks
		add_action('save_post_group-trip', array($this, 'on_group_trip_change'), 10, 3);
		add_action('deleted_post', array($this, 'on_post_deleted'), 10, 1);
		add_action('created_group-trip-category', array($this, 'on_term_change'), 10, 3);
		add_action('edited_group-trip-category', array($this, 'on_term_change'), 10, 3);
		add_action('delete_group-trip-category', array($this, 'on_term_change'), 10, 3);
		add_action('created_destination', array($this, 'on_term_change'), 10, 3);
		add_action('edited_destination', array($this, 'on_term_change'), 10, 3);
		add_action('delete_destination', array($this, 'on_term_change'), 10, 3);
		add_action('created_host-couple', array($this, 'on_term_change'), 10, 3);
		add_action('edited_host-couple', array($this, 'on_term_change'), 10, 3);
		add_action('delete_host-couple', array($this, 'on_term_change'), 10, 3);
		add_action('created_group-trip-name', array($this, 'on_term_change'), 10, 3);
		add_action('edited_group-trip-name', array($this, 'on_term_change'), 10, 3);
		add_action('delete_group-trip-name', array($this, 'on_term_change'), 10, 3);

	}
	 
	 
	 public function agregar_meta_noindex_archives() {
		if (is_post_type_archive('group-trip')) {
			echo '<meta name="robots" content="index, follow">' . "\n";
		}
	}
	 
	 



	//Register Post Type group-trip
	public function register_group_trip(){
		 $labels = array(
			 'name'                  => _x('Group trips', 'Post type general name', 'castawaystravel'),
			 'singular_name'         => _x('Group trip', 'Post type singular name', 'castawaystravel'),
			 'menu_name'             => _x('Group trips', 'Admin Menu text', 'castawaystravel'),
			 'name_admin_bar'        => _x('Group trip', 'Add New on Toolbar', 'castawaystravel'),
			 'add_new'               => __('Add New', 'castawaystravel'),
			 'add_new_item'          => __('Add New Group trip', 'castawaystravel'),
			 'new_item'              => __('New Group trip', 'castawaystravel'),
			 'edit_item'             => __('Edit Group trip', 'castawaystravel'),
			 'view_item'             => __('View Group trip', 'castawaystravel'),
			 'all_items'             => __('All Group trips', 'castawaystravel'),
			 'search_items'          => __('Search Group trips', 'castawaystravel'),
			 'parent_item_colon'     => __('Parent Group trips:', 'castawaystravel'),
			 'not_found'             => __('No Group trips found.', 'castawaystravel'),
			 'not_found_in_trash'    => __('No Group trips found in Trash.', 'castawaystravel'),
			 'featured_image'        => _x('Group trip Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'castawaystravel'),
			 'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
			 'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
			 'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
			 'archives'              => _x('Group trip archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'castawaystravel'),
			 'insert_into_item'      => _x('Insert into Group trip', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'castawaystravel'),
			 'uploaded_to_this_item' => _x('Uploaded to this Group trip', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'castawaystravel'),
			 'filter_items_list'     => _x('Filter Group trips list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'castawaystravel'),
			 'items_list_navigation' => _x('Group trips list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'castawaystravel'),
			 'items_list'            => _x('Group trips list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'castawaystravel'),
		 );

		 $args = array(
			 'labels'             => $labels,
			 'public'             => true,
			 'publicly_queryable' => true,
			 'show_ui'            => true,
			 'show_in_menu'       => true,
			 'show_in_rest' 		=> true,
			 'query_var'          => true,
			 'rewrite'            => array( 'slug' => 'group-trip' ),
			 'capability_type'    => 'post',
			 'has_archive'        => true,
			 'hierarchical'       => false,
			 'menu_position'      => null,
			 'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields')
		 );

		 register_post_type('group-trip', $args);
	}

	/* Caching helpers */
	protected static function cache_version(){
		$v = get_option('castaways_cache_v');
		return $v ? (int) $v : 1;
	}

	protected static function bump_cache_version(){
		$v = self::cache_version();
		update_option('castaways_cache_v', $v + 1, false);
	}

	protected static function make_key($name, $args = array()){
		$hash = md5(wp_json_encode($args));
		return 'ct_' . $name . '_v' . self::cache_version() . '_' . $hash;
	}

	protected static function cache_get($key){
		return get_transient($key);
	}

	protected static function cache_set($key, $value, $ttl = 600){
		set_transient($key, $value, $ttl);
	}

	protected static function debug_log($label, $start, $key){
		if (defined('CASTAWAYS_DEBUG') && CASTAWAYS_DEBUG) {
			$elapsed = round((microtime(true) - $start) * 1000);
			error_log('[Castaways] ' . $label . ' key=' . $key . ' ' . $elapsed . 'ms');
		}
	}

	public function on_group_trip_change($post_id, $post, $update){
		if (get_post_type($post_id) === 'group-trip') {
			self::bump_cache_version();
		}
	}

	public function on_post_deleted($post_id){
		if (get_post_type($post_id) === 'group-trip') {
			self::bump_cache_version();
		}
	}

	public function on_term_change(){
		self::bump_cache_version();
	}
	 
	public static function register_taxomonies() {
    
    $taxonomy_cat = [
        'labels' => [
            'name' => 'Group Trips Categories',
            'singular_name' => 'Group Trip Category',
            'menu_name' => 'Group Trips Categories',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => [ 'slug' => 'group-trip-category' ],
    ];
    register_taxonomy( 'group-trip-category', 'group-trip', $taxonomy_cat );

    $taxonomy_destinations = [
        'labels' => [
            'name' => 'Group Trips Destinations',
            'singular_name' => 'Group Trip Destination',
            'menu_name' => 'Group Trips Destinations',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => [ 'slug' => 'destination' ],
    ];
    register_taxonomy( 'destination', 'group-trip', $taxonomy_destinations );

    $taxonomy_host_couples = [
        'labels' => [
            'name' => 'Group Trips Host Couple',
            'singular_name' => 'Group Trip Host Couple',
            'menu_name' => 'Group Trips Host Couple',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => [ 'slug' => 'host-couple' ],
    ];
    register_taxonomy( 'host-couple', 'group-trip', $taxonomy_host_couples );

    $taxonomy_group_trip_names = [
        'labels' => [
            'name' => 'Group Trips Names',
            'singular_name' => 'Group Trip Name',
            'menu_name' => 'Group Trips Names',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => [ 'slug' => 'group-trip-name' ],
    ];
    register_taxonomy( 'group-trip-name', 'group-trip', $taxonomy_group_trip_names );
}

	public function get_start_date(){
		 global $wpdb;
		$post_id = get_the_ID();
		$start_date = get_post_meta($post_id, 'wpcf-start-date', true);

		// Verifica si la fecha es válida y conviértela a timestamp
		$timestamp = is_numeric($start_date) ? (int) $start_date : strtotime($start_date);

		// Verifica si se pudo obtener un timestamp válido
		if ($timestamp === false) {
			return '<div class="group-trip-dates-templates">Invalid date</div>';
		}

		$format_start_date = date("Y-m-d", $timestamp);
		return '<div class="group-trip-dates-templates">' . esc_html($format_start_date) . '</div>';
	}
	
	public function get_end_date() {
		global $wpdb;
		$post_id = get_the_ID();
		$end_date = get_post_meta($post_id, 'wpcf-end-date', true);

		// Verifica si la fecha es válida y conviértela a timestamp
		$timestamp = is_numeric($end_date) ? (int) $end_date : strtotime($end_date);

		// Verifica si se pudo obtener un timestamp válido
		if ($timestamp === false) {
			return '<div class="group-trip-dates-templates">Invalid date</div>';
		}

		$format_end_date = date("Y-m-d", $timestamp);
		return '<div class="group-trip-dates-templates">' . esc_html($format_end_date) . '</div>';
	}

	
	public function get_group_trips(){
		$start = microtime(true);
		$key = self::make_key('get_group_trips');
		$cached = self::cache_get($key);
		if ($cached) { self::debug_log('cache hit get_group_trips', $start, $key); return $cached; }
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip',
			'meta_key' => 'wpcf-start-date',
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC',
		);
		$posts = get_posts($args);
		$get_group_trips = '<div class="group_trips-container">';
			foreach($posts as $post){
				$post_id = $post->ID;
				$post_url = get_the_permalink($post_id);
				$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
				$title =  get_the_title($post_id);
				$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
				$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
				$group_trip_names = get_the_terms($post_id, 'group-trip-name');
				$destination = get_the_terms($post_id, 'destination');
				$hosts = get_the_terms($post_id, 'host-couple');
				
				if (strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d"))) {
					$get_group_trips .= '<div class="group_trips-item">';

					$get_group_trips .= '<div class="group_trips-item-image">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<img src="'.$featured_image_url.'">';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-title">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<h3>'.$title.'</h3>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-description-container">';
					// Start date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= esc_html__('Start date:', 'castawaystravel');
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= esc_html(wp_date("F d Y", $start_date));
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// End date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= esc_html__('End date', 'castawaystravel');
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= esc_html(wp_date("F d Y", $end_date));
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Group Trip Name
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= esc_html__('Group Trip Name', 'castawaystravel');
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					if ($group_trip_names && !is_wp_error($group_trip_names)) {
						foreach ($group_trip_names as $term) {
							$term_url = get_term_link($term->term_id, 'group-trip-name');
							$get_group_trips .= '<a href="'.esc_url($term_url).'">'.esc_html($term->name).'</a>';
						}
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Destination
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= esc_html__('Destinations', 'castawaystravel');
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					
					if ($destination && !is_wp_error($destination)) {
						foreach ($destination as $term_dest) {
							$term_url_dest = get_term_link($term_dest->term_id, 'destination');
							//$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ';
							$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_attr($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
						}
					}
					
					
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Host
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= esc_html__('Hosts', 'castawaystravel');
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					if ($hosts && !is_wp_error($hosts)) {
						foreach ($hosts as $term_host) {
							$term_url_host = get_term_link($term_host->term_id, 'host-couple');
							//$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ';
							$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_attr($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
						}
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-view">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<span>' . esc_html__('View details', 'castawaystravel') . '</span>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
				}
			}
		$get_group_trips .= '</div>'; // Corrected container closure
		self::cache_set($key, $get_group_trips);
		self::debug_log('cache set get_group_trips', $start, $key);
		return $get_group_trips;
	}
	 
	 //
	public function get_group_trips_all(){
		$start = microtime(true);
		$key = self::make_key('get_group_trips_all');
		$cached = self::cache_get($key);
		if ($cached) { echo $cached; self::debug_log('cache hit get_group_trips_all', $start, $key); return; }
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip',
			'meta_key' => 'wpcf-start-date',
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC',
		);
		// Security: verify AJAX nonce
		if (defined('DOING_AJAX') && DOING_AJAX) {
			check_ajax_referer('get_posts_by_taxonomy_nonce', 'nonce');
		}
		$posts = get_posts($args);
		$get_group_trips = '<div class="group_trips-container">';
			foreach($posts as $post){
				$post_id = $post->ID;
				$post_url = get_the_permalink($post_id);
				$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
				$title =  get_the_title($post_id);
				$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
				$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
				$group_trip_names = get_the_terms($post_id, 'group-trip-name');
				$destination = get_the_terms($post_id, 'destination');
				$hosts = get_the_terms($post_id, 'host-couple');

				if (strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d"))) {
					$get_group_trips .= '<div class="group_trips-item">';

					$get_group_trips .= '<div class="group_trips-item-image">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<img src="'.$featured_image_url.'">';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-title">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<h3>'.$title.'</h3>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-description-container">';
					// Start date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Start date: ';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= wp_date("F d Y", $start_date);
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// End date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'End date';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= wp_date("F d Y", $end_date);
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Group Trip Name
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Group Trip Name';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					foreach ($group_trip_names as $term) {
						$term_url = get_term_link($term->term_id, 'group-trip-name');
						$get_group_trips .= '<a href="'.$post_url.'">'.$term->name.'</a>';
						
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Destination
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Destinations';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					foreach ($destination as $term_dest) {
						$term_url_dest = get_term_link($term_dest->term_id, 'destination');
						//$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ';
						$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Host
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Hosts';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					foreach ($hosts as $term_host) {
						$term_url_host = get_term_link($term_host->term_id, 'host-couple');
						//$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ';
						$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-view">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<span>View details</span>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
				}
			}
		$get_group_trips .= '</div>'; // Corrected container closure
		self::cache_set($key, $get_group_trips);
		self::debug_log('cache set get_group_trips_all', $start, $key);
		echo $get_group_trips;
	}
	 //
	 
	 

	public function get_group_trips_bliss_cruises(){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category', // Replace with your actual taxonomy name
					'field' => 'slug', // You can also use 'id' or 'term_id'
					'terms' => 'bliss-cruises', // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		$get_group_trips_homepage = '<div class="group_trips-homepage-container">';
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips_homepage .= '<div class="group_trips-homepage-item">';
				$get_group_trips_homepage .= '<a href="'.get_the_permalink($post_id).'">';
					$get_group_trips_homepage .= '<div class="group_trips-homepage-item-image">';
						$get_group_trips_homepage .= '<img src="'.$featured_image_url.'">';
					$get_group_trips_homepage .= '</div>';
					$get_group_trips_homepage .= '<div class="group_trips-homepage-item-text">';
						$get_group_trips_homepage .= '<h3>'.$title.'</h3>';
						$get_group_trips_homepage .= '<span>View details</span>';
					$get_group_trips_homepage .= '</div>';
				$get_group_trips_homepage .= '</a>';
				$get_group_trips_homepage .= '</div>';
			}
		}
		$get_group_trips_homepage .= '</div>';
		return $get_group_trips_homepage;
	}
	 
	 public function get_group_trips_vanilla_cruises() {
    $today = current_time('timestamp');

    $args = [
        'post_type'      => 'group-trip',
        'posts_per_page' => -1,
        'tax_query'      => [[
            'taxonomy'         => 'group-trip-category',
            'field'            => 'slug',
            'terms'            => ['vanilla-cruises'],
            'include_children' => false,
            'operator'         => 'IN',
        ]],
        'meta_key'       => 'wpcf-start-date',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => [[
            'key'     => 'wpcf-start-date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ]],
    ];

    $posts = get_posts($args);

    if (empty($posts)) {
        return '<div class="group_trips-container"><p>No upcoming trips.</p></div>';
    }

    $html = '<div class="group_trips-container">';

    foreach ($posts as $post) {
        $post_id  = $post->ID;
        $post_url = get_permalink($post_id);
        $img_url  = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
        $title    = get_the_title($post_id);

        $start_ts = (int) get_post_meta($post_id, 'wpcf-start-date', true);
        $end_ts   = (int) get_post_meta($post_id, 'wpcf-end-date', true);

        $names = get_the_terms($post_id, 'group-trip-name');
        $dests = get_the_terms($post_id, 'destination');
        $hosts = get_the_terms($post_id, 'host-couple');

        $names = (!is_wp_error($names) && is_array($names)) ? $names : [];
        $dests = (!is_wp_error($dests) && is_array($dests)) ? $dests : [];
        $hosts = (!is_wp_error($hosts) && is_array($hosts)) ? $hosts : [];

        $html .= '<div class="group_trips-item">';

        // Imagen
        $html .= '<div class="group_trips-item-image">';
        $html .= '<a class="group_trips-item-link" href="'.esc_url($post_url).'">';
        if ($img_url) {
            $html .= '<img src="'.esc_url($img_url).'" alt="'.esc_attr($title).'">';
        }
        $html .= '</a></div>';

        // Título
        $html .= '<div class="group_trips-item-title">';
        $html .= '<a class="group_trips-item-link" href="'.esc_url($post_url).'">';
        $html .= '<h3>'.esc_html($title).'</h3>';
        $html .= '</a></div>';

        // Descripciones
        $html .= '<div class="group_trips-item-description-container">';

        // Start date
        $html .= '<div class="group_trips-item-description-item">';
		$html .= '<div class="group_trips-item-description-title">' . esc_html__('Start date:', 'castawaystravel') . '</div>';
        $html .= '<div class="group_trips-item-description-content">';
        $html .= $start_ts ? esc_html(wp_date('F d Y', $start_ts)) : '-';
        $html .= '</div></div>';

        // End date
        $html .= '<div class="group_trips-item-description-item">';
		$html .= '<div class="group_trips-item-description-title">' . esc_html__('End date:', 'castawaystravel') . '</div>';
        $html .= '<div class="group_trips-item-description-content">';
        $html .= $end_ts ? esc_html(wp_date('F d Y', $end_ts)) : '-';
        $html .= '</div></div>';

        // Group Trip Name
        $html .= '<div class="group_trips-item-description-item">';
		$html .= '<div class="group_trips-item-description-title">' . esc_html__('Group Trip Name', 'castawaystravel') . '</div>';
        $html .= '<div class="group_trips-item-description-content">';
        foreach ($names as $t) {
            $term_link = get_term_link($t);
            if (!is_wp_error($term_link)) {
                $html .= '<a href="'.esc_url($term_link).'">'.esc_html($t->name).'</a> ';
            }
        }
        $html .= '</div></div>';

        // Destinations
        $html .= '<div class="group_trips-item-description-item">';
		$html .= '<div class="group_trips-item-description-title">' . esc_html__('Destinations', 'castawaystravel') . '</div>';
        $html .= '<div class="group_trips-item-description-content">';
        foreach ($dests as $t) {
            $html .= '<div class="get-posts-destination link-decoration" data-term-id="'.esc_attr($t->term_id).'">'.esc_html($t->name).'</div>';
        }
        $html .= '</div></div>';

        // Hosts
        $html .= '<div class="group_trips-item-description-item">';
		$html .= '<div class="group_trips-item-description-title">' . esc_html__('Hosts', 'castawaystravel') . '</div>';
        $html .= '<div class="group_trips-item-description-content">';
        foreach ($hosts as $t) {
            $html .= '<div class="get-posts-host_couple link-decoration" data-term-id="'.esc_attr($t->term_id).'">'.esc_html($t->name).'</div>';
        }
        $html .= '</div></div>';

        // Botón
        $html .= '<div class="group_trips-item-view">';
		$html .= '<a class="group_trips-item-link" href="'.esc_url($post_url).'"><span>' . esc_html__('View details', 'castawaystravel') . '</span></a>';
        $html .= '</div>';

        // cierres
        $html .= '</div>'; // description-container
        $html .= '</div>'; // item
    }

    $html .= '</div>'; // container
    return $html;
}

	
	public function get_group_trips_nude_cruises(){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category', // Replace with your actual taxonomy name
					'field' => 'slug', // You can also use 'id' or 'term_id'
					'terms' => 'nude-cruises', // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		$get_group_trips_homepage = '<div class="group_trips-homepage-container">';
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				if($post_url != 'https://castawaystravel.com/group-trip/caribbean-cruise-by-temptation-february-2025/'){
					$get_group_trips_homepage .= '<div class="group_trips-homepage-item">';
					$get_group_trips_homepage .= '<a href="'.get_the_permalink($post_id).'">';
						$get_group_trips_homepage .= '<div class="group_trips-homepage-item-image">';
							$get_group_trips_homepage .= '<img src="'.$featured_image_url.'">';
						$get_group_trips_homepage .= '</div>';
						$get_group_trips_homepage .= '<div class="group_trips-homepage-item-text">';
							$get_group_trips_homepage .= '<h3>'.$title.'</h3>';
							$get_group_trips_homepage .= '<span>View details</span>';
						$get_group_trips_homepage .= '</div>';
					$get_group_trips_homepage .= '</a>';
					$get_group_trips_homepage .= '</div>';
				}
			}
		}
		$get_group_trips_homepage .= '</div>';
		return $get_group_trips_homepage;
	}
	
	public function get_group_trips_calendar(){
		$start = microtime(true);
		$key = self::make_key('get_group_trips_calendar');
		$cached = self::cache_get($key);
		if ($cached) { return $cached; }
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
		);
		$posts = get_posts($args);
		$get_group_trips = '<div class="group_trips_calendar_container" >';
		$get_group_trips .= '<table class="group_trips_calendar">';
		
		$get_group_trips .= '<tr>';
		
			$get_group_trips .= '<th>';
				$get_group_trips .= 'Star date';
			$get_group_trips .= '</th>';
		
			$get_group_trips .= '<th>';
				$get_group_trips .= 'End date';
			$get_group_trips .= '</th>';
		
			$get_group_trips .= '<th>';
				$get_group_trips .= 'Trip name';
			$get_group_trips .= '</th>';
		
			$get_group_trips .= '<th>';
				$get_group_trips .= 'Destinations';
			$get_group_trips .= '</th>';
		
			$get_group_trips .= '<th>';
				$get_group_trips .= 'Host couple';
			$get_group_trips .= '</th>';
			
		$get_group_trips .= '</tr>';
		
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<tr>';
		
				$get_group_trips .= '<td>';
					$get_group_trips .= wp_date("F d Y", $start_date);;
				$get_group_trips .= '</td>';

				$get_group_trips .= '<td>';
					$get_group_trips .= wp_date("F d Y", $end_date);;
				$get_group_trips .= '</td>';

				$get_group_trips .= '<td>';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
						$get_group_trips .= '<spam>'.$title.'</spam>';
					$get_group_trips .= '</a>';
				$get_group_trips .= '</td>';

				$get_group_trips .= '<td>';
					foreach($destination as $term_dest){
						$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
						//$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
						$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
						}
				$get_group_trips .= '</td>';

				$get_group_trips .= '<td>';
					foreach($hosts as $term_host){
						$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
						$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
					}
				$get_group_trips .= '</td>';

			$get_group_trips .= '</tr>';
			}//end if
			}//end foreach 
			
		$get_group_trips .= '</table>';
		$get_group_trips .= '</div>';
		self::cache_set($key, $get_group_trips);
		self::debug_log('cache set get_group_trips_calendar', $start, $key);
		return $get_group_trips;
	}

	public function get_group_trips_calendar_sidebar(){
		$start = microtime(true);
		$key = self::make_key('get_group_trips_calendar_sidebar');
		$cached = self::cache_get($key);
		if ($cached) { return $cached; }
    global $wpdb;
    $args = array(
        'post_type' => 'group-trip', // Replace with your actual post type
        'meta_key' => 'wpcf-start-date', // Replace with your meta field key
        'orderby' => 'wpcf-start-date',
        'posts_per_page' => -1,
        'order' => 'ASC', // Change to 'ASC' for ascending order
    );
    $posts = get_posts($args);
    $get_group_trips = '<div class="group_trips_calendar_container" >';
    $get_group_trips .= '<h2 style="font-size: 20px; text-align: center;">Group Trips Calendar</h2>';
    $get_group_trips .= '<table class="group_trips_calendar">';
		
    foreach($posts as $post){
        $post_id = $post->ID;
        $post_url = get_the_permalink($post_id);
        $featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
        $title =  get_the_title( $post_id);
        $start_date = get_post_meta($post_id, 'wpcf-start-date', true);
        $end_date = get_post_meta($post_id, 'wpcf-end-date', true);
        $group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
        $destination = get_the_terms( $post_id, 'destination' );
        $hosts = get_the_terms( $post_id, 'host-couple' );

        if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
            $get_group_trips .= '<tr class="group_trips_calendar-sidebar">';
                $get_group_trips .= '<td>';
                    $get_group_trips .= '<div>';
                        $get_group_trips .= '<div class="group_trips_calendar-content">';
                            $get_group_trips .= '<label>Start date</label>';
                            $get_group_trips .= wp_date("F d Y", $start_date);
                        $get_group_trips .= '</div>';
                    $get_group_trips .= '</div>';

                    $get_group_trips .= '<div>';
                        $get_group_trips .= '<div class="group_trips_calendar-content">';
                            $get_group_trips .= '<label>End date</label>';
                            $get_group_trips .= wp_date("F d Y", $end_date);
                        $get_group_trips .= '</div>';
                    $get_group_trips .= '</div>';
                $get_group_trips .= '</td>';
				

                $get_group_trips .= '<td>';
                    $get_group_trips .= '<div class="group_trips_calendar-content">';
                        $get_group_trips .= '<label>Trip name</label>';
                        $get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
                            $get_group_trips .= '<spam>'.$title.'</spam>';
                        $get_group_trips .= '</a>';
                    $get_group_trips .= '</div>';
                $get_group_trips .= '</td>';

                $get_group_trips .= '<td>';
                    $get_group_trips .= '<div>';
                        $get_group_trips .= '<div class="group_trips_calendar-content">';
                            $get_group_trips .= '<label>Destinations</label>';
                                $get_group_trips .= '<ul>';
                            foreach($destination as $term_dest){
                                $term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
                                    $get_group_trips .= '<li>';
                                        //$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>' ;
                                        $get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
                                    $get_group_trips .= '</li>';
                                }
                            $get_group_trips .= '</ul>';
                        $get_group_trips .= '</div>';
                    $get_group_trips .= '</div>';
                $get_group_trips .= '</td>';

                $get_group_trips .= '<td>';
                    $get_group_trips .= '<div>';
                    $get_group_trips .= '<div class="group_trips_calendar-content">';
                        $get_group_trips .= '<label>Host couple</label>';
                            
                            $get_group_trips .= '<ul>';
                                foreach($hosts as $term_host){
                                    $term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
                                    $get_group_trips .= '<li>';
                                        //$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>' ;
                                        $get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
                                    $get_group_trips .= '</li>';
                                }
                            $get_group_trips .= '</ul>';
                        $get_group_trips .= '</div>';
                    $get_group_trips .= '</div>';
                $get_group_trips .= '</td>';
				
        $get_group_trips .= '</tr>';
				
        }//end if
        }//end foreach 
			
    $get_group_trips .= '</table>';
    $get_group_trips .= '</div>';
	self::cache_set($key, $get_group_trips);
	self::debug_log('cache set get_group_trips_calendar_sidebar', $start, $key);
	return $get_group_trips;
}
	/*
	public function get_group_trips_categories_link(){
		$custom_post_type_slug = 'group-trip';

		$terms = get_terms(array(
			'taxonomy' => 'group-trip-category',
			'hide_empty' => true, // Cambia a true si solo quieres mostrar categorías con posts
		));
		
		
		if (!empty($terms) && !is_wp_error($terms)) {
		$group_trips_categories_link =  '<div class = "categories_link" >';
			
			$group_trips_categories_link .=  '<div class = "categories_link_item" >';
				$group_trips_categories_link .='<input type="button" class="all"  id="all" name="all" value="All">';
			$group_trips_categories_link .= '</div>';
			
			$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					//$group_trips_categories_link .='<img class="calendar" src="https://castawaystravel.com/wp-content/uploads/2024/12/mi-noun-calendar-large-label.png">';
					$group_trips_categories_link .='<input type="button" value="Calendar view" class="calendar">';
			$group_trips_categories_link .= '</div>';
			
		foreach ($terms as $term) {
			$term_link = get_term_link($term);
			if (!is_wp_error($term_link)) {
				$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					$group_trips_categories_link .='<input type="button" id="term_id"  name="term_id" class="get-posts" data-term-id="'.esc_html($term->term_id).'" value="' . esc_html($term->name) . '">';
					
				$group_trips_categories_link .= '</div>';
			}
		}
			$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					$group_trips_categories_link .='<a style="background-color: #2b7bb9; color: #fff;font-size: 16px; line-height: 1.2; padding: 6px 12px; font-weight: 400; text-shadow: none; border: 1px solid #1f5a87; -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; min-height: 60px; display: flex;cjustify-content: end; align-items: center;" href="https://castawaystravel.com/group-trip/south-africa-escorted-naturist-safaris/"  >South Africa Safari</a>';
			$group_trips_categories_link .= '</div>';
			
		$group_trips_categories_link .= '</div>';
		} 
		return $group_trips_categories_link;
	}*/
	public function get_group_trips_categories_link(){
		$start = microtime(true);
		$key = self::make_key('get_group_trips_categories_link');
		$cached = self::cache_get($key);
		if ($cached) { return $cached; }
    $terms = get_terms(array(
        'taxonomy' => 'group-trip-category',
        'hide_empty' => true,
    ));

    $group_trips_categories_link = '<div class="categories_link">';

    $group_trips_categories_link .= '<div class="categories_link_item">';
    $group_trips_categories_link .= '<input type="button" class="all" id="all" name="all" value="All">';
    $group_trips_categories_link .= '</div>';

    $today = time();

    foreach ($terms as $term) {
        $has_active_posts = new \WP_Query(array(
            'post_type'      => 'group-trip',
            'posts_per_page' => 1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'group-trip-category',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'meta_query'     => array(
                array(
                    'key'     => 'wpcf-start-date',
                    'value'   => $today,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        ));

        if ($has_active_posts->have_posts()) {
            $group_trips_categories_link .= '<div class="categories_link_item">';
            $group_trips_categories_link .= '<input type="button" id="term_id" name="term_id" class="get-posts" data-term-id="' . esc_attr($term->term_id) . '" value="' . esc_html($term->name) . '">';
            $group_trips_categories_link .= '</div>';
        }

        wp_reset_postdata();
    }

    $group_trips_categories_link .= '</div>';

	self::cache_set($key, $group_trips_categories_link, 300);
	self::debug_log('cache set get_group_trips_categories_link', $start, $key);
	return $group_trips_categories_link;
}

	public static function group_trips_by_taxonomy(){
		// Security: verify AJAX nonce and sanitize input
		check_ajax_referer('get_posts_by_taxonomy_nonce', 'nonce');
		$term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
		if ($term_id <= 0) { echo '<p>Invalid term.</p>'; return; }
		$start = microtime(true);
		$key = self::make_key('group_trips_by_taxonomy', array('term_id' => (int) $term_id));
		$cached = self::cache_get($key);
		if ($cached) { echo $cached; self::debug_log('cache hit group_trips_by_taxonomy', $start, $key); return; }
		$args = array(
			'post_type' => 'group-trip',
			'meta_key' => 'wpcf-start-date',
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category',
					'field' => 'term_id',
					'terms' => $term_id,
				)
			)
		);

		$posts = get_posts($args);
		$get_group_trips = ''; // ✅ Inicializa variable para evitar warning

		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title = get_the_title($post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names = get_the_terms($post_id, 'group-trip-name');
			$destination = get_the_terms($post_id, 'destination');
			$hosts = get_the_terms($post_id, 'host-couple');

			if(strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d"))){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';

				// Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">Start date:</div>';
				$get_group_trips .= '<div class="group_trips-item-description-content">'.wp_date("F d Y", $start_date).'</div>';
				$get_group_trips .= '</div>';

				// End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">End date</div>';
				$get_group_trips .= '<div class="group_trips-item-description-content">'.wp_date("F d Y", $end_date).'</div>';
				$get_group_trips .= '</div>';

				// Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">Group Trip Name</div>';
				$get_group_trips .= '<div class="group_trips-item-description-content">';
				if (!empty($group_trip_names) && !is_wp_error($group_trip_names)) {
					foreach($group_trip_names as $term){
						$get_group_trips .= '<a href="'.$post_url.'">'.$term->name.'</a>';
					}
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				// Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">Destinations</div>';
				$get_group_trips .= '<div class="group_trips-item-description-content">';
				if (!empty($destination) && !is_wp_error($destination)) {
					foreach($destination as $term_dest){
						$term_url_dest = get_term_link($term_dest->term_id, 'destination');
						$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ';
					}
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				// Hosts
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">Hosts</div>';
				$get_group_trips .= '<div class="group_trips-item-description-content">';
				if (!empty($hosts) && !is_wp_error($hosts)) {
					foreach($hosts as $term_host){
						$get_group_trips .= '<div id="term_id" name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'">'.esc_html($term_host->name).'</div>';
					}
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>'; // group_trips-item-description-container

				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link"><span>View details</span></a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>'; // group_trips-item
			}
		}

		if (empty($get_group_trips)) {
			echo '<p>No upcoming trips found.</p>';
		} else {
			self::cache_set($key, $get_group_trips);
			self::debug_log('cache set group_trips_by_taxonomy', $start, $key);
			echo $get_group_trips;
		}
	}

	 
	public static function group_trips_by_destination(){
		// Security: verify AJAX nonce and sanitize input
		check_ajax_referer('get_posts_by_taxonomy_nonce', 'nonce');
		$term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
		if ($term_id <= 0) { echo '<p>Invalid term.</p>'; return; }
		$start = microtime(true);
		$key = self::make_key('group_trips_by_destination', array('term_id' => (int) $term_id));
		$cached = self::cache_get($key);
		if ($cached) { echo $cached; self::debug_log('cache hit group_trips_by_destination', $start, $key); return; }
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'destination', // Replace with your actual taxonomy name
					'field' => 'term_id', // You can also use 'id' or 'term_id'
					'terms' => $term_id, // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';
				//Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Start date: ';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $start_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				//End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'End date';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $end_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Group Trip Name';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($group_trip_names as $term){
					$term_url = get_term_link( $term->term_id, 'group-trip-name' );
					$get_group_trips .= '<a href="'.$post_url.'">'.$term->name.'</a>' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Destinations';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($destination as $term_dest){
					$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
					//$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
					$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Host
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Hosts';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($hosts as $term_host){
					$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
					//$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
					$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<span>View details</span>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>';
			}
			//$get_group_trips .= '</div>';
		}
		self::cache_set($key, $get_group_trips);
		self::debug_log('cache set group_trips_by_destination', $start, $key);
		echo($get_group_trips);
	}

	public static function group_trips_by_host_couple(){
		// Security: verify AJAX nonce and sanitize input
		check_ajax_referer('get_posts_by_taxonomy_nonce', 'nonce');
		$term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
		if ($term_id <= 0) { echo '<p>Invalid term.</p>'; return; }
		$start = microtime(true);
		$key = self::make_key('group_trips_by_host', array('term_id' => (int) $term_id));
		$cached = self::cache_get($key);
		if ($cached) { echo $cached; self::debug_log('cache hit group_trips_by_host', $start, $key); return; }
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'host-couple', // Replace with your actual taxonomy name
					'field' => 'term_id', // You can also use 'id' or 'term_id'
					'terms' => $term_id, // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';
				//Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Start date: ';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $start_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				//End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'End date';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $end_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Group Trip Name';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($group_trip_names as $term){
					$term_url = get_term_link( $term->term_id, 'group-trip-name' );
					$get_group_trips .= '<a href="'.$post_url.'">'.$term->name.'</a>' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Destinations';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($destination as $term_dest){
					$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
					//$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
					$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Host
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Hosts';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($hosts as $term_host){
					$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
					//$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
					$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<span>View details</span>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>';
			}
			//$get_group_trips .= '</div>';
		}
		self::cache_set($key, $get_group_trips);
		self::debug_log('cache set group_trips_by_host', $start, $key);
		echo($get_group_trips);
	}

	//get_menu_hosted_group_trips_categories_link
	public function get_menu_hosted_group_trips_categories_link(){
		$custom_post_type_slug = 'group-trip';

		$terms = get_terms(array(
			'taxonomy' => 'group-trip-category', // Reemplaza con el nombre real de tu taxonomía
			'hide_empty' => true, // Cambia a true si solo quieres mostrar categorías con posts
			'slug' => array('bliss-cruises', 'nude-cruises', 'bucket-list-cruises', 'galapagos-cruise'), // Los slugs de los términos que quieres obtener
		));
		
		
		if (!empty($terms) && !is_wp_error($terms)) {
		$group_trips_categories_link =  '<div class = "categories_link" >';
		foreach ($terms as $term) {
			$term_link = get_term_link($term);
			if (!is_wp_error($term_link)) {
				$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					$group_trips_categories_link .='<input type="button" id="term_id"  name="term_id" class="get-posts" data-term-id="'.esc_html($term->term_id).'" value="' . esc_html($term->name) . '">';

				$group_trips_categories_link .= '</div>';
			}
		}
		$group_trips_categories_link .= '</div>';
		} 
		return $group_trips_categories_link;
	}

	public function get_menu_hosted_group_trips(){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category', // Replace with your actual taxonomy name
					'field' => 'slug', // You can also use 'id' or 'term_id'
					'terms' => array('bliss-cruises', 'nude-cruises', 'bucket-list-cruises', 'galapagos-cruise'), // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		
		$get_group_trips = $this->get_menu_hosted_group_trips_categories_link();

		$get_group_trips .= '<div class="group_trips-container">';
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';
				//Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Start date: ';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $start_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				//End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'End date';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $end_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Group Trip Name';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($group_trip_names as $term){
					$term_url = get_term_link( $term->term_id, 'group-trip-name' );
					$get_group_trips .= '<a href="'.$term_url.'">'.$term->name.'</a>' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Destinations';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($destination as $term_dest){
					$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
					$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Host
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Hosts';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($hosts as $term_host){
					$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
					$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<span>View details</span>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>';
			}
			$get_group_trips .= '</div">';
		}//end if start date is bigger than current
		return $get_group_trips;
	}
	 
	 public function get_menu_hosted_group_trips_and_resorts_categories_link(){
		$custom_post_type_slug = 'group-trip';

		$terms = get_terms(array(
			'taxonomy' => 'group-trip-category', // Reemplaza con el nombre real de tu taxonomía
			'hide_empty' => true, // Cambia a true si solo quieres mostrar categorías con posts
			'slug' => array('hedonism', 'hidden-beach', 'desire', 'temptation'), // Los slugs de los términos que quieres obtener
		));
		
		
		if (!empty($terms) && !is_wp_error($terms)) {
		$group_trips_categories_link =  '<div class = "categories_link" >';
		foreach ($terms as $term) {
			$term_link = get_term_link($term);
			if (!is_wp_error($term_link)) {
				$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					$group_trips_categories_link .='<input type="button" id="term_id"  name="term_id" class="get-posts" data-term-id="'.esc_html($term->term_id).'" value="' . esc_html($term->name) . '">';
					//$group_trips_categories_link .= '<input type="button" id="term_id" name="term_id" class="get-posts" data-term-id="5743" value="Bliss Cruises">';
				$group_trips_categories_link .= '</div>';
			}
		}
		$group_trips_categories_link .= '</div>';
		} 
		return $group_trips_categories_link;
	}

	public function get_menu_hosted_group_trips_and_resorts(){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category', // Replace with your actual taxonomy name
					'field' => 'slug', // You can also use 'id' or 'term_id'
					'terms' => array('hedonism', 'hidden-beach', 'desire', 'temptation'), // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		$get_group_trips = $this->get_menu_hosted_group_trips_and_resorts_categories_link();
		$get_group_trips .= '<div class="group_trips-container">';
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';
				//Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Start date: ';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $start_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				//End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'End date';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $end_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Group Trip Name';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($group_trip_names as $term){
					$term_url = get_term_link( $term->term_id, 'group-trip-name' );
					$get_group_trips .= '<a href="'.$term_url.'">'.$term->name.'</a>' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Destinations';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($destination as $term_dest){
					$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
					$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Host
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Hosts';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($hosts as $term_host){
					$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
					$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<span>View details</span>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>';
			}
			$get_group_trips .= '</div">';
		}//end if start date is bigger than current
		return $get_group_trips;
	}
	 
	 	//get_menu_hosted_group_trips_categories_link
	public function get_menu_exotic_trips_and_resorts_categories_link(){
		$custom_post_type_slug = 'group-trip';

		$terms = get_terms(array(
			'taxonomy' => 'group-trip-category', // Reemplaza con el nombre real de tu taxonomía
			'hide_empty' => true, // Cambia a true si solo quieres mostrar categorías con posts
			'slug' => array('other-group-trips'), // Los slugs de los términos que quieres obtener
		));
		
		
		if (!empty($terms) && !is_wp_error($terms)) {
		$group_trips_categories_link =  '<div class = "categories_link" >';
		foreach ($terms as $term) {
			$term_link = get_term_link($term);
			if (!is_wp_error($term_link)) {
				$group_trips_categories_link .=  '<div class = "categories_link_item" >';
					$group_trips_categories_link .='<input type="button" id="term_id"  name="term_id" class="get-posts" data-term-id="'.esc_html($term->term_id).'" value="' . esc_html($term->name) . '">';
					//$group_trips_categories_link .= '<input type="button" id="term_id" name="term_id" class="get-posts" data-term-id="5743" value="Bliss Cruises">';
				$group_trips_categories_link .= '</div>';
			}
		}
		$group_trips_categories_link .= '</div>';
		} 
		return $group_trips_categories_link;
	}

	public function get_menu_exotic_trips_and_resorts(){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip', // Replace with your actual post type
			'meta_key' => 'wpcf-start-date', // Replace with your meta field key
			'orderby' => 'wpcf-start-date',
			'posts_per_page' => -1,
			'order' => 'ASC', // Change to 'ASC' for ascending order
			'tax_query' => array(
				array(
					'taxonomy' => 'group-trip-category', // Replace with your actual taxonomy name
					'field' => 'slug', // You can also use 'id' or 'term_id'
					'terms' => array('other-group-trips'), // Replace with the term slug you want to match
				)
			)

		);
		$posts = get_posts($args);
		
		$get_group_trips = $this->get_menu_exotic_trips_and_resorts_categories_link();
		
		$get_group_trips .= '<div class="group_trips-container">';
		foreach($posts as $post){
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
			$title =  get_the_title( $post_id);
			$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
			$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
			$group_trip_names =  get_the_terms( $post_id, 'group-trip-name' );
			$destination = get_the_terms( $post_id, 'destination' );
			$hosts = get_the_terms( $post_id, 'host-couple' );

			if( strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d")) ){
				$get_group_trips .= '<div class="group_trips-item">';

				$get_group_trips .= '<div class="group_trips-item-image">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<img src="'.$featured_image_url.'">';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-title">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<h3>'.$title.'</h3>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-container">';
				//Start date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Start date: ';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $start_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';

				//End date
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'End date';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				$get_group_trips .= wp_date("F d Y", $end_date);
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Group Trip Name
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Group Trip Name';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($group_trip_names as $term){
					$term_url = get_term_link( $term->term_id, 'group-trip-name' );
					$get_group_trips .= '<a href="'.$term_url.'">'.$term->name.'</a>' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Destination
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Destinations';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($destination as $term_dest){
					$term_url_dest = get_term_link( $term_dest->term_id, 'destination' );
					$get_group_trips .= '<a href="'.$term_url_dest.'">'.$term_dest->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				//Host
				$get_group_trips .= '<div class="group_trips-item-description-item">';
				$get_group_trips .= '<div class="group_trips-item-description-title">';
				$get_group_trips .= 'Hosts';
				$get_group_trips .= '</div>';

				$get_group_trips .= '<div class="group_trips-item-description-content">';
				foreach($hosts as $term_host){
					$term_url_host = get_term_link( $term_host->term_id, 'host-couple' );
					$get_group_trips .= '<a href="'.$term_url_host.'">'.$term_host->name.'</a>, ' ;
				}
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '</div>';
				$get_group_trips .= '<div class="group_trips-item-view">';
				$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
				$get_group_trips .= '<span>View details</span>';
				$get_group_trips .= '</a>';
				$get_group_trips .= '</div>';

				$get_group_trips .= '</div>';
			}
			$get_group_trips .= '</div">';
		}//end if start date is bigger than current
		return $get_group_trips;
	}
	 
	 // Group trips excludind categories
	public function get_group_trips_exclude_terms_shortcode($atts = []) {
		$atts = shortcode_atts([
			'exclude_terms' => '', // slugs separados por comas
		], $atts, 'group_trips_exclude');

		$exclude_terms = array_map('trim', explode(',', $atts['exclude_terms']));

		return $this->get_group_trips_exclude($exclude_terms);
	}

	public function get_group_trips_exclude($exclude_terms = array()){
		global $wpdb;
		$args = array(
			'post_type' => 'group-trip',
			'posts_per_page' => -1,
			'meta_key' => 'wpcf-start-date',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		);

		if (!empty($exclude_terms)) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'group-trip-category',
					'field'    => 'slug',
					'terms'    => $exclude_terms,
					'operator' => 'NOT IN',
				),
			);
		}

		$posts = get_posts($args);
		$get_group_trips = '<div class="group_trips-container">';
			foreach($posts as $post){
				$post_id = $post->ID;
				$post_url = get_the_permalink($post_id);
				$featured_image_url = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
				$title =  get_the_title($post_id);
				$start_date = get_post_meta($post_id, 'wpcf-start-date', true);
				$end_date = get_post_meta($post_id, 'wpcf-end-date', true);
				$group_trip_names = get_the_terms($post_id, 'group-trip-name');
				$destination = get_the_terms($post_id, 'destination');
				$hosts = get_the_terms($post_id, 'host-couple');

				if (strtotime(wp_date("Y-m-d", $start_date)) > strtotime(wp_date("Y-m-d"))) {
					$get_group_trips .= '<div class="group_trips-item">';

					$get_group_trips .= '<div class="group_trips-item-image">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<img src="'.$featured_image_url.'">';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-title">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<h3>'.$title.'</h3>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-description-container">';
					// Start date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Start date: ';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= wp_date("F d Y", $start_date);
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// End date
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'End date';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					$get_group_trips .= wp_date("F d Y", $end_date);
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Group Trip Name
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Group Trip Name';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					if ($group_trip_names && !is_wp_error($group_trip_names)) {
						foreach ($group_trip_names as $term) {
							$get_group_trips .= '<a href="'.$post_url.'">'.$term->name.'</a>';
						}
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Destination
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Destinations';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					if ($destination && !is_wp_error($destination)) {
						foreach ($destination as $term_dest) {
							$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-destination link-decoration" data-term-id="'.esc_html($term_dest->term_id).'"> ' . esc_html($term_dest->name) . '</div>';
						}
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					// Host
					$get_group_trips .= '<div class="group_trips-item-description-item">';
					$get_group_trips .= '<div class="group_trips-item-description-title">';
					$get_group_trips .= 'Hosts';
					$get_group_trips .= '</div>';
					$get_group_trips .= '<div class="group_trips-item-description-content">';
					if ($hosts && !is_wp_error($hosts)) {
						foreach ($hosts as $term_host) {
							$get_group_trips .='<div id="term_id"  name="term_id" class="get-posts-host_couple link-decoration" data-term-id="'.esc_html($term_host->term_id).'"> ' . esc_html($term_host->name) . '</div>';
						}
					}
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';

					$get_group_trips .= '<div class="group_trips-item-view">';
					$get_group_trips .= '<a href="'.$post_url.'" class="group_trips-item-link">';
					$get_group_trips .= '<span>View details</span>';
					$get_group_trips .= '</a>';
					$get_group_trips .= '</div>';
					$get_group_trips .= '</div>';
				}
			}
		$get_group_trips .= '</div>';
		return $get_group_trips;
	}


}