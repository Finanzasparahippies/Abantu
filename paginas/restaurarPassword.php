<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require '../includes/funciones.php';
incluirTemplate('header');
require '../includes/config/database.php';
$db = conectarDB();

session_start();

function generateToken($length = 20){
    return bin2hex(random_bytes($length));
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Correo'])) {
        $correo = $_POST['Correo']; // Aquí obtienes el correo electrónico desde el formulario POST

        // Verifica si el correo electrónico existe en la base de datos
        $query = "SELECT * FROM usuario WHERE correo = '$correo'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            // El correo electrónico existe en la base de datos
            $token = generateToken();
            $expiry_time = time() + 3600; // el token expira después de una hora

            $query = "UPDATE usuario SET reset_token = '$token', password_reset_expiry = '$expiry_time' WHERE correo = '$correo'";
            $result = mysqli_query($db, $query);

            $_SESSION['reset_email'] = $correo;
        }
    } else if (isset($_POST['password'])) {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $correo = $_SESSION['reset_email'];

        $query = "UPDATE usuario SET password = '$new_password', reset_token = NULL, password_reset_expiry = NULL WHERE correo = '$correo'";
        $result = mysqli_query($db, $query);

        unset($_SESSION['reset_email']); // eliminamos la variable de sesión para completar el proceso

        // redirige al usuario a la página de inicio de sesión con un mensaje de éxito
        header('Location: login.php?password_reset=success');
        exit();
    }
}

?>





    <main class="contenedor2 sombra">



    </main>
    <section class="RestaurarPassword">
        <form  id="formulario" class="formulario" method="POST" action="">
            <fieldset>
              <legend>Olvidaste tu contraseña?</legend>
    
             <div class="contenedor-campos">
    
              <div class="campo">
                <label for="Correo">Correo</label>
                <input class="input-text" type="email" placeholder="*Tu Email" id="Correo" />
              </div>
    
    
              <div class="alinear-derecha">
                <input class="botonform" type="submit" value="Enviar Codigo" />
              </div>
            </fieldset>
          </form>

    </section>

 <?php
// require '../'
incluirTemplate('footer');
?>

