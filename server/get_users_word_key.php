<?php 
require_once("userDao.php");
require_once("friendshipsDao.php");

if($_SERVER['REQUEST_METHOD'] === 'GET') {  
    if(isset($_GET)) {
        $user = UserDao::get_user_by($_GET['userKey'], "userKey");
        $users = UserDao::get_users_key_word($_GET['keyWord'], $user['idUser']);
        if(sizeof($users)>0){
            echo json_encode($users);
        }
    }
}
?>