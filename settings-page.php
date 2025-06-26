<?php
// Settings Page for Vertical Notifications

// Register settings
function vn_register_settings() {
    register_setting('vn_settings_group', 'vn_post_limit', array(
        'sanitize_callback' => 'absint',
    ));

    register_setting('vn_settings_group', 'vn_scroll_speed', array(
        'sanitize_callback' => 'absint',
    ));

    register_setting('vn_settings_group', 'vn_auto_inject', array(
        'sanitize_callback' => 'vn_sanitize_checkbox',
    ));

    register_setting('vn_settings_group', 'vn_auto_inject_page', array(
        'sanitize_callback' => 'absint',
    ));

    register_setting('vn_settings_group', 'vn_open_new_tab', array(
        'sanitize_callback' => 'vn_sanitize_checkbox',
    ));
}
add_action('admin_init', 'vn_register_settings');

// Sanitize checkbox (returns 1 or 0)
function vn_sanitize_checkbox($input) {
    return $input === '1' ? 1 : 0;
}

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
        <div class="header_inner">
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/images/logo.png'); ?>" class="plugin__logo" alt="<?php esc_attr_e('Plugin Logo', 'vertical-notifications'); ?>">
            <h1 style="margin: 0;"><?php esc_html_e('Vertical Notifications Settings', 'vertical-notifications'); ?></h1>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('vn_settings_group'); ?>
            <?php do_settings_sections('vn_settings_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Number of Notifications', 'vertical-notifications'); ?></th>
                    <td><input type="number" name="vn_post_limit" value="<?php echo esc_attr(get_option('vn_post_limit', 5)); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Scroll Speed (seconds)', 'vertical-notifications'); ?></th>
                    <td><input type="number" name="vn_scroll_speed" value="<?php echo esc_attr(get_option('vn_scroll_speed', 18)); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Enable Auto Inject', 'vertical-notifications'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="vn_auto_inject" value="1" <?php checked(1, get_option('vn_auto_inject', 0)); ?> />
                            <?php esc_html_e('Automatically show notifications on selected page', 'vertical-notifications'); ?>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Select Page to Inject', 'vertical-notifications'); ?></th>
                    <td>
                        <?php
                        $pages = get_pages();
                        $selected = absint(get_option('vn_auto_inject_page'));
                        ?>
                        <select name="vn_auto_inject_page">
                            <option value=""><?php esc_html_e('-- Select a Page --', 'vertical-notifications'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($selected, $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Open Links in New Window', 'vertical-notifications'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="vn_open_new_tab" value="1" <?php checked(1, get_option('vn_open_new_tab', 0)); ?> />
                            <?php esc_html_e('Open each notification in a new browser tab', 'vertical-notifications'); ?>
                        </label>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>

        <hr>
        <div class="shortcode__wrapper__123">
            <h2 class="short__title"><?php esc_html_e('Usage', 'vertical-notifications'); ?></h2>
            <p><?php esc_html_e('To display vertical notifications manually, use the following shortcode anywhere:', 'vertical-notifications'); ?></p>
            <code>[vertical_notifications]</code>
        </div>
    </div>
    <?php
}
