<?php
     session_start();


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Conectar la base de datos

require 'usuario.php';

require '../includes/config/database.php';
include '../includes/funciones.php';



incluirTemplate('header');


  $db = conectarDB();

$resultado = $_GET['resultado'] ?? null;
$errores = [];
$usuarioObj = '';
$nombreTabla = '';
$RFC = isset($usuario['RFC']) ? $usuario['RFC'] : '';
$tipoSubusuario = isset($usuario['tipoSubusuario']) ? $usuario['tipoSubusuario'] : '';
$validacion100 = $validacion500 = $validacion1000 = $auspiciadorDirecto = 0;
$auspiciador100 = isset($usuario['auspiciador100']) ? $usuario['auspiciador1000'] : 0;
$auspiciador500 = isset($usuario['auspiciador500']) ? $usuario['auspiciador500'] : 0;
$auspiciador1000 = isset($usuario['auspiciador1000']) ? $usuario['auspiciador1000'] : 0;
$nivel100 = isset($usuario['nivel100']) ? $usuario['nivel100'] : 0;
$nivel1000 = isset($usuario['nivel1000']) ? $usuario['nivel1000'] : 0;
$nivel500 = isset($usuario['nivel500']) ? $usuario['nivel500'] : 0;
$tipoSubusuario100 = isset($usuario['tipoSubusuario100']) ? $usuario['tipoSubusuario100'] : '';
$tipoSubusuario500 = isset($usuario['tipoSubusuario500']) ? $usuario['tipoSubusuario500'] : '';
$tipoSubusuario1000 = isset($usuario['tipoSubusuario1000']) ? $usuario['tipoSubusuario1000'] : '';
$montoSeleccionado = isset($usuario['montoSeleccionado']) ? $usuario['montoSeleccionado'] : 0;
$comunidad100 = isset($usuario['comunidad100']) ? $usuario['comunidad100'] : 0;
$comunidad500 = isset($usuario['comunidad500']) ? $usuario['comunidad500'] : 0;
$comunidad1000 = isset($usuario['comunidad1000']) ? $usuario['comunidad1000'] : 0;
$cuentaDepositar100 = isset($usuario['cuentaDepositar100']) ? $usuario['cuentaDepositar100'] : 0;
$cuentaDepositar500 = isset($usuario['cuentaDepositar500']) ? $usuario['cuentaDepositar500'] : 0;
$cuentaDepositar1000 = isset($usuario['cuentaDepositar1000']) ? $usuario['cuentaDepositar1000'] : 0;
$usuariosNivel0 = $usuariosNivel1 = $usuariosNivel2 = $usuariosNivel3 = $usuariosNivel4 = $usuariosNivel5 = [];
$auspiciadorTabla = '';
$password = '';
$imagenPerfil = '';

