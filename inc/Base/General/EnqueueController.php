<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\General;


 use \Inc\Base\General\BaseController;

 class EnqueueController extends BaseController
 {
	public function register(){
		// Enqueue assets on the front-end at the proper hook
		add_action('wp_enqueue_scripts', array($this, 'enqueue_files'));
	}

	private function asset_url($relative){
		return plugin_dir_url(dirname(__FILE__, 3)) . ltrim($relative, '/');
	}

	private function asset_path($relative){
		return plugin_dir_path(dirname(__FILE__, 3)) . ltrim($relative, '/');
	}

	private function asset_version($relative){
		$path = $this->asset_path($relative);
		return file_exists($path) ? filemtime($path) : '1.0.45';
	}

	private function page_has_shortcode(array $shortcodes){
		if (!is_singular()) { return false; }
		global $post;
		if (!$post || empty($post->post_content)) { return false; }
		foreach($shortcodes as $sc){
			if (has_shortcode($post->post_content, $sc)) { return true; }
		}
		return false;
	}

	public function enqueue_files(){
		if (is_admin()) { return; }

		$shortcodes = array(
			'group_trips',
			'group-trips-calendar',
			'group_trips_calendar_sidebar',
			'get_group_trips_categories_list',
			'bliss_cruises',
			'nude_cruises',
			'group_trips_vanilla_cruises'
		);

		$is_grouptrip_context = (
			is_post_type_archive('group-trip') ||
			is_singular('group-trip') ||
			is_tax(array('group-trip-category','destination','host-couple'))
		);

		$has_sc = $this->page_has_shortcode($shortcodes);

		$enqueue_css = false;
		$enqueue_js  = false;

		// CSS needed on home/blog for header styling
		if (is_front_page() || is_home()) { $enqueue_css = true; }

		if ($has_sc || $is_grouptrip_context) {
			$enqueue_css = true;
			$enqueue_js  = true;
		}

		// Decide between minified and non-minified assets if available
		$css_rel = file_exists($this->asset_path('assets/css/castawaystravel.min.css'))
			? 'assets/css/castawaystravel.min.css'
			: 'assets/css/castawaystravel.css';

		$js_rel = file_exists($this->asset_path('assets/js/castawaystravel.min.js'))
			? 'assets/js/castawaystravel.min.js'
			: 'assets/js/castawaystravel.js';

		if ($enqueue_css) {
			wp_enqueue_style(
				'castawaystravel-styles-css',
				$this->asset_url($css_rel),
				array(),
				$this->asset_version($css_rel),
				'all'
			);
		}

		if ($enqueue_js) {
			wp_enqueue_script('jquery');
			wp_enqueue_script(
				'castaways-scripts-js',
				$this->asset_url($js_rel),
				array('jquery'),
				$this->asset_version($js_rel),
				true
			);

			// Localize AJAX data for front-end requests
			wp_localize_script('castaways-scripts-js', 'ajaxCall', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('get_posts_by_taxonomy_nonce')
			));
		}
	}
 }