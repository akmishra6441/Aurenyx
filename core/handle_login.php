<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
    ];

    $user = new User();

    $loggedInUser = $user->login($data['email'], $data['password']);

    if ($loggedInUser) {
        createUserSession($loggedInUser);
    } else {
        header('location: ../login.php?error=invalidcredentials');
    }

} else {
    header('location: ../login.php');
}


function createUserSession($user) {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->full_name;
    header('location: ../index.php');
}