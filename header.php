<!---->
<?php
@session_start();
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/headerStyle.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/header.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/searchbarStyle.css">
    <link rel="icon" href="./logo.png">
</head> <!---->

<header>
    <a href="./index.php" title="MVJ">
        <img id="mvjLogo" src="logo.png" alt="" class="logo">
    </a>
    <i class="fa-solid fa-bars fa-2xl hamburger"></i>
    <div class="header-right">
        <div class="searchBar-container">
            <form class="research-form" method="get">
                <input class="input-search input-t" name="q" id="search" type="search" placeholder="Rechercher dans mvj..." />
                <button class="search-btn" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <div>&nbsp;</div>
        <a href="./friends.php" title="Amis"><i class="fa-solid fa-user-group"><i class="label"> amis</i></i></a>
        <a href="./groupe.php" title="Groupes"><i class="fa-solid fa-people-group"><i class="label"> groupes</i></i></a>
        <a href="./market.php" title="Market"><i class="fa-solid fa-store"><i class="label"> magasin</i></i></a>
        <a href="./messenger.php" title="Messagerie"><i class="fa-solid fa-message"><i class="label"> messagerie</i></i></a>
        <a href="./profil.php?<?= isset($_SESSION['userKey']) ? $_SESSION['userKey'] : '' ?>" title="Profil"><i class="fa-solid fa-user"><i class="label"> profil</i></i></a>
        <a href="./index.php?disconnect" title="Déconnexion"><i class="fa-solid fa-right-from-bracket"><i class="label"> deconnexion</i></i></a>
    </div>
</header>