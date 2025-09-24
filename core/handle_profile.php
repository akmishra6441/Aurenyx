<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die(json_encode(['error' => 'User not logged in.']));
}

$action = $_GET['action'] ?? '';
$userObj = new User();
$user_id = $_SESSION['user_id'];

if ($action == 'update_details' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentUser = $userObj->getUserById($user_id);
    
    $data = [
        'user_id' => $user_id,
        'full_name' => $currentUser->full_name,
        'age' => $currentUser->age
    ];

    $field = $_POST['field'];
    $value = trim($_POST['value']);

    if (isset($data[$field])) {
        $data[$field] = htmlspecialchars($value);
        if($userObj->updateProfile($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Database error.']);
        }
    }
}

elseif ($action == 'update_picture' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = ['success' => false, 'error' => 'An unknown error occurred.'];
    if (isset($_FILES['new_profile_picture']) && $_FILES['new_profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['new_profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed) && $_FILES['new_profile_picture']['size'] < 2000000) { // 2MB
            $newFilename = uniqid('', true) . '.' . $filetype;
            $destination = '../uploads/profiles/' . $newFilename;

            if (move_uploaded_file($_FILES['new_profile_picture']['tmp_name'], $destination)) {
                if ($userObj->updateProfilePicture($user_id, $newFilename)) {
                    $response = ['success' => true, 'filename' => $newFilename];
                } else {
                    $response['error'] = 'Could not update database.';
                }
            } else {
                $response['error'] = 'Could not move uploaded file.';
            }
        } else {
            $response['error'] = 'Invalid file type or size is too large (Max 2MB).';
        }
    } else {
        $response['error'] = 'No file uploaded or upload error.';
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}