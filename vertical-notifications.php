<?php
/*
Plugin Name: Vertical Notifications
Plugin URI: https://digihiveassam.com/resources/plugins
Description: Adds a Notification post type and displays vertical scrolling notices using a shortcode.
Version: 1.3.6
Author: Digihive Assam
Author URI: https://digihiveassam.com
License: GPLv2 or later
Text Domain: vertical-notifications
Tags: notification, scroll, ticker, elementor, education, updates, vertical notification
*/

if (!defined('ABSPATH')) exit;

// Include admin hooks and notices
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin-hooks.php';
    require_once plugin_dir_path(__FILE__) . 'dashboard-widget.php';
    require_once plugin_dir_path(__FILE__) . 'settings-page.php';
}

// Register Custom Post Type: Notification
function vn_register_post_type() {
    register_post_type('notification', array(
        'labels' => array(
            'name' => __('Notifications', 'vertical-notifications'),
            'singular_name' => __('Notification', 'vertical-notifications'),
            'add_new' => __('Add New', 'vertical-notifications'),
            'add_new_item' => __('Add New Notification', 'vertical-notifications'),
            'edit_item' => __('Edit Notification', 'vertical-notifications'),
            'new_item' => __('New Notification', 'vertical-notifications'),
            'view_item' => __('View Notification', 'vertical-notifications'),
            'not_found' => __('No notifications found', 'vertical-notifications'),
        ),
        'public' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor'),
        'show_in_rest' => true
    ));
}
add_action('init', 'vn_register_post_type');

// Enqueue CSS and JS for frontend
function vn_enqueue_assets() {
    wp_enqueue_style(
        'vn-style',
        plugin_dir_url(__FILE__) . 'assets/css/vertical-notifications.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'vn-script',
        plugin_dir_url(__FILE__) . 'assets/js/vertical-notifications.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'vn_enqueue_assets');

// Load custom admin styles
function vn_admin_styles($hook) {
    if ($hook === 'settings_page_vertical-notifications') {
        wp_enqueue_style(
            'vn-admin-style',
            plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'vn_admin_styles');

// Shortcode: [vertical_notifications]
function vn_display_shortcode() {
    $post_limit = intval(get_option('vn_post_limit', 5));
    $scroll_speed = intval(get_option('vn_scroll_speed', 18));
    $open_new_tab = get_option('vn_open_new_tab', 0);

    $args = array(
        'post_type' => 'notification',
        'posts_per_page' => $post_limit,
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) :
        ?>
        <div id="notification_wrapper" data-speed="<?php echo esc_attr($scroll_speed); ?>">
            <div class="vn-notification-list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="vn-notification-item">
                        <?php
                        $link_target = $open_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
                        $title = get_the_title();
                        $permalink = get_permalink();
                        $date = get_the_date();
                        $author = get_the_author();
                        ?>
                        📢 <a href="<?php echo esc_url($permalink); ?>"<?php echo $link_target; ?>>
                            <?php echo esc_html($title); ?>
                        </a><br>
                        🗓️ <?php echo esc_html($date); ?> | ✍️ Author: <?php echo esc_html($author); ?>
                        <!-- <div class="vn-description">
                            <?php// the_excerpt(); ?>
                        </div> -->
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('vertical_notifications', 'vn_display_shortcode');

// Auto inject notification on selected page if enabled
function vn_auto_inject_selected_page($content) {
    if (!is_admin() && is_singular('page') && is_main_query() && in_the_loop()) {
        $enabled = get_option('vn_auto_inject', 0);
        $selected_page = get_option('vn_auto_inject_page');

        if ($enabled && is_page($selected_page)) {
            return $content . do_shortcode('[vertical_notifications]');
        }
    }
    return $content;
}
add_filter('the_content', 'vn_auto_inject_selected_page');
