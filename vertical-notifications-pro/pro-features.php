<?php

// Adds post content below title if Pro version is active and both plugins are installed
function vn_pro_extend_notification_output($content) {
    if (!function_exists('get_post_type')) return $content;

    // Check if this is a notification post type
    if (get_post_type() === 'notification') {
        $post_content = get_the_content();
        $content .= '<div class="vn-pro-content" style="font-size: 90%; color: #444; margin-top: 5px;">' . wp_kses_post(wpautop($post_content)) . '</div>';
    }

    return $content;
}
add_filter('vn_notification_output', 'vn_pro_extend_notification_output');
