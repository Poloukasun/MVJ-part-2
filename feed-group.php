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
if ($user['profilePic'] == null) {
  $user['profilePic'] = 'default-profile-pic-.jpg';
}


$query_string = $_SERVER['QUERY_STRING'];

// Utilisez une fonction ou une méthode pour extraire la valeur du paramètre.
$param_value = getParamValue($query_string);

if ($param_value == false) {
  header("Location:./groupe.php");
} else {
  $user = UserDao::get_user_by($_SESSION['userKey'], 'userKey');
  if (!GroupDao::user_in_group($user, $param_value)) {
    header("Location:./groupe.php");
  }

  $info = GroupDao::get_Group($param_value);
  $nbMember = GroupDao::get_Members_Group($param_value, 1);
}
if ($info['isPrivate'] == 0) {
  $message = '<i class="fas fa-globe-americas"></i> Groupe (Public) - ';
} else {
  $message = '<i class="fas fa-lock"></i> Groupe (Privé) - ';
}

if ($user['idUser'] == $info['idAdmin']) {
  $divQuitter = false;
} else {
  $divQuitter = true;
}

function getParamValue($query_string)
{
  // Divise la chaîne en utilisant "?" comme séparateur.
  $parts = explode("?", $query_string);

  // Si la chaîne de requête a deux parties (avant et après "?"), la valeur du paramètre est dans la deuxième partie.
  if (count($parts) == 1) {
    return $parts[0];
  } else {
    return false;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="./js/getGroups.js" defer></script>
  <script type="module" src="./js/GetInfoGroup.js" defer></script>
  <script type="module" src="./js/publicationsGroup.js" defer></script>
  <link rel="stylesheet" href="./css/styles.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Groupe</title>
  <style>
    .imageSelect {
      width: 50px;
      height: 50px;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 10px;
    }

    .profileImage {
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border: 1px solid #fff;
    }

    .imageGeant {
      width: 80%;
      height: 70%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      margin-left: 10%;
      box-shadow: 0px 5px 10px gray;
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }

    .divGroupe {
      display: flex;
      align-items: center;
      margin-left: 10px;
      width: 90%;
      margin-bottom: 10px;
      margin-top: 10px;
    }

    .divTitre {
      display: grid;
      /* justify-items: center; */
      align-items: center;
      justify-content: center;
    }

    .dropdown {
      display: none;
      position: absolute;
      background-color: #fff;
      border: 1px solid #ccc;
      right: 5px;
      top: 50px;
      padding: 2px 7px;
      border-radius: 5px;
      user-select: none;
    }

    .acceuil {
      font-size: medium;
      height: 40px;
      background-color: #7dd7a9;
      color: #013701;
      width: 90%;
      margin-left: 10px;
      border: none;
      cursor: pointer;
      border-radius: 10px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .bigDiv {
      width: 100%;
      justify-items: center;
      background-color: white;
      height: 100%;
    }

    .nomGroupe {
      font-size: xx-large;
      font-weight: bold;
      margin-left: 13%;
      margin-top: 1%;
    }

    .image-container {
      display: flex;
      margin-left: 13%;
    }

    .image-container img {
      border: 2px solid #fff;
    }

    /*  */
    .dropbtn {
      font-size: 16px;
      border: none;
      cursor: pointer;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: white;
      min-width: 160px;
      overflow: auto;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
      margin-bottom: 10px;
      margin-left: 17px;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
      cursor: pointer;
    }

    .show {
      display: block;
    }

    .profil-pic-container {
      margin: 5px 10px;
      width: 4em;
      height: 4em;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .container-users {
      height: fit-content;
      grid-area: main;
      width: 100%;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
      grid-gap: 10px;
      justify-content: flex-start;
      align-content: flex-start;
    }

    div.gallery {
      margin: 5px;
      border: 1px solid #ccc;
      float: left;
      width: 180px;
      background-color: white;
      border-radius: 15px;
      margin: 10px;
    }

    div.gallery:hover {
      border: 1px solid #777;
    }

    div.gallery img {
      width: 100%;
      height: 240px;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    div.desc {
      padding: 15px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div style="display: flex; height:100%">
    <!-- le nav sur le coter gauche -->
    <div class="groupe-container">
      <a style="text-decoration: none;" href="./feed-group.php?<?= $param_value ?>"></style>
        <div class="divGroupe">
          <input type="hidden" id="idGroup" idGroup="<?= $param_value ?>">
          <div class="imageSelect" style='background-image: url(<?= $info['imageUrl'] ?>);'></div>
          <div class="divTitre" style="margin-left: 10px;">
            <div style="font-weight: bold;color:black;"><?= $info['name'] ?></div>
            <div style="font-size:small; color:gray;"><?= $message . " " . $nbMember['nbMember'] . " membres" ?></div>
          </div>
        </div>
      </a>
      <button style="font-size: medium; height:40px; width:90%; margin-left:10px;" class="choix dropbtn" onclick="myFunction()" id="toggleButton">
        <i class="fas fa-users-cog"></i> Membres <i class="fas fa-caret-down"></i></button>
      <div id="myDropdown" class="dropdown-content">
        <!-- DropdownList -->
        <a id="All-Members"><i class="fas fa-users"></i> Voir tout les membres</a>
        <?php if (!$divQuitter) {
          $style = "display:none";
          echo '<a id="SupprimerGroupe" href="./groupe.php"><i class="fas fa-trash-alt"></i> Supprimer le groupe</a>';
        } else {
          $style = "display: block;";
        }
        echo '<a style=' . $style . ' id="Quitter" href="./groupe.php"><i class="fas fa-sign-out-alt"></i> Quitter le groupe</a>';
        ?>
      </div>
      <?php
      $nbDemande = GroupDao::get_Members_Group($param_value, 0);
      if ($user['idUser'] == $info['idAdmin'] && $info['isPrivate'] == 1) {
        echo '<button id="btnDemande" style="font-size: medium; height:40px; width:90%; margin-left:10px; margin-top:10px;" class="choix"><i class="fas fa-door-open"></i>
               Demande de groupe (' . $nbDemande['nbMember'] . ')</button>';
      } else {
        echo '<button style="display:none;" id="btnDemande" style="font-size: medium; height:40px; width:90%; margin-left:10px; margin-top:10px;" class="choix"><i class="fas fa-door-open"></i>
               Demande de groupe (' . $nbDemande['nbMember'] . ')</button>';
      }
      ?>
      <a href="./groupe.php"><button class="acceuil">
          <i class="fas fa-home"></i> Acceuil de la communauté</button></a>

    </div>
    <!-- Affichage des groupes rejoin -->
    <div style="display: grid; width:100%;">
      <div class="bigDiv" id="groupe">
        <div class="imageGeant" style="background-image: url(<?= $info['imageUrl'] ?>);"></div>
        <div class="nomGroupe"><?= $info['name'] ?></div>
        <div class="image-container">
        </div>
        <hr style="width: 90%; margin-left:5%">
      </div>
      <!-- Publications affiche ici -->
      <div id="pub" style="background-color: #f1f1f1;;">
        <div style="background-color: gray; background-color: gray;margin-top: 2%; width: 80%;margin-left: 10%;" class="container">
          <?php echo '<div class="profil-pic-container" style="background-image: url(' . $user['profilePic'] . '); background-color:white"></div>' ?>
          <input type="text" id="thought" placeholder="Exprimez-vous...">
          <label for="choose-image"></label>
          <input type="file" name="image" id="choose-image">
          <span id="nomFichier"></span>
          <input type="submit" id="share-btn">
          <div></div>
        </div>
        <div class="publications-container">

        </div>
      </div>
      <!-- Affichage des demandes pour rejoindre -->
      <div id="demande" style="display: none;font-size: xxx-large;text-align: center;">
      </div>
      <div id="all-Member" style="display: none;">
      </div>
    </div>
  </div>

</body>

</html>
<script>
  document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('choose-image').addEventListener('change', function() {
      var nomFichier = document.getElementById('nomFichier');
      nomFichier.textContent = this.files[0].name;
    });
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }


  });

  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
</script>