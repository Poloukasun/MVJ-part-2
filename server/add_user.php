<?php
require_once("absDao.php");
require_once("user.php");
require_once("userDao.php");
require_once("functions.php");
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST)) {
        $pdo = Database::connect();
        $data = $_POST['data'];
        $password_hashed = password_hash($data['pass'], PASSWORD_DEFAULT);
        $key = generate_unique_key();
        $user = new User(
            $data['firstName'],
            $data['secondName'],
            $data['birthDate'],
            $data['email'],
            -1,
            'profil/default-image-pic-.jpg',
            $data['gender'],
            $password_hashed,
            $key
        );

        if (UserDao::add_user($user, $pdo)) {
            send_mail($user, "Confirmation");
            Database::disconnect();
            echo json_encode("added");
        }
    }
}
