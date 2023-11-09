<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once("functions.php");
require_once("userDao.php");
require_once("groupDao.php");

$userKey = null;
$user = null;

if (isset($_GET)) {
    //$nb_pubs = (int)$_GET['nbPubs'];
    $idGroup = $_GET['idgroup'];
    //$user_key = $_GET['userKey'];
    //$user = UserDao::get_user_by($user_key, "userKey");
    $pubs = GroupDao::get_all_pub_group($idGroup);
    //$liked = PublicationDao::get_pub_liked_by_user($user['idUser']);
    //$size = sizeof($pubs);
    // echo json_encode(is_int(sizeof($pubs)));
    echo json_encode($pubs);
    // if($nb_pubs !== sizeof($pubs)) {

    // }
    // if(sizeof($pubs)) {
    //     echo json_encode($pubs);
    // }

}