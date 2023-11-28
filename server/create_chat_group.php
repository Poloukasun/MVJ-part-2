<?php 
require_once("messagesDao.php");
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['userKey'])) {
        $group = $_POST['group'];
        $lst = $group['lstKeys'];
        $user = UserDao::get_user_by($_POST['userKey'], "userKey");

        if($user) {
            // create group
            $last_id = MessagesDao::create_chat_group($user['idUser'],$group['nom']);
            MessagesDao::add_user_to_group($user['idUser'],$last_id['LastInsertedId']);

            if(sizeof($lst) > 1) {
                for($i = 0; $i < sizeof($lst); ++$i) {
                    MessagesDao::add_user_to_group($lst[$i], $last_id['LastInsertedId']);
                }
            } else {
                echo json_encode("1 et moins");
            }
        }
    }

}


?>