<?php
require_once("absDao.php");
require_once("groupDao.php");
require_once("userDao.php");
require_once("functions.php");
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST)) {
    $pdo = Database::connect();
    $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
    $idGroup = (int)$_POST['idGroup'];
    $isPrivate = (int)$_POST['isPrivate'];
    $res = GroupDao::user_in_group($user, $idGroup);

    if ($res === null) {
      // 0 = En attente et 1 = Rejoin
      if ($isPrivate == 0) {
        $res = GroupDao::add_user_group($user['idUser'], $idGroup, 1);
      } elseif ($isPrivate == 1) {
        $res = GroupDao::add_user_group($user['idUser'], $idGroup, 0);
      }
      if ($res) echo json_encode(true);
    } else {
      echo json_encode("deja dans le groupe");
    }
  }
}
