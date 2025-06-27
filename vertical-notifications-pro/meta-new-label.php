<?php
if (!defined('ABSPATH')) exit;

// Add Meta Box
function vn_pro_add_new_label_meta_box() {
    add_meta_box(
        'vn_new_label_meta',
        'Mark as New (Pro)',
        'vn_pro_new_label_meta_callback',
        'notification',
        'side'
    );
}
add_action('add_meta_boxes', 'vn_pro_add_new_label_meta_box');

function vn_pro_new_label_meta_callback($post) {
    $is_new = get_post_meta($post->ID, '_vn_is_new', true);
    wp_nonce_field('vn_new_label_meta_nonce', 'vn_new_label_nonce_field');

    // Check if Pro plugin is active
    $pro_active = function_exists('vn_pro_check_dependencies');
    ?>
    <label style="display: block; margin-bottom: 8px;">
        <input type="checkbox" name="vn_is_new" value="1" <?php checked($is_new, '1'); ?> <?php disabled(!$pro_active); ?> />
        🆕 Show "NEW" Label
    </label>

    <?php if (!$pro_active): ?>
        <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 10px; font-size: 90%; border-radius: 4px; color: #856404;">
            <strong>This feature is available in the Pro version only.</strong><br>
            To unlock this feature, please activate the <strong>Vertical Notifications Pro</strong> plugin.<br><br>
            👉 <a href="https://digihiveassam.com/resources/plugins" target="_blank" style="color: #004085; text-decoration: underline;">Upgrade to Pro</a>
        </div>
    <?php endif;
}



// Save Meta Value
function vn_pro_save_new_label_meta($post_id) {
    if (!isset($_POST['vn_new_label_nonce_field']) || !wp_verify_nonce($_POST['vn_new_label_nonce_field'], 'vn_new_label_meta_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['vn_is_new'])) {
        update_post_meta($post_id, '_vn_is_new', '1');
    } else {
        delete_post_meta($post_id, '_vn_is_new');
    }
}
add_action('save_post', 'vn_pro_save_new_label_meta');
