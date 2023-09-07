<?php



//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//Base de Datos
require '../includes/config/database.php';

require 'usuario.php';

session_start();

require '../includes/funciones.php';

incluirTemplate('header');

$db = conectarDB();

  $errores = [];


$id = $_SESSION['usuarioObj']->id ?? null; 


// Consulta para obtener los datos del usuario
$query = "SELECT * FROM usuario WHERE id = ${id}";
$resultado = mysqli_query($db, $query);
$usuario = mysqli_fetch_assoc($resultado);
$referidoAntiguo = $usuario['referido'];
$referidoNuevo = $usuario['referido'];

// Verificamos que haya datos
if(!$usuario) {
    header('Location: /index.php');
    exit;
}

// Después de obtener $usuario de la base de datos
$usuario_id = $usuario['id'] ?? "";
$RFC = $usuario['RFC'] ?? "";
$nombres = $usuario['nombres'] ?? "";
$apellidos = $usuario['apellidos'] ?? "";
$telefono = $usuario['telefono'] ?? "";
$correo = $usuario['correo'] ?? "";
$cuentaBancaria = $usuario['cuentaBancaria'] ?? "";
$tarjetaBancaria = $usuario['TarjetaBancaria'] ?? "";
$CLABE = $usuario['CLABE'] ?? "";
$cuentaBancaria2 = $usuario['cuentaBancaria2'] ?? "";
$eWallet = $usuario['eWallet'] ?? "";
$eWallet2 = $usuario['eWallet2'] ?? "";
$password = $usuario['password'] ?? "";
$confirmPassword = $usuario['password'] ?? "";
$imagenPerfil = $usuario['ImagenPerfil'] ?? "";

// ... y así sucesivamente para todas las variables que quieras mostrar.


if(!$id) {
    header('Location: /index.php');
    exit;
}



if($_SERVER['REQUEST_METHOD'] === 'POST') {
 $nombres = mysqli_real_escape_string ( $db, $_POST ['nombres'] );
  $apellidos = mysqli_real_escape_string( $db, $_POST ['apellidos'] );
  $telefono = mysqli_real_escape_string( $db, $_POST ['telefono'] );
  $correo = mysqli_real_escape_string( $db, filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) ;
  $password = mysqli_real_escape_string( $db, $_POST ['password'] );
  $confirmPassword = $_POST ['confirmPassword'] ;
  $RFC = $_POST['RFC'];
  $cuentaBancaria = mysqli_real_escape_string( $db, $_POST ['cuentaBancaria'] );
  $cuentaBancaria2 = mysqli_real_escape_string( $db, $_POST ['cuentaBancaria2'] );
  $eWallet = mysqli_real_escape_string( $db, $_POST ['eWallet'] );
  $eWallet2 = mysqli_real_escape_string( $db, $_POST ['eWallet2'] );
  $imagenPerfil = isset($_POST['imagenPerfil']) ? mysqli_real_escape_string($db, $_POST['imagenPerfil']) : '';
  $fechaRegistro = mysqli_real_escape_string( $db, date('Y/m/d H:i:s') );
  $nivel100 = isset($usuario['nivel100']) ? $usuario['nivel100'] : 0;
  $nivel500 = isset($usuario['nivel500']) ? $usuario['nivel500'] : 0;
  $nivel1000 = isset($usuario['nivel1000']) ? $usuario['nivel1000'] : 0;
 // $referidoNuevo = mysqli_real_escape_string($db, $_POST['referido']);


  $passwordHash = password_hash($password, PASSWORD_DEFAULT);
   
    $campos = [];

    if(!empty($nombres)) {
        $campos[] = "nombres = '{$nombres}'";
    }

    if(!empty($apellidos)) {
        $campos[] = "apellidos = '{$apellidos}'";
    }

    if(!empty($telefono)) {
        $campos[] = "telefono = '{$telefono}'";
    }

    if(!empty($correo)) {
        $campos[] = "correo = '{$correo}'";
    }

    // Asegurarse de que la contraseña y confirmación coinciden y no están vacías
    if(!empty($password) && $password == $confirmPassword) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $campos[] = "password = '{$passwordHash}'";
    } elseif(!empty($password) || !empty($confirmPassword)) {
        $errores[] = "La contraseña y su confirmación no coinciden.";
    }

    if(!empty($RFC)) {
        $campos[] = "RFC = '{$RFC}'";
    }

    if(!empty($cuentaBancaria)) {
        $campos[] = "cuentaBancaria = '{$cuentaBancaria}'";
    }

    if(!empty($eWallet)) {
        $campos[] = "eWallet = '{$eWallet}'";
    }
    if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagenPerfil']['tmp_name'];
        $fileName = $_FILES['imagenPerfil']['name'];
        $fileSize = $_FILES['imagenPerfil']['size'];
        $fileType = $_FILES['imagenPerfil']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Configurar las extensiones permitidas
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions, true)) {
            // Cambiar el nombre del archivo (opcional)
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            // Directorio donde se guardará el archivo
            $dest_path = "../uploads/imagenesPerfil/" . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                 optimizarImagen($dest_path, $dest_path, 65); // 85 es la calidad de la imagen en un rango de 0 a 100

                // Aquí se guardó la imagen correctamente
                $imagenPerfil = $newFileName; // Esto guardará solo el nombre del archivo en la DB
                $campos[] = "imagenPerfil = '{$imagenPerfil}'"; // Añadir al conjunto de campos para actualizar
                
            } else {
                $errores[] = "Hubo un error al subir la imagen. Inténtalo de nuevo.";
            }
        } else {
            $errores[] = "Formato de archivo no permitido.";
        }
    }


    // Solo actualiza si hay campos válidos y no hay errores
    if(empty($errores) && !empty($campos)) {
        $query = "UPDATE usuario SET " . implode(', ', $campos) . " WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        
        $_SESSION['usuarioObj']->imagenPerfil = $imagenPerfil;

      
            header('Location: dashboard.php?actualizacion=2');  
        } else {
            $errores[] = "Error al actualizar el usuario: " . mysqli_error($db);
        }
    }





