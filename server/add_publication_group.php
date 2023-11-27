<?php
require("groupDao.php");
require("userDao.php");
define("REP_IMAGE", "../publicationsGroup/");

if (isset($_FILES['file'])) {
    $desc = $_POST['desc'];
    $idGroup = $_POST['idGroup'];
    $target_file = REP_IMAGE . basename($_FILES['file']['name']);
    $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $newfilename = REP_IMAGE . uniqid() . preg_replace('/[^A-Za-z0-9\-]/', '', '-' . str_replace(' ', '-', $desc)) . '.' . $filetype;
    // get user
    $user = UserDao::get_user_by($_POST['userKey'], 'userKey');

    // ajouter 
    if (move_uploaded_file($_FILES['file']['tmp_name'], $newfilename)) {
        $res = GroupDao::add_publication_group($newfilename, $desc, $user['idUser'], $idGroup);
        if ($res) {
            echo json_encode(true);
        }
    } else {
        echo json_encode(false);
    }
}
