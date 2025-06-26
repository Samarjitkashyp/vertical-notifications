<?php
if (!defined('ABSPATH')) exit;

// Add settings menu
function vn_register_settings_menu() {
    add_options_page(
        'Vertical Notifications Settings',
        'Vertical Notifications',
        'manage_options',
        'vertical-notifications-settings',
        'vn_settings_page_html'
    );
}
add_action('admin_menu', 'vn_register_settings_menu');

// Register settings
function vn_register_settings() {
    register_setting('vn_settings_group', 'vn_post_limit');
    register_setting('vn_settings_group', 'vn_scroll_speed');
}
add_action('admin_init', 'vn_register_settings');

// Settings page content
function vn_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Vertical Notifications Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('vn_settings_group'); ?>
            <?php do_settings_sections('vn_settings_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Number of Notifications</th>
                    <td><input type="number" name="vn_post_limit" value="<?php echo esc_attr(get_option('vn_post_limit', 5)); ?>" min="1" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Scroll Duration (seconds)</th>
                    <td><input type="number" name="vn_scroll_speed" value="<?php echo esc_attr(get_option('vn_scroll_speed', 18)); ?>" min="1" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
