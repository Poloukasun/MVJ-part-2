<?php
session_start();
require_once("./header.php");
require_once("./server/userDao.php");
require_once("./sessions.php");
require_once("./server/userDao.php");
$user = null;

if (!is_logged())
    header("Location:login_register.php");

else $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");

?>

<body class="friends">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="module" src="./js/getUsers.js" defer></script>
    <script type="module" src="./js/index.js"></script>
    <script type="module" src="js/research.js"></script>
    <link rel="stylesheet" href="./css/friends.css">

    <div class="no-friends">
        <!-- message -->
    </div>
    <div class="container">
        <div class="friends-container">
            <h2>Amitiés <i title="Tout rafraichir" id="refresh-all" class="fa fa-refresh" aria-hidden="true"></i></h2>
            <button class="all-users choix selected" id="all-btn"> <i class="fa fa-users"></i> Tous les usagers</button>
            <button class="all-friend-btn choix" id="friends"><i class='fas fa-user-friends'></i> Mes amis <span class="nb-amis" style="font-weight: bold;">(0)</span></button>
            <button class="request choix" id="see-requests-btn"><i class='fas fa-door-open'></i> Demandes d'amis reçues <span class="nb-demandes">(0)</span></button>
            <button class="req-sent choix" id="see-sent-req"><i class="fa fa-paper-plane" aria-hidden="true"></i> Demandes d'amis envoyées <span style="font-weight: bold;" class="nb-sent">(0)</span></button>
            <button class="more choix" id="more"><i class="fa fa-plus" aria-hidden="true"></i> Voir plus d'usagers</button>
            <button class="less choix" id="less"><i class="fa fa-minus" aria-hidden="true"></i> Voir moins d'usagers</button>
        </div>
        <span class="message"></span>
        <div class="container-users">
            <!-- rafraichissement -->
        </div>

    </div>
</body>

</html>