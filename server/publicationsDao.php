<?php 
require_once("absDao.php");

class PublicationDao extends AbstractDao {
    
    public static function add_publication ($src,$desc ,$idUser) {
        $request = "Call addPublication(?,?,?)";
        $statement = self::process($request, [$idUser, $src, $desc])[1];
        return $statement->rowCount()>0;
    }   

    public static function get_all_pubs () {
        $request = "Call getAllPub()";
        $statement = self::process($request, [])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function get_pub_likes_by_id($id_pub) {
        $request = "Call getLikesPub(?)";
        $statement = self::process($request, [$id_pub])[1]->fetch(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function like_pub($user, $id_pub) {
        $request = "Call toggleLike(?,?)";
        $statement = self::process($request, [$user['idUser'], $id_pub])[1];
        return $statement->rowCount() > 0;
    }
}
