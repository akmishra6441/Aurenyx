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



function handleInlineEdit(displayId, inputId, fieldName) {
    const displayElement = $('#' + displayId);
    const inputElement = $('#' + inputId);
    const container = displayElement.closest('.info-item');

    container.on('click', '.edit-icon', function() {
        displayElement.hide();
        inputElement.show().focus();
    });

    inputElement.on('blur keypress', function(e) {
        if (e.type === 'blur' || e.which === 13) { // 13 is the Enter key
            const newValue = $(this).val();
            displayElement.show();
            inputElement.hide();

            // AJAX call to update the data
            $.ajax({
                url: 'core/handle_profile.php?action=update_details',
                type: 'POST',
                data: { field: fieldName, value: newValue },
                success: function(response) {
                    // Update the display text
                    if(fieldName === 'age') {
                        displayElement.text(newValue + ' years old');
                    } else {
                        displayElement.text(newValue);
                    }
                },
                error: function() { alert('Error updating profile.'); }
            });
        }
    });
}

handleInlineEdit('name-display', 'name-input', 'full_name');
handleInlineEdit('age-display', 'age-input', 'age');

// Handle profile picture change
$('#change-pic-btn').on('click', function() {
    $('#new_profile_picture').click(); // Trigger the hidden file input
});

$('#new_profile_picture').on('change', function() {
    var formData = new FormData();
    formData.append('new_profile_picture', $(this)[0].files[0]);

    $.ajax({
        url: 'core/handle_profile.php?action=update_picture',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                // Update the profile picture on the page
                const newPicUrl = 'uploads/profiles/' + response.filename + '?t=' + new Date().getTime();
                $('#profile-pic-display').attr('src', newPicUrl);
            } else {
                alert(response.error);
            }
        },
        error: function() { alert('Error uploading picture.'); }
    });
});