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
        $results = $this->db->resultSet();
        return $results;
    }

    // Create a new post
    public function createPost($data) {
        $this->db->query('INSERT INTO posts (user_id, description, image) VALUES (:user_id, :description, :image)');
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}