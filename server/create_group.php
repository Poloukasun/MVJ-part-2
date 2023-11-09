<?php
require("groupDao.php");
require("userDao.php");
define("REP_IMAGE", "../profil/");

if (isset($_FILES['file'])) {

  $target_file = REP_IMAGE . basename($_FILES['file']['name']);

  $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  $user = UserDao::get_user_by($_POST['userKey'], 'userKey');

  $newfilename = REP_IMAGE . uniqid() . preg_replace('/[^A-Za-z0-9\-]/', '', '-' . str_replace(' ', '-', $_POST['nom'])) . '.' . $filetype;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $newfilename)) {
    $filename = substr($newfilename, 1);

    $res = GroupDao::add_group($user['idUser'], $_POST['nom'], $_POST['isPrivate'], $filename);

    if ($res) echo json_encode(true);
  } else {
    echo json_decode(false);
  }
}
