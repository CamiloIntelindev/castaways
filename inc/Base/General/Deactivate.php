<?php
/**
 * @package castawaystravel
 * 
 * DEACTIVATION HOOKS
 */

namespace Inc\Base\General;

class Deactivate
{
    public static function deactivate(){
        // Limpia reglas de reescritura
        flush_rewrite_rules();

        // Elimina el evento programado de expiración de group trips
        if (wp_next_scheduled('group_trip_expire_event')) {
            wp_clear_scheduled_hook('group_trip_expire_event');
        }
    }
}
