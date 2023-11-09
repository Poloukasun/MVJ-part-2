<?php
require_once('userDao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST)) {
        $user = UserDao::get_user_by($_POST['userKey'], 'userKey');
        if ($user) {
            $user['idUser'];
        }
    }
}
