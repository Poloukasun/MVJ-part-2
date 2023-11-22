<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("marketDao.php");
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
            $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
            $item = MarketDao::get_all_item();
            echo json_encode($item);
        }
    }
   
?>