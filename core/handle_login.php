<?php
// Include core files
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize POST data
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
    ];

    // Instantiate User
    $user = new User();

    // Attempt to log in
    $loggedInUser = $user->login($data['email'], $data['password']);

    if ($loggedInUser) {
        // Create Session
        createUserSession($loggedInUser);
    } else {
        // Redirect back with an error
        header('location: ../login.php?error=invalidcredentials');
    }

} else {
    // Not a POST request, redirect to login
    header('location: ../login.php');
}


function createUserSession($user) {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->full_name;
    // Redirect to the profile page (index.php)
    header('location: ../index.php');
}