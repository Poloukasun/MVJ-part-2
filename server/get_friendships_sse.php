<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("functions.php");
require_once("userDao.php");
require_once("friendshipsDao.php");
require_once("database.php");

$userKey = null;
$pdo = null;
$user = null;
$attente = 1;
$nombre_friendships = 0;


if (isset($_GET)) {
    $pdo = Database::connect();
    while (!connection_aborted()) {

        try {
            $userKey = $_GET['userKey'];
            $user = UserDao::get_user_by($userKey, "userKey");
            $friendships = FriendshipsDao::get_friendships($user, $pdo);

            if ($nombre_friendships !== sizeof($friendships)) {
                sent_sse($friendships);
            }
        } catch (Exception $e) {
            echo $e;
        }
    }
    Database::disconnect();
}
