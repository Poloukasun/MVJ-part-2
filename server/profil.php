<?php
session_start();
require_once("./server/userDao.php");
require_once("./sessions.php");
$connected_user = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET)) {
    $connected_user = true;
    $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");
    $nbPublication = UserDao::get_pub_by($user['idUser']);
    $friend = UserDao::get_friend_by($user['idUser']);
    $publication = UserDao::get_all_pub($user['idUser']);
  }
  else
  header("Location:login_register.php");
}
if($user['profilePic'] == null)
{
  $user['profilePic'] = "profil-default.jpg";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script type="module" src="/js/index.js"></script>
  <title>Profil</title>

</head>

<?php
require_once("./header.php");
?>



<body>

  <div style="display: flex; background-color: rgb(110, 170, 214); color: white; align-items:center;">
    <div >
      <img src="<?= $user['profilePic'] ?>" class="profil-image">
    </div>
    <div style="display: block;">
      <div style="display: flex; margin-left: 20px;">
        <div style="font-size: 28px"><?= $user['firstName'] ?> <?= $user['lastName'] ?></div><br>
        <br>

      </div>
      <div style="display: flex; justify-content: space-evenly; margin-left: 20px;">
        <div class="container-info">
          <span id="Pub"><?= $nbPublication['nb_pub'] ?> publication</span> |
          <span id="Amis"><?= $friend['nb_friend'] ?> ami(e)s</span> 
          <?php
          if ($connected_user) : ?>
          <br>
            <a href="modif.php">
            <button class="btn-modify">Modifier mon profil</button></a>
          <?php endif; ?>
        </div>
      </div>

    </div>
    <br>


  </div>

  <hr>
  <div style="font-size: 24px; text-align:center;">Publications</div>
  <br>
  <!--Exemple-->
  <div class="responsive">
    <div class="gallery">
      <a href="">
        <img src="default-image.jpg" width="500" height="300">
      </a>
    </div>
  </div>

  <div class="responsive">
    <div class="gallery">
      <a href="">
        <img src="default-image.jpg" width="500" height="300">
      </a>
    </div>
  </div>

  <div class="responsive">
    <div class="gallery">
      <a href="">
        <img src="default-image.jpg" width="500" height="300">
      </a>
    </div>
  </div>

  <div class="responsive">
    <div class="gallery">
      <a href="">
        <img src="default-image.jpg" width="500" height="300">
      </a>
    </div>
  </div>
</body>

</html>

