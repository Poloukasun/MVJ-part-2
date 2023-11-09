<?php 
require_once("functions.php");
require_once("userDao.php");
require_once("friendshipsDao.php");
require_once("database.php");

$userKey = null;
$pdo = null;
$user = null;
$attente = 1;
$nb_mises_ajour = 0;

if(isset($_GET)) {

    $userKey = $_GET['userKey'];
    $limit = $_GET['limit'];
    $user = UserDao::get_user_by($userKey, "userKey");
    $users = UserDao::get_users($user,$limit); 
    
    if(sizeof($users)) {
        echo json_encode($users);
    }
}




?>