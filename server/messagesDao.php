<?php 
require_once("absDao.php");

class MessagesDao extends AbstractDao {
    public static function add_message($content, $id_sender, $id_receiver,$type) {
        $request = "Call InsertMessage(?,?,?,?)";
        $statement = self::process($request, [$content, $id_sender, $id_receiver,$type])[1];
        return $statement->rowCount()>0;
    }

    public static function get_messages_by_users($id_user_connected, $id_friends_choosed, $pdo) {
        $request = "Call getMessagesByUsers(?,?)";
        return self::process_procedure($request, [$id_user_connected, $id_friends_choosed], "", $pdo)[0]->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_messages_with($connected_user, $friend) {
        $request = "Call getMessagesWith(?,?)";
        $statement = self::process($request, [$connected_user['idUser'], $friend['idUser']])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function get_last_message ($connected_user, $friend) {
        $request = "Call getLastMessages(?,?)";
        $statement = self::process($request, [$connected_user['idUser'], $friend['idUser']])[1]->fetch(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function get_deleted_messages() {
        $request = "Call getAllDeleted()";
        $statement = self::process($request, [])[1]->fetch(PDO::FETCH_ASSOC);
        
        return $statement !== null ? $statement : null;
    }

    public static function delete_message($id_message) {
        $request = "Call deleteMessage(?)";
        $response = self::process($request, [$id_message])[1];
        return $response->rowCount() > 0;
    }

    public static function delete_message_group ($id_message) {
        $req = "Call deleteChatMessage(?)";
        $res = self::process($req, [$id_message])[1];

        return $res->rowCount() > 0;
    }
    
    public static function edit_msg($id_msg) {
        $req = "Call editMsg(?)";
        $res = self::process($req, [$id_msg])[1];
        return $res->rowCount() > 0;
    }

    public static function hasEditedMessages($id_friends_choosed, $id_user_connected) {
        $req = "Call EditedMessage(?,?)";
        $res = self::process($req, [$id_user_connected, $id_friends_choosed])[1]->fetch(PDO::FETCH_ASSOC);
        return $res !== null ? $res: null;
    }

    public static function create_chat_group($connected_user_id,$nom) {
        $req = 'Call createGroupChat(?,?)';
        $res = self::process($req, [$connected_user_id,$nom])[1]->fetch(PDO::FETCH_ASSOC);

        return $res !== null ? $res: null;
    }

    public static function add_user_to_group ($id_user,$id_group_chat) {
        $req = "Call addUserGroupChat(?,?)";
        $res = self::process($req,[$id_user,$id_group_chat])[1];

        return $res->rowCount() > 0;
    }

    public static function get_user_chat_groups($id_user) {
        $req = 'Call getMyChatGroups(?)';
        $res = self::process($req, [$id_user])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $res !== null ? $res: null;
    }

    public static function get_users_by_groupchat_id($groups_chat_id) {
        $req = 'Call getUsersByGroup(?)';
        $res = self::process($req, [$groups_chat_id])[1]->fetchAll(PDO::FETCH_ASSOC);
        
        return $res !== null ? $res: null;
    }

    public static function get_chat_group_data($id_group_chat, $id_user) {
        $req = "Call getChatGroupById(?, ?)";
        $res = self::process($req,[$id_group_chat, $id_user])[1]->fetch(PDO::FETCH_ASSOC);

        return $res !== null ? $res: null;
    }

    public static function get_messages_group ($id_group_chat,$_user_id) {
        $req = "Call getGroupMessages(?,?);";
        $res = self::process($req, [$id_group_chat,$_user_id])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $res !== null ? $res: null;
    }

    public static function add_group_message($id_user, $id_group, $content, $type) {
        $req = 'Call addGroupMessage(?,?,?,?)';
        $res = self::process($req, [$id_user, $id_group, $content, $type])[1];

        return $res->rowCount() > 0;
    }
}

?>