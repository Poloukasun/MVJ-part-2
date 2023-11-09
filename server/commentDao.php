<?php 
require_once("absDao.php");

class CommentaireDao extends AbstractDao {
    static public function add_comment($content, $idUser, $idPub) {
        $req = 'Call addComment(?,?,?)';
        $res = self::process($req, [$idUser,$content,$idPub])[1]->fetch(PDO::FETCH_ASSOC);

        return $res;
    }

    static public function get_comments_by_pub($id_pub) {
        $req = "Call getCommentsByPubs(?)";
        $res = self::process($req, [$id_pub])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $res !== null ? $res : null;
    }

    static public function get_comment_by_id($id_comment) {
        $req = "Call getCommentById(?)";
        $res = self::process($req, [$id_comment])[1]->fetch(PDO::FETCH_ASSOC);

        return $res !== null ? $res : null;
    }

    static public function delete_comment($id_comment, $id_pub) {
        $req = "Call deleteComment(?,?)";
        $res = self::process($req, [$id_comment, $id_pub])[1];

        return $res->rowCount()>0;
    }
}



?>