<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require 'usuario.php';

require '../includes/config/database.php';
include '../includes/funciones.php';


session_start();



$db = conectarDB();


//echo "<pre>";
//var_dump($_SESSION['usuarioObj']);
//echo "</pre>";

incluirTemplate('header');

// $correo = '';
$resultado = $_GET['resultado'] ?? null;
$actualizacion = $_GET['actualizacion'] ?? null;
$usuario = isset($_SESSION['usuarioObj']) ? $_SESSION['usuarioObj'] : null;
// $referido = '';
$id = '';
$nombreTabla = '';
$montoSeleccionado = 0; 
$tipoSubusuarioAsignado = '';
// $tipoSubusuario = '';o'
$auspiciadorDirecto = '';
// // $subusuarios = $usuario->obtenerSubusuarios($pdo, $nombreTabla, $referido);
// $nombres = '';
$nivelAsignado = 1;
$usuarios = ''; // si $usuarios es un array
//$referido = $usuario->referido;



// Aquí accedemos a las propiedades del objeto
if ($usuario) {
    $id = isset($usuario->id) ? $usuario->id : null;
    $nombres = isset($usuario->nombres) ? $usuario->nombres : null;
    // echo 'el nombre es' . '' . $nombres ;
    $nivel = isset($usuario->nivel) ? $usuario->nivel : null;
    $correo = isset($usuario->correo) ? $usuario->correo : null;
    $usuario_id = isset($usuario->usuario_id) ? $usuario->usuario_id : null;
    $fechaRegistro = isset($usuario->fechaRegistro) ? $usuario->fechaRegistro : null;
    $referido = isset($usuario->referido) ? $usuario->referido : null;
} else {
    $id = null;
    $nombres = null;
    $referido = null;
    $nivel = null;
    $correo = null;
    $usuario_id = null;
    $fechaRegistro = null;
}

