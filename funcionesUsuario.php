<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include 'login.php';
require 'config/database.php';
$db = new dataBase();
$pdo = $db->getPdo();
var_dump($pdo);

if (isset($_SESSION['usuarioObj']) && isset($_SESSION['usuarioObj']->usuario_id)) {

    // Crear una instancia de Usuario con los valores necesarios
    $usuario = new Usuario($usuario['usuario_id'], $usuario['nombres'], $usuario['apellidos'], $usuario['telefono'], $usuario['correo'], $usuario['eWallet'], $usuario['eWallet2'], $usuario['cuentaBancaria'], $usuario['cuentaBancaria2'], $usuario['nivel'], $usuario['fechaRegistro'], $usuario['referido'], $usuario['subusuario'], $usuario['tipoSubusuario']);

// Asignar la instancia de Usuario a $_SESSION['usuarioObj']
$_SESSION['usuarioObj'] = $usuario;


    // $usuarioObj = $_SESSION['usuarioObj'];
    $usuario_id = $usuario->usuario_id;
    echo "El usuario_id es: " . $usuario_id;
    var_dump($usuario_id);
    // Asumiendo que ya tienes una conexión PDO establecida en $db.


    // Obtener los datos del usuario actual
    $query = "SELECT usuario.*, subusuario.*, comunidad100.*
          FROM usuario
          LEFT JOIN subusuario ON usuario.id = subusuario.usuario_id
          LEFT JOIN comunidad100 ON subusuario.id = comunidad100.usuario_id
          LEFT JOIN comunidad500 ON subusuario.id = comunidad500.usuario_id
          LEFT JOIN comunidad1000 ON subusuario.id = comunidad1000.usuario_id
          WHERE usuario.id = :usuario_id";

    $stmtUser = $pdo->prepare($query);

    $stmtUser->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtUser->execute();

    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $id = $usuario_id;
        $auspiciador = $usuario['referido'];
        $auspiciadorDirecto = $usuario['auspiciador_id'];
        $nombres = $usuario['nombres'];
        $apellidos = $usuario['apellidos'];
        $correo = $usuario['correo'];
        $nivel = $usuario['nivel'];
        $cuentaBancaria = $usuario['cuentaBancaria'];
        $cuentaBancaria2 = $usuario['cuentaBancaria2'];
        $eWallet = $usuario['eWallet'];
        $eWallet2 = $usuario['eWallet2'];

        echo " Mi código de ID es: " . $id;
        echo " Mi código de auspiciador es: " . $auspiciador;
        echo " Mi upline es: " . $auspiciadorDirecto;
        echo " Bienvenido: " . $nombres . $apellidos;
        echo " Mi córreo unico es: " . $correo;
        echo " Mi cuenta bancaria es: " . $cuentaBancaria;
        echo " Mi cuenta bancaria adicional es: " . $cuentaBancaria2;
        echo " Mi cuenta cripto es: " . $eWallet;
        echo " Mi cuenta cripto adicional es: " . $eWallet2;
        echo " Mi nivel es: " . $nivel;

        print_r($usuario);
    } else {
        echo "No se encontró ningún usuario con el ID: " . $usuario_id;
    }
}

// session_start();




class Usuario
{
    public $esUsuarioInicial;
    public $usuario_id;
    public $usuarios = [];
    public $subUsuarios = [];
    public $subusuario;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $correo;
    public $eWallet;
    public $eWallet2;
    public $referido;
    public $montoSeleccionado;
    public $tipoSubusuario = [];
    public $nuevoTipoSubusuario;
    public $auspiciador;
    public $auspiciadorDisponible;
    public $auspiciadorDirecto;
    public $subusuariosNivel1 = [];
    public $subusuariosNivel2 = [];
    public $subusuariosNivel3 = [];
    public $subusuariosNivel4 = [];
    public $subusuariosNivel5 = [];
    public $cuentaBancaria;
    public $cuentaBancaria2;
    public $nivel = [0, 1, 2, 3, 4, 5];
    public $arbolBinario1 = [];
    public $arbolBinario2 = [];
    public $arbolActual = 1;
    public $fechaRegistro;

