<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("groupDao.php");
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            $idGroup = $_POST['idGroup'];
            $group = GroupDao::get_Members_Group($idGroup, 1);
            echo json_encode($group);
        }
    }
   
?>