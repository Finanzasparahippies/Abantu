<?php
include '../includes/funciones.php';
incluirTemplate('header');

//Base de Datos
  require '../includes/config/database.php';
  $db = conectarDB();

  // Arreglos con mensajes de errores

 


  // if($_SERVER['REQUEST_METHOD'] === 'post')



?>

    <main class="contenedor2 sombra">
        <h1 class="pasea">Pasos a Seguir</h1>

        <div class="pasos-seguir">
            <section class="paso">
              <div class="iconos">
                <h2>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-info-circle"
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
              <p>2.- Comparte esto a quien le ayude.</p>
              </a>
            </section>

            <section class="paso">
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

        <form  id="formulario" class="formulario" method="POST" action="">
            <fieldset>
              <legend>PreRegistrate llenando todos los campos</legend>

             <div class="contenedor-campos">
              <div class="campo">
                <label for="nombres">nombres</label>
                <input class="input-text" type="text" placeholder="*Tus nombres" id="nombres" name="nombres" value="<?php echo $nombres; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input class="input-text" type="text" placeholder="*Tus apellidos" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" />
              </div class="campo">

              <div class="campo">
                <label for="telefono">Telefono</label>
                <input class="input-text" type="tel" placeholder="*Tu telefono" id="telefono" name="telefono" value="<?php echo $telefono; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="correo">Correo</label>
                <input class="input-text" type="email" placeholder="*Tu Email" id="correo" name="correo" value="<?php echo $correo; ?>"/>
              </div class="campo">

              <div class="campo">
                <label for="referido">Invitado por</label>
                <input class="input-text" type="text" placeholder="Si no existe poner NA" id="referido" name="referido" value="<?php echo $referido; ?>"/>
              </div> <!--contenedor-campos-->

              <div class="campo">
                <label for="cuentaBancaria">Cuenta Bancaria</label>
                <input class="input-text" type="text" placeholder="*Solo para reicibir depositos o transferencias" id="cuentaBancaria" name="cuentaBancaria" value="<?php echo $cuentaBancaria; ?>"/>
              </div>

              <div class="campo">
                <label for="eWallet">E-wallet</label>
                <input class="input-text" type="text" placeholder="Solo para recibir criptomonedas" id="eWallet" name="eWallet" value="<?php echo $eWallet; ?>"/>
              </div>

              <!-- <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea class="input-text" name="mensaje" id="mensaje" cols="30" rows="5"></textarea>
              </div> -->
            </div>
            <?php foreach($errores as $error): ?>
    <div class="alerta error">
    <?php echo $error; ?>

    </div>
    <?php endforeach; ?>

              <div class="alinear-derecha">
                <input class="botonform" type="submit" value="Enviar Mensaje" />
              </div>
            </fieldset>
          </form>

    </section>

    <?php

incluirTemplate('footer');

?>
