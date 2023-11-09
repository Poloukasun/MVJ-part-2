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
        
        // verifier sil y a des messages supprimees 
        $deleted_message = MessagesDao::get_deleted_messages();

        if($deleted_message) {
            // supprimer ce message et renvoyer les messages a partir du dernier (lastId)
            
            if(MessagesDao::delete_message($deleted_message['idMessage'])) {
                $messages = MessagesDao::get_messages_with($connected_user, $friend);
                echo json_encode($messages);
                exit;
            }
            
        } else {
            if($last_message['idMessage'] > $last_id) {
                echo json_encode($last_message);
                exit;
            }
        }
    }
}

?>