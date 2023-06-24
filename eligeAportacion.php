<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);





include 'funcionesUsuario.php'; // Asegúrate de que la ruta sea correcta

session_start();

//Base de Datos
include_once 'public/funciones.php';

incluirTemplate('header');


if(isset($_SESSION['usuarioObj'])) {
    
    $sesion = $_SESSION['usuarioObj'];
    
    if ($_SESSION['usuarioObj']) {

    $usuario_id = $sesion->usuario_id;

     echo "<pre>";
     var_dump($sesion);
     echo "</pre>";

    echo "El usuario_id es: " . (isset($sesion->usuario_id) ? $sesion->usuario_id : 'No disponible') . "<br>";
    echo "El correo es: " . (isset($sesion->correo) ? $sesion->correo : 'No disponible') . "<br>";
    echo "El nivel es: " . (isset($sesion->nivel) ? $sesion->nivel : 'No disponible') . "<br>";
    echo "El codigo de invitacion es: " . (isset($sesion->referido) ? $sesion->referido : 'No disponible') . "<br>";
    echo "El arbolActual es: " . (isset($sesion->arbolActual) ? $sesion->arbolActual : 'No disponible') . "<br>";
    echo "La fecha de Registro es: " . (isset($sesion->fechaRegistro) ? $sesion->fechaRegistro : 'No disponible') . "<br>";
    // echo "Tu Auspiciador es: " . isset($_POST['referido']) ? $_POST['referido'] : 'No disponible' . "<br>";


} else {
    echo 'La variable de sesión no contiene una instancia válida de Usuario.';
}
}
else {
    echo "La sesión no contiene una instancia de Usuario.<br>";
}

// var_dump($sesion);


$correo = '';
$resultado = $_GET['resultado'] ?? null;
$usuario = isset($_SESSION['usuarioObj']) ? $_SESSION['usuarioObj'] : null;
$referido = '';
// $usuario_id = '';
$nombreTabla = '';
$montoSeleccionado = '';
// Aquí accedemos a las propiedades del objeto
if ($usuario) {
    $usuarioObj;
    $nombres = $usuario->nombres;
    $referido = $usuario->referido;
    $auspiciadorDirecto = '';
    $usuarios = '';
    $nivel = $usuario->nivel;
    $correo = $usuario->correo;
    $usuario_id = $usuario->usuario_id;
    $fechaRegistro = $usuario->fechaRegistro;
    // $comunidad = $usuario->comunidad;
    // y así sucesivamente para las demás propiedades que necesites...
} else {
    $usuarioObj = null;
    $nombres = null;
    $referido = null;
    $auspiciadorDirecto = null;
    $usuarios = null;
    $nivel = null;
    $correo = null;
    $usuario_id = null;
    $fechaRegistro = null;
}
// Puedes agregar aquí la lógica de procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $montoSeleccionado = isset($_POST['montoSeleccionado']) ? $_POST['montoSeleccionado'] : '';
    $auspiciadorDirecto = isset($_POST['auspiciadorDirecto']) ? $_POST['auspiciadorDirecto'] : '';

    if(empty($auspiciadorDirecto)){
        echo "Error: El campo auspiciadorDirecto está vacío.";
        exit; // detiene la ejecución del script
    }

    if ($usuario && method_exists($usuario, 'obtenerUsuario_id')) {
        $usuario_id = $usuario->obtenerUsuario_id($pdo);

        //  var_dump($usuario);

        if ($usuario_id) {
        $_SESSION['usuarioObj']->usuario_id = $usuario_id;
        $nombreTabla = $usuario->obtenerNombreTabla($montoSeleccionado);

        if ($nombreTabla) {
            //   var_dump($auspiciadorDirecto, $usuario_id, $nombreTabla);
            //  exit;
            
            $updateComunidad = $usuario ->actualizarAuspiciadorComunidad($pdo, $nombreTabla, $auspiciadorDirecto, $usuario_id);

            $_SESSION['usuarioObj']->auspiciadorDirecto = $auspiciadorDirecto; // <-- Añade esta línea

            // header('Location: eligeAportacion.php');

        } else {
            // Hubo un error al insertar
            echo "error al actualizar";
        }

        echo "usuario_id obtenido: " . $usuario_id . "<br>";
    } else {
     echo "No se encontraron detalles del usuario.";
 }
    // exit;

    }
}

?>

<?php if ($sesion instanceof Usuario) { ?>
    <strong> Hola </strong>: <?php echo $nombres; ?>
    <strong> Tu codigo de invitacion es: </strong> <?php echo $referido; ?>
    <strong> Tu patrocinador es: </strong> <?php echo $auspiciadorDirecto; ?>
    <strong> Tu comunidad Actual es: </strong> <?php echo $nombreTabla; ?>
    <strong> Tu nivel en lacomunidad Actual es: </strong> <?php echo $nivel; ?>
    <strong> Ya tienes </strong>: <?php print_r($usuarios); ?> <strong> Compartidos Activos </strong>
<?php } ?>

<h2>Elige tu donacion mensual</h2>
<!-- dashboard.php?resultado=1// -->
<form class="formulario" action="eligeAportacion.php" method="POST">
    <label for="montoSeleccionado"><strong>Elige una comunidad:</strong></label>
    <select id="montoSeleccionado" name="montoSeleccionado">
        <option value="">Seleccione una opción</option>
        <option value="100">$100</option>
        <option value="500">$500</option>
        <option value="1000">$1000</option>
    </select><br>
    <div class="campo">
        <label for="auspiciadorDirecto">Referido por:</label>
        <input class="input-text" type="text" placeholder="Obligatorio escribir id del auspiciador" id="auspiciadorDirecto" name="auspiciadorDirecto" value="<?php echo htmlspecialchars($auspiciadorDirecto); ?>">
    </div>
    <br>
    <input class="botonform" type="submit" value="Entrar a la Comnuidad Seleccionada">
</form>

</body>

<?php

incluirTemplate('footer');

// } catch (Exception $error) {
//     echo "<div class='error'>Lo siento, error en el sistema. Por favor, inténtalo de nuevo más tarde.</div>"; 
// }
?>