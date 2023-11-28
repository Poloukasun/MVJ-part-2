<?php 
require_once("messagesDao.php");
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST)) {
        // get data
        $in_groups = null;
        $message = $_POST['message'];
        $sender = UserDao::get_user_by($message['senderKey'], 'userKey');
        $content = $message['content'];
        $type = $message['type'];

        if(isset($_POST['inGroups'])) {
            $in_groups = $_POST['inGroups'] === "true";
        }

        if($sender) {

            if($in_groups) {
                if(MessagesDao::add_group_message($sender['idUser'], $message['idGroupe'], $content, $type)) {
                    echo json_encode($message);
                    exit;
                }
            } else {
                $receiver = UserDao::get_user_by($message['friendKey'], "userKey");
                if(MessagesDao::add_message($content, $sender['idUser'], $receiver['idUser'], $type)) {
                    echo json_encode($message);
                }
            }
        }
    }
}


?>