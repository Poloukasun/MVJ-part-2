<?php 
require_once("friendshipsDao.php");
require_once("userDao.php");
require_once("database.php");

if($_SERVER['REQUEST_METHOD'] === "POST") {
    
    if(isset($_POST)) {
        $connectedUser = UserDao::get_user_by($_POST['connectedUserKey'], "userKey");
        $targetedUser = UserDao::get_user_by($_POST['targetedUserKey'], "userKey");
        $pdo = Database::connect();

        if($connectedUser && $targetedUser) {
            if(FriendshipsDao::create_friendship($connectedUser['idUser'], $targetedUser['idUser'], $pdo)) {
                Database::disconnect();
                echo json_encode(true);
            }
        }
    }
}
    
?>