?>

<!-- Aquí iría tu formulario, que sería prácticamente el mismo, pero con la acción configurada para actualizar en lugar de insertar -->

<section class="contacto">


<form id="formulario" class="formulario" method="POST" action="" enctype="multipart/form-data">
            <fieldset>
              <legend><h2 style="color:black;"><strong>Información Personal</strong></h2></legend>
  <div class="campo imagen-perfil-container">
    <?php 
    if(!empty($imagenPerfil) && file_exists("../uploads/imagenesPerfil/$imagenPerfil")) {
        // Si la imagenPerfil existe y el archivo también existe en el directorio
        echo "<div class='imagen-perfil'><img src='../uploads/imagenesPerfil/$imagenPerfil' alt='Imagen de Perfil'></div>";
    } else {
        // Si la imagenPerfil no existe o el archivo no está presente en el directorio, muestra una imagen predeterminada
        echo "<div class='imagen-perfil'><img src='../src/img/hojitadeabantu.png' alt='Imagen Predeterminada'></div>";
    }
    ?>
</div>


             <div class="contenedor-campos">
              <div class="campo">
                <label for="nombres">Nombres</label>
              <input class="input-text" type="text" placeholder="*Nombre(s)" id="nombres" name="nombres" value="<?php echo $nombres; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="apellidos">Apellidos</label>
<input class="input-text" type="text" placeholder="*Apellidos" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="telefono">Teléfono</label>
                <input class="input-text" type="tel" placeholder="*Tu teléfono" id="telefono" name="telefono" value="<?php echo $telefono; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="correo">Correo</label>
                <input class="input-text" type="email" placeholder="*Tu Email" id="correo" name="correo" value="<?php echo $correo; ?>"/>
              </div class="campo">

<div class="campo">
    <label for="password">Cambiar contraseña</label>
    <div class="password-container">
        <input class="input-text" type="password" placeholder="*Cambia tu contraseña" id="password" name="password" value=""/>
        <i class="fas fa-eye password-icon" id="togglePassword"></i>
    </div>
   
</div>


<div class="campo">
    <label for="confirm_password">Confirmar contraseña</label>
    <div class="password-container">
        <input class="input-text" type="password" placeholder="*Confirma tu contraseña" id="confirmPassword" name="confirmPassword" value=""/>
        <i class="fas fa-eye password-icon" id="toggleConfirmPassword"></i>
    </div>
</div>

<!--<div class="campo">
    <label for="referido">Personaliza tu Codigo de Invitación</label>
<input class="input-text" type="text" id="referido" name="referido" value="<?php //echo $usuario['referido'] ?? ''; ?>"/>
</div>-->


              <div class="campo">
                <label for="tarjetaBancaria">Tarjeta Bancaria</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="tarjetaBancaria"  name="tarjetaBancaria" value="<?php echo $cuentaBancaria; ?>"/>
              </div>
              <div class="campo">
                <label for="cuentaBancaria">Cuenta de Banco</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="cuentaBancaria"  name="cuentaBancaria" value="<?php echo $tarjetaBancaria; ?>"/>
              </div>
              <div class="campo">
                <label for="CLABE">CLABE Interbancaria</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="CLABE"  name="CLABE" value="<?php echo $CLABE; ?>"/>
              </div>
              <div class="campo">
                <label for="cuentaBancaria2">Banco</label>
                <input class="input-text" type="text" placeholder="*Especificar el banco para depositos" id="cuentaBancaria2"  name="cuentaBancaria2" value="<?php echo $cuentaBancaria2; ?>"/>
              </div>
              
               <div class="campo">
                <label for="RFC">RFC</label>
                <input class="input-text" type="text" placeholder="*Registro Federal" id="RFC" name="RFC" value="<?php echo $RFC; ?>" />
              </div class="campo">

              <div class="campo">
                <label for="eWallet">E-wallet</label>
                <input class="input-text" type="text" placeholder="Sólo para recibir criptomonedas" id="eWallet" name="eWallet" value="<?php echo $eWallet; ?>"/>
              </div>
              <div class="campo">
                <label for="eWallet2">Tipo de Criptodivisa</label>
                <input class="input-text" type="text" placeholder="Especificar si es Bitcoin, Theter, Etc." id="eWallet2" name="eWallet2" value="<?php echo $eWallet2; ?>"/>
              </div>
              
              <div class="campo">
    <label for="imagenPerfil">Imágen de Perfíl</label>
    <input class="input-text" type="file" id="imagenPerfil" name="imagenPerfil" value="<?php echo $imagenPerfil; ?>"/>
</div>


              <!-- <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea class="input-text" name="mensaje" id="mensaje" cols="30" rows="5"></textarea>
              </div> -->
              <?php foreach($errores as $error): ?>
    <div class="alerta error">
    <?php echo $error; ?>

    </div>
    <?php endforeach; ?>
            </div><br>

             <div class=" align-right">
    <input class="styled-button" type="submit" value="Actualizar Información" />
</div>


            </fieldset>
          </form>

    </section>
    
<?php

incluirTemplate('footer');

// } catch (Exception $error) {
//     echo "<div class='error'>Lo siento, error en el sistema. Por favor, inténtalo de nuevo más tarde.</div>"; 
// }
?>
