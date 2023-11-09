<?php 
require_once("messagesDao.php");

if($_SERVER['REQUEST_METHOD']==='POST') {
    if(isset($_POST['newContent'])) {
        if(MessagesDao::edit_msg($_POST['idmsg'])) {
            echo json_encode(true);
        }
    }
}

?>