<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }
    
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (full_name, email, password, age, profile_picture) VALUES(:full_name, :email, :password, :age, :profile_picture)');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':profile_picture', $data['profile_picture']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password) {
        $row = $this->findUserByEmail($email);

        if ($row == false) {
            return false;
        }

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row; 
        } else {
            return false; 
        }
    }

    public function updateProfile($data) {
    $this->db->query('UPDATE users SET full_name = :full_name, age = :age WHERE id = :id');
    $this->db->bind(':full_name', $data['full_name']);
    $this->db->bind(':age', $data['age']);
    $this->db->bind(':id', $data['user_id']);

    return $this->db->execute();
}
public function updateProfilePicture($user_id, $filename) {
    $this->db->query('UPDATE users SET profile_picture = :profile_picture WHERE id = :id');
    $this->db->bind(':profile_picture', $filename);
    $this->db->bind(':id', $user_id);

    return $this->db->execute();
}

}