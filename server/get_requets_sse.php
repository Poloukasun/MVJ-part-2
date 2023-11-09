<?php
require_once("functions.php");
require_once("userDao.php");
require_once("friendshipsDao.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userKey = $_GET['userKey'];
    if (!$userKey) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid userKey']);
        exit;
    }

    $user = UserDao::get_user_by($userKey, "userKey");
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $requests = FriendshipsDao::get_users_frienships_request($user);
    if ($requests === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
        exit;
    }

    echo json_encode($requests ?: []);
}
