<?php 
require_once("absDao.php");

class FriendshipsDao extends AbstractDao {


    public static function create_friendship ($id_asking, $id_receiver, $pdo) {
        $request = "Call addFriendship (?,?)";
        $statement = self::process_procedure($request, [$id_asking, $id_receiver], "",$pdo)[0];

        return $statement->rowCount();
    }

    public static function get_friendships ($connected_user, $pdo) {
        $request = "Call getFriendshipsByUserId(?)";

        $statement = self::process_procedure($request, [$connected_user['idUser']], "",$pdo)[0];

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_friends_user($connected_user) {
        $request = "Call getFriendsConnectedUser(?)";
        $statement = self::process($request, [$connected_user['idUser']])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

   
     public static function get_users_frienships_request ($connected_user) {
        $request = "Call getUserFrienshipsRequets(?)";
        $statement = self::process($request, [$connected_user['idUser']])[1];

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete_friendship ($id_friendship) {
        $request = "Call rejectFriendship(?)";
        $statement = self::process($request, [$id_friendship])[1];

        return $statement->rowCount() == 1;
    }

    public static function delete_friendship_by_users_id($user_connected, $user_target) {

        $request = "Call deleteFriendshipByUsersId(?,?)";
        $statement = self::process($request, [$user_connected['idUser'], $user_target['idUser']])[1];

        return $statement->rowCount() > 0;
    }

    public static function get_friendships_on_wait($user) {
        $request = "Call getFriendshipsOnWaitByUser(?)";
        $statement = self::process($request, [$user['idUser']])[1]->fetchAll(PDO::FETCH_ASSOC);

        
        return $statement !== null ? $statement : null;
    }

    public static function accept_friendship($id_friendship) {
        $request = "Call acceptFriendship(?)";
        $statement = self::process($request, [$id_friendship])[1];

        return $statement->rowCount() > 0;
    }

    public static function get_nb_friends($user) {
        $request = "Call nbFriendsUser(?)";
        $statement = self::process($request, [$user['idUser']])[1]->fetch(PDO::FETCH_ASSOC);

        return $statement['nbFriends'];
    }

    public static function statement_if_not_null($statement) {
        return $statement !== null ? $statement : null;
    }

    public static function get_friends_connected_user_by_key_word ($connected_user, $keyword) {
        $request = "Call getFriendsConnectedUserByKeyWord(?,?)";
        $statement = self::process($request, [$connected_user['idUser'], $keyword])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }
}

?>