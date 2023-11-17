<?php
require_once("./header.php");
require_once("./sessions.php");
require_once("./server/userDao.php");
session_start();

if (!is_logged()) {
    header("Location:login_register.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (isset($_GET['disconnect'])) {
        delete_cookie("userKey");
        delete_session();
        header("Location:login_register.php");
        exit;
    }
}

$pPicUrl = UserDao::get_user_by($_SESSION['userKey'], 'userKey')['profilePic'];
?>
<link rel="stylesheet" href="css/about.css">

<body class="about">
    <div class="enHaut">À propos de MVJ</div>
    <div class="auCentre"></div>
    <div class="enBas">
        <div>© MVJ 2023</div>
        <div>&emsp;&nbsp;&nbsp;<b>M</b>ikaël Lévesque</div>
        <div>&emsp;&nbsp;&nbsp;<b>V</b>lad Lukyanov</div>
        <div>&emsp;&nbsp;&nbsp;<b>J</b>ébastien Labrie</div>
    </div>

    </html>