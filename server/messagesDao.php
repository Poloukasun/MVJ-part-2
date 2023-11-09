<?php 
require_once("absDao.php");

class MessagesDao extends AbstractDao {
    public static function add_message($content, $id_sender, $id_receiver) {
        $request = "Call InsertMessage(?,?,?)";
        $statement = self::process($request, [$content, $id_sender, $id_receiver])[1];
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
        $statement = self::process($request, [$connected_user['idUser'], $friend['idUser']])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement[0] : null;
    }

    public static function get_deleted_messages() {
        $request = "Call getAllDeleted()";
        $statement = self::process($request, [])[1]->fetch(PDO::FETCH_ASSOC);
        
        return sizeof($statement) > 0 ? $statement : false;
    }

    public static function delete_message($id_message) {
        $request = "Call deleteMessage(?)";
        $response = self::process($request, [$id_message])[1];

        return $response->rowCount() > 0;
    }
}

?>