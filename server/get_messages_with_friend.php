<?php 
require_once("userDao.php");
require_once("messagesDao.php");
require_once("database.php");

if($_SERVER['REQUEST_METHOD']==='POST') {
    if(isset($_POST['userKey']) && isset($_POST['friendKey']) && isset($_POST['last'])) {
        $connected_user = UserDao::get_user_by($_POST['userKey'], "userKey");
        $friend = UserDao::get_user_by($_POST['friendKey'], "userKey");
        $last_nb = (int)$_POST['last'];

        if($connected_user && $friend) {
            $messages = MessagesDao::get_messages_with($connected_user, $friend);
            if(sizeof($messages) !== $last_nb) {
                echo json_encode($messages);
            } 
        }
    }
}
?>
