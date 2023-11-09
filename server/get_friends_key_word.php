<?php 
require_once("userDao.php");
require_once("friendshipsDao.php");

if($_SERVER['REQUEST_METHOD'] === 'GET') {  
    if(isset($_GET)) {
        $user = UserDao::get_user_by($_GET['userKey'], "userKey");
        $friends = FriendshipsDao::get_friends_connected_user_by_key_word($user, $_GET['keyWord']);
        if(sizeof($friends)>0){
            echo json_encode($friends);
        }
    }
}
?>

