<?php
require_once("absDao.php");

class UserDao extends AbstractDao
{

    public static function user_exists($firstName)
    {
        $pdo = Database::connect();

        $request = "SELECT * FROM Users WHERE firstName = ?";
        $enregistrement = $pdo->prepare($request);
        $enregistrement->execute([$firstName]);
        $resultat = $enregistrement->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $resultat !== null;
    }

    public static function add_user($user, $pdo)
    {
        $request = "Call addUser(?,?,?,?,?,?,?)";
        $statement = self::process_procedure($request, [
            $user->get_first_name(),
            $user->get_last_name(),
            $user->get_birth_date(),
            $user->get_password_hash(),
            $user->get_email(),
            $user->get_gender(),
            $user->get_key()
        ], "", $pdo)[0];

        Database::disconnect();
        return $statement->rowCount() > 0;
    }

    public static function get_user_by($value, $colonne)
    {
        $request = "SELECT * FROM Users WHERE $colonne = ?";
        $enregistrement = self::process($request, [$value])[1]->fetch(PDO::FETCH_ASSOC); // voir AbstractDao
        return $enregistrement;
    }

    public static function get_pub_by($value)
    {
        $request = "SELECT Count(*) as nb_pub FROM Publications where idUser = ?";
        $enregistrement = self::process($request, [$value])[1]->fetch(PDO::FETCH_ASSOC); // voir AbstractDao
        return $enregistrement;
    }

    public static function get_friend_by($value, $value1)
    {
        $request = "SELECT Count(*) as nb_friend FROM Friendships where (idUserAsking = ? OR idUserReceiver = ?) AND state = 1";
        $enregistrement = self::process($request, [$value, $value1])[1]->fetch(PDO::FETCH_ASSOC); // voir AbstractDao
        return $enregistrement;
    }

    public static function get_all_pub($value)
    {
        $request = "SELECT * from Publications where idUser = ?";
        $enregistrement = self::process($request, [$value])[1]->fetch(PDO::FETCH_ASSOC); // voir AbstractDao
        return $enregistrement;
    }


    public static function confirmer_compte($key, $pdo)
    {
        $user = self::get_user_by($key, "userKey");
        if ($user !== null) {
            $request = "Call confirmUser(?)";
            self::process_procedure($request, [$key], "", $pdo);
            return true;
        }
        return false;
    }

    public static function is_confirmed($user)
    {
        return $user['confirmed'] === 1;
    }

    public static function get_users($connected_user, $offset)
    {
        $request = "Call getUsers(?,?)";

        return self::process($request, [$connected_user['idUser'], $offset])[1]->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_users_key_word($word, $userId)
    {
        $request = "Call getUsersByKeyWord(?, ?)";
        $statement = self::process($request, [$word, $userId])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function Afficher_pub($value)
    {
        $pub = 0;
        $pdo = Database::connect();
        try {
            $sql = "SELECT * from Publications where idUser = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$value]);
            while ($row = $stmt->fetch()) {
                $pub++;
                if ($pub == 1) {
                    echo '<div style="font-size: 24px; text-align:center;">Publications</div>
                        <br>';
                }
                if ($row['urlImage'] != null) {
                    echo '
                        <div class="responsive">
                        <div class="gallery">
                            <a href="">
                            <img src=publications/' . $row['urlImage'] . ' width="500" height="300">
                            </a>
                        </div>
                        </div>';
                }
            }
            if ($pub == 0) {
                echo '<div style="font-size: 24px; text-align:center;">Aucune publication</div>
                        <br>';
            }
        } catch (Exception $e) {
            echo "Erreur...";
            exit;
        }
    }

    public static function update_user($firstName, $lastname, $userId, $mdp, $email, $bio, $img)
    {
        $request = "Call updateProfile(?,?,?,?,?,?,?)";

        $statement = self::process($request, [$userId, $firstName, $lastname, $mdp, $bio, $email, $img])[1];
        return $statement->rowCount() > 0;
    }

    public static function get_nb_requests($user)
    {
        $request = "Call getNbRequests(?)";
        $statement = self::process($request, [$user['idUser']])[1]->fetchAll(PDO::FETCH_ASSOC);

        return $statement !== null ? $statement : null;
    }

    public static function delete_account($user_id) {
        $req = "Call deleteCompte(?)";
        $res = self::process($req, [$user_id])[1];

        return $res->rowCount() > 0;
    }
}
