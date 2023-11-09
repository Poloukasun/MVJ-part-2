<?php
    
    class Database {
        private static $host = "149.56.128.77";
        public  static $databaseName = "mvj";
        private static $user = "vladz";
        private static $password = "Pommerouge23!";
        private static $charset = "utf8";    
       
        private static $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

        private static $pdo = null;

        public static function connect() {
            try {
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname="
                        . self::$databaseName . ";charset=" . self::$charset, 
                    self::$user, self::$password, self::$options);
            }
            catch (PDOException $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }

            return self::$pdo;
        }

        public static function disconnect() {
            self::$pdo = null;
        }
    }

?>