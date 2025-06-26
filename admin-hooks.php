<?php
// Admin footer branding
function vn_custom_admin_footer() {
    echo 'Developed with ❤️ by <a href="https://digihiveassam.com" target="_blank">Digihive Assam</a>';
}
add_filter('admin_footer_text', 'vn_custom_admin_footer');

// Show admin notice on activation
function vn_activation_notice() {
    set_transient('vn_show_activation_notice', true, 30);
}
register_activation_hook(__FILE__, 'vn_activation_notice');

// Display the notice
function vn_display_admin_notice() {
    if (get_transient('vn_show_activation_notice')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Vertical Notifications Activated!</strong> Go to <a href="<?php echo admin_url('options-general.php?page=vertical-notifications'); ?>">Settings</a> to configure.</p>
        </div>
        <?php
        delete_transient('vn_show_activation_notice');
    }
}
add_action('admin_notices', 'vn_display_admin_notice');
