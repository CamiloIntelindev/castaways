<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyDeals;

 use \Inc\Base\General\BaseController;

 class MyDealController extends BaseController
 {
    public function register()
    {
      add_action( 'init', array( $this, 'register_deal' ) );
      add_action(	'init', array($this, 'register_taxomonies'));
      add_shortcode('deal-start_date', array($this, 'get_start_date'));
      add_shortcode('deal-end_date', array($this, 'get_end_date'));
      add_shortcode('deals', array($this, 'get_deals'));
      add_shortcode('deal_trips_categories_list', array($this, 'deal_categories_link'));
      add_action('wp_ajax_get_deals_by_taxonomy',array($this, 'deals_by_taxonomy'));
      add_action('wp_ajax_nopriv_get_deals_by_taxonomy', array($this, 'deals_by_taxonomy'));

            // Cache invalidation hooks for deals
            add_action('save_post_deal', array($this, 'on_deal_change'), 10, 3);
            add_action('deleted_post', array($this, 'on_post_deleted'), 10, 1);
            add_action('created_deal-category', array($this, 'on_term_change'), 10, 3);
            add_action('edited_deal-category', array($this, 'on_term_change'), 10, 3);
            add_action('delete_deal-category', array($this, 'on_term_change'), 10, 3);
            add_action('created_deal-destination', array($this, 'on_term_change'), 10, 3);
            add_action('edited_deal-destination', array($this, 'on_term_change'), 10, 3);
            add_action('delete_deal-destination', array($this, 'on_term_change'), 10, 3);
            add_action('created_deal-host-couple', array($this, 'on_term_change'), 10, 3);
            add_action('edited_deal-host-couple', array($this, 'on_term_change'), 10, 3);
            add_action('delete_deal-host-couple', array($this, 'on_term_change'), 10, 3);
            add_action('created_deal-name', array($this, 'on_term_change'), 10, 3);
            add_action('edited_deal-name', array($this, 'on_term_change'), 10, 3);
            add_action('delete_deal-name', array($this, 'on_term_change'), 10, 3);
    }

    //Register Post Type deal
    public function register_deal()
    {
        $labels = array(
            'name'                  => _x('Deals', 'Post type general name', 'castawaystravel'),
            'singular_name'         => _x('Deal', 'Post type singular name', 'castawaystravel'),
            'menu_name'             => _x('Deals', 'Admin Menu text', 'castawaystravel'),
            'name_admin_bar'        => _x('Deal', 'Add New on Toolbar', 'castawaystravel'),
            'add_new'               => __('Add New', 'castawaystravel'),
            'add_new_item'          => __('Add New Deal', 'castawaystravel'),
            'new_item'              => __('New Deal', 'castawaystravel'),
            'edit_item'             => __('Edit Deal', 'castawaystravel'),
            'view_item'             => __('View Deal', 'castawaystravel'),
            'all_items'             => __('All Deals', 'castawaystravel'),
            'search_items'          => __('Search Deals', 'castawaystravel'),
            'parent_item_colon'     => __('Parent Deals:', 'castawaystravel'),
            'not_found'             => __('No Deals found.', 'castawaystravel'),
            'not_found_in_trash'    => __('No Deals found in Trash.', 'castawaystravel'),
            'featured_image'        => _x('Deal Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'castawaystravel'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'castawaystravel'),
            'archives'              => _x('Deal archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'castawaystravel'),
            'insert_into_item'      => _x('Insert into Deal', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'castawaystravel'),
            'uploaded_to_this_item' => _x('Uploaded to this Deal', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'castawaystravel'),
            'filter_items_list'     => _x('Filter Deals list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'castawaystravel'),
            'items_list_navigation' => _x('Deals list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'castawaystravel'),
            'items_list'            => _x('Deals list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'castawaystravel'),
        );
	
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
			'show_in_rest' 		 => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'deal' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
        );
	
        register_post_type('deal', $args);
    }
	 
   public static function register_taxomonies() {

    $taxonomy_cat = [
        'labels' => [
            'name'          => __('Deals Categories', 'castawaystravel'),
            'singular_name' => __('Deal Category', 'castawaystravel'),
            'menu_name'     => __('Deals Categories', 'castawaystravel'),
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true, // Clave para editor de bloques
        'rewrite'           => [ 'slug' => 'deal-category' ],
    ];
    register_taxonomy( 'deal-category', 'deal', $taxonomy_cat );

    $taxonomy_destinations = [
        'labels' => [
            'name'          => __('Deals Destinations', 'castawaystravel'),
            'singular_name' => __('Deal Destination', 'castawaystravel'),
            'menu_name'     => __('Deals Destinations', 'castawaystravel'),
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'deal-destination' ],
    ];
    register_taxonomy( 'deal-destination', 'deal', $taxonomy_destinations );

    $taxonomy_host_couples = [
        'labels' => [
            'name'          => __('Deals Host Couples', 'castawaystravel'),
            'singular_name' => __('Deal Host Couple', 'castawaystravel'),
            'menu_name'     => __('Deals Host Couples', 'castawaystravel'),
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'deal-host-couple' ],
    ];
    register_taxonomy( 'deal-host-couple', 'deal', $taxonomy_host_couples );

    $taxonomy_deal_names = [
        'labels' => [
            'name'          => __('Deals Names', 'castawaystravel'),
            'singular_name' => __('Deal Name', 'castawaystravel'),
            'menu_name'     => __('Deals Names', 'castawaystravel'),
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'deal-name' ],
    ];
    register_taxonomy( 'deal-name', 'deal', $taxonomy_deal_names );

}

    /* === Simple transient caching helpers === */
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
        return 'ct_deals_' . $name . '_v' . self::cache_version() . '_' . $hash;
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

    public function on_deal_change($post_id, $post, $update){
        if (get_post_type($post_id) === 'deal') {
            self::bump_cache_version();
        }
    }

    public function on_post_deleted($post_id){
        if (get_post_type($post_id) === 'deal') {
            self::bump_cache_version();
        }
    }

    public function on_term_change(){
        self::bump_cache_version();
    }
	public function get_start_date() {
    $post_id = get_the_ID();
    $start_date = get_post_meta($post_id, 'wpcf-deal-start-date', true);

    $timestamp = is_numeric($start_date) ? (int) $start_date : strtotime($start_date);
    if (!$timestamp) {
        return '<div class="deal-dates-templates">' . esc_html__("Invalid date", 'castawaystravel') . '</div>';
    }

    $format_start_date = date("Y-m-d", $timestamp);
    return '<div class="deal-dates-templates">' . esc_html($format_start_date) . '</div>';
}


	 public function get_end_date() {
    $post_id = get_the_ID();
    $end_date = get_post_meta($post_id, 'wpcf-deal-end-date', true);

    $timestamp = is_numeric($end_date) ? (int) $end_date : strtotime($end_date);
    if (!$timestamp) {
        return '<div class="deal-dates-templates">' . esc_html__("Invalid date", 'castawaystravel') . '</div>';
    }

    $format_end_date = date("Y-m-d", $timestamp);
    return '<div class="deal-dates-templates">' . esc_html($format_end_date) . '</div>';
}

	 
   public function get_deals() {
    $start = microtime(true);
    $key = self::make_key('get_deals');
    $cached = self::cache_get($key);
    if ($cached) { self::debug_log('cache hit get_deals', $start, $key); return $cached; }
    $args = array(
        'post_type' => 'deal',
        'meta_key' => 'wpcf-deal-start-date',
        'orderby' => 'wpcf-deal-start-date',
        'posts_per_page' => -1,
        'order' => 'ASC',
    );

    $posts = get_posts($args);
    $get_deals = '<div class="group_trips-container">';

    foreach ($posts as $post) {
        $post_id = $post->ID;
        $post_url = esc_url(get_permalink($post_id));
        
		//$featured_image_url = esc_url(get_the_post_thumbnail_url($post_id, 'post-thumbnail'));
		$attachment_id = (int) get_post_meta($post_id, '_deal_logo_id', true);
    	$image_url     = get_post_meta($post_id, '_deal_logo_url', true);
		
        $title = esc_html(get_the_title($post_id));
        $start_date = get_post_meta($post_id, 'wpcf-deal-start-date', true);
        $end_date = get_post_meta($post_id, 'wpcf-deal-end-date', true);

        $get_deals .= '<div class="group_trips-item">';
        $get_deals .= '<div class="group_trips-item-image">';
        $get_deals .= '<a href="'.$post_url.'" class="deals-item-link">';
        $get_deals .= '<img src="'.esc_url($image_url).'">';
        $get_deals .= '</a>';
        $get_deals .= '</div>';

        $get_deals .= '<div class="group_trips-item-title">';
        $get_deals .= '<a href="'.$post_url.'" class="group_trips-item-link">';
        $get_deals .= '<h3>'.$title.'</h3>';
        $get_deals .= '</a>';
        $get_deals .= '</div>';

        $get_deals .= '<div class="group_trips-item-view">';
        $get_deals .= '<a href="'.$post_url.'" class="group_trips-item-link">';
        $get_deals .= '<span>' . esc_html__('View details', 'castawaystravel') . '</span>';
        $get_deals .= '</a>';
        $get_deals .= '</div>';
        $get_deals .= '</div>';
    }

    $get_deals .= '</div>';
    self::cache_set($key, $get_deals);
    self::debug_log('cache set get_deals', $start, $key);
    return $get_deals;
}

	 
	 public function deal_categories_link() {
    $start = microtime(true);
    $key = self::make_key('deal_categories_link');
    $cached = self::cache_get($key);
    if ($cached) { return $cached; }
    $terms = get_terms(array(
        'taxonomy' => 'deal-category',
        'hide_empty' => true,
    ));

    $group_trips_categories_link = '';
    if (!empty($terms) && !is_wp_error($terms)) {
        $group_trips_categories_link = '<div class="categories_link">';
        foreach ($terms as $term) {
            $term_link = get_term_link($term);
            if (!is_wp_error($term_link)) {
                $group_trips_categories_link .= '<div class="categories_link_item">';
                $group_trips_categories_link .= '<input type="button" id="term_id" name="term_id" class="get-deal" data-term-id="'.esc_attr($term->term_id).'" value="' . esc_attr($term->name) . '">';
                $group_trips_categories_link .= '</div>';
            }
        }
        $group_trips_categories_link .= '</div>';
    }

    self::cache_set($key, $group_trips_categories_link, 300);
    self::debug_log('cache set deal_categories_link', $start, $key);
    return $group_trips_categories_link;
}

	 
	 //
    public static function deals_by_taxonomy() {
    // Security: verify nonce and sanitize input
    check_ajax_referer('get_posts_by_taxonomy_nonce', 'nonce');
    $term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
    if ($term_id <= 0) { echo '<p>Invalid term.</p>'; return; }

    $start = microtime(true);
    $key = self::make_key('deals_by_taxonomy', array('term_id' => $term_id));
    $cached = self::cache_get($key);
    if ($cached) { echo $cached; self::debug_log('cache hit deals_by_taxonomy', $start, $key); return; }

    $args = array(
        'post_type' => 'deal',
        'meta_key' => 'wpcf-deal-start-date',
        'orderby' => 'wpcf-deal-start-date',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'deal-category',
                'field' => 'term_id',
                'terms' => $term_id,
            )
        )
    );

    $posts = get_posts($args);
    $get_group_trips = '';

    foreach ($posts as $post) {
        $post_id = $post->ID;
        $post_url = esc_url(get_permalink($post_id));
        $featured_image_url = esc_url(get_the_post_thumbnail_url($post_id, 'post-thumbnail'));
        $title = esc_html(get_the_title($post_id));
        $start_date = get_post_meta($post_id, 'wpcf-deal-start-date', true);
        $end_date = get_post_meta($post_id, 'wpcf-deal-end-date', true);
        $group_trip_names = get_the_terms($post_id, 'deal-name');
        $destination = get_the_terms($post_id, 'deal-destination');
        $hosts = get_the_terms($post_id, 'deal-host-couple');

        $start_timestamp = is_numeric($start_date) ? (int) $start_date : strtotime($start_date);
        $today_timestamp = strtotime(current_time('Y-m-d'));

        if ($start_timestamp > $today_timestamp) {
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

            if ($start_timestamp) {
                $get_group_trips .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">' . esc_html__('Start date:', 'castawaystravel') . ' </div><div class="group_trips-item-description-content">' . esc_html(wp_date("F d Y", $start_timestamp)) . '</div></div>';
            }

            $end_timestamp = is_numeric($end_date) ? (int) $end_date : strtotime($end_date);
            if ($end_timestamp) {
                $get_group_trips .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">' . esc_html__('End date', 'castawaystravel') . '</div><div class="group_trips-item-description-content">' . esc_html(wp_date("F d Y", $end_timestamp)) . '</div></div>';
            }

            if (!empty($group_trip_names) && is_array($group_trip_names)) {
                $get_group_trips .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">' . esc_html__('Deal Name', 'castawaystravel') . '</div><div class="group_trips-item-description-content">';
                foreach ($group_trip_names as $term) {
                    $term_url = esc_url(get_term_link($term->term_id, 'deal-name'));
                    $get_group_trips .= '<a href="'.$term_url.'">'.esc_html($term->name).'</a> ';
                }
                $get_group_trips .= '</div></div>';
            }

            if (!empty($destination) && is_array($destination)) {
                $get_group_trips .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">' . esc_html__('Destinations', 'castawaystravel') . '</div><div class="group_trips-item-description-content">';
                foreach ($destination as $term_dest) {
                    $term_url_dest = esc_url(get_term_link($term_dest->term_id, 'deal-destination'));
                    $get_group_trips .= '<a href="'.$term_url_dest.'">'.esc_html($term_dest->name).'</a> ';
                }
                $get_group_trips .= '</div></div>';
            }

            if (!empty($hosts) && is_array($hosts)) {
                $get_group_trips .= '<div class="group_trips-item-description-item"><div class="group_trips-item-description-title">' . esc_html__('Hosts', 'castawaystravel') . '</div><div class="group_trips-item-description-content">';
                foreach ($hosts as $term_host) {
                    $term_url_host = esc_url(get_term_link($term_host->term_id, 'deal-host-couple'));
                    $get_group_trips .= '<a href="'.$term_url_host.'">'.esc_html($term_host->name).'</a> ';
                }
                $get_group_trips .= '</div></div>';
            }

            $get_group_trips .= '</div>';
            $get_group_trips .= '<div class="group_trips-item-view"><a href="'.$post_url.'" class="group_trips-item-link"><span>View details</span></a></div>';
            $get_group_trips .= '</div>';
        }
    }

    self::cache_set($key, $get_group_trips);
    self::debug_log('cache set deals_by_taxonomy', $start, $key);
    echo $get_group_trips;
}

	 //
	 
	 
 }