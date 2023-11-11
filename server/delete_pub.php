<?php
require_once("publicationsDao.php");
require_once("userDao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idPub']) && isset($_POST['userKey'])) {
        $pubId = $_POST['idPub'];
        $user = UserDao::get_user_by($_POST['userKey'], "userKey");
        $res = PublicationDao::delete_pub($user['idUser'], $pubId);
        echo json_encode($res);
    }
}
