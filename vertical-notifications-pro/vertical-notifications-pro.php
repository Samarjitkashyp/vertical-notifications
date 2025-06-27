<?php
/*
Plugin Name: Vertical Notifications Pro
Plugin URI: https://digihiveassam.com/resources/plugins
Description: Adds premium features to Vertical Notifications.
Version: 1.4.5
Author: Digihive Assam
Author URI: https://digihiveassam.com
License: GPLv2 or later
Text Domain: vertical-notifications-pro
*/

if (!defined('ABSPATH')) exit;

// Check if the free plugin is active
function vn_pro_check_dependencies() {
    if (!function_exists('vn_display_shortcode')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>Vertical Notifications Pro</strong> requires the <a href="https://wordpress.org/plugins/vertical-notifications/" target="_blank">Vertical Notifications</a> plugin to be installed and activated.</p></div>';
        });
        return;
    }

    // Load Pro features here
    require_once plugin_dir_path(__FILE__) . 'pro-hooks.php';
}
add_action('plugins_loaded', 'vn_pro_check_dependencies');

function vn_pro_register_settings() {
    register_setting('vn_settings_group', 'vn_pro_show_content', [
        'sanitize_callback' => 'vn_sanitize_checkbox'
    ]);
}
add_action('admin_init', 'vn_pro_register_settings');

// Add color options
function vn_pro_register_color_settings() {
    register_setting('vn_settings_group', 'vn_pro_bg_color', [
        'sanitize_callback' => 'sanitize_hex_color'
    ]);

    register_setting('vn_settings_group', 'vn_pro_text_color', [
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
}
add_action('admin_init', 'vn_pro_register_color_settings');