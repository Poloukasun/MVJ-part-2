<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("marketDao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
          $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
            $enregistrement = MarketDao::get_Group($user['idUser']);
            echo json_encode($enregistrement);
        }
    }
   
?>