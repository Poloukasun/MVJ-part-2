<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("groupDao.php");
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
            $idGroup = $_POST['idGroup'];
            $group = GroupDao::get_ProfilPic_Members($idGroup);
            echo json_encode($group);
        }
    }
   
?>