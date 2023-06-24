<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'funcionesUsuario.php';

//Base de Datos
require 'public/funciones.php';
incluirTemplate('header');




$usuario_id = $nombres = $apellidos = $telefono = $correo = $password  = $cuentaBancaria = $cuentaBancaria2 = $eWallet = $eWallet2 = $nivel = $referido = $fechaRegistro = $subusuario = $tipoSubusuario = $passwordHash = "";
$errores = [];
$db = new dataBase();
$pdo = $db->getPdo();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombres = $_POST['nombres'];
  $apellidos = $_POST['apellidos'];
  $telefono = $_POST['telefono'];
  $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'];
  $cuentaBancaria = $_POST['cuentaBancaria'];
  $cuentaBancaria2 = $_POST['cuentaBancaria2'];
  $eWallet = $_POST['eWallet'];
  $eWallet2 = $_POST['eWallet2'];
  $fechaRegistro = date('Y/m/d H:i:s');



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

  if (!$cuentaBancaria) {
    $errores[] = "debes añadir tu cuenta bancaria ";
  }
    if (empty($errores)) {

      //  $usuario = new Usuario($usuario['nombres'], $usuario['apellidos'], $usuario['telefono'], $usuario['correo'], $usuario['eWallet'], $usuario['eWallet2'], $usuario['cuentaBancaria'], $usuario['cuentaBancaria2'], $usuario['nivel'], $usuario['referido']);
      $usuario = new Usuario($usuario_id, $nombres, $apellidos, $telefono, $correo, $eWallet, $eWallet2, $cuentaBancaria, $cuentaBancaria2, $nivel, $fechaRegistro, $referido, $subusuario, $tipoSubusuario);



       try {
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

             $nuevoUsuarioId = $usuario -> insertarUsuario($pdo, $nombres, $apellidos, $telefono, $correo, $passwordHash, $cuentaBancaria, $cuentaBancaria2, $eWallet, $eWallet2, $fechaRegistro);
             $usuario_id = $pdo->lastInsertId();
            
              $usuario = new Usuario($usuario_id, $nombres, $apellidos, $telefono, $correo, $eWallet, $eWallet2, $cuentaBancaria, $cuentaBancaria2, $nivel, $fechaRegistro, $referido, $subusuario, $tipoSubusuario);

             if ($nuevoUsuarioId !== false) {
           // Obtén el último ID insertado pasado como parámetro
// Consulta la base de datos para obtener el valor de la columna "referido"
 $query = "SELECT referido FROM usuario WHERE id = :lastInsertedId";
 $stmt = $pdo->prepare($query);
 $stmt->bindParam(':lastInsertedId', $usuario_id, PDO::PARAM_INT);
 $stmt->execute();

// // Obtén los resultados
 $valorReferido = $stmt->fetchColumn();
 $stmt->closeCursor();

// // Muestra los resultados (por ejemplo, en un var_dump para depuración)
 echo ($valorReferido);
// var_dump($valorReferido);
// exit;


              // Inserta el nuevo registro en la tabla subusuario
                $querySubusuario = "INSERT INTO subusuario (usuario_id, auspiciador) VALUES (:lastInsertedId, :auspiciador)";

                
             $stmtSubusuario = $pdo->prepare($querySubusuario);
            
                // Aquí debes definir el valor de auspiciador, suponiendo que lo obtienes de alguna parte.
                // $usuario_id = $pdo->lastInsertId();

                 $stmtSubusuario->bindValue(':lastInsertedId', $usuario_id, PDO::PARAM_INT);
                $stmtSubusuario->bindValue(':auspiciador', $valorReferido, PDO::PARAM_INT);
            
                    // Éxito en la inserción en la tabla subusuario
                    if (!$stmtSubusuario->execute()) {
                      print_r($stmtSubusuario->errorInfo());
                  }
                    // if ($stmtSubusuario->execute()) {
                    $stmtSubusuario->closeCursor();
                    
                      // Array de nombres de tablas en las que deseas insertar
                      $tablasComunidad = ['comunidad100', 'comunidad500', 'comunidad1000'];
                      
                      $insercionExitosa = true;

                      // Iterar a través de cada tabla y ejecutar la consulta de inserción
                      foreach ($tablasComunidad as $tabla) {
                          $queryComunidad = "INSERT INTO $tabla (usuario_id) VALUES (:usuario_id)";
                          $stmtComunidad = $pdo->prepare($queryComunidad);
                  
                          // Aquí debes definir el valor de alguna_otra_columna, suponiendo que lo obtienes de alguna parte.
                          // $valor_otra_columna = "valor_otra_columna"; // Reemplazar con el valor real
                  
                          $stmtComunidad->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
                  
                          // Si alguna inserción falla, marcamos insercionExitosa como false
                          if (!$stmtComunidad->execute()) {
                              $insercionExitosa = false;
                              $errores[] = "Hubo un error al insertar en la tabla $tabla";
                          }
                      }
                      
                      // Si todas las inserciones fueron exitosas
                      if ($insercionExitosa) {
                        // $stmtSubusuario->closeCursor();

                         $stmtComunidad->closeCursor();
                        
                          header('Location: login.php?resultado=1');
                          die();
                      }
                    
                  } else {
                      $errores[] = "Hubo un error al insertar en la tabla subusuario";
                  }
                
                
            //} } else {
            //     $errores[] = "Hubo un error al insertar el usuario";
            // }
            
         

       } catch (PDOException $e) {
//           // Manejo de errores de la base de datos
           $errores[] = "Error de la base de datos: " . $e->getMessage();
       }
//   }

   if (!empty($errores)) {
       // Muestra los errores o haz algo con ellos
   }
 }
}
?>

 

<main class="contenedor2 sombra">
  <h1 class="pasea">Pasos a Seguir</h1>

  <div class="pasos-seguir">
    <section class="paso">
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
      <p>1.- Conoce el sistema de Abantu y preregistrate ya.</p> </a>
    </section>

    <section class="paso">
      <div class="iconos">
        <h2>
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-affiliate" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
      <p>2.- Comparte esto a quien le ayude.</p>
      </a>
    </section>

    <section class="paso">
      <div class="iconos">
        <h2>
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <rect x="3" y="5" width="18" height="14" rx="2" />
            <polyline points="3 7 12 13 21 7" />
          </svg>
        </h2>
      </div>
      <p>
        3.- Espera en tu correo las indicaciones para comenzar los
        financiamientos.
      </p>
      </a>
    </section>
  </div>
  <!-- cierre de pasos -->

  <h3 class="felices">Felices de Ayudar</h3>
  <p>
    Abantu es una organización social sin fines de lucro<br />
    utilizando la tecnologia para romper con la desigualdad.
  </p>

</main>
<section class="contacto">

  <form id="formulario" class="formulario" method="POST" action="">
    <fieldset>
      <legend>PreRegistrate llenando todos los campos</legend>

      <div class="contenedor-campos">
        <div class="campo">
          <label for="nombres">nombres *</label>
          <input class="input-text" type="text" placeholder="*Tus nombres" id="nombres" required name="nombres" value="<?php echo $nombres; ?>" />
        </div>

        <div class="campo">
          <label for="apellidos">Apellidos *</label>
          <input class="input-text" type="text" placeholder="*Tus apellidos" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" />
        </div>

        <div class="campo">
          <label for="telefono">Telefono</label>
          <input class="input-text" type="tel" placeholder="*Tu telefono" id="telefono" name="telefono" value="<?php echo $telefono; ?>" />
        </div>

        <div class="campo">
          <label for="correo">Correo</label>
          <input class="input-text" type="email" autocomplete="username" placeholder="*Tu Email" id="correo" name="correo" value="<?php echo $correo; ?>" />
        </div>

        <div class="campo">
          <label for="password">Crear contraseña</label>
          <input class="input-text" type="password" autocomplete="current-password" placeholder="*Crea tu contraseña" id="password" name="password" value="<?php echo $password; ?>" />
        </div> <!--contenedor-campos-->

        <!-- <div class="campo">
                <label for="referido">Referido por</label>
                <input class="input-text" type="tel" placeholder="Si no existe escribir NA" id="referido" name="referido" value="<?php $referido; ?>"/>
              </div> -->

        <div class="campo">
          <label for="cuentaBancaria">Cuenta Bancaria</label>
          <input class="input-text" type="text" placeholder="*Solo para reicibir depositos o transferencias" id="cuentaBancaria" name="cuentaBancaria" value="<?php echo $cuentaBancaria; ?>" />
        </div>

        <div class="campo">
          <label for="cuentaBancaria2">Cuenta Bancaria Alterna</label>
          <input class="input-text" type="text" placeholder="*Solo para reicibir depositos o transferencias" id="cuentaBancaria2" name="cuentaBancaria2" value="<?php echo $cuentaBancaria2; ?>" />
        </div>

        <div class="campo">
          <label for="eWallet">E-wallet</label>
          <input class="input-text" type="text" placeholder="Solo para recibir criptomonedas" id="eWallet" name="eWallet" value="<?php echo $eWallet; ?>" />
        </div>

        <div class="campo">
          <label for="eWallet2">E-wallet Alterna</label>
          <input class="input-text" type="text" placeholder="Solo para recibir criptomonedas" id="eWallet2" name="eWallet2" value="<?php echo $eWallet2; ?>" />
        </div>

        <!-- <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea class="input-text" name="mensaje" id="mensaje" cols="30" rows="5"></textarea>
              </div> -->
        <?php foreach ($errores as $error) : ?>
          <div class="alerta error">
            <?php echo $error; ?>

          </div>
        <?php endforeach; ?>
      </div>


      <div class="alinear-derecha">
        <input class="botonform" id="botonRegistro" type="submit" value="Registrarte" />
      </div>
    </fieldset>
  </form>

</section>

<?php

incluirTemplate('footer');


?>