<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'Post.php';
require_once 'User.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die('User not logged in.');
}

$action = $_GET['action'] ?? '';
$postObj = new Post();
$user_id = $_SESSION['user_id'];

// Handle Post Creation
if ($action == 'create' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... existing post creation code from Step 5 ...
    $userObj = new User();
    $currentUser = $userObj->getUserById($user_id);
    $data = ['user_id' => $user_id, 'description' => trim($_POST['description']), 'image' => ''];
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['post_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($filetype), $allowed) && $_FILES['post_image']['size'] < 5000000) {
            $newFilename = uniqid('', true) . '.' . $filetype;
            $destination = '../uploads/posts/' . $newFilename;
            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $destination)) {
                $data['image'] = $newFilename;
            }
        }
    }
    if ($postObj->createPost($data)) {
        // Echo the HTML for the new post
        $counts = $postObj->getLikeDislikeCounts(0); // This won't work perfectly, let's simplify
        ?>
        <div class="post-item" id="post-new">
            <div class="post-header">
                <img src="uploads/profiles/<?php echo htmlspecialchars($currentUser->profile_picture); ?>" class="post-author-pic">
                <div>
                    <strong><?php echo htmlspecialchars($currentUser->full_name); ?></strong>
                    <small>Posted just now</small>
                </div>
                <button class="delete-post-btn" data-post-id="new">×</button>
            </div>
            <p class="post-content"><?php echo htmlspecialchars($data['description']); ?></p>
            <?php if ($data['image']): ?><img src="uploads/posts/<?php echo htmlspecialchars($data['image']); ?>" class="post-image"><?php endif; ?>
            <div class="post-actions">
                <button class="like-btn" data-post-id="new">👍 Like <span class="like-count">0</span></button>
                <button class="dislike-btn" data-post-id="new">👎 Dislike <span class="dislike-count">0</span></button>
            </div>
        </div>
        <?php
    } else {
        http_response_code(500);
        echo "Error creating post.";
    }

// Handle Post Deletion
} elseif ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    if ($postObj->deletePost($post_id, $user_id)) {
        echo 'Success';
    } else {
        http_response_code(500);
        echo 'Error deleting post.';
    }

// Handle Like/Dislike Interaction
} elseif ($action == 'interact' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $action_type = $_POST['action_type']; // 'like' or 'dislike'
    
    $newCounts = $postObj->handleLikeDislike($post_id, $user_id, $action_type);
    
    header('Content-Type: application/json');
    echo json_encode($newCounts);
}
?>