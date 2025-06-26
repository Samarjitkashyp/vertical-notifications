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
            echo '<li><a href="' . get_permalink() . '" target="_blank">' . get_the_title() . '</a></li>';
        endwhile;
        echo '</ul>';
    } else {
        echo '<p>No notifications found.</p>';
    }

    wp_reset_postdata();
}
