<?php 
require_once('userDao.php');
require_once("messagesDao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['userKey']) && isset($_POST['idGroup'])) {
            $user = UserDao::get_user_by($_POST['userKey'], "userKey");

            if(MessagesDao::quit_group_chat($_POST['idGroup'], $user['idUser'])) {
                echo json_encode(true);
            }
        }
    }

?>