<?php
require_once("absDao.php");

class MarketDao extends AbstractDao
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

  public static function get_all_item()
  {
    $request = "SELECT * from market";
    $stmt = self::process($request, [])[1]->fetchAll(PDO::FETCH_ASSOC);
    return $stmt;
  }

  public static function add_enregistrement($idPub, $idUser)
  {
    $request = "Call addBookmark(?,?)";
    $stmt = self::process($request, [$idPub, $idUser])[1];
    return $stmt->rowCount() > 0;
  }

  public static function get_Group($idUser)
  {
    $request = "SELECT r.*, p.urlImage, p.isImage, p.description FROM `Recordings` r LEFT JOIN Publications p ON r.idPub = p.idPublication WHERE r.idUser = ? ORDER BY r.idRecording DESC";
    $stmt = self::process($request, [$idUser])[1]->fetchAll(PDO::FETCH_ASSOC);
    return $stmt;
  }
  public static function pub_in_recording($idUser, $idPub)
  {
    $req = "select * from Recordings where idUser = ? and idPub = ?";
    $res = self::process($req, [$idUser, $idPub])[1]->fetch(PDO::FETCH_ASSOC);
    if (is_array($res)) {
      return $res;
    }
    return null;
  }
}
