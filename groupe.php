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
  <script type="module" src="./js/getGroups.js" defer></script>
  <link rel="stylesheet" href="./css/styles.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Groupe</title>
</head>
<style>
  body {
    background-color: rgb(220, 220, 220) !important;
  }

  #refresh-all {
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
  }

  #refresh-all:hover {
    transform: translateY(-5px);
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .spin {
    animation: spin 1s linear infinite;
  }
</style>

<body>
  <div class="groupe-main">
    <!-- le nav sur le coter gauche -->
    <div class="groupe-container-group" style="background-color: white;">
      <h1 class="nav-groupe">Groupes <i title="Tout rafraichir" id="refresh-all" class="fa fa-refresh" aria-hidden="true"></i></h1>
      <button class="nav-groupe choix selected" id="all-btn"> <i class="far fa-calendar-alt"></i> Tous les groupes</button><br>
      <button class="nav-groupe choix" id="Create-btn"> <i class="fas fa-plus"></i> Créer un nouveau groupe</button>
      <hr style="width: 90%;">
      <span class="nav-groupe" style="margin-left: 20px;display: block;"><b>Groupes dont vous êtes membre</b></span>
      <!-- Affichage des groupes dont vous êtes membres -->
      <?php //GroupDao::Afficher_group_rejoin($user['idUser']) 
      ?>
      <!-- Affichage des groupes rejoin -->
      <div id="renderYourGroup" class="nav-groupe">

      </div>
    </div>
    <!-- Affichage des groupes -->
    <div id="renderGroup" style="text-align: center;">
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