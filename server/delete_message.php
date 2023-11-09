<?php 
require_once("messagesDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['idMessage'])) {

        $id_message = $_POST['idMessage'];
        if(MessagesDao::delete_message($id_message)) {
            json_encode(true);
        }
    }
}


?>