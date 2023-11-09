<?php
session_start();
require_once("./server/userDao.php");
require_once("./sessions.php");
$connected_user = false;

if (!is_logged())
  header("Location:login_register.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_GET)) {
    $connected_user = true;
    $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");
  }
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
  <!-- <link rel="stylesheet" href="css/styles.css"> -->
  <title>Profil</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/friends.css">
  <!-- <link rel="stylesheet" href="./css/styles.css"> -->
  <script type="module" src="js/modif.js"></script>
  <script type="module" src="js/updateProfil.js"></script>
  <?php require_once("header.php"); ?>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      color: #333;
    }

    fieldset {
      margin: 15px;
      border-radius: 15px;
      border-color: #007bff;
    }

    .update-form {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      margin-top: 100px;
    }

    .centered-div {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
    }

    #Name {
      font-size: 28px;
      font-weight: bold;
      margin: 0;
      color: #007bff;
    }

    #enregistrer {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 18px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    #enregistrer:hover {
      background-color: #0056b3;
    }

    #thumb {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      margin: 20px auto;
      display: block;
      border: 5px solid #fff;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
      cursor: pointer;
      object-fit: cover;
    }

    #thumb:hover {
      transform: scale(1.05);
    }

    .modification {
      margin-top: 20px;
    }

    .title {
      font-size: 28px;
      font-weight: bold;
      color: #007bff;
    }

    label {
      font-size: 18px;
      width: 45%;
      display: inline-block;
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    textarea {
      width: 100%;
      padding: 10px;
      margin: 5px 0 20px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="email"]:focus,
    textarea:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      margin-right: 10px;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #0056b3;
    }

    #inputImage {
      display: none;
    }

    label {
      font-size: larger;
    }

    .mdp,
    .email {
      display: none;
    }

    .btns {
      margin: 2px;
    }

    #notification {
      background-color: #4CAF50;
      color: white;
      text-align: center;
      border-radius: 5px;
      padding: 10px;
      position: fixed;
      left: 50%;
      top: -50px;
      transform: translateX(-50%);
      z-index: 1;
      font-family: Arial, sans-serif;
      transition: top 0.5s ease-in-out;
    }

    .input-field {
      border: none;
    }
  </style>
</head>
<?php require_once("header.php"); ?>

<body class="friends">
  <div id="notification">
    C'est fait!
  </div>
  <form class="update-form" method="POST">
    <div class="centered-div">
      <div class="title-name" id="Name"><?= $user['firstName'] ?> <?= $user['lastName'] ?></div>
      <input name="enregistrer" id="enregistrer" class="enregistrer" type="button" value="Enregistrer">
    </div>
    <div><img id="thumb" title="Cliquer pour choisir une autre photo" src="<?= $user['profilePic'] ?>" alt="Profile Picture"></div>
    <div><input id="inputImage" type="file" class="form-control" name="inputImage" accept=".png, .jpeg, .jpg"></div>
    <br><br>
    <div class="modification">
      <fieldset>
        <legend class="title">Modification du profil</legend>
        <legend class="bio-legend">Biographie</legend>
        <input id="bio" name="bio" type="text" placeholder="Biographie" maxlength="100" value="<?= $user['bio'] ?>">
        <div>
          <legend class="nom-legend">Nom</legend>
          <input id="nom" name="nom" type="text" placeholder="Nom" maxlength="20" value="<?= $user['lastName'] ?>" required>

          <legend class="prenom-legend">Prenom</legend>
          <input id="prenom" name="prenom" type="text" placeholder="Prénom" maxlength="20" value="<?= $user['firstName'] ?>" required>

          <div class="mdp">
            <legend class="mdp-legend">Mot de passe</legend>
            <input id="mdp" name="mdp" type="password" placeholder="Mot de passe actuel" maxlength="40" value=<?= $_SESSION['password'] ?>>

            <legend class="Nmdp-legend">Nouveau mot de passe</legend>
            <input id="Nmdp" name="Nmdp" type="password" placeholder="Nouveau mot de passe" maxlength="40" value=<?= $_SESSION['password'] ?>>
          </div>
          <div class="email">
            <legend class="email-legend">Adresse courriel actuelle</legend>
            <input id="email" name="email" type="email" placeholder="Adresse courriel" maxlength="60" value="<?= $user['email'] ?>" disabled>
            <legend class="Nemail-legend">Nouvelle adresse courriel</legend>
            <input id="Nemail" name="Nemail" type="email" placeholder="Nouvelle adresse courriel" maxlength="60">
          </div>
        </div>

        <div class="btns">
          <button class="button" type="button" id="Modif-mdp">Modifier mot de passe</button>
          <button class="button" type="button" id="Modif-email">Modifier email</button>
        </div>
      </fieldset>
    </div>
  </form>
</body>

</html>