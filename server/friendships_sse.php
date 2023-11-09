<?php 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("functions.php");
require_once("userDao.php");
require_once("friendshipsDao.php");
require_once("database.php");

$nombre_frienships = 0;
$attente = 1;

if(isset($_GET)) {

    $userKey = $_GET['userKey'];
    $pdo = Database::connect();
    $user = UserDao::get_user_by($userKey, "userKey");


    while(!connection_aborted()) {
        $friendships = FriendshipsDao::get_friends_user($user, $pdo);
        if($nombre_frienships !== count($friendships)) {
            $nombre_frienships = count($friendships);
            sent_sse($friendships);
        }
        
        sleep ( $attente );
    }
}

Database::disconnect();


?>