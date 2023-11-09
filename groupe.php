<?php
session_start();
require_once("./header.php");
require_once("./server/userDao.php");
require_once("./sessions.php");
require_once("./server/groupDao.php");
$user = null;

if (!is_logged())
  header("Location:login_register.php");

else $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="./js/getUsers.js" defer></script>
  <script type="module" src="./js/index.js"></script>
  <script type="module" src="./js/getFriendshipsRequets.js"></script>
  <script type="module" src="./js/getFriends.js"></script>
  <script type="module" src="js/research.js"></script>
  <link rel="stylesheet" href="./css/styles.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Groupe</title>
</head>
<style>
  body {
    background-color: rgb(220, 220, 220) !important;
  }

  div.gallery {
    margin: 5px;  
    border: 1px solid #ccc;
    float: left;
    width: 300px;
    height: 320px;
    background-color: white;
  }

  div.gallery:hover {
    border: 1px solid #777;
  }

  div.gallery img {
    width: 100%;
    height: auto;
  }

  div.desc {
    padding: 15px;
    text-align: center;
  }
</style>

<body>
  <div style="display: flex; height:100%">
    <!-- le nav sur le coter gauche -->
    <div class="groupe-container">
      <h1 class="nav-groupe">Groupes</h1>
      <button class="nav-groupe choix selected" id="all-btn"> <i class="far fa-calendar-alt"></i> Tous les groupes</button><br>
      <button class="nav-groupe choix" id="Create-btn"> <i class="fas fa-plus"></i> Créer un nouveau groupe</button>
      <hr style="width: 90%;">
      <span class="nav-groupe"><b>Groupes dont vous êtes membre</b></span><br>
      <a class="tout">Tout voir</a><br>
      <p class="nav-groupe box-groupe"><img style="width: 50px; border-radius:15px;" src="default-image.jpg"><span class="nom-box">Nom du groupe</span></p>
      <p class="nav-groupe box-groupe"><img style="width: 50px; border-radius:15px;" src="default-image.jpg"><span class="nom-box">Nom du groupe</span></p>
      <p class="nav-groupe box-groupe"><img style="width: 50px; border-radius:15px;" src="default-image.jpg"><span class="nom-box">Nom du groupe</span></p>
    </div>
    <!-- Affichage des groupes -->
    <div style="text-align: center;">
      <?php GroupDao::Afficher_group(); ?>
    </div>



</body>

</html>
<script>
  $(document).ready(function() {
    $("#Create-btn").click(function(e) {

      $(this).addClass("selected");
      $("#all-btn").removeClass("selected");
      header("Location:groupCreation.php");
    });
    var btn = document.getElementById('Create-btn');
    btn.addEventListener('click', function() {
      document.location.href = './groupCreation.php';
    });
  });
</script>