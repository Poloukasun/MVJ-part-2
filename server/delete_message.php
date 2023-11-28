<?php 
require_once("messagesDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['idMessage'])) {
        $id_message = $_POST['idMessage'];
        $_in_groups = $_POST['inGroups'] === "true";

        if($_in_groups) {
            
            if(MessagesDao::delete_message_group($id_message)) {
                echo json_encode(true);
                exit;
            }

        } else {
            
            if(MessagesDao::delete_message($id_message)) {
               json_encode(true);
               exit;
            }
        }
    }
}


?>