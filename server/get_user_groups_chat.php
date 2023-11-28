<?php 
require_once("messagesDao.php");
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['userKey'])) {
        $user = UserDao::get_user_by($_POST['userKey'],'userKey');
        if($user) {
            $groups_chat_id = MessagesDao::get_user_chat_groups($user['idUser']);
            if(sizeof($groups_chat_id)===1) {
                $id = $groups_chat_id[0]['idGroupChat'];
                $friends_in_group = MessagesDao::get_users_by_groupchat_id($id);

                echo json_encode($friends_in_group);
            } else {
                $groups = array();
                for ($i=0; $i<sizeof($groups_chat_id); ++$i) {
                    $groups["groupe" . $i] = MessagesDao::get_users_by_groupchat_id($groups_chat_id[$i]['idGroupChat']); 
                }
                echo json_encode($groups);  
            }
        }
    }
}

?>