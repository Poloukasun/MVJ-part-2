<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");
    require_once("marketDao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST)) {
          $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
          $idPub = $_POST['idPub'];
          $inTable = MarketDao::pub_in_recording($user['idUser'], $idPub);
          if($inTable === null)
          {
            $enregistrement = MarketDao::add_enregistrement($idPub, $user['idUser']);
            
            echo json_encode($enregistrement);
          }
          else
          {
            echo json_encode("deja enregistrer");
          }
        }
    }
   
?>