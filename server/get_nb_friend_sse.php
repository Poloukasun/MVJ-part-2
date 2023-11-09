<?php
require_once("userDao.php");
require_once("friendshipsDao.php");

if($_SERVER['REQUEST_METHOD']==='GET') {
    if(isset($_GET)) {
        $userKey = $_GET['userKey'];
        $user = UserDao::get_user_by($userKey, "userKey");
        $nb_friends = FriendshipsDao::get_nb_friends($user);
        
        echo json_encode($nb_friends);
    }
}


?>