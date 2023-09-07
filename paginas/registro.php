<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


//Base de Datos
  require '../includes/funciones.php';
  
  incluirTemplate('header');

  require '../includes/config/database.php';
  $db = conectarDB();

  // Arreglos con mensajes de errores

  $errores = [];

  $nombres = $apellidos = $telefono = $correo = $password = $confirmPassword  = $cuentaBancaria = $tarjetaBancaria = $CLABE = $cuentaBancaria2 = $eWallet = $eWallet2 = $fechaRegistro = $subusuario = $tipoSubusuario100 = $tipoSubusuario500 = $tipoSubusuario1000 = $referencia100 = $referencia500 = $referencia1000 = $validacion100 = $validacion500 = $validacion1000 = $passwordHash = $tipoSubusuario = $comunidad100 = $comunidad500 = $comunidad1000 = $imagenPerfil = "";
$nivel100 = $nivel500 = $nivel1000 = $referido =  $auspiciador100 = $auspiciador500 = $auspiciador1000 = $auspiciadorDirecto = $auspiciador2 =$auspiciador3 = $montoSeleccionado = $cuentaDepositar100 = $cuentaDepositar500 = $cuentaDepositar1000 = 0;
date_default_timezone_set('America/Hermosillo');

  $RFC = '';

  // Ejecutar el codigo

 
if($_SERVER ['REQUEST_METHOD'] === 'POST') {

  $nombres = mysqli_real_escape_string ( $db, $_POST ['nombres'] );
  $apellidos = mysqli_real_escape_string( $db, $_POST ['apellidos'] );
  $telefono = mysqli_real_escape_string( $db, $_POST ['telefono'] );
  $correo = mysqli_real_escape_string( $db, filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) ;
  $password = mysqli_real_escape_string( $db, $_POST ['password'] );
  $confirmPassword = $_POST ['confirmPassword'] ;
  $RFC = $_POST['RFC'];
  $cuentaBancaria = mysqli_real_escape_string( $db, $_POST ['cuentaBancaria'] );
  $tarjetaBancaria = mysqli_real_escape_string( $db, $_POST ['tarjetaBancaria'] );
  $CLABE = mysqli_real_escape_string( $db, $_POST ['CLABE'] );
  $cuentaBancaria2 = mysqli_real_escape_string( $db, $_POST ['cuentaBancaria2'] );
  $eWallet = mysqli_real_escape_string( $db, $_POST ['eWallet'] );
  $eWallet2 = mysqli_real_escape_string( $db, $_POST ['eWallet2'] );
  $fechaRegistro = mysqli_real_escape_string( $db, date('Y/m/d H:i:s') );
  $nivel100 = isset($usuario['nivel100']) ? $usuario['nivel100'] : 0;
  $nivel500 = isset($usuario['nivel500']) ? $usuario['nivel500'] : 0;
  $nivel1000 = isset($usuario['nivel1000']) ? $usuario['nivel1000'] : 0;

  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  if (!$nombres) {
    $errores[] = "debes añadir tu nombre";
  }

  if (!$apellidos) {
    $errores[] = "debes añadir tu apellido";
  }

  if (!$telefono) {
    $errores[] = "debes añadir tu telefono";
  }

  if (!$correo) {
    $errores[] = "debes agregar un correo valido";
  }

  if (!$password) {
    $errores[] = "La contraseña es obligatoria";
  }
  if ($password !== $confirmPassword) {
    $errores[] = "Las contraseñas no coinciden";
  }

  if (!$cuentaBancaria && !$tarjetaBancaria && !$CLABE) {
    $errores[] = "debes agrgar al menos una opcion para recibir donaciones ";
  }


// Revisar que el arreglo de errores este vacio

if (empty($errores)) {
  

 // Insertar en la base de datos usuario
$stmt = $db->prepare("INSERT INTO usuario (nombres, apellidos, RFC, telefono, correo, cuentaBancaria, tarjetaBancaria, CLABE, cuentaBancaria2, eWallet, eWallet2, fechaRegistro, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssss", $nombres, $apellidos, $RFC, $telefono, $correo, $cuentaBancaria, $tarjetaBancaria, $CLABE, $cuentaBancaria2, $eWallet, $eWallet2, $fechaRegistro, $passwordHash);
$resultado = $stmt->execute();

if ($resultado !== false) {
    // Obtener el último id insertado
    $ultimo_id = $stmt->insert_id;
    
    $queryReferido = "SELECT referido FROM usuario WHERE id = ?";
    $stmtReferido = $db->prepare($queryReferido);
    $stmtReferido->bind_param("i", $ultimo_id);
    $stmtReferido->execute();
    $resultReferido = $stmtReferido->get_result();
    $row = $resultReferido->fetch_assoc();
    $referido = $row['referido'];

    // Usar este id para insertar en la tabla subusuario
    $stmtSubusuario = $db->prepare("INSERT INTO subusuario (usuario_id, correo) VALUES (?, ?)");
    $stmtSubusuario->bind_param("is", $ultimo_id, $correo);
    $resultado_subusuario = $stmtSubusuario->execute();

   if ($resultado_subusuario !== false) {
    // Para comunidad100
    $stmtComunidad100 = $db->prepare("INSERT INTO comunidad100 (usuario_id, codigoReferido, correo, nivel, compartirCuenta) VALUES (?, ?, ?, 0, 0)");
    $stmtComunidad100->bind_param("iis", $ultimo_id, $referido, $correo);
    $resultado1 = $stmtComunidad100->execute();

    // Para comunidad500
    $stmtComunidad500 = $db->prepare("INSERT INTO comunidad500 (usuario_id, codigoReferido, correo, nivel, compartirCuenta) VALUES (?, ?, ?, 0, 0)");
    $stmtComunidad500->bind_param("iis", $ultimo_id, $referido, $correo);
    $resultado2 = $stmtComunidad500->execute();

    // Para comunidad1000
    $stmtComunidad1000 = $db->prepare("INSERT INTO comunidad1000 (usuario_id, codigoReferido, correo, nivel, compartirCuenta) VALUES (?, ?, ?, 0, 0)");
    $stmtComunidad1000->bind_param("iis", $ultimo_id, $referido, $correo);
    $resultado3 = $stmtComunidad1000->execute();
}
}



if ($resultado1 && $resultado2 && $resultado3) {
    // todo salió bien, redireccionar al usuario
    

            header('Location: login.php?resultado=1');
            die();
        } else {
            // manejador de errores de la inserción subusuario
            // por ejemplo, puedes añadir a $errores
            $errores[] = "Error al insertar en comunidades: " . mysqli_error($db);
        }
        } else {
            // manejador de errores de la inserción subusuario
            // por ejemplo, puedes añadir a $errores
            $errores[] = "Error al insertar en subusuario: " . mysqli_error($db);
        }
    }