    public function __construct($usuario_id, $nombres, $apellidos, $telefono, $correo, $eWallet, $eWallet2, $cuentaBancaria, $cuentaBancaria2, $nivel, $fechaRegistro, $referido, $subusuario, $tipoSubusuario)
    {
        $this->usuario_id = $usuario_id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->eWallet = $eWallet;
        $this->eWallet2 = $eWallet2;
        $this->cuentaBancaria = $cuentaBancaria;
        $this->cuentaBancaria2 = $cuentaBancaria2;
        $this->nivel = $nivel;
        $this->fechaRegistro = $fechaRegistro;
        $this->referido = $referido;
        $this->subusuario = $subusuario;
        $this->tipoSubusuario = $tipoSubusuario;
    }
    public function generarArbolReferencias()
    {
        $arbol = [];
        $usuarios = $this->usuarios;
        for ($i = 0; $i < count($usuarios); $i++) {
            if ($usuarios[$i]['referido'] != null) {
                $arbol[$usuarios[$i]['referido']] = $usuarios[$i];
            }
        }
        return $arbol;
    }

    public function agregarSubusuario(Usuario $subusuario, $nivel = 1)
    {
        if ($nivel > 5) {
            // Si el nivel es mayor que 5, no permitas agregar más subusuarios
            return "No se puede agregar subusuario más allá del nivel 5";
        } else {
            // Determinar a qué árbol binario se debe agregar el subusuario
            $arbolBinario = null;
            if ($this->arbolActual === 1) {
                $arbolBinario = &$this->arbolBinario1;
                $this->arbolActual = 2; // El próximo irá al árbol 2
            } else {
                $arbolBinario = &$this->arbolBinario2;
                $this->arbolActual = 1; // El próximo irá al árbol 1
            }

            // Verificar que no se exceda el número máximo de subusuarios por nivel en el árbol seleccionado
            $numSubusuariosActual = 0;
            foreach ($arbolBinario as $item) {
                if ($item['nivel'] === $nivel) {
                    $numSubusuariosActual++;
                }
            }

            if ($numSubusuariosActual < 2) {
                $arbolBinario[] = [
                    'nivel' => $nivel,
                    'subusuario' => $subusuario
                ];
                return "Subusuario agregado con éxito.";
            } else {
                $this->obtenerNivelInferiorDisponible();
            }
            // Aquí puedes agregar la lógica para agregar el subusuario
            // Puedes guardarlos en las propiedades subusuariosNivel1, subusuariosNivel2, ..., según corresponda
            switch ($nivel) {
                case 1:
                    $this->subusuariosNivel1[] = $subusuario;
                    break;
                case 2:
                    $this->subusuariosNivel2[] = $subusuario;
                    break;
                case 3:
                    $this->subusuariosNivel3[] = $subusuario;
                    break;
                case 4:
                    $this->subusuariosNivel4[] = $subusuario;
                    break;
                case 5:
                    $this->subusuariosNivel5[] = $subusuario;
                    break;
            }
        }
    }

    public function obtenerNivelInferiorDisponible()
    {
        // Buscar el siguiente nivel disponible entre el nivel 2 al nivel 5
        for ($nivel = 2; $nivel <= 5; $nivel++) {
            // Determinar a qué árbol binario se debe agregar el subusuario
            $arbolBinario = null;
            if ($this->arbolActual === 1) {
                $arbolBinario = &$this->arbolBinario1;
                $this->arbolActual = 2; // El próximo irá al árbol 2
            } else {
                $arbolBinario = &$this->arbolBinario2;
                $this->arbolActual = 1; // El próximo irá al árbol 1
            }

            // Verificar que no se exceda el número máximo de subusuarios por nivel en el árbol seleccionado
            $numSubusuariosActual = 0;
            foreach ($arbolBinario as $item) {
                if ($item['nivel'] === $nivel) {
                    $numSubusuariosActual++;
                }
            }

            // Si hay espacio en este nivel, agregar el subusuario aquí
            if ($numSubusuariosActual < 2) {
                $arbolBinario[] = [
                    'nivel' => $nivel,
                    'subusuario' => $this->subusuario // Asumiendo que $this->subusuario contiene la información del subusuario a agregar
                ];

                // Agregar a la propiedad correspondiente
                switch ($nivel) {
                    case 2:
                        $this->subusuariosNivel2[] = $this->subusuario;
                        break;
                    case 3:
                        $this->subusuariosNivel3[] = $this->subusuario;
                        break;
                    case 4:
                        $this->subusuariosNivel4[] = $this->subusuario;
                        break;
                    case 5:
                        $this->subusuariosNivel5[] = $this->subusuario;
                        break;
                }
                return "Subusuario agregado en el nivel $nivel con éxito.";
            }
        }

        return "No se encontró un nivel inferior disponible";
    }



