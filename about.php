<?php
require_once("./header.php");
require_once("./sessions.php");
require_once("./server/userDao.php");
?>
<link rel="stylesheet" href="css/about.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="module" src="js/about.js"></script>

<body class="about">
    <!-- <div class="enHaut">À propos de MVJ</div> -->
    <div class="auCentre">
        <!-- <h3 class="realise-par"></h3> -->

        <h3 class="titre-desc">À propos de </h3>
        <!-- debut -->
        <div class="container">
            <div class="logo"></div>
        </div>


        <div class="membre">
            <div class="img">
            </div>
            <div class="info">
                <div><span class="name">Mikaël Lévesque</span></div>
                <p>

                </p>
            </div>
        </div>

        <div class="desc">
            MVJ est un réseau social simplifié, s'inspirant de géants technologiques tels que
            Facebook et Instagram. Ce réseau permet à ses utilisateurs de rechercher
            efficacement d'autres membres inscrits et de découvrir des publications sur la page
            principale. Il offre la possibilité de publier des photos et vidéos,
            ainsi que de communiquer instantanément avec des amis, que ce soit au sein de groupes
            ou en privé. De plus, MVJ permet la création de communautés, privées ou publiques,
            pour un partage de contenu dynamique et interactif.
        </div>




        <div class="photo-grid">
            <a href="./aboutImg/amities.png">
                <div class="photo" style="background-image: url('aboutImg/amities.png');"></div>
            </a>
            <a href="./aboutImg/communautes.png">
                <div class="photo" style="background-image: url('aboutImg/communautes.png');"></div>
            </a>
            <a href="./aboutImg/consulterPubs.png">
                <div class="photo" style="background-image: url('aboutImg/consulterPubs.png');"></div>
            </a>
            <a href="./aboutImg/formPub.png">
                <div class="photo" style="background-image: url('aboutImg/formPub.png');"></div>
            </a>
            <a href="./aboutImg/messagerie.png">
                <div class="photo" style="background-image: url('aboutImg/messagerie.png');"></div>
            </a>
            <a href="./aboutImg/profil.png">
                <div class="photo" style="background-image: url('aboutImg/profil.png');"></div>
            </a>
        </div>


        <div class="btns">
            <i class="fa fa-chevron-left btn-left" id="btnG" title="reculer" aria-hidden="true"></i>
            <p class="nb-p"><i>0</i></p>
            <i class="fa fa-chevron-right btn-right" id="btnR" title="avancer" aria-hidden="true"></i>
        </div>
    </div>
    <div class="enBas">
        <div>© MVJ 2023</div>
        <div>&emsp;&nbsp;&nbsp;<b>M</b>ikaël Lévesque</div>
        <div>&emsp;&nbsp;&nbsp;<b>V</b>lad Lukyanov</div>
        <div>&emsp;&nbsp;&nbsp;<b>J</b>ébastien Labrie</div>
    </div>

</body>

</html>