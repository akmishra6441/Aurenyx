<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'Post.php';
require_once 'User.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$action = $_GET['action'] ?? '';

if ($action == 'create' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $postObj = new Post();
    $userObj = new User();

    $currentUser = $userObj->getUserById($_SESSION['user_id']);

    $data = [
        'user_id' => $_SESSION['user_id'],
        'description' => trim($_POST['description']),
        'image' => ''
    ];

    // Handle file upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['post_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed) && $_FILES['post_image']['size'] < 5000000) { // 5MB
            $newFilename = uniqid('', true) . '.' . $filetype;
            $destination = '../uploads/posts/' . $newFilename;
            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $destination)) {
                $data['image'] = $newFilename;
            }
        }
    }
    
    // Create the post
    if ($postObj->createPost($data)) {
        // If successful, echo the HTML for the new post
        ?>
        <div class="post-item">
            <div class="post-header">
                <img src="uploads/profiles/<?php echo htmlspecialchars($currentUser->profile_picture); ?>" class="post-author-pic">
                <div>
                    <strong><?php echo htmlspecialchars($currentUser->full_name); ?></strong>
                    <small>Posted just now</small>
                </div>
            </div>
            <p class="post-content"><?php echo htmlspecialchars($data['description']); ?></p>
            <?php if ($data['image']): ?>
                <img src="uploads/posts/<?php echo htmlspecialchars($data['image']); ?>" class="post-image">
            <?php endif; ?>
        </div>
        <?php
    } else {
        http_response_code(500);
        echo "Error creating post.";
    }
}