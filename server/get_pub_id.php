<?php 
require_once("publicationsDao.php");
require_once("userDao.php");

if($_SERVER['REQUEST_METHOD']==='POST') {
    if(isset($_POST['id'])&&isset($_POST['userKey'])) {
        $id = $_POST['id'];
        $user = UserDao::get_user_by($_POST['userKey'],"userKey");
        $pub = PublicationDao::get_pub_id($id);
        echo json_encode([$pub['idUser'], $user['idUser']]);
    }
}

?>