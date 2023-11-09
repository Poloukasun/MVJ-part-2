<?php 
require_once("userDao.php");
require_once("friendshipsDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST)) {
        $connected_user = UserDao::get_user_by($_POST['connectedUserKey'], "userKey");
        $target_user = UserDao::get_user_by($_POST['targetUserKey'], "userKey");

        if($connected_user && $target_user) {
            if(FriendshipsDao::delete_friendship_by_users_id($connected_user, $target_user)) {
                echo json_encode(true);
            }
        }

    }
}


?>