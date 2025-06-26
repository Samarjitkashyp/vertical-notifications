<?php
// Admin footer branding
function vn_custom_admin_footer() {
    echo wp_kses_post(
        sprintf(
            'Developed with ❤️ by <a href="%s" target="_blank" rel="noopener noreferrer">Digihive Assam</a>',
            esc_url('https://digihiveassam.com')
        )
    );
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
        $settings_url = esc_url(admin_url('options-general.php?page=vertical-notifications'));
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong><?php esc_html_e('Vertical Notifications Activated!', 'vertical-notifications'); ?></strong>
                <?php printf(
                    wp_kses(
                        __('Go to <a href="%s">Settings</a> to configure.', 'vertical-notifications'),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    ),
                    $settings_url
                ); ?>
            </p>
        </div>
        <?php
        delete_transient('vn_show_activation_notice');
    }
}
add_action('admin_notices', 'vn_display_admin_notice');
