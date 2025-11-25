<?php
/**
 * @package castawaystravel
 */

namespace Inc\Base\General;

class ExpireGroupTripsController
{
    public function register()
    {
        add_action('init', [$this, 'schedule_group_trip_task']);
        add_action('group_trip_expire_event', [$this, 'expire_group_trips']);
    }

    public function schedule_group_trip_task()
    {
        if (!wp_next_scheduled('group_trip_expire_event')) {
            $tz = \wp_timezone();
            $dt = new \DateTime('tomorrow 00:01', $tz);
            $first_run = $dt->getTimestamp();
            wp_schedule_event($first_run, 'daily', 'group_trip_expire_event');
        }
    }

    public function expire_group_trips()
    {
        $today_ts = current_time('timestamp');

        $args = [
            'post_type'      => 'group-trip',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => 'wpcf-start-date',
                    'value'   => $today_ts,
                    'compare' => '<=',
                    'type'    => 'NUMERIC'
                ]
            ]
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            foreach ($query->posts as $post_id) {
                wp_update_post([
                    'ID'          => $post_id,
                    'post_status' => 'draft'
                ]);
            }
        }

        wp_reset_postdata();
    }
}
