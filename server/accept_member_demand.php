<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("groupDao.php");
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            $idGroup = $_POST['idGroup'];
            $idUser = $_POST['idUser'];
            $group = GroupDao::accept_Demand_Group($idUser, $idGroup);
            echo json_encode($group);
        }
    }
   
?>