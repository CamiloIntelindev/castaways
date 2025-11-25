<?php
/**
 * @package castawaystravel
 * 
 * ACTIVATION HOOKS
 */

 namespace Inc\Base\General;

 class Activate
 {
    public static function activate(){
        flush_rewrite_rules();
    }

 }