// Autenticar el Usuario
if($_SERVER ['REQUEST_METHOD'] === 'POST') {
  // echo "<pre>";
// var_dump($_POST);
// echo "</pre>";

  $correo = mysqli_real_escape_string( $db, filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) ;
  $password = mysqli_real_escape_string( $db, $_POST ['password']) ;

    if(!$correo) {
    $errores[] = "El correo es obligatorio o no es valido";
  }

    if(!$password) {
      $errores[] = "La contraseña es obligatoria";
    }
      
    if(empty($errores)) {

      //Revisar si el usuario existe.
         
  $query = "SELECT usuario.*, 
        subusuario.*, comunidad100.*, comunidad500.*, comunidad1000.*,
        comunidad100.tipoSubusuario AS tipoSubusuario100,
        comunidad100.nivel AS nivel100,
        comunidad100.referenciaValidacion AS referencia100,
        comunidad100.validacionMensual AS validacion100,
        comunidad100.auspiciador_id AS auspiciador100,
        comunidad100.cuentaDepositar AS cuentaDepositar100,
        comunidad100.banco AS banco100,
        comunidad500.tipoSubusuario AS tipoSubusuario500,
        comunidad500.nivel AS nivel500,
        comunidad500.referenciaValidacion AS referencia500,
        comunidad500.validacionMensual AS validacion500,
        comunidad500.auspiciador_id AS auspiciador500,
        comunidad500.cuentaDepositar AS cuentaDepositar500,
        comunidad500.banco AS banco500,
        comunidad1000.tipoSubusuario AS tipoSubusuario1000,
        comunidad1000.referenciaValidacion AS referencia1000,
        comunidad1000.nivel AS nivel1000,
        comunidad1000.validacionMensual AS validacion1000,
        comunidad1000.auspiciador_id AS auspiciador1000,
        comunidad1000.cuentaDepositar AS cuentaDepositar1000,
        comunidad1000.banco AS banco1000
 FROM usuario
 LEFT JOIN subusuario ON usuario.id = subusuario.usuario_id
 LEFT JOIN comunidad100 ON usuario.id = comunidad100.usuario_id
 LEFT JOIN comunidad500 ON usuario.id = comunidad500.usuario_id
 LEFT JOIN comunidad1000 ON usuario.id = comunidad1000.usuario_id
WHERE usuario.correo = ?"; // Cambiado :correo por ?

if ($stmt = $db->prepare($query)) {
    $stmt->bind_param("s", $correo); // Agregado el bind_param()
    $stmt->execute();
    $resultado2 = $stmt->get_result();
} else {
    die("Error al preparar la consulta: {$db->errno} - {$db->error}");
}


  }
    
if( $resultado2->num_rows ) {
  // Revisar si el password es correcto
  $usuario = mysqli_fetch_assoc($resultado2);
  // var_dump($usuario);
  // Verificar si el password es correcto o no
  $auth = password_verify($password, $usuario['password']);
  if($auth) {

     
      $id = $usuario['id'];
            $nombres = $usuario['nombres'];
            $apellidos = $usuario['apellidos'];
            $telefono = $usuario['telefono'];
            $correo = $usuario['correo'];
            $eWallet = $usuario['eWallet'];
            $eWallet2 = $usuario['eWallet2'];
            $cuentaBancaria = $usuario['cuentaBancaria'];
            $cuentaBancaria2 = $usuario['cuentaBancaria2'];
            $fechaRegistro = $usuario['fechaRegistro'];
            $referido = $usuario['referido'];
            $auspiciador100 = isset($usuario['auspiciador100']) ? $usuario['auspiciador100'] : 0;
            $auspiciador500 = isset($usuario['auspiciador500']) ? $usuario['auspiciador500'] : 0;
            $auspiciador1000 = isset($usuario['auspiciador1000']) ? $usuario['auspiciador1000'] : 0;
            $referencia100 = isset($usuario['referencia100']);
            $referencia500 = isset($usuario['referencia500']);
            $referencia1000 = isset($usuario['referencia1000']);
            $cuentaDepositar100 = isset($usuario['cuentaDepositar100']);
            $cuentaDepositar500 = isset($usuario['cuentaDepositar500']);
            $cuentaDepositar1000 = isset($usuario['cuentaDepositar1000']);
            $banco100 = isset($usuario['banco100']);
            $banco500 = isset($usuario['banco500']);
            $banco1000 = isset($usuario['banco1000']);
            $tipoSubusuario100 = isset($usuario['tipoSubusuario100']) ? $usuario['tipoSubusuario100'] : '';
            echo $tipoSubusuario100;  // Debería mostrar "B"
            $tipoSubusuario500 = isset($usuario['tipoSubusuario500']) ? $usuario['tipoSubusuario500'] : '';
            $tipoSubusuario1000 = isset($usuario['tipoSubusuario1000']) ? $usuario['tipoSubusuario1000'] : '';
          

            // $comunidad = $usuario ['comunidad'];
            // Verificar si el password es correcto o no
          //echo "<pre>";
          // print_r($usuario);
         // echo "</pre>";exit;


                $usuarioObj = new Usuario($id, $nombres, $apellidos, $RFC, $telefono, $correo, $eWallet, $eWallet2, $cuentaBancaria, $cuentaBancaria2, $referido, $fechaRegistro, $nivel100, $nivel500, $nivel1000, $auspiciadorDirecto, $auspiciador2, $auspiciador3, $auspiciador100, $auspiciador500, $auspiciador1000, $tipoSubusuario, $tipoSubusuario100, $tipoSubusuario500, $tipoSubusuario1000, $referencia100, $referencia500, $referencia1000,$validacion100, $validacion500, $validacion1000, $montoSeleccionado, $cuentaDepositar100, $cuentaDepositar500, $cuentaDepositar1000, $banco100, $banco500, $banco1000, $usuariosNivel0, $usuariosNivel1, $usuariosNivel2, $usuariosNivel3, $usuariosNivel4, $usuariosNivel5, $comunidad100, $comunidad500, $comunidad1000, $imagenPerfil);
               
                $_SESSION['usuarioObj'] = $usuarioObj;

                 if (!empty($errores)) {
                    foreach ($errores as $error) {
                        echo "<p>{$error}</p>";
                    }
                }
               // echo "<pre>";
                //print_r($usuarioObj);
                //echo "</pre>";
               // exit;
     
                session_write_close();
              
                // echo "<pre>";
                // var_dump($_SESSION);
                // echo "</pre>";


                header('Location: dashboard.php?resultado=1');
                exit; // Asegúrate de llamar a exit después de redireccionar
     
  } else {
    $errores[] = 'El password es incorrecto';
  }

} else {
    $errores[] = "El Ususario no existe";

  }
  
  }







