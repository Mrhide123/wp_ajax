<?php

function creote_child_enqueue_styles() {
    // Enqueue the custom script
    wp_enqueue_script('creote-custom-script', get_stylesheet_directory_uri() . '/custom.js', ['jquery'], null, true);

    // Localize the script with the necessary AJAX parameters
    wp_localize_script('creote-custom-script', 'load_more_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'posts_nonce' => wp_create_nonce('load_more_posts')
    ));
}

add_action('wp_enqueue_scripts', 'creote_child_enqueue_styles');


function submit_contact_form() {
    // Check nonce for security
    check_ajax_referer('load_more_posts', 'nonce');

    // Parse the form data
    parse_str($_POST['form_data'], $form_data);

    // List of fields to exclude
    $exclude_fields = array(
        '_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag', '_wpcf7_container_post',
        '_wpcf7_posted_data_hash', '_wpcf7_recaptcha_response'
    );

    // Filter out the excluded fields
    $filtered_data = array_filter($form_data, function($key) use ($exclude_fields) {
        return !in_array($key, $exclude_fields);
    }, ARRAY_FILTER_USE_KEY);

    // Prepare the email
    $to = 'your-email@example.com'; // Replace with your email address
    $subject = 'New Contact Form Submission';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $message = '<p>You have received a new message from your website contact form.</p>';
    $message .= '<ul>';
    foreach ($filtered_data as $key => $value) {
        $message .= '<li><strong>' . esc_html($key) . ':</strong> ' . nl2br(esc_html($value)) . '</li>';
    }
    $message .= '</ul>';

    // Send the email
    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success('Email sent successfully');
    } else {
        wp_send_json_error('Email sending failed');
    }

    wp_die();
}
add_action('wp_ajax_submit_contact_form', 'submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form');
