<?php
// Dashboard Widget for latest 5 notifications
function vn_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'vn_dashboard_widget',                      // Widget slug
        '📢 Latest Notifications',                  // Title
        'vn_dashboard_widget_display'              // Display callback
    );
}
add_action('wp_dashboard_setup', 'vn_add_dashboard_widget');

// Display widget content
function vn_dashboard_widget_display() {
    $args = array(
        'post_type' => 'notification',
        'posts_per_page' => 5,
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<ul>';
        while ($query->have_posts()) : $query->the_post();
            $title = get_the_title();
            $permalink = get_permalink();
            ?>
            <li>
                <a href="<?php echo esc_url($permalink); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html($title); ?>
                </a>
            </li>
            <?php
        endwhile;
        echo '</ul>';
    } else {
        echo '<p>' . esc_html__('No notifications found.', 'vertical-notifications') . '</p>';
    }

    wp_reset_postdata();
}
