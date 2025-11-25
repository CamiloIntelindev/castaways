<?php
/**
 * @package castawaystravel
 * Plugin Name:  Castaways Custom Code
 * Description: Add Custom CSS, JS and PHP
 * Version: 1.0.0
 * Author: p5marketing Development Team
 * Text Domain: castawaystravel
 */


if ( ! defined( 'ABSPATH' ) ) { die( 'Invalid request.' ); }

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

// Optional diagnostics flag
if (!defined('CASTAWAYS_DEBUG')) {
    define('CASTAWAYS_DEBUG', (bool) get_option('castaways_debug', false));
}


use Inc\Base\General\Activate;
use Inc\Base\General\Deactivate;

function activate_castawaystravel_plugin(){
	Activate::activate();
}

function deactivate_castawaystravel_plugin(){
	Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_castawaystravel_plugin' );
register_deactivation_hook(__FILE__, 'deactivate_castawaystravel_plugin' );



if(class_exists('Inc\\Init')){
	Inc\Init::register_services();
}