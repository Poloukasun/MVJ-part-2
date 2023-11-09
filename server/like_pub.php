<?php 
require_once("userDao.php");
require_once("publicationsDao.php");


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['userKey']) && isset($_POST['idPub'])) {
        $id_pub = $_POST['idPub'];
        $user = UserDao::get_user_by($_POST['userKey'], "userKey");

        if($user) {
            if(PublicationDao::like_pub($user, $id_pub)) {
                $nb_likes_pub = PublicationDao::get_pub_likes_by_id($id_pub);
                echo json_encode($nb_likes_pub);
            }
        }
    }
}


?>