<!DOCTYPE html>
<html lang="fr">

<head>
  <title>MVJ</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" href="./css/create.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="./js/inscription.js"></script>
</head>

<body>

  <div class="confirmation" style="display: none;">
    <h2>Inscription réussie!</h2>
  </div>
  
  <!-- login -->
  <div class="container" style="display: none;">
    <img src="./logo.png" alt="" width="50" height="50">
    <div class="header">
      <div class="header-left">
        <h1>S'inscrire</h1>
      </div>
      <div class="header-right">
        <button class="cancel" id="cancel">X</button>
      </div>
    </div>


    <div class="form-row">
      <div class="input-field">
        <input type="text" placeholder="Prénom" id="firstName" required>
      </div>
      <div class="input-field">
        <input type="text" id="secondName" placeholder="Nom de famille" required>
      </div>
    </div>
    <div class="form-row">
      <div class="input-field">
        <input type="email" placeholder="Adresse e-mail" id="email" required>
      </div>
      <div class="input-field">
        <input type="password" placeholder="Mot de passe" id="pass" required>
      </div>
    </div>
    <div class="form-row">
      <div class="input-field">
        <input type="date" id="birthday" name="birthday" required>
      </div>
      <div class="input-field radio-group">
        <div class="gender" data="Homme">Homme</div>
        <div class="gender" data="Femme">Femme</div>
        <div class="gender" data="Autre">Autre</div>
      </div>
    </div>
    <button type="submit" class="signup-button">Créer un compte</button>
    <div class="policy">
      <p>En cliquant sur créer un compte, vous acceptez nos <a href="">Conditions générales</a> , <a href="">Politique de
          confidentialité</a> et notre <a href="">Politique d’utilisation des cookies</a>. Vous recevrez peut-être des
        notifications par texto de notre part et vous pouvez à tout moment supprimer votre compte.
      </p>
    </div>
  </div>

  <div class="container-login">
    <div class="boxLogo">
      <img width="200px;" src="logo.png">
      <div style="font-size: 28px;">Connectez-vous à l'essentiel de votre vie<br>
        Restez proche de ceux qui comptent<br>
        Connectez-vous au coeur de vos relations
      </div>
      <hr>
    </div>
    <div class="boxLogin">
      <form style="text-align: center;" method="POST" class="connexion">
        <h2>Connexion</h2>
        <br>
        <input style="width: 80%;height: 25px;" placeholder="Courriel" type="text" id="courriel" name="courriel" required>
        <br><br>
        <input style="width: 80%; height: 25px;" placeholder="Mot de passe" type="password" id="mot_de_passe" name="mot_de_passe" required>
        <br><br><br>

        <input class="submit" type="submit" value="Se Connecter">
      </form>
      <p style="text-align: center;"><a href="oublie.php">Mot de passe oublié?</a></p>
      <hr style="width: 90%; text-align: center;">
      <p style="text-align: center;"><a href="inscrip.php"><button class="create" id="create-btn">Créer un compte</button></a></p>
    </div>
  </div>

</body>


</html>