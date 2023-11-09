<?php 
    require_once("functions.php");
    require_once("userDao.php");
    require_once("friendshipsDao.php");
    require_once("database.php");

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(isset($_GET)) {
            $user = UserDao::get_user_by($_GET['userKey'], "userKey");
    
            $pdo = Database::connect();
            $friendships = FriendshipsDao::get_friendships($user, $pdo);
            Database::disconnect();
    
            echo json_encode($friendships);
        }
    }
   
?>