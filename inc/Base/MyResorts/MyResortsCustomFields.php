<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyResorts;

 use \Inc\Base\General\BaseController;

 class MyResortsCustomFields extends BaseController
 {
    public function register()
    {
        add_action( 'add_meta_boxes', array($this,'add_custom_fields' ));
        add_action( 'save_post' , array($this , 'save_group_trips'), 1, 2 );
    }
    /*
    *   ==========================================
    *       DateTime Class
    *   ==========================================
    */

    function add_custom_fields(){
		
		$post_type = 'resort';
        $context = 'advanced';
        $priority = 'high';
		add_meta_box('ct_resort_location', 'location', array($this,'resort_location'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_overview', 'Overview', array($this,'resort_overview'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_photo_albun', 'Photo_albun', array($this,'resort_photo_albun'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_services', 'Services', array($this,'resort_services'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_ambiance', 'Ambiance', array($this,'resort_ambiance'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_group_trip', 'Group trip', array($this,'resort_group_trip'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_getting_there', 'Getting there', array($this,'resort_getting_there'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_services', 'Services', array($this,'resort_services'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_theme_nights_ambiance', 'Theme nights ambiance', array($this,'resort_theme_nights_ambiance'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_dining', 'Dining', array($this,'resort_dining'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_rooms_and_reservations', 'Rooms and reservations', array($this,'resort_rooms_and_reservations'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_impressions', 'Impressions', array($this,'resort_impressions'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_hedonism_ebook', 'Hedonism ebook', array($this,'resort_hedonism_ebook'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_trip_reports', 'Trip reports', array($this,'resort_trip_reports'), $post_type,  $context, $priority);
		add_meta_box('ct_resort_sightseeing', 'Sightseeing', array($this,'resort_sightseeing'), $post_type,  $context, $priority);
    }


    /*
    *   ==========================================
    *       Add Resort Overview field
    *   ==========================================
    */
    function resort_overview($post) {
		
	}



 }