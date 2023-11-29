<?php
session_start();
require_once("./header.php");
require_once("./server/userDao.php");
require_once("./sessions.php");
require_once("./server/groupDao.php");
$user = null;

if (!is_logged()) {
  header("Location:login_register.php");
  exit;
} else {
  $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");
}
if ($user['profilePic'] == null) {
  $user['profilePic'] = 'default-profile-pic-.jpg';
}

$query_string = $_SERVER['QUERY_STRING'];

// Utilisez une fonction ou une méthode pour extraire la valeur du paramètre.
$param_value = getParamValue($query_string);
if ($param_value == null) {
  header("Location:./groupe.php");
  exit;
} else {
  $info = GroupDao::get_Group($param_value);
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

if (!GroupDao::user_in_group($user, $param_value)) {
  header('Location:./feed-group.php?' . $_SESSION['idGroup']);
  exit;
} else {
  $_SESSION['idGroup'] = $param_value;
}

$pPicUrl = UserDao::get_user_by($_SESSION['userKey'], 'userKey')['profilePic'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="js/getGroups.js" defer></script>
  <script type="module" src="js/GetInfoGroup.js" defer></script>
  <script type="module" src="js/publications.js" defer></script>
  <script type="module" src="js/index.js" defer></script>
  <script type="module" src="js/feed-group.js" defer></script>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/feed-group.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Groupe</title>
</head>

<body>
  <div class="feed-group-container">
    <!-- le nav sur le coter gauche -->
    <div class="groupe-container">
      <a style="text-decoration: none;" href="./feed-group.php?<?= $param_value ?>"></style>
        <div class="divGroupe">
          <input type="hidden" id="idGroup" idGroup="<?= $param_value ?>">
          <input type="hidden" id="idAdmin" idAdmin="<?= $info['idAdmin'] ?>">
          <input type="hidden" id="idUserOnline" idUser="<?= $user['idUser'] ?>">
          <div class="imageSelect" style='background-image: url(<?= $info['imageUrl'] ?>);'></div>
          <div class="divTitre" style="margin-left: 10px;">
            <div style="font-weight: bold;color:black;"><?= $info['name'] ?></div>
            <div style="font-size:small; color:gray;"><?= $message . " <span id='nbMembre'></span> membres" ?></div>
          </div>
        </div>
      </a>
      <button style="font-size: medium; height:40px; width:90%; margin-left:10px;" class="choix dropbtn" id="toggleButton">
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
               Demande de groupe (<span id="nbDemande"></span>)</button>';
      } else {
        echo '<button style="display:none;" id="btnDemande" style="font-size: medium; height:40px; width:90%; margin-left:10px; margin-top:10px;" class="choix"><i class="fas fa-door-open"></i>
               Demande de groupe (<span id="nbDemande"></span>)</button>';
      }
      ?>
      <a href="./groupe.php"><button class="acceuil">
          <i class="fas fa-home"></i> Accueil de la communauté</button></a>

    </div>
    <!-- Affichage des groupes rejoin -->
    <div style="display: grid; width:100%;overflow-y:scroll;background-color: f1f1f1;" class="container-feed-group">
      <div class="bigDiv" id="groupe">
        <a href="<?= $info['imageUrl'] ?>">
          <div class="imageGeant" style="background-image: url(<?= $info['imageUrl'] ?>);"></div>
        </a>
        <div class="nomGroupe"><?= $info['name'] ?></div>
        <div class="image-container">
        </div>
        <hr style="width: 90%; margin-left:5%">
      </div>
      <!-- Publications affiche ici -->
      <div id="pub" style="background-color: #f1f1f1;" dir="rtl">
        <div class="publication" id="post-form">
          <div class="container">
            <div class="input-container" dir="auto">
              <div class=" left-section">
                <a href="./profil.php?<?= $_SESSION['userKey'] ?>" class="profil-pic">
                  <div class="profil-pic-container" style="background-image: url(<?= $pPicUrl ? $pPicUrl : 'default-profile-pic-.jpg' ?>); margin: auto 20px 0 0">
                  </div>
                </a>
              </div>
              <div class="right-section">
                <textarea id="thought" placeholder="Publier dans le groupe..."></textarea>

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
              <input type="file" name="video" id="choose-video" accept="video/*">
            </div>
          </div>
        </div>
      </div>
      <!-- Affichage des demandes pour rejoindre -->
      <div id="demande" style="display: none;font-size: xx-large;text-align: center;">
      </div>
      <div id="all-Member" style="display: none;font-size: xx-large;text-align: center;">
      </div>
      <footer style="height: 100px"></footer>
    </div>
  </div>

</body>

</html>