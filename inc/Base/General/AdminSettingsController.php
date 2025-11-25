<?php
/**
 * @package castawaystravel
 * Admin settings for debug toggle
 */

namespace Inc\Base\General;

class AdminSettingsController extends BaseController
{
    public function register()
    {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_menu'));
    }

    public function register_settings()
    {
        register_setting('castaways_settings_group', 'castaways_debug', array(
            'type' => 'boolean',
            'sanitize_callback' => array($this, 'sanitize_bool'),
            'default' => false,
        ));

        add_settings_section(
            'castaways_main_section',
            __('Castaways Settings', 'castawaystravel'),
            function () {
                echo '<p>' . esc_html__('Plugin diagnostics and tools.', 'castawaystravel') . '</p>';
            },
            'castaways_settings_page'
        );

        add_settings_field(
            'castaways_debug_field',
            __('Enable Debug Logging', 'castawaystravel'),
            array($this, 'render_debug_field'),
            'castaways_settings_page',
            'castaways_main_section'
        );
    }

    public function add_menu()
    {
        add_options_page(
            __('Castaways', 'castawaystravel'),
            __('Castaways', 'castawaystravel'),
            'manage_options',
            'castaways_settings_page',
            array($this, 'render_page')
        );
    }

    public function render_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Castaways Settings', 'castawaystravel') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('castaways_settings_group');
        do_settings_sections('castaways_settings_page');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function render_debug_field()
    {
        $value = (bool) get_option('castaways_debug', false);
        echo '<label for="castaways_debug">';
        echo '<input type="checkbox" id="castaways_debug" name="castaways_debug" value="1"' . checked($value, true, false) . ' /> ';
        echo esc_html__('Enable logging of query timings and decisions.', 'castawaystravel');
        echo '</label>';
    }

    public function sanitize_bool($value)
    {
        return !empty($value);
    }
}
