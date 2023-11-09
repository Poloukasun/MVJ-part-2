<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");

    if(isset($_GET)) {
        $user = UserDao::get_user_by($_GET['userKey'], "userKey");

        $friendships_on_wait = FriendshipsDao::get_friendships_on_wait($user);

        echo json_encode($friendships_on_wait);
    }

?>