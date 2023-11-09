<?php
session_start();
require_once("./header.php");
require_once("./server/userDao.php");
require_once("./server/groupDao.php");
require_once("./sessions.php");
$user = null;

if (!is_logged())
  header("Location:login_register.php");

else $user = UserDao::get_user_by($_SESSION['userKey'], "userKey");

?>

<body>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script type="module" src="js/groupeCreate.js"></script>
  <link rel="stylesheet" href="./css/styles.css">

  <div style="display: flex; height:100%">
    <!-- le nav sur le coter gauche -->
    <div class="groupe-container">
      <h1 class="nav-groupe">Céer un groupe</h1>
      <div style="display: flex;" class="nav-groupe">
        <img src="profil-default.jpg" style="width: 40px; border-radius:50%;">
        <div style="display: grid; margin-left:10px;">
          <span> <?= $user['firstName'] ?> <?= $user['lastName'] ?></span>
          <span style="font-size: 13px; color:gray;"> Admin</span>
        </div>
      </div><br>
      <form method="POST" class="nav-groupe"> 
        <input name="nom-groupe" id="inputField" class="groupImg" type="text" placeholder="Nom du groupe" maxlength="30" required><br>
        <div style="margin-bottom: 5px;font-size: 18px;">Confidentialité:</div>
        <input type="radio" id="public" name="confidentialite" value="Public">
        <label title="Tout le monde peut voir qui est dans le groupe et ce qui est publié" for="public">Public</label>
        <input type="radio" id="prive" name="confidentialite" value="Privé">
        <label title="Seuls les membres peuvent voir qui est dans le groupe et ce qui est publié" for="prive">Privé</label><br>
        <hr>
        <div style="margin-bottom: 5px;font-size: 18px;margin-top:5px;">Image du groupe:</div>
        <input name="groupe-image" type="file" id="fileInput" required>
      </form>
      <button id="create" name="create" class="createGroup">Créer</button>
    </div>
    <!-- Coter droit -->

    <div class="centered-div2">
      <div class="apercu">Aperçu du groupe</div>
      <!-- <img style="width: 100%; height: 40%;" src="default-image.jpg"><br> -->
      <div class="imageGroup" id="imageDiv"></div>
      <div id="outputDiv" class="name-group">Nom du groupe</div>
      <hr>
      <div class="confidentialite" id="confidentialite">Confidentialité du groupe : 1 membre</div>
      <div class="A-propos">
        <br>
        <div style="margin: 5px;">
          <div id="propos" style="font-size: 25px;font-weight: bold;"></div>
          <div id="Option" style="margin-top: 1%;font-size: 18px;margin-bottom: 1%;"></div>
          <span id="info-Option"></span>
        </div>
      </div>
    </div>
 
  </body>


</html>
<?php
  // define("REP_IMAGE", "../profile/");
  // if ($_SERVER['REQUEST_METHOD'] == "POST") {
  //   print_r('oui');
  //   if(isset($_POST['create'])){
  //     print_r('non');
  //     if(!empty(trim($_POST['nom-groupe'])) && $_POST['confidentialite'] != null && !empty($_POST['groupe-image'])){
  //       $newfilename = REP_IMAGE . $_POST['groupe-image'];
  //       //priver = 1 et public = 0
  //       if($_POST['confidentialite'] == 'Public')
  //       {
  //         $private = 0;
  //       }
  //       else{
  //         $private = 1;
  //       }
  //       GroupDao::add_group($user['idUser'],$_POST['nom-groupe'] , $private, $newfilename);
  //       echo 'Créer' . $user['idUser'] . $_POST['nom-groupe'] . $private . $newfilename;
  //       //header("Location:groupe.php");
  //     }
  //   }
  // }
?>