?>
<br>
    <section class="contacto">

        <form  id="formulario" class="dark-mode formulario" method="POST" action="">
            <fieldset>
              <legend>Registro</legend>

             <div class="contenedor-campos">
              <div class="campo">
                <label for="nombres">Nombres</label>
                <input class="input-text" type="text" placeholder="*Nombre(s)" id="nombres" required name="nombres" value="<?php echo $nombres; ?>"/>
              </div>

              <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input class="input-text" type="text" placeholder="*Apellidos" id="apellidos" required name="apellidos" value="<?php echo $apellidos; ?>" />
              </div>

              <div class="campo">
                <label for="telefono">Teléfono</label>
                <input class="input-text" type="tel" placeholder="*Tu teléfono" id="telefono" required name="telefono" value="<?php echo $telefono; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="correo">Correo</label>
                <input class="input-text" type="email" placeholder="*Tu correo electrónico" id="correo" required name="correo" value="<?php echo $correo; ?>"/>
              </div>

<div class="campo">
    <label for="password">Crear contraseña</label>
    <div class="password-container">
        <input class="input-text" type="password" placeholder="*Crea tu contraseña" id="password" required name="password" value="<?php echo $password; ?>"/>
        <i class="fas fa-eye password-icon" id="togglePassword"></i>
    </div>
   
</div>


<div class="campo">
    <label for="confirm_password">Confirmar contraseña</label>
    <div class="password-container">
        <input class="input-text" type="password" placeholder="*Confirma tu contraseña" id="confirmPassword" required name="confirmPassword" value="<?php echo $confirmPassword; ?>"/>
        <i class="fas fa-eye password-icon" id="toggleConfirmPassword"></i>
    </div>
