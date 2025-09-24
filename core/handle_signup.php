<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'full_name' => trim($_POST['full_name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'age' => trim($_POST['age']),
        'profile_picture' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'age_err' => '',
        'picture_err' => ''
    ];

    if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
    } else {
        $user = new User();
        if ($user->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Email is already taken';
        }
    }

    if (empty($data['full_name'])) {
        $data['name_err'] = 'Please enter name';
    }

    if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
    } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must be at least 6 characters';
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $filesize = $_FILES['profile_picture']['size'];

        if (!in_array(strtolower($filetype), $allowed)) {
            $data['picture_err'] = 'Invalid file type. Only JPG, JPEG, and PNG are allowed.';
        } elseif ($filesize > 2000000) { 
            $data['picture_err'] = 'File size is too large. Max size is 2MB.';
        } else {
            $newFilename = uniqid('', true) . '.' . $filetype;
            $destination = '../uploads/profiles/' . $newFilename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                $data['profile_picture'] = $newFilename;
            } else {
                $data['picture_err'] = 'Failed to upload image.';
            }
        }
    } else {
        $data['profile_picture'] = 'default.png'; 
    }


    if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['picture_err'])) {
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $user = new User();
        if ($user->register($data)) {
            header('location: ../login.php?status=success');
        } else {
            die('Something went wrong.');
        }

    } else {
        $errors = array_filter($data, function($key) {
            return strpos($key, '_err') !== false && !empty($GLOBALS['data'][$key]);
        }, ARRAY_FILTER_USE_KEY);
        die('Errors found: ' . implode(', ', $errors));
    }

} else {
    header('location: ../signup.php');
}
?>