$(document).ready(function() {
    // Handle the form submission for adding a new post
    $('#add-post-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Use FormData to handle file uploads
        var formData = new FormData(this);

        $.ajax({
            url: 'core/handle_post.php?action=create',
            type: 'POST',
            data: formData,
            processData: false, // Important!
            contentType: false, // Important!
            success: function(response) {
                // Prepend the new post to the container
                $('#posts-container').prepend(response);
                // Clear the form fields
                $('#add-post-form')[0].reset();
                $('#no-posts-message').hide(); // Hide the 'no posts yet' message
            },
            error: function() {
                alert('Error creating post. Please try again.');
            }
        });
    });
});