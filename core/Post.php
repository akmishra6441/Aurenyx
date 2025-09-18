<?php
class Post {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all posts by a specific user
    public function getPostsByUserId($user_id) {
        $this->db->query('SELECT posts.*, users.full_name, users.profile_picture 
                         FROM posts 
                         JOIN users ON posts.user_id = users.id
                         WHERE posts.user_id = :user_id 
                         ORDER BY posts.created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Create a new post
    public function createPost($data) {
        $this->db->query('INSERT INTO posts (user_id, description, image) VALUES (:user_id, :description, :image)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        return $this->db->execute();
    }

    // Delete a post
    public function deletePost($post_id, $user_id) {
        $this->db->query('DELETE FROM posts WHERE id = :post_id AND user_id = :user_id');
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    // Get like and dislike counts for a post
    public function getLikeDislikeCounts($post_id) {
        $this->db->query("SELECT 
            SUM(CASE WHEN action = 'like' THEN 1 ELSE 0 END) as likes,
            SUM(CASE WHEN action = 'dislike' THEN 1 ELSE 0 END) as dislikes
            FROM post_likes WHERE post_id = :post_id");
        $this->db->bind(':post_id', $post_id);
        $row = $this->db->single();
        return ['likes' => $row->likes ?? 0, 'dislikes' => $row->dislikes ?? 0];
    }
    
    // Handle a like or dislike action
    public function handleLikeDislike($post_id, $user_id, $action) {
        // Check if user has already reacted
        $this->db->query('SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $user_id);
        $existing = $this->db->single();

        if ($this->db->rowCount() > 0) {
            // User has reacted before
            if ($existing->action == $action) {
                // User clicked the same button again, so remove the reaction (toggle off)
                $this->db->query('DELETE FROM post_likes WHERE id = :id');
                $this->db->bind(':id', $existing->id);
            } else {
                // User changed their reaction (e.g., from like to dislike)
                $this->db->query('UPDATE post_likes SET action = :action WHERE id = :id');
                $this->db->bind(':action', $action);
                $this->db->bind(':id', $existing->id);
            }
        } else {
            // User is reacting for the first time
            $this->db->query('INSERT INTO post_likes (post_id, user_id, action) VALUES (:post_id, :user_id, :action)');
            $this->db->bind(':post_id', $post_id);
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':action', $action);
        }
        
        $this->db->execute();
        // Return the new counts
        return $this->getLikeDislikeCounts($post_id);
    }
}