jQuery(document).ready(function($) {
    // This function runs when the document (webpage) is fully loaded and ready.
    // The $ symbol is passed as an argument to ensure jQuery is referenced correctly, avoiding conflicts with other libraries.

    $('#wpcf7-form').on('submit', function(e) {
        // This line attaches an event handler to the form with the ID 'wpcf7-form'.
        // The event handler listens for the 'submit' event, which is triggered when the form is submitted.

        e.preventDefault();
        // This line prevents the default form submission behavior, which would normally cause the page to reload.
        // Preventing the default action allows the form data to be processed via AJAX instead.

        // Serialize the form data
        var formData = $(this).serialize();
        // This line serializes the form data into a query string format.
        // The serialized data is suitable for sending to the server via AJAX.

        // Send the form data via AJAX
        $.ajax({
            type: 'POST',
            // The type of HTTP request is specified as POST.
            
            url: load_more_params.ajaxurl,
            // The URL to which the request is sent. This value is retrieved from the `load_more_params` object.
            // Typically, this URL points to the WordPress admin-ajax.php file to handle the AJAX request.
            
            data: {
                action: 'submit_contact_form',
                // The 'action' parameter specifies the AJAX action to be performed.
                // In this case, it tells the server to execute the 'submit_contact_form' action.

                form_data: formData,
                // The serialized form data is included in the AJAX request under the key 'form_data'.

                nonce: load_more_params.posts_nonce
                // A nonce (number used once) for security purposes is included in the request.
                // This prevents CSRF (Cross-Site Request Forgery) attacks.
            },

            success: function(response) {
                // This function is executed if the AJAX request is successful.

                if (response.success) {
                    // If the response indicates success, an alert is shown to the user.
                    alert('Your message has been sent successfully!');

                    $('#wpcf7-form')[0].reset();
                    // The form is reset to clear all input fields.
                } else {
                    // If the response indicates failure, an error alert is shown to the user.
                    alert('There was an error sending your message. Please try again.');
                }
            }
        });
    });
});
