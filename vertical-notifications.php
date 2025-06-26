<?php
/*
Plugin Name: Vertical Notifications
Description: Adds a Notification post type and displays vertical scrolling notices on homepage using shortcode.
Version: 1.0
Author: Samarjit Kashyp
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register "Notification" Custom Post Type
function vn_register_notification_post_type() {
    register_post_type('notification', array(
        'labels' => array(
            'name' => 'Notifications',
            'singular_name' => 'Notification',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Notification',
            'edit_item' => 'Edit Notification',
            'new_item' => 'New Notification',
            'view_item' => 'View Notification',
            'search_items' => 'Search Notifications',
            'not_found' => 'No notifications found',
            'not_found_in_trash' => 'No notifications found in Trash',
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'notification'),
        'supports' => array('title'),
        'menu_icon' => 'dashicons-megaphone',
    ));
}
add_action('init', 'vn_register_notification_post_type');

// Shortcode to display vertical notifications
function vn_display_notifications() {
    $args = array(
        'post_type' => 'notification',
        'posts_per_page' => 5,
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) : ?>
        <div id="notification_wrapper">
            <div class="vn-notification-list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="vn-notification-item">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <style>
            #notification_wrapper {
                height: 200px;
                overflow: hidden;
                position: relative;
                background: #f8fbff;
                border: 1px solid #007bff;
                padding: 10px;
                margin-bottom: 20px;
            }

            .vn-notification-list {
                display: flex;
                flex-direction: column;
                gap: 15px;
                animation: vnScroll 18s linear infinite;
            }

            .vn-notification-item {
                padding: 5px 0;
                border-bottom: 1px dashed #ddd;
                font-weight: 600;
            }

            .vn-notification-item a {
                text-decoration: none;
                color: #003366;
            }

            .vn-notification-item a:hover {
                color: #007bff;
            }

            @keyframes vnScroll {
                0% {
                    transform: translateY(0);
                }
                100% {
                    transform: translateY(-100%);
                }
            }

            #notification_wrapper:hover .vn-notification-list {
                animation-play-state: paused;
            }
        </style>
    <?php
    endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('vertical_notifications', 'vn_display_notifications');
