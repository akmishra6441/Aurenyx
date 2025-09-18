<?php
// Include core files and start session
require_once 'core/config.php';
require_once 'core/Database.php';
require_once 'core/User.php';
require_once 'core/Post.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch logged-in user's data
$userObj = new User();
$currentUser = $userObj->getUserById($_SESSION['user_id']);

// Fetch user's posts
$postObj = new Post();
$posts = $postObj->getPostsByUserId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Postify</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <img src="uploads/profiles/<?php echo htmlspecialchars($currentUser->profile_picture); ?>" alt="Profile Picture" class="profile-pic">
            <h3><?php echo htmlspecialchars($currentUser->full_name); ?></h3>
            <p><?php echo htmlspecialchars($currentUser->email); ?></p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="posts-section">
            <div class="add-post-box">
                <form id="add-post-form" enctype="multipart/form-data">
                    <textarea name="description" placeholder="What's on your mind?"></textarea>
                    <div class="form-actions">
                        <input type="file" name="post_image" id="post_image" accept="image/*">
                        <button type="submit">Post</button>
                    </div>
                </form>
            </div>

            <div id="posts-container">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post-item" id="post-<?php echo $post->id; ?>">
                            <div class="post-header">
                                <img src="uploads/profiles/<?php echo htmlspecialchars($post->profile_picture); ?>" class="post-author-pic">
                                <div>
                                    <strong><?php echo htmlspecialchars($post->full_name); ?></strong>
                                    <small>Posted on <?php echo date('d M Y', strtotime($post->created_at)); ?></small>
                                </div>
                            </div>
                            <p class="post-content"><?php echo htmlspecialchars($post->description); ?></p>
                            <?php if ($post->image): ?>
                                <img src="uploads/posts/<?php echo htmlspecialchars($post->image); ?>" class="post-image">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p id="no-posts-message">No posts yet. Be the first to share something!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>