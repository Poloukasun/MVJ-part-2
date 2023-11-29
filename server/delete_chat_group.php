<?php 
require_once("messagesDao.php");
require_once("userDao.php");
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['idGroup'])&&isset($_POST['userKey'])) {
            $user = UserDao::get_user_by($_POST['userKey'], "userKey");

            if(MessagesDao::delete_chat_group($_POST['idGroup'], $user['idUser'])) {
                echo json_encode(true);
            }
        }
    }

?>