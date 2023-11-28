<?php
header('Content-Type: application/json');
require_once("userDao.php");
require_once("messagesDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['lastId'])) {

        $connected_user = UserDao::get_user_by($_POST['userKey'], "userKey");
        $friend = UserDao::get_user_by($_POST['friendKey'], "userKey");
        $last_id = (int)$_POST['lastId'];
        $last_message = MessagesDao::get_last_message($connected_user, $friend);
        
        
        if($last_message['idMessage'] > $last_id) {
            echo json_encode($last_message);
            exit;
        } 
    }
}

?>