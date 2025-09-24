<?php
require_once 'core/config.php';
require_once 'core/Database.php';
require_once 'core/User.php';
require_once 'core/Post.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userObj = new User();
$postObj = new Post();

$currentUser = $userObj->getUserById($_SESSION['user_id']);

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
    <div class="profile-pic-container">
        <img src="uploads/profiles/<?php echo htmlspecialchars($currentUser->profile_picture); ?>" alt="Profile Picture" class="profile-pic" id="profile-pic-display">
        <form id="profile-pic-form" style="display:none;">
            <input type="file" name="new_profile_picture" id="new_profile_picture" accept="image/*">
        </form>
        <button id="change-pic-btn" class="change-pic-btn">Change</button>
    </div>

    <div class="profile-info">
        <div class="info-item" id="name-container">
            <span id="name-display"><?php echo htmlspecialchars($currentUser->full_name); ?></span>
            <input type="text" id="name-input" value="<?php echo htmlspecialchars($currentUser->full_name); ?>" class="edit-input">
            <span class="edit-icon">✏️</span>
        </div>
        <p class="email-display"><?php echo htmlspecialchars($currentUser->email); ?> <span class="lock-icon">🔒</span></p>
         <div class="info-item" id="age-container">
            <span id="age-display"><?php echo htmlspecialchars($currentUser->age); ?> years old</span>
            <input type="number" id="age-input" value="<?php echo htmlspecialchars($currentUser->age); ?>" class="edit-input">
            <span class="edit-icon">✏️</span>
        </div>
    </div>
    
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
                        <?php $counts = $postObj->getLikeDislikeCounts($post->id); ?>
                        <div class="post-item" id="post-<?php echo $post->id; ?>">
                            <div class="post-header">
                                <img src="uploads/profiles/<?php echo htmlspecialchars($post->profile_picture); ?>" class="post-author-pic">
                                <div>
                                    <strong><?php echo htmlspecialchars($post->full_name); ?></strong>
                                    <small>Posted on <?php echo date('d M Y', strtotime($post->created_at)); ?></small>
                                </div>
                                <button class="delete-post-btn" data-post-id="<?php echo $post->id; ?>">×</button>
                            </div>
                            <p class="post-content"><?php echo htmlspecialchars($post->description); ?></p>
                            <?php if ($post->image): ?>
                                <img src="uploads/posts/<?php echo htmlspecialchars($post->image); ?>" class="post-image">
                            <?php endif; ?>
                            <div class="post-actions">
                                <button class="like-btn" data-post-id="<?php echo $post->id; ?>">
                                    👍 Like <span class="like-count"><?php echo $counts['likes']; ?></span>
                                </button>
                                <button class="dislike-btn" data-post-id="<?php echo $post->id; ?>">
                                    👎 Dislike <span class="dislike-count"><?php echo $counts['dislikes']; ?></span>
                                </button>
                            </div>
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