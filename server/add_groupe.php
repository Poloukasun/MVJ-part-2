<?php
// require("publicationsDao.php");
require("userDao.php");
define("REP_IMAGE", "../profile/");

// if($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if(isset($_POST)) {
//         echo json_encode($_POST);
//     }
// }

// if (isset($_FILES['file'])) {
//     var_dump($_FILES['file']);
//     $desc = $_POST['desc'];
//     $target_file = REP_IMAGE . basename($_FILES['file']['name']);
//     $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
//     $newfilename = REP_IMAGE . $desc . "." . $filetype;
//     echo $newfilename;
//     // get user
//     $user = UserDao::get_user_by($_POST['userKey'], 'userKey');

//     move_uploaded_file($_FILES['file']['tmp_name'], $newfilename);
//         // ajouter 
//     $res = PublicationDao::add_publication($newfilename, $desc, $user['idUser']);
//     if($res) {
//         echo json_encode(true);
//     }
// }