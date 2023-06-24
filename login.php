<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia la sesión al principio del script
// Conectar la base de datos

// include 'config/database.php';

include 'funcionesUsuario.php';

include 'public/funciones.php';
incluirTemplate('header');


$db = new dataBase();
$pdo = $db->getPdo();

// incluirTemplate('header');

//     $db = new dataBase();
//  $pdo = $db->getPdo();
$resultado = $_GET['resultado'] ?? null;
$errores = [];
$usuarioObj = '';


// Autenticar el Usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$correo) {
        $errores[] = "El correo es obligatorio o no es válido";
    }

    if (!$password) {
        $errores[] = "La contraseña es obligatoria";
    }

    if (empty($errores)) {
        // Revisar si el usuario existe.
        $query = "SELECT * FROM usuario WHERE correo = :correo";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar si el password es correcto o no
            $auth = password_verify($password, $usuario['password']);


            // var_dump($usuario);

            if ($auth) {
                $usuario_id = $usuario['usuario_id'];
                $nombres = $usuario['nombres'];
                $apellidos = $usuario['apellidos'];
                $telefono = $usuario['telefono'];
                $correo = $usuario['correo'];
                $eWallet = $usuario['eWallet'];
                $eWallet2 = $usuario['eWallet2'];
                $cuentaBancaria = $usuario['cuentaBancaria'];
                $cuentaBancaria2 = $usuario['cuentaBancaria2'];
                $nivel = $usuario['nivel'];
                $fechaRegistro = $usuario['fechaRegistro'];
                $referido = $usuario['referido'];
                $subusuario = $usuario['subusuario'];
                $tipoSubusuario = $usuario['tipoSubusuario'];
                
                // Guardar el usuario_id en la sesión
                $usuarioObj = new Usuario($usuario_id, $nombres, $apellidos, $telefono, $correo, $eWallet, $eWallet2, $cuentaBancaria, $cuentaBancaria2, $nivel, $fechaRegistro, $referido, $subusuario, $tipoSubusuario);
                // $_SESSION['usuarioObj'] = new Usuario($usuario['usuario_id'], $usuario['nombres'], $usuario['apellidos'], $usuario['telefono'], $usuario['correo'], $usuario['eWallet'], $usuario['eWallet2'], $usuario['cuentaBancaria'], $usuario['cuentaBancaria2'], $usuario['nivel'], $usuario['fechaRegistro'], $usuario['referido'], $usuario['subusuario'], $usuario['tipoSubusuario']);
               
                $_SESSION['usuarioObj'] = $usuarioObj;
                //  var_dump($_SESSION['usuarioObj']);
                //   exit;
                if (!empty($errores)) {
                    foreach ($errores as $error) {
                        echo "<p>{$error}</p>";
                    }
                }

                $usuario_id = $pdo->lastInsertId();

                session_write_close();
                header('Location: eligeAportacion.php');
                exit; // Asegúrate de llamar a exit después de redireccionar

            } else {
                $errores[] = 'El password es incorrecto';
            }
        } else {
            $errores[] = "El Usuario no existe";
        }
    }
}


?>

<main class="contenedor2 sombra">
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Registrado Correctamente, Gracias por ayudar</p>
    <?php endif; ?>


    <h3 class="felices">Felices de Ayudar</h3>
    <p>
        Abantu es una organización social sin fines de lucro<br />
        utilizando la tecnologia para romper con la desigualdad.
    </p>
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>

        </div>
    <?php endforeach; ?>
</main>
<section class="contacto">
    <form id="formulario" class="formulario" method="POST" action="">
        <fieldset>
            <legend>Bienvenido a la comunidad Abantu</legend>

            <div class="contenedor-campos">
                <div class="campo">
                    <label for="correo">Correo</label>
                    <input class="input-text" type="email" placeholder="Correo Electronico" id="correo" name="correo" />
                </div class="campo">

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input class="input-text" type="password" autocomplete="current-password" placeholder="Tu contraseña" id="password" name="password" />
                </div class="campo">

        </fieldset>

        <div class="alinear-derecha">
            <input class="botonform" type="submit" value="Iniciar Sesion" />
        </div>
    </form>

</section>

<?php


// require '../'


incluirTemplate('footer');
?>