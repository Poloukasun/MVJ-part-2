<?php 
require_once("friendshipsDao.php");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST)) {
            $id_friendship = $_POST['idFriendship'];

            if(FriendshipsDao::delete_friendship($id_friendship)) {
                echo json_encode(true);
            }
        }
    }
?>