<?php 
require_once("messagesDao.php");
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST)) {
        // get data

        $message = $_POST['message'];
        $sender = UserDao::get_user_by($message['senderKey'], 'userKey');
        $receiver = UserDao::get_user_by($message['friendKey'], "userKey");
        $content = $message['content'];

        if($sender && $receiver) {
            // ajouter message
            if(MessagesDao::add_message($content, $sender['idUser'], $receiver['idUser'])){
                echo json_encode($message);
            }
        }

    }
}


?>