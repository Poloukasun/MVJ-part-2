<?php 
require_once("userDao.php");
require_once("messagesDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['idGroup'])&&isset($_POST['userKey'])&&isset($_POST['nbMessages'])) {
        
        $_user_id = UserDao::get_user_by($_POST['userKey'], "userKey")['idUser']; 
        $_info_sent = $_POST['infoGroupRecu'] === 'true';
        $_group = MessagesDao::get_chat_group_data($_POST['idGroup'], $_user_id);
        $_messages = MessagesDao::get_messages_group($_POST['idGroup'],$_user_id);
        $_nb_messages = (int)$_POST['nbMessages'];


        if($_group) {

            $data = [];
            $data[0] = $_group; 
            $data[1] = $_messages;

            if(sizeof($_messages) !== $_nb_messages) {
                echo json_encode($data);
                exit;
            } else if(!$_info_sent) {
                echo json_encode($_group);
                exit;
            }
        }
    }
}

?>