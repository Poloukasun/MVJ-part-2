<?php
require("publicationsDao.php");
require("userDao.php");
define("REP_IMAGE", "../publications/");

if (isset($_FILES['file'])) {
    $desc = $_POST['desc'];
    $isImage = $_POST['isImage'];
    $target_file = REP_IMAGE . basename($_FILES['file']['name']);
    $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $newfilename = REP_IMAGE . uniqid() . preg_replace('/[^A-Za-z0-9\-]/', '', '-' . str_replace(' ', '-', $desc)) . '.' . $filetype;
    // get user
    $user = UserDao::get_user_by($_POST['userKey'], 'userKey');

    // ajouter 
    if (move_uploaded_file($_FILES['file']['tmp_name'], $newfilename)) {
        $res = PublicationDao::add_publication(substr($newfilename, 1), $desc, $user['idUser'], $isImage);
        if ($res) {
            echo json_encode(true);
        }
    } else {
        echo json_encode(false);
    }
}
