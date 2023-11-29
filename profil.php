<?php
session_start();
require_once("./server/userDao.php");
require_once("./sessions.php");
$connected_user = false;

if (!is_logged())
  header("Location:login_register.php");


$query_string = $_SERVER['QUERY_STRING'];
$idUserKey = getParamValue($query_string);
if ($idUserKey == null) {
  header("Location:index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET)) {
    $connected_user = true;
    $user = UserDao::get_user_by($idUserKey, "userKey");
    if ($user == null) {
      header("Location:index.php");
      exit;
    }
    $nbPublication = UserDao::get_pub_by($user['idUser']);
    $friend = UserDao::get_friend_by($user['idUser'], $user['idUser']);
    $publication = UserDao::get_all_pub($user['idUser']);
  } else
    header("Location:login_register.php");
}
if ($user['profilePic'] == null) {
  $user['profilePic'] = "profil-default.jpg";
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

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/profil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="./js/index.js"></script>
  <script type="module" src="./js/profil.js"></script>
  <title>Profil</title>
  <style>

  </style>
</head>

<?php
require_once("./header.php");
?>



<body>
  <?php require_once("./header.php"); ?>
  <main>
    <div class="profile-header" style="background-color: <?= isset($_COOKIE['couleur_background']) ? $_COOKIE['couleur_background'] : '' ?>">
      <a href="<?= $user['profilePic'] ?>" class="profile-image-a">
        <div class="profile-image" style="background-image: url(<?= $user['profilePic'] ?>);"></div>
      </a>
      <div class="profile-info">
        <h1 class="profile-name"><?= $user['firstName'] ?> <?= $user['lastName'] ?>
          <?php if ($connected_user && $idUserKey == $_SESSION['userKey']) : ?>
            <br>
            <button class="btn-modify"><i class="fa-solid fa-pen-to-square"></i> Modifier mon profil</button>
            <button id="voirEnregistrement"><i id="font" class="fas fa-bookmark"></i> Enregistrements</button>
          <?php endif; ?>
        </h1>
        <div class="profile-stats">
          <span class="publications"><?= $nbPublication['nb_pub'] ?> publications</span> |
          <span class="friends"><?= $friend['nb_friend'] ?> amis</span>
        </div>
        <div class="bio"><?= $user['bio'] ?></div>
      </div>
      <?php if ($connected_user && $idUserKey == $_SESSION['userKey']) : ?>
        <div>
          <label for="back">Personnaliser la couleur du fond <input type="checkbox" name="" id="back"></label>
          <input type="color" name="" id="back-color">
        </div>
      <?php endif; ?>

    </div>

    <hr>
    <div id="titreEnre" style="display: none;font-size: 24px; text-align:center;"></div>
    <div id="pubUser"><?php UserDao::Afficher_pub($user['idUser']); ?></div>
    <div id="pubEnre" style="display: none;flex-wrap: wrap;"></div>
  </main>
</body>

<footer style="height: 100px;"></footer>

</html>