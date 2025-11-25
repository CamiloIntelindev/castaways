<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyGroupTrips;

 use \Inc\Base\General\BaseController;

 class MyGroupTripsCustomFields extends BaseController
 {
    public function register()
    {
        add_action( 'add_meta_boxes', array($this,'add_custom_fields' ));
        add_action( 'save_post' , array($this , 'save_group_trips'), 1, 2 );
		add_shortcode( 'pages_by_slug',array($this,'shortcode_pages_by_slug') );

    }
    /*
    *   ==========================================
    *       DateTime Class
    *   ==========================================
    */

    function add_custom_fields(){
		
		$post_type = 'group-trip';
        $context = 'advanced';
        $priority = 'high';

        add_meta_box('wpcf-start-date', 'Start date', array($this,'start_date'), $post_type,  $context, $priority);
       	add_meta_box('wpcf-end-date', 'End date', array($this,'end_date'), $post_type,  $context, $priority);
		add_meta_box('wpcf-book-now-url', 'Book url', array($this,'book_url'), $post_type,  $context, $priority);
    }


    /*
    *   ==========================================
    *       Add Start date field
    *   ==========================================
    */
	function start_date($post) {
		global $wpdb;
		$post_id = get_the_ID();
		$start_date = get_post_meta($post_id, 'wpcf-start-date', true);

		if (!empty($start_date) && is_numeric($start_date)) {
			$format_start_date = date("Y-m-d", intval($start_date));
		} else {
			$format_start_date = '';
		}

		if ($start_date) {
			echo '<div class="fields_container">';
				echo '<div class="fields_item">Update?</div>';
				echo '<div class="fields_item">';
					echo '<input type="date" name="wpcf-start-date" id="wpcf-start-date" value="' . $format_start_date . '" />';
				echo '</div>';
			echo '</div>';
		} else {
			echo '<input type="date" name="wpcf-start-date" id="wpcf-start-date" />';
		}

		echo wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
	}



    /*
    *   ==========================================
    *       Add End date field
    *   ==========================================
    */
	function end_date($post) {
		global $wpdb;
		$post_id = get_the_ID();
		$end_date = get_post_meta($post_id, 'wpcf-end-date', true);

		if (!empty($end_date) && is_numeric($end_date)) {
			$format_end_date = date("Y-m-d", intval($end_date));
		} else {
			$format_end_date = '';
		}

		if ($end_date) {
			echo '<div class="fields_container">';
				echo '<div class="fields_item">Update?</div>';
				echo '<div class="fields_item">';
					echo '<input type="date" name="wpcf-end-date" id="wpcf-end-date" value="' . $format_end_date . '"/>';
				echo '</div>';
			echo '</div>';
		} else {
			echo '<input type="date" name="wpcf-end-date" id="wpcf-end-date" />';
		}

		echo wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
	}



    /*
    *   ==========================================
    *       Add Book url field
    *   ==========================================
    */
    function book_url($post){
       global $wpdb;
        $post_id = get_the_ID();
		$book_url = get_post_meta($post_id, 'wpcf-book-now-url', true);
		if($book_url){
			echo '<div class="fields_container">';
				echo '<div class="fields_item">';
					echo 'Update?';
				echo '</div>';
				echo '<div class="fields_item">';
					echo '<input style="min-width: 100%;" type="text" name="wpcf-book-now-url" id="wpcf-book-now-url" value="'.$book_url.'"/>' ;
				echo '</div>';
			echo '</div>';
		}else{
			echo '<input type="text" style="min-width: 100%;"  name="wpcf-book-now-url" id="wpcf-book-now-url" value=""  />';
		}
		echo wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
    }
	 
    /*
    *   ==========================================
    *       Save Custom Fields
    *   ==========================================
    */
    function save_group_trips($post_id, $post){
        // Verify that our security field exists. If not, bail.
        if (!isset($_POST['_namespace_form_metabox_dates_fields'])) return;

        // Verify data came from edit/dashboard screen
        if (!wp_verify_nonce($_POST['_namespace_form_metabox_dates_fields'], '_namespace_form_metabox_dates')) {
            return $post->ID;
        }

        // Verify user has permission to edit post
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        if (!isset($_POST['wpcf-start-date'])) {
            return $post->ID;
        }
        if (!isset($_POST['wpcf-end-date'])) {
            return $post->ID;
        }
        if (!isset($_POST['wpcf-book-now-url'])) {
            return $post->ID;
        }
        
    
        // Save and update Custom Fields
        // Save and update start-date
        $sanitized_start_date       = wp_filter_post_kses($_POST['wpcf-start-date']);
		if(!empty($_POST['wpcf-start-date'])){
			update_post_meta($post->ID, 'wpcf-start-date',strtotime($sanitized_start_date));
		}
        
        
        // Save and update end-date
        $sanitized_end_date       = wp_filter_post_kses($_POST['wpcf-end-date']);
		if(!empty($_POST['wpcf-end-date'])){
        	update_post_meta($post->ID, 'wpcf-end-date',strtotime($sanitized_end_date));
		}
		
		
        // Save and update book-now-url
        $sanitized_book_url       = wp_filter_post_kses($_POST['wpcf-book-now-url']);
		if(!empty($_POST['wpcf-book-now-url'])){
        	update_post_meta($post->ID, 'wpcf-book-now-url', $sanitized_book_url);
		}
    }
	 
	 // get pages resort's by slug
	 
	function shortcode_pages_by_slug( $atts ) {
		$atts = shortcode_atts( array(
			'slugs' => '', // Lista de slugs separados por comas
		), $atts );

		$slugs_str = sanitize_text_field( $atts['slugs'] );
		$slugs_array = array_filter(array_map( 'trim', explode( ',', $slugs_str ) ));

		if ( empty( $slugs_array ) ) {
			return '<p>Por favor, especifica los slugs de las páginas en el atributo "slugs".</p>';
		}

		$args = array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post_name__in'  => $slugs_array,
			'orderby'        => 'post_name__in',
		);

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return '<p>No se encontraron páginas con los slugs: ' . esc_html( $slugs_str ) . '</p>';
		}

		$output = '<div class="group_trips-container group_trips-container_excluded">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			$title = get_the_title();
			$post_url = get_permalink();
			$featured_image_url = get_the_post_thumbnail_url( $post_id, 'post-thumbnail' );

			$output .= '<div class="group_trips-item group_trips-item_excluded">';
			if ( $featured_image_url ) {
				$output .= '<div class="group_trips-item-image group_trips-item-image_excluded" style="background-image:url('. esc_url( $featured_image_url ).')">';
				$output .= '</div>';
			}else{
				$output .= '<div class="group_trips-item-image group_trips-item-image_excluded" style="background:#f0f0f0">';
				$output .= '</div>';
			}

			$output .= '<div class="group_trips-item-title">';
			$output .= '<a href="' . esc_url( $post_url ) . '" class="group_trips-item-link"><h3>' . esc_html( $title ) . '</h3></a>';
			$output .= '</div>';

			$output .= '<div class="group_trips-item-view">';
			$output .= '<a href="' . esc_url( $post_url ) . '" class="group_trips-item-link"><span>View details</span></a>';
			$output .= '</div>';

			$output .= '</div>';
		}
		wp_reset_postdata();

		$output .= '</div>';
		return $output;
	}





 }