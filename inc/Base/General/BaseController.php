<?php
/**
 * @package castawaystravel
 * 
 * ACTIVATION HOOKS
 */

namespace Inc\Base\General;

class BaseController {
public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    public $site_logo_url;

    public function __construct(){
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin_name = plugin_basename(dirname(__FILE__, 3));
        $this->site_logo_url = get_site_icon_url();
    }
    
    public function get_site_logo_url() {
        return $this->site_logo_url;
    }
}