//Inclute el Header

?>
 <main class="contenedor2 sombra">
        <?php if ( intval( $resultado ) === 1 ): ?>
      <p class="alerta exito">Registro Exitoso, Bienvenido a la comunidad ABANTU</p>
    <?php endif; ?>


<section class="contacto">
        <form  id="formulario" class="formulario" method="POST" action="">
            <fieldset>
              <legend>Bienvenido a la comunidad Abantu</legend>
     <?php foreach($errores as $error): ?>
    <div class="alerta error">
    <?php echo $error; ?>

    </div>
    <?php endforeach; ?>
             <div class="contenedor-campos">
              <div class="campo">
                <label for="correo">Correo</label>
                <input class="input-text" type="email" placeholder="Correo Electrónico" id="correo" name="correo"  />
              </div class="campo">
    
              <div class="campo">
                <label for="password">Contraseña</label>
                    <div class="password-container">
                <input class="input-text" type="password" placeholder="Tu contraseña" id="password" name="password"  value="<?php echo $password;?>" />
                        <i class="fas fa-eye password-icon" id="togglePassword"></i>
              </div>
              </div>
    
            </fieldset>

            <div class="alinear-derecha">
                <input class="botonform" type="submit" value="Iniciar Sesión" />
              </div>
                <a href="restaurarPassword.php" style="display: block; text-align: center; margin-top: 10px;">¿Olvidaste tu contraseña?</a>

          </form>

    </section>

   
    
         <div class="contenedor sombra">
 <section class="comunidad-intro">
    <p>La comunidad <span class="resaltar">ABANTU</span> es una organización social sin fines de lucro que utiliza la tecnología para <span class="resaltar">contribuir a la igualdad</span>, promoviendo el trabajo en equipo, cooperación y lealtad. Por lo que ha desarrollado una plataforma en línea al servicio de sus usuarios que crea un <span class="resaltar">flujo de capital para el beneficio de la comunidad.</span></p>
</section>
    </div>
     <p class="llamativo" style = "color:white;">
    ¡Todos somos ABANTU!
</p>
         
    </main>
    

    <?php


// require '../'


incluirTemplate('footer');
?>
