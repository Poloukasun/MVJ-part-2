<?php 
require_once("userDao.php");
require_once("publicationsDao.php");
require_once("commentDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['commentaire'])) {
        $commentaire = $_POST['commentaire'];
        $user = UserDao::get_user_by($commentaire['userKey'], "userKey");
        $idUser = $user['idUser'];
        

        
        // renvoie le dernier id insere
        $last_in = CommentaireDao::add_comment($commentaire['content'], $idUser, $commentaire['idPublication'])['lastIn'];
        
        if($last_in) {
            $inserted_comment = CommentaireDao::get_comment_by_id($last_in);
            $inserted_comment['idPublication'] = (int)$commentaire['idPublication'];
            echo json_encode($inserted_comment);
        }
    }
}



?>