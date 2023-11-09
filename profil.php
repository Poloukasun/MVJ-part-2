<?php
session_start();
require_once("./server/userDao.php");
require_once("./sessions.php");
$connected_user = false;

if (!is_logged())
  header("Location:login_register.php");

if (!is_logged())
  header("Location:login_register.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET)) {
    $connected_user = true;
    $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");
    $nbPublication = UserDao::get_pub_by($user['idUser']);
    $friend = UserDao::get_friend_by($user['idUser'], $user['idUser']);
    $publication = UserDao::get_all_pub($user['idUser']);
  } else
    header("Location:login_register.php");
}
if ($user['profilePic'] == null) {
  $user['profilePic'] = "profil-default.jpg";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/profil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script type="module" src="./js/index.js"></script>
  <title>Profil</title>

</head>

<?php
require_once("./header.php");
?>



<body>
  <?php require_once("./header.php"); ?>
  <main>
    <div class="profile-header">
      <div class="profile-image">
        <img src="<?= $user['profilePic'] ?>" alt="<?= $user['firstName'] ?> <?= $user['lastName'] ?>">
      </div>
      <div class="profile-info">
        <h1 class="profile-name"><?= $user['firstName'] ?> <?= $user['lastName'] ?></h1>
        <div class="profile-stats">
          <span class="publications"><?= $nbPublication['nb_pub'] ?> publications</span> |
          <span class="friends"><?= $friend['nb_friend'] ?> amis</span>
        </div>
        <?php if ($connected_user) : ?>
          <button class="btn-modify">Modifier mon profil</button>
        <?php endif; ?>
      </div>
      <div>
        <label for="back">Personnaliser la couleur du fond <input type="checkbox" name="" id="back"></label>
        <input type="color" name="" id="back-color">
      </div>

    </div>

    <hr>

    <?php UserDao::Afficher_pub($user['idUser']); ?>
  </main>
</body>

</html>