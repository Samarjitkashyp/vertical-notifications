<?php
if (!defined('ABSPATH')) exit;

/**
 * Add full content (editor content) below title — Pro feature
 */
function vn_pro_add_content_to_notification($output, $post_id) {
    if (get_option('vn_pro_show_content') !== '1') {
        return $output;
    }

    $post_content = get_post_field('post_content', $post_id);

    if (!empty($post_content)) {
        $output .= '<div class="vn-notification-content">' . wp_kses_post(wpautop($post_content)) . '</div>';
    }

    return $output;
}
add_filter('vn_notification_output', 'vn_pro_add_content_to_notification', 10, 2);

// Add "Mark as New" checkbox (Pro)
// Add checkbox to Notification edit screen (Pro)
function vn_pro_add_new_label_meta_box() {
    add_meta_box(
        'vn_new_label_meta',
        'Mark as New (Pro)',
        'vn_pro_new_label_meta_box_callback',
        'notification',
        'side'
    );
}
add_action('add_meta_boxes', 'vn_pro_add_new_label_meta_box');

// Meta box HTML
function vn_pro_new_label_meta_box_callback($post) {
    $is_new = get_post_meta($post->ID, '_vn_pro_is_new', true);
    wp_nonce_field('vn_pro_new_label_meta_nonce', 'vn_pro_new_label_nonce_field');
    ?>
    <label>
        <input type="checkbox" name="vn_pro_is_new" value="1" <?php checked($is_new, '1'); ?> />
        🆕 Mark this as NEW notification
    </label>
    <?php
}

add_action('save_post', 'vn_pro_save_new_label_meta');
function vn_pro_save_new_label_meta($post_id) {
    if (!isset($_POST['vn_pro_new_label_nonce_field']) || !wp_verify_nonce($_POST['vn_pro_new_label_nonce_field'], 'vn_pro_new_label_meta_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['vn_pro_is_new'])) {
        update_post_meta($post_id, '_vn_pro_is_new', '1');
    } else {
        delete_post_meta($post_id, '_vn_pro_is_new');
    }
}



// Add "🆕 NEW" label to notifications if marked as new
add_filter('vn_notification_output', 'vn_pro_add_new_label_to_output', 20, 2);
function vn_pro_add_new_label_to_output($output, $post_id) {
    $is_new = get_post_meta($post_id, '_vn_pro_is_new', true);
    if ($is_new === '1') {
        $output = '🆕 <strong>NEW</strong> — ' . $output;
    }
    return $output;
}

