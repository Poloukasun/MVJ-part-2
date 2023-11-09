<?php 
require_once("commentDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['idComment'])) {
        if(CommentaireDao::delete_comment($_POST['idComment'], $_POST['idPub'])) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(null);
    }
}
?>