    function insertarUsuario($pdo, $nombres, $apellidos, $telefono, $correo, $passwordHash, $cuentaBancaria, $cuentaBancaria2, $eWallet, $eWallet2)
    {
        // Preparar la consulta SQL para insertar un nuevo usuario
        $query = "INSERT INTO usuario (nombres, apellidos, telefono, correo, password, cuentaBancaria, cuentaBancaria2, eWallet, eWallet2)
              VALUES (:nombres, :apellidos, :telefono, :correo, :passwordHash, :cuentaBancaria, :cuentaBancaria2, :eWallet, :eWallet2)";
        $stmt = $pdo->prepare($query);

        // Vincular los parámetros a la consulta SQL
        $stmt->bindValue(':nombres', $nombres);
        $stmt->bindValue(':apellidos', $apellidos);
        $stmt->bindValue(':telefono', $telefono);
        $stmt->bindValue(':correo', $correo);
        $stmt->bindValue(':passwordHash', $passwordHash);
        $stmt->bindValue(':cuentaBancaria', $cuentaBancaria);
        $stmt->bindValue(':cuentaBancaria2', $cuentaBancaria2);
        $stmt->bindValue(':eWallet', $eWallet);
        $stmt->bindValue(':eWallet2', $eWallet2);
        // $stmt->bindValue(':nivel', $nivel);
        // $stmt->bindValue(':referido', $referido);

        // Ejecutar la consulta SQL
        if ($stmt->execute()) {
            // Obtiene el ID del último usuario insertado
            $usuario = $pdo->lastInsertId();

            // Crear una nueva instancia de la clase Usuario
            //  $usuario = new Usuario($usuario['nombres'], $usuario['apellidos'], $usuario['telefono'], $usuario['correo'], $usuario['eWallet'], $usuario['eWallet2'], $usuario['cuentaBancaria'], $usuario['cuentaBancaria2'], $usuario['nivel'], $usuario['referido']);



            //     // Retorna la nueva instancia de Usuario
            return $usuario;
        } else {
            return false; // Retorna false si algo salió mal
        }
    }


