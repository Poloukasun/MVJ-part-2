<?php 
require_once("userDao.php");
require_once('functions.php');
define("REP_IMAGE", "../profil/");
$uploaded = false;
$filename=null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $target_file = REP_IMAGE . basename($_FILES['file']['name']);
        $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $newfilename = REP_IMAGE . uniqid() . preg_replace('/[^A-Za-z0-9\-]/', '', '-' . str_replace(' ', '-', $_POST['nom'])) . '.' . $filetype;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $newfilename)) {
            $uploaded = true;
            $filename = substr($newfilename, 1);
        }
    }

    if (isset($_POST['userKey'])) {
        $userKey = $_POST['userKey'];
        $user = UserDao::get_user_by($userKey, "userKey");

        $pass = $_POST['pass'];
        $nouveau_pass = $_POST['newPass'];

        if ($pass === '' && $nouveau_pass === '') {
            $pass = $user['passwordHash'];
            $nouveau_pass = $user['passwordHash'];
        }
        
        if (compare($pass, $nouveau_pass) && compare($pass, $user['passwordHash']) && compare($nouveau_pass, $user['passwordHash'])) {
            $nouveau_pass = $pass;
        } else if (!compare($pass, $nouveau_pass) && password_verify($pass, $user['passwordHash'])) {
            $nouveau_pass = password_hash($nouveau_pass, PASSWORD_DEFAULT);
        }

        if(!$filename) {
            $filename = $user['profilePic'];
        }

        $res = UserDao::update_user($_POST['prenom'], $_POST['nom'], $user['idUser'], $nouveau_pass, $_POST['email'], $_POST['bio'], $filename);

        $updatedUser = UserDao::get_user_by($userKey, "userKey");

        if ($res) {
            echo json_encode($updatedUser);
        } else {
            echo json_encode(["error" => "La mise à jour de l'utilisateur a échoué"]);
        }
    } else {
        echo json_encode(["error" => "Aucun userKey fourni"]);
    }
} else {
    echo json_encode(["error" => "La méthode de requête doit être POST"]);
}
?>
