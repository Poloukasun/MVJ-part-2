<?php
require_once("absDao.php");

class GroupDao extends AbstractDao {
    
    public static function user_exists($firstName) {
        $pdo = Database::connect();
        
        $request = "SELECT * FROM Users WHERE firstName = ?";
        $enregistrement = $pdo->prepare($request);
        $enregistrement->execute([$firstName]);
        $resultat = $enregistrement->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $resultat !== null;
    }

    public static function add_group($userId,$nomGroup,$private, $image) {
        $pdo = Database::connect();
        $request = "Call addGroup(?,?,?,?)";
        $statement = self::process_procedure($request, [$userId, $nomGroup, $private, $image], "", $pdo)[0];

        Database::disconnect();
        return $statement->rowCount() > 0;
    }

    public static function Afficher_group()
    {
        $pdo = Database::connect();
        try {
            $sql = "SELECT * from Groups";
            $stmt= $pdo->prepare($sql);
            $stmt->execute();
            
            while($row = $stmt->fetch())
            {
                if($row['imageUrl'] == null)
                {
                    $row['imageUrl'] = '../default-image.jpg';
                }
               echo' <div class="gallery" idGroupe='.$row['idGroupe'].'>
                    <a target="_blank" href="' . $row['imageUrl'] .'">
                    <div class="image-groupe" style="background-image:url('. $row['imageUrl'] .')"></div>
                    </a>
                    <div class="titre-groupe">'. $row['name'] .'</div>
                    <div class="desc"><button groupKey='.$row['idGroupe'].' class="Bgroupe" type="button">Rejoindre le groupe</button></div>
                </div>';
            }
            
            
        } catch (Exception $e) {
            echo "Erreur...";
            exit;
        }
    }
}
