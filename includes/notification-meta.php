<?php
// Add "Mark as New" checkbox to Notification edit screen
function vn_add_new_label_meta_box() {
    add_meta_box(
        'vn_new_label_meta',
        'Mark as New (Pro)',
        'vn_new_label_meta_box_callback',
        'notification',
        'side'
    );
}
add_action('add_meta_boxes', 'vn_add_new_label_meta_box');

// Render the checkbox
function vn_new_label_meta_box_callback($post) {
    $is_new = get_post_meta($post->ID, '_vn_is_new', true);
    wp_nonce_field('vn_new_label_meta_nonce', 'vn_new_label_meta_nonce_field');
    ?>
    <label>
        <input type="checkbox" name="vn_is_new" value="1" <?php checked($is_new, '1'); ?> />
        🆕 Mark as NEW
    </label>
    <?php
}

// Save meta when post is saved
function vn_save_new_label_meta_box($post_id) {
    if (!isset($_POST['vn_new_label_meta_nonce_field']) || !wp_verify_nonce($_POST['vn_new_label_meta_nonce_field'], 'vn_new_label_meta_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['vn_is_new'])) {
        update_post_meta($post_id, '_vn_is_new', '1');
    } else {
        delete_post_meta($post_id, '_vn_is_new');
    }
}
add_action('save_post', 'vn_save_new_label_meta_box');

