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

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="module" src="./js/index.js"></script>
    <script type="module" src="./js/publications.js"></script>
    <script type="module" src></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="publications-container" dir="rtl">
        <div class="publication" id="post-form">
            <div class="container">
                <div class="input-container" dir="auto">
                    <div class=" left-section">
                        <a href="" class="profil-pic">
                            <div class="profil-pic-container" style="background-image: url(<?= $pPicUrl ? $pPicUrl : 'default-profile-pic-.jpg' ?>); margin: auto 20px 0 0">
                            </div>
                        </a>
                    </div>
                    <div class="right-section">
                        <textarea id="thought" placeholder="Votre pensée..."></textarea>

                    </div>
                    <label id="share-btn" class="file-label" title="Publier">
                        <i id="share" class="fa fa-paper-plane" aria-hidden="true"></i>
                    </label>
                </div>
                <hr>
                <i class="fa-solid fa-circle-xmark fa-xl cancel none"></i>
                <div class="file-choosed">
                    <span class="empty-file-msg">aucun fichier selectionné</span>
                </div>
                <hr>
                <div class="publish">
                    <label for="choose-image" class="file-label" title="Choisir une image">
                        <i class="fas fa-image" aria-hidden="true"></i>
                    </label>
                    <span><strong>Photo/Video</strong></span>
                    <label for="choose-video" class="file-label" title="Choisir une vidéo">
                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                    </label>
                    <input type="file" id="choose-image" name="image" accept="image/*">
                    <input type="file" name="video" id="choose-video">
                </div>
            </div>
        </div>
    </div>
    <div class="comments" dir="ltr">
    </div>
</body>

</html>