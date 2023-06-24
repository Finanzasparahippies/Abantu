<?php



class dataBase
{
    private $pdo;

    private const DSN = 'mysql:host=localhost;dbname=abantu';
    private const USUARIO = 'root';
    private const CONTRASENA = 'FELICESdeayudar4242';

    public function __construct()
    {
        try {
            $this->pdo = new PDO(self::DSN, self::USUARIO, self::CONTRASENA);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error al conectarse a la base de datos: ' . $e->getMessage();
            var_dump($e);

        }
    }

    
    public function prepare($query) {
        return $this->pdo->prepare($query);
    }

     function getPdo() {
         return $this->pdo;
     }
}



   // public function __construct()
    // {
    //     try {
    //         $this->pdo = new PDO(self::DSN, self::USUARIO, self::CONTRASENA);
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         echo 'Error al conectarse a la base de datos: ' . $e->getMessage();
    //         var_dump($e);

    //     }