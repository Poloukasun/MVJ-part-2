<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("groupDao.php");
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            $idGroup = $_POST['idGroup'];
            $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
            $group = GroupDao::delete_Member_Group($user['idUser'], $idGroup);
            echo json_encode($group);
        }
    }
   
?>