    public function obtenerUsuario_id($pdo)
    {
        // En este ejemplo, asumo que tienes el nombre de usuario en la sesión
        // y que quieres obtener el ID basado en este nombre de usuario.
        if (isset($_SESSION['usuarioObj']) && isset($_SESSION['usuarioObj']->nombres)) {
            $nombreUsuario = $_SESSION['usuarioObj']->nombres;

            // Preparar consulta para obtener el ID del usuario de la base de datos.
            $query = "SELECT id FROM usuario WHERE nombres = :nombre_usuario";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombreUsuario, PDO::PARAM_STR);
            $stmt->execute();

            // Obtener y retornar el ID del usuario.
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado && isset($resultado['id'])) {
                return $resultado['id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }




    function obtenerValorReferido($pdo, $usuario_id)
    {
        try {
            $query = "SELECT id, referido FROM usuario WHERE id = :lastInsertedId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':lastInsertedId', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            // Obtén los resultados
            $valorReferido = $stmt->fetchColumn();

            // Cerrar el cursor
            $stmt->closeCursor();

            // Devuelve el valor referido
            return $valorReferido;
        } catch (PDOException $e) {
            // Opcional: puedes manejar errores aquí o simplemente devolver null
            // en caso de que algo salga mal
            return null;
        }
    }


    public function obtenerNombreTabla($montoSeleccionado)
    {
        switch ($montoSeleccionado) {
            case 100:
                return 'comunidad100';
            case 500:
                return 'comunidad500';
            case 1000:
                return 'comunidad1000';
            default:
                return false;
        }
    }


    public function actualizarAuspiciadorComunidad($pdo, $nombreTabla, $auspiciadorDirecto, $usuario_id)
    {
        try {
            // Crear consulta SQL para actualizar la tabla
            $query = "UPDATE $nombreTabla SET auspiciador_id = :auspiciador_id WHERE usuario_id = :usuario_id";

            // Preparar y ejecutar la consulta
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':auspiciador_id', $auspiciadorDirecto, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el número de filas afectadas
            $numRows = $stmt->rowCount();
            echo "Número de filas afectadas: $numRows<br>";

            // Verificar si la actualización fue exitosa
            if ($numRows > 0) {
                echo "Auspiciador actualizado con éxito.";
                return true;
            } else {
                echo "Error al actualizar.";
                return false;
            }
        } catch (PDOException $e) {
            // Manejar errores de base de datos
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function getNombre()
    {
        return $this->nombres;
    }

    public function getCorreo()
    {
        return $this->correo;
    }
    public function getCuentaBancaria()
    {
        return $this->cuentaBancaria;
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function mostrarErrores($errores)
    {
        foreach ($errores as $error) {
            echo "<div class='alerta error'>$error</div>";
        }
    }

    public function obtenerDetalleUsuario($pdo)
    {
        if (isset($_SESSION['usuarioObj'])) {
            $usuarioObj = $_SESSION['usuarioObj'];
            $usuario_id = $usuarioObj->usuario_id;
            echo "El usuario_id es: " . $usuario_id;
            var_dump($usuario_id);
            // Asumiendo que ya tienes una conexión PDO establecida en $db.


            // Obtener los datos del usuario actual
            $query = "SELECT usuario.*, subusuario.*, comunidad100.*
                  FROM usuario
                  LEFT JOIN subusuario ON usuario.id = subusuario.usuario_id
                  LEFT JOIN comunidad100 ON subusuario.id = comunidad100.usuario_id
                  LEFT JOIN comunidad500 ON subusuario.id = comunidad500.usuario_id
                  LEFT JOIN comunidad1000 ON subusuario.id = comunidad1000.usuario_id
                  WHERE usuario.id = :usuario_id";

            $stmtUser = $pdo->prepare($query);

            $stmtUser->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtUser->execute();

            $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $id = $usuario_id;
                $auspiciador = $usuario['referido'];
                $auspiciadorDirecto = $usuario['auspiciador_id'];
                $nombres = $usuario['nombres'];
                $apellidos = $usuario['apellidos'];
                $correo = $usuario['correo'];
                $nivel = $usuario['nivel'];
                $cuentaBancaria = $usuario['cuentaBancaria'];
                $cuentaBancaria2 = $usuario['cuentaBancaria2'];
                $eWallet = $usuario['eWallet'];
                $eWallet2 = $usuario['eWallet2'];

                echo " Mi código de ID es: " . $id;
                echo " Mi código de auspiciador es: " . $auspiciador;
                echo " Mi upline es: " . $auspiciadorDirecto;
                echo " Bienvenido: " . $nombres . $apellidos;
                echo " Mi córreo unico es: " . $correo;
                echo " Mi cuenta bancaria es: " . $cuentaBancaria;
                echo " Mi cuenta bancaria adicional es: " . $cuentaBancaria2;
                echo " Mi cuenta cripto es: " . $eWallet;
                echo " Mi cuenta cripto adicional es: " . $eWallet2;
                echo " Mi nivel es: " . $nivel;

                print_r($usuario);
            } else {
                echo "No se encontró ningún usuario con el ID: " . $usuario_id;
            }
        }
    }
}
