<?php



class DatabaseConnection
{
    private $pdo;

    private const DSN = 'mysql:host=localhost;dbname=abantu';
    private const USUARIO = '';
    private const CONTRASENA = '';

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

    // Resto de los métodos...
    public function getPdo() {
        return $this->pdo;
    }
    
}
$conexion = new DatabaseConnection();
var_dump($conexion->getPdo());


// function conectarDB() : mysqli {
//     $db = mysqli_connect('localhost', 'root', 'FELICESdeayudar4242', 'Abantu');

//     if(!$db) {
//         echo "Error no se pudo conectar";
//         exit;
//     }

//     return $db;
// }