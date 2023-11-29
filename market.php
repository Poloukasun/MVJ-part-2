<?php
session_start();
require_once("./header.php");
require_once("./server/userDao.php");
require_once("./sessions.php");
require_once("./server/marketDao.php");
$user = null;

if (!is_logged())
  header("Location:login_register.php");

else $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");
if ($user['profilePic'] == null) {
  $user['profilePic'] = 'default-profile-pic-.jpg';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marketplace</title>
  <link rel="stylesheet" href="./css/friends.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="./js/getMarket.js" defer></script>
</head>
<style>
  body {
    background-color: lightgrey;
  }

  .acceuil {
    font-size: medium;
    height: 40px;
    background-color: #7dd7a9;
    color: #013701;
    width: 100%;
    border: none;
    cursor: pointer;
    border-radius: 10px;
  }

  .inputPrix {
    font-size: 16px;
    outline: none;
    border: 0.5px solid;
    width: 45%;
    background-color: #e9e5e5;
    border-radius: 5px;
    height: 40px;
  }
</style>

<body>
  <div class="container">
    <div class="friends-container" style="padding: 20px;">
      <h2>Marché MVJ <i title="Tout rafraichir" id="refresh-all" class="fa fa-refresh" aria-hidden="true"></i></h2>
      <span style="font-weight: bold;font-size: 20px;margin-bottom: 5px;">Résultats de la recherche</span>
      <input type="search" style="border: 0.5px solid gray;width: 100%;height: 4%;background-color: #e7e7e7;color: black;" placeholder="Rechercher dans MVJ">
      <hr style="width: 100%;">
      <a href=""><button class="acceuil">
          <i class="fas fa-plus"></i> Ajouter un article</button></a>
      <hr style="width: 100%;">
      <span style="font-weight: bold;font-size: 20px;margin-bottom: 10px;">Filtres</span>
      <span style="font-size: 18px;margin-bottom: 5px;">Prix:</span>
      <div>
        <input id="Min" placeholder="Min." type="number" class="inputPrix">
        <span style="width: 10%;text-align:center;">à</span>
        <input id="Max" placeholder="Max." type="number" class="inputPrix">
      </div>
      <span style="font-size: 18px;margin-bottom: 5px;margin-top:5px;">Trier prix par:</span>
      <div style="display: flex;margin-bottom: 5px;">
        <label for="asc">Ascendant </label>
        <button id="asc" style="position: absolute;left: 110px;"><i class="fas fa-arrow-up"></i></button>
      </div>

      <div style="display: flex;">
        <label for="desc">Descendant </label>
        <button id="desc" style="position: absolute;left: 110px;"><i class="fas fa-arrow-down"></i></button>
      </div>
      <hr style="width: 100%;">
    </div>
    <span class="message"></span>
        <div class="container-users">
          <!-- <div class="profil">
            <div style="background-image:url(default-profile-pic-.jpg)" class="photo-profil">
            </div>
            <div style="margin: 10px;">100 000$</div>
            <div class="nom">Golf R</div>
          </div>
        
          <div class="profil">
            <div style="background-image:url(default-profile-pic-.jpg)" class="photo-profil">
            </div>
            <div style="margin: 10px;">0.1$</div>
            <div class="nom">Fucking Monkey</div>
          </div> -->
        </div>
  </div>


</body>

</html>
<script>
  var numberMin = document.getElementById('Min');
  var numberMax = document.getElementById('Max');

  numberMin.addEventListener('focus', function() {
    this.style.borderColor = '#7dd7a9';
  });
  numberMin.addEventListener('blur', function() {
    this.style.borderColor = 'black';
  });

  numberMax.addEventListener('focus', function() {
    this.style.borderColor = '#7dd7a9';
  });
  numberMax.addEventListener('blur', function() {
    this.style.borderColor = 'black';
  });
</script>