// Puedes agregar aquí la lógica de procesamiento del formulario
if (isset($_POST['submit'])){
    $nombreTabla = isset($_POST['montoSeleccionado']) ? $_POST['montoSeleccionado'] : '';
    $auspiciadorDirecto = isset($_POST['auspiciadorDirecto']) ? $_POST['auspiciadorDirecto'] : '';
    
    $montoSeleccionado = $_POST['montoSeleccionado'] ?? ''; // <-- Añade esta línea para definir $montoSeleccionado
    
        $_SESSION['usuarioObj']->montoSeleccionado = $montoSeleccionado; // <-- Añade esta línea
    $_SESSION['usuarioObj']->auspiciadorDirecto = $auspiciadorDirecto; // <-- Añade esta línea
    
    if ($referido != $auspiciadorDirecto) {
    // Aquí colocas el código que debe ejecutarse si no son iguales
    
    // Consulta para comprobar si tipoSubusuario es nulo para el auspiciadorDirecto
$queryCheck = "SELECT tipoSubusuario FROM $nombreTabla WHERE codigoReferido = $auspiciadorDirecto";
$resultadoCheck = mysqli_query($db, $queryCheck);
$row = mysqli_fetch_assoc($resultadoCheck);

// Verifica si el resultado es nulo
if (!$row || is_null($row['tipoSubusuario'])) {
      echo "
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            No es posible unirte debido a que el usuario que elegiste está Inactivo en esta Red de Donación.
        </div>";
    return;
}

$queryTipos = "SELECT COUNT(*) AS num FROM $nombreTabla WHERE auspiciador_id = $auspiciadorDirecto AND (tipoSubusuario = 'A' OR tipoSubusuario = 'B' OR tipoSubusuario = 'C' OR tipoSubusuario = 'D')";
$resultadoTipos = mysqli_query($db, $queryTipos);
$rowTipos = mysqli_fetch_assoc($resultadoTipos);


if ($rowTipos['num'] == 4) {
    echo "
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            No es posible unirte debido a que el usuario que elegiste ya compartió 4 veces su código de Invitación.
        </div>";
    return;
}

    

    if(isset($_SESSION['usuarioObj'])) {
       // echo 'la tabla actual es:' . '' . $nombreTabla. '<br>';
      
        // Consulta a la base de datos para obtener los datos del usuario
      $query = "SELECT usuario.*, subusuario.*, {$nombreTabla}.*
FROM usuario
JOIN subusuario ON usuario.id = subusuario.usuario_id
JOIN {$nombreTabla} ON usuario.id = {$nombreTabla}.usuario_id
ORDER BY usuario.id ASC";

$resultado = mysqli_query($db, $query);


if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($db));
}

// MySQLi devuelve un objeto mysqli_result. Para obtener un array asociativo, utilizamos mysqli_fetch_all con el flag MYSQLI_ASSOC.
$usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
        
        //if($usuarios) {
           // echo "<pre>";
           // print_r($_SESSION['usuarioObj']);
           //  echo "</pre>";

        
            // echo "El usuario_id es: " . (isset($_SESSION['usuarioObj']->id) ? $_SESSION['usuarioObj']->id : 'No disponible') . "<br>";
           // echo "El correo es: " . (isset($_SESSION['usuarioObj']->correo) ? $_SESSION['usuarioObj']->correo : 'No disponible') . "<br>";
           // echo "El nivel en la comunidad100 es: " . (isset($_SESSION['usuarioObj']->nivel100) ? $_SESSION['usuarioObj']->nivel100 : 'No disponible') . "<br>";
           // echo "El nivel en la comunidad500 es: " . (isset($_SESSION['usuarioObj']->nivel500) ? $_SESSION['usuarioObj']->nivel500 : 'No disponible') . "<br>";
           // echo "El nivel en la comunidad1000 es: " . (isset($_SESSION['usuarioObj']->nivel1000) ? $_SESSION['usuarioObj']->nivel1000 : 'No disponible') . "<br>";
          //  echo "El código de invitación es: " . (isset($_SESSION['usuarioObj']->referido) ? $_SESSION['usuarioObj']->referido : 'No disponible') . "<br>";
          //  echo "tu auspiciador en la comunidad100 es: " . (isset($_SESSION['usuarioObj']->auspiciador100) ? $_SESSION['usuarioObj']->auspiciador100 : 'No disponible') . "<br>";
          //  echo "tu auspiciador en la comunidad500 es: " . (isset($_SESSION['usuarioObj']->auspiciador500) ? $_SESSION['usuarioObj']->auspiciador500 : 'No disponible') . "<br>";
          //  echo "tu auspiciador en la comunidad1000 es: " . (isset($_SESSION['usuarioObj']->auspiciador1000) ? $_SESSION['usuarioObj']->auspiciador1000 : 'No disponible') . "<br>";
          //  echo "La fecha de Registro es: " . (isset($_SESSION['usuarioObj']->fechaRegistro) ? $_SESSION['usuarioObj']->fechaRegistro : 'No disponible') . "<br>";
           // echo "Tu tipo de subusuario en la comunidad100 es: " . (isset($_SESSION['usuarioObj']->tipoSubusuario100) ? $_SESSION['usuarioObj']->tipoSubusuario100 : 'No disponible') . "<br>";
          //  echo "Tu tipo de subusuario en la comunidad500 es: " . (isset($_SESSION['usuarioObj']->tipoSubusuario500) ? $_SESSION['usuarioObj']->tipoSubusuario500 : 'No disponible') . "<br>";
          //  echo "Tu tipo de subusuario en la comunidad1000 es: " . (isset($_SESSION['usuarioObj']->tipoSubusuario1000) ? $_SESSION['usuarioObj']->tipoSubusuario1000 : 'No disponible') . "<br>";
       // } else {
        //    echo "No se encontró usuario con ID: " . $usuario_id;
       // }
    
    } else {
        echo 'La variable de sesión no contiene una instancia válida de Usuario.';
    }
      
        $_SESSION['usuarioObj']->id = $id;

            
            //echo "<pre>";
           // print_r($_SESSION['usuarioObj']);
           // echo "</pre>";

            
            $updateComunidad = $_SESSION['usuarioObj'] ->actualizarAuspiciadorComunidad($db, $nombreTabla, $auspiciadorDirecto, $id);
       

            if ($updateComunidad) {
                $tipoSubusuarioAsignado = $_SESSION['usuarioObj']->actualizarTipoSubusuarioComunidad($db, $nombreTabla, $auspiciadorDirecto, $id);
                $_SESSION['usuarioObj']->tipoSubusuario = $tipoSubusuarioAsignado;
                
                
            $updateValidaciones = $_SESSION['usuarioObj']->insertarEnRed($db, $nombreTabla, $correo, $id, $auspiciadorDirecto);

            $updateCuenta = $_SESSION['usuarioObj']->actualizarCuentaDepositar($db, $id, $nombreTabla, $auspiciadorDirecto);

               
    if (!$updateCuenta || !$updateValidaciones) {
      renderBetaErrorMessage();
    return;
    }
            }

        header("Location: dashboard.php?actualizacion=2");
        exit;

} else {
    renderErrorMessageEligeAportacion();
    return;
}

    }
 
?>

<?php if ($_SESSION['usuarioObj']) { ?>
    
<?php } ?>

<h2><strong>Unirse a Red de Donación</strong></h2>
<!-- dashboard.php?resultado=1// -->
<form class="formulario" action="" method="POST">
    <div class="campo">
        <label for="auspiciadorDirecto">Referido por:</label>
        <input class="input-text" type="text" placeholder="Código de invitación" id="auspiciadorDirecto" name="auspiciadorDirecto">
    </div>
    <br>
     <label for="montoSeleccionado"><strong>Elige una Donación:</strong></label>
    <select id="montoSeleccionado" name="montoSeleccionado">
        <option value="">Seleccione una opción</option>
        <option value="comunidad100">$100</option>
        <option value="comunidad500">$500</option>
        <option value="comunidad1000">$1000</option>
    </select><br>
    <input class="botonform" name="submit" type="submit" value="Unirse a la Red de Donación">
</form>

</body>

<?php

incluirTemplate('footer');

// } catch (Exception $error) {
//     echo "<div class='error'>Lo siento, error en el sistema. Por favor, inténtalo de nuevo más tarde.</div>"; 
// }
?>