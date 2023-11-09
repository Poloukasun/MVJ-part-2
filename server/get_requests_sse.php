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
$nombre_demandes = 0;

if(isset($_GET)) {

    $userKey = $_GET['userKey'];
    $user = UserDao::get_user_by($userKey, "userKey");
    $pdo = Database::connect();
    
    while(!connection_aborted()) {
        $requests = FriendshipsDao::get_users_frienships_request($user, $pdo);
        
        if(count($requests) !== $nombre_demandes) {
            sent_sse($requests);
            $nombre_demandes = count($requests);
        }

        sleep( $attente );
    }
    Database::disconnect();
}

?>
