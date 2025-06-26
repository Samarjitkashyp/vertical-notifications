<?php
// Settings Page for Vertical Notifications

// Register settings
function vn_register_settings() {
    register_setting('vn_settings_group', 'vn_post_limit');
    register_setting('vn_settings_group', 'vn_scroll_speed');
    register_setting('vn_settings_group', 'vn_auto_inject');
    register_setting('vn_settings_group', 'vn_auto_inject_page');
    register_setting('vn_settings_group', 'vn_open_new_tab');
}
add_action('admin_init', 'vn_register_settings');

// Add menu item
function vn_add_settings_page() {
    add_options_page(
        'Vertical Notifications Settings',
        'Vertical Notifications',
        'manage_options',
        'vertical-notifications',
        'vn_settings_page_html'
    );
}
add_action('admin_menu', 'vn_add_settings_page');

// Settings Page HTML
function vn_settings_page_html() {
    ?>
    <div class="wrap notification__wrapper">
        <h1>Vertical Notifications Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('vn_settings_group'); ?>
            <?php do_settings_sections('vn_settings_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Number of Notifications</th>
                    <td><input type="number" name="vn_post_limit" value="<?php echo esc_attr(get_option('vn_post_limit', 5)); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Scroll Speed (seconds)</th>
                    <td><input type="number" name="vn_scroll_speed" value="<?php echo esc_attr(get_option('vn_scroll_speed', 18)); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Enable Auto Inject</th>
                    <td>
                        <input type="checkbox" name="vn_auto_inject" value="1" <?php checked(1, get_option('vn_auto_inject', 0)); ?> />
                        <label for="vn_auto_inject">Automatically show notifications on selected page</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Select Page to Inject</th>
                    <td>
                        <?php
                        $pages = get_pages();
                        $selected = get_option('vn_auto_inject_page');
                        ?>
                        <select name="vn_auto_inject_page">
                            <option value="">-- Select a Page --</option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo $page->ID; ?>" <?php selected($selected, $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Open Links in New Window</th>
                    <td>
                        <input type="checkbox" name="vn_open_new_tab" value="1" <?php checked(1, get_option('vn_open_new_tab', 0)); ?> />
                        <label for="vn_open_new_tab">Open each notification in a new browser tab</label>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>

        <hr>
            <div class="shortcode__wrapper__123">
                <h2 class="short__title">Usage</h2>
                <p>To display vertical notifications manually, use the following shortcode anywhere:</p>
                <code>[vertical_notifications]</code>
            </div>
    </div>
    <?php
}
