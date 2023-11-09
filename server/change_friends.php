<?php 
require("userDao.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST)) {
        $friend_key = $_POST['friendKey'];
        $friend = UserDao::get_user_by($friend_key, "userKey");

        if($friend) {
            echo json_encode($friend);
        }
    }
}
?>