</div>
              <div class="campo">
                <label for="tarjetaBancaria">Tarjeta Bancaria</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="tarjetaBancaria" required name="tarjetaBancaria" value="<?php echo $tarjetaBancaria; ?>"/>
              </div>
               <div class="campo">
                <label for="cuentaBancaria">Número de Cuenta Bancaria</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="cuentaBancaria" required name="cuentaBancaria" value="<?php echo $cuentaBancaria; ?>"/>
              </div>
              <div class="campo">
                <label for="CLABE">CLABE interbancaria</label>
                <input class="input-text" type="text" placeholder="*Sólo para recibir depósitos o transferencias" id="CLABE" required name="CLABE" value="<?php echo $CLABE; ?>"/>
              </div>
              <div class="campo">
                <label for="cuentaBancaria2">Banco</label>
                <input class="input-text" type="text" placeholder="*Especifíca el banco para depósitos" id="cuentaBancaria2"  name="cuentaBancaria2" value="<?php echo $cuentaBancaria2; ?>"/>
              </div>
              
 <div class="campo">
                <label for="RFC">RFC</label>
                <input class="input-text" type="text" placeholder="*Registro Federal de Contribuyentes" id="RFC" required name="RFC" value="<?php echo $RFC; ?>" />
              </div>
              
          <!--    <div class="campo"> -->
          <!--      <label for="eWallet">E-wallet</label> -->
          <!--      <input class="input-text" type="text" placeholder="Sólo para recibir criptomonedas" id="eWallet" name="eWallet" value="<?//php echo $eWallet; ?>"/> -->
           <!--   </div> -->
             <!-- <div class="campo"> -->
              <!--  <label for="eWallet2">Tipo de Criptodivisa</label> -->
              <!--  <input class="input-text" type="text" placeholder="Bitcoin, Theter, etc." id="eWallet2" name="eWallet2" value="<?//php echo $eWallet2; ?>"/> -->
            <!--  </div> -->

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
          <strong>  
    <input class="styled-checkbox" type="checkbox" id="terminos" name="terminos" required>
    <label for="terminos">He leído y acepto los <a href="/paginas/terminosYcondiciones.php" target="_blank" style="color:blue;">Términos y Condiciones</a></label>
</strong>
             <div class=" align-right">
    <input class="styled-button btn-red" type="submit" value="Regístrate" />
</div>


            </fieldset>
          </form>

    </section>

<main class="contenedor2 sombra">
    
        <div class="pasos-seguir">

            <section class="paso">
            <a href="/paginas/registro.php" class="icon-link">
    <div class="iconos">
        <h2>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <circle cx="12" cy="12" r="9" />
                <line x1="12" y1="8" x2="12.01" y2="8" />
                <polyline points="11 12 12 12 12 16 13 16" />
            </svg>
        </h2>
    </div>
</a>

              <p>1.- Regístrate en la Plataforma.</p> </a>
            </section>
    
           <section class="paso">
    <div class="icon-link" onclick="document.getElementById('shareMenu').style.display='block'">
        <div class="iconos">
            <h2>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-affiliate"
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="#000000"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5.931 6.936l1.275 4.249m5.607 5.609l4.251 1.275" />
                    <path d="M11.683 12.317l5.759 -5.759" />
                    <circle cx="5.5" cy="5.5" r="1.5" />
                    <circle cx="18.5" cy="5.5" r="1.5" />
                    <circle cx="18.5" cy="18.5" r="1.5" />
                    <circle cx="8.5" cy="15.5" r="4.5" />
                  </svg>
                </h2>
        </div>
        <div id="shareMenu" class="share-menu icon-link">
        <a href="https://www.facebook.com/sharer.php?u=https://www.facebook.com/profile.php?id=100095195865538" target="_blank">Facebook</a>
        <a href="#" onclick="document.getElementById('shareMenu').style.display='none'">Cancelar</a>
    </div>
    </div>
        <p>2.- Comparte esto a quien le ayude.</p>
    
    
    
</section>
    
            <section class="paso">
                <a href="/paginas/login.php" class="icon-link">

              <div class="iconos">
                <h2>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-mail"
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="#000000"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <polyline points="3 7 12 13 21 7" />
                  </svg>
                </h2>
              </div>
                </a>

              <p>
                3.- Envía y recibe tus Donaciones.
              </p>

            </section>
          </div>
          <!-- cierre de pasos -->
    </main>

    <?php

incluirTemplate('footer');

?>
