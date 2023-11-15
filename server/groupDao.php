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
        $request = "Call addGroup(?,?,?,?)";
        $statement = self::process($request, [$userId, $nomGroup, $private, $image])[1];
        return $statement -> rowCount() > 0;
    }

    public static function Afficher_group($userKey)
    {
        $request = "SELECT G.*, UG.*
        FROM Groups G
        LEFT JOIN User_Group UG ON G.idGroupe = UG.idGroup AND UG.idUser = ?
        WHERE UG.idUser IS NULL or UG.isJoin = 0";
        $stmt = self::process($request, [$userKey])[1]->fetchAll(PDO::FETCH_ASSOC);
        return $stmt;
    }
    public static function user_in_group($connected_user, $id_group) {
        $req = "select * from User_Group where idUser = ? and idGroup = ?";
        $res = self::process($req, [$connected_user['idUser'], $id_group])[1]->fetch(PDO::FETCH_ASSOC);
        if(is_array($res)) {
            return $res;
        }
        return null;
    }
    public static function add_user_group($idUser, $idGroup, $isJoin)
    {
        $request = "Call addUserGroup(?,?,?)";
        $statement = self::process($request, [$idUser, $idGroup, $isJoin])[1];
        return $statement->rowCount() > 0;
    }
    public static function Afficher_group_rejoin($userKey)
    {
        $request = "Call getUserGroups(?)";
        $stmt= self::process($request, [$userKey])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function get_Group($idGroup){
        $request = "SELECT * from Groups where idGroupe = ?";
        $stmt = self::process($request, [$idGroup])[1]->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    public static function get_Members_Group($idGroup, $join){
        $request = "SELECT Count(*) as nbMember from User_Group where idGroup = ? and isJoin=?";
        $stmt = self::process($request, [$idGroup, $join])[1]->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    public static function get_ProfilPic_Members($idGroup){
        $request = "Call getProfilGroup(?)";
        $stmt= self::process($request, [$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function delete_Member_Group($idUser, $idGroup){
        $request = "Call deleteMemberGroup(?,?)";
        $stmt= self::process($request, [$idUser,$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function get_Demand_Group($idGroup){
        $request = "Call getDemandGroup(?)";
        $stmt= self::process($request, [$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function accept_Demand_Group($idUser,$idGroup){
        $request = "Call acceptDemandGroup(?,?)";
        $stmt= self::process($request, [$idUser,$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function add_publication_group ($src,$desc ,$idUser, $idGroup) {
        $request = "Call addPubGroup(?,?,?,?)";
        $statement = self::process($request, [$idUser,$idGroup, $src, $desc])[1];
        return $statement->rowCount()>0;
    } 
    public static function get_all_pub_group($idGroup){
        $request = "Call getAllPubGroup(?)";
        $stmt= self::process($request, [$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function get_member_group($idGroup, $idUser){
        $request = "Call getMemberGroup(?,?)";
        $stmt= self::process($request, [$idGroup, $idUser])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function kick_out_member($idUser,$idGroup){
        $request = "Call kickOutMember(?,?)";
        $stmt= self::process($request, [ $idUser,$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }
    public static function delete_group($idGroup){
        $request = "Call deleteGroup(?)";
        $stmt= self::process($request, [$idGroup])[1]->fetchAll((PDO::FETCH_ASSOC));
        return $stmt;
    }

}
