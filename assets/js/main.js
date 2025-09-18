$(document).ready(function() {
    // Handle the form submission for adding a new post
    $('#add-post-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'core/handle_post.php?action=create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#posts-container').prepend(response);
                $('#add-post-form')[0].reset();
                $('#no-posts-message').hide();
            },
            error: function() {
                alert('Error creating post.');
            }
        });
    });

    // --- NEW: Handle deleting a post ---
    $('#posts-container').on('click', '.delete-post-btn', function() {
        if (!confirm('Are you sure you want to delete this post?')) {
            return;
        }
        var postId = $(this).data('post-id');
        $.ajax({
            url: 'core/handle_post.php?action=delete',
            type: 'POST',
            data: { post_id: postId },
            success: function(response) {
                $('#post-' + postId).fadeOut(500, function() { $(this).remove(); });
            },
            error: function() {
                alert('Error deleting post.');
            }
        });
    });

    // --- NEW: Handle liking or disliking a post ---
    $('#posts-container').on('click', '.like-btn, .dislike-btn', function() {
        var postId = $(this).data('post-id');
        var action = $(this).hasClass('like-btn') ? 'like' : 'dislike';
        var button = $(this);

        $.ajax({
            url: 'core/handle_post.php?action=interact',
            type: 'POST',
            data: { post_id: postId, action_type: action },
            dataType: 'json',
            success: function(response) {
                // Find the specific post item and update its counts
                var postItem = button.closest('.post-item');
                postItem.find('.like-count').text(response.likes);
                postItem.find('.dislike-count').text(response.dislikes);
            },
            error: function() {
                alert('Error processing your request.');
            }
        });
    });
});