<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("functions.php");
require_once("userDao.php");
require_once("publicationsDao.php");

$userKey = null;
$user = null;

if(isset($_GET)) {
    $nb_pubs = (int)$_GET['nbPubs'];
    $user_key = $_GET['userKey'];
    $user = UserDao::get_user_by($user_key, "userKey");
    $pubs = PublicationDao::get_all_pubs();
    $size = sizeof($pubs);
    // echo json_encode(is_int(sizeof($pubs)));
    echo json_encode($pubs);
    // if($nb_pubs !== sizeof($pubs)) {
        
    // }
    // if(sizeof($pubs)) {
    //     echo json_encode($pubs);
    // }

}
