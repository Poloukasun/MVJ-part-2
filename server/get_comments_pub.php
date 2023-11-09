<?php
require_once("commentDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['idPub'])) {
        $comments = CommentaireDao::get_comments_by_pub($_POST['idPub']);
        echo json_encode($comments);
    } else {
        echo json_encode(false);
    }
}


?>