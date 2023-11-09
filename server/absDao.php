<?php
require_once("database.php");

abstract class AbstractDao
{
    public static function process($sql, $parameters) {
        $pdo = Database::connect();

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->execute($parameters);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
            exit;
        }

        $lastId = $pdo->lastInsertId();
        Database::disconnect();

        return [$lastId, $stmt];
    }

    public static function process_fonction($sqlFonction, $parametres)
    {
        $pdo = Database::connect();

        try {
            $statement = $pdo->prepare($sqlFonction);
            for ($i =1; $i <= count($parametres); $i+=1 ) {
                $statement->bindParam($i, $parametres[$i-1]);
            }
            $statement->execute($parametres);
            $returnedFunctionResult = $statement->fetchColumn();
        } catch (Exception $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        Database::disconnect();

        return $returnedFunctionResult;
    }
    public static function process_procedure($sqlProcedure, $parametres,$valeur_out = "", $pdo)
    {
        
        try {
            $statement = $pdo->prepare($sqlProcedure); 
            for ($i = 1; $i <= count($parametres); $i += 1) {
                $statement->bindParam($i, $parametres[$i - 1]);
            }
            $statement->execute($parametres);
            $lastId = $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }


        return [$statement, $lastId];
    }
}
?>