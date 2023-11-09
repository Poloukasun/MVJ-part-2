<?php
require_once("./absDao.php");
require_once("./userDao.php");
require_once("database.php");

if(isset($_GET)) {
    $pdo = Database::connect();
    $user_key = $_GET['key'];
    if(UserDao::confirmer_compte($user_key, $pdo)) {
        Database::disconnect();
        header("Location:../login_register.php");
    }
}
?>
