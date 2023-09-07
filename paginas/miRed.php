<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require 'usuario.php';

require '../includes/config/database.php';
include '../includes/funciones.php';


session_start();


incluirTemplate('header');

$db = conectarDB();

$usuario = $_SESSION['usuarioObj'];
$actualizacion = $_GET['actualizacion'] ?? null;
$nombres = $usuario->nombres;
$apellidos = $usuario->apellidos;
$tipoSubusuario100 = $usuario->tipoSubusuario100;
$tipoSubusuario500 = $usuario->tipoSubusuario500;
$tipoSubusuario1000 = $usuario->tipoSubusuario1000;
$nombreTabla = '';
$cuentaDepositar = '';
$tarjetaBancaria = '';
$CLABE = '';
$banco = '';
$fechaRegistro = '';
$validacionMensual = '';
$montoDonacion = null;
$imagenPerfil = '';
$id = $usuario->id;
$fechaDepositoFormat = '';
$allowedTables = '';
// Fecha actual
$dateNow = new DateTime();

// Primer día del mes en curso
$firstDayOfMonth = $dateNow->format('Y-m-01');

// Primer día del próximo mes
$dateNow->modify('+1 month');
$firstDayOfNextMonth = $dateNow->format('Y-m-01');

$uploadDir = '../uploads/imagenesPerfil/';
$response = ['success' => false];

          // echo "<pre>";
          //  print_r($usuario);
          //  echo "</pre>";
//exit;

if (isset($_SESSION['usuarioObj'])) {
    $usuarioObjRecuperado = ($_SESSION['usuarioObj']);
}

$allowedTables = ['comunidad100', 'comunidad500', 'comunidad1000'];


// Intenta obtener $nombreTabla de $_POST, luego de $_GET, y finalmente de $_SESSION.
if (isset($_POST['nombreTabla']) && in_array($_POST['nombreTabla'], $allowedTables)) {
    $nombreTabla = $_POST['nombreTabla'];
    $_SESSION['nombreTabla'] = $nombreTabla;  // Guarda en la sesión también para acceder posteriormente
} elseif (isset($_GET['nombreTabla']) && in_array($_GET['nombreTabla'], $allowedTables)) {
    $nombreTabla = $_GET['nombreTabla'];
    $_SESSION['nombreTabla'] = $nombreTabla;  // Guarda en la sesión también para acceder posteriormente
} elseif (isset($_SESSION['nombreTabla']) && in_array($_SESSION['nombreTabla'], $allowedTables)) {
    $nombreTabla = $_SESSION['nombreTabla'];
}

if (empty($nombreTabla)) {
    echo renderErrorMessage();
    exit();
}

switch ($nombreTabla) {
    case 'comunidad100':
        $montoDonacion = "100";
        $_SESSION['usuarioObj']->montoSeleccionado = $montoDonacion;
        break;
    case 'comunidad500':
        $montoDonacion = "500";
        $_SESSION['usuarioObj']->montoSeleccionado = $montoDonacion;
        break;
    case 'comunidad1000':
        $montoDonacion = "1000";        
        $_SESSION['usuarioObj']->montoSeleccionado = $montoDonacion;
        break;
    default:
        die("Nombre de tabla no reconocido.");
}

$tablaValidacion = str_replace('comunidad', 'validacion', $nombreTabla);


if (!empty($_SESSION['nombreTabla'])):
    
    switch ($_SESSION['nombreTabla']) {
        case 'comunidad100':
            $tipoSubusuario = $tipoSubusuario100;
            break;
        case 'comunidad500':
            $tipoSubusuario = $tipoSubusuario500;
            break;
        case 'comunidad1000':
            $tipoSubusuario = $tipoSubusuario1000;
            break;
        default:
            $tipoSubusuario = '';
            break;
    }
    
 endif;



//$_SESSION['usuarioObjeto']->nombreTabla = $nombreTabla;


// Define cuántos resultados te gustaría mostrar por página
$resultsPerPage = 15;

// Obtén la página actual. Si no se proporciona ningún número de página, se establece en 1
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

$queryTotal = "SELECT COUNT(usuario.id) AS total 
FROM usuario 
JOIN {$nombreTabla} ON usuario.id = {$nombreTabla}.usuario_id 
ORDER BY usuario.id ASC";
$resultTotal = mysqli_query($db, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalRecords = $rowTotal['total'];

//print_r($totalRecords);
//exit;

// Calcula el número total de páginas
$totalPages = ceil($totalRecords / $resultsPerPage);

// Calcula la cláusula OFFSET para la consulta SQL
$offset = ($currentPage - 1) * $resultsPerPage;


// Modifica la consulta para incluir las cláusulas LIMIT y OFFSET
// 1. Seleccionar los campos
$campos = "SELECT usuario.id, usuario.nombres, usuario.apellidos, usuario.correo, usuario.referido, 
{$nombreTabla}.referenciaValidacion, {$nombreTabla}.validacionMensual, {$nombreTabla}.Banco, {$tablaValidacion}.fecha, 
{$nombreTabla}.tipoSubusuario, {$nombreTabla}.auspiciador_id, {$nombreTabla}.nivel, {$nombreTabla}.cuentaDepositar";

// 2. Las tablas y las uniones (joins)
$tablasYJoins = "FROM usuario 
JOIN {$nombreTabla} ON usuario.id = {$nombreTabla}.usuario_id 
JOIN {$tablaValidacion} ON usuario.id = {$tablaValidacion}.usuario_id";

// 3. Las condiciones (where)
//$condiciones = "WHERE {$tablaValidacion}.fecha >= '{$firstDayOfMonth}' AND {$tablaValidacion}.fecha < '{$firstDayOfNextMonth}'";

// 4. Ordenar y limitar la consulta
$ordenarYLimitar = "ORDER BY usuario.id ASC 
LIMIT {$resultsPerPage} OFFSET {$offset}";

// Unir todas las partes para formar la consulta completa
$query = "{$campos} {$tablasYJoins} {$ordenarYLimitar}";

// Ahora puedes ejecutar la consulta con mysqli_query() como ya lo hacías
$resultado = mysqli_query($db, $query);

if (!$resultado) {
    die('Error en la consulta: ' . mysqli_error($db));
}

//while ($row = mysqli_fetch_assoc($resultado)) {
//    echo "<pre>";
//    print_r($row);
//    echo "</pre>";
//}

//echo "<pre>";
//print_r($resultado);
//print_r($query);
//echo "</pre>";
//exit;

// Asegúrate de que 'nombreTabla' esté en los parámetros
if (!isset($params['nombreTabla']) && !empty($nombreTabla)) {
    $params['nombreTabla'] = $nombreTabla;
}

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($db));
}

// MySQLi devuelve un objeto mysqli_result. Para obtener un array asociativo, utilizamos mysqli_fetch_all con el flag MYSQLI_ASSOC.
$datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($datos){
    
    $usuarioId = $usuario->id;

    // Consulta para obtener cuentaDepositar de la tabla $nombreTabla
    $queryCuenta = "SELECT cuentaDepositar, TarjetaBancaria, CLABE, Banco, tipoSubusuario FROM {$nombreTabla} WHERE usuario_id = {$usuarioId}";
    $resultadoCuenta = mysqli_query($db, $queryCuenta);

    if (!$resultadoCuenta) {
    die("Error en la consulta: " . mysqli_error($db) . ". Consulta: " . $query);
    }

    $usuarioCuenta = mysqli_fetch_assoc($resultadoCuenta);
    $cuentaDepositar = isset($usuarioCuenta['cuentaDepositar']) ? $usuarioCuenta['cuentaDepositar'] : '';
    $tarjetaBancaria = isset($usuarioCuenta['TarjetaBancaria']) ? $usuarioCuenta['TarjetaBancaria'] : '';
    $CLABE = isset($usuarioCuenta['CLABE']) ? $usuarioCuenta['CLABE'] : '';
    $banco = isset($usuarioCuenta['Banco']) ? $usuarioCuenta['Banco'] : '';
    $tipoSubusuario = isset($usuarioCuenta['tipoSubusuario']) ? $usuarioCuenta['tipoSubusuario'] : '';
    
    // Consulta para obtener fechaRegistro de la tabla usuario
$queryFecha = "SELECT fechaRegistro FROM usuario WHERE id = {$usuarioId}";
$resultadoFecha = mysqli_query($db, $queryFecha);

// Consulta para obtener la columna fecha de la tabla $nombreTabla para el usuario en sesión
$tablaFecha = str_replace('comunidad', 'validacion', $nombreTabla);

$queryFechaDeposito = "SELECT fecha FROM {$tablaFecha} WHERE usuario_id = {$usuarioId}";
$resultadoFechaDeposito = mysqli_query($db, $queryFechaDeposito);

if (!$resultadoFechaDeposito) {
    die("Error en la consulta para obtener fechaDeposito: " . mysqli_error($db));
}

$datosFechaDeposito = mysqli_fetch_assoc($resultadoFechaDeposito);

$fechaDeposito = isset($datosFechaDeposito['fecha']) ? $datosFechaDeposito['fecha'] : '';

// Establece el locale a español (ajusta según tu país, si es necesario)
setlocale(LC_TIME, "es_MX");

if ($fechaDeposito) {
    $date = new DateTime($fechaDeposito);
    
    // Utiliza IntlDateFormatter para formatear la fecha en español
    $fmt = new IntlDateFormatter(
        'es_MX',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE
    );
    
    $fechaDepositoFormat = $fmt->format($date);
} else {
    print_r($fechaDeposito); // Si no hay fecha, simplemente muestra la variable como está
}


if (isset($datosFechaDeposito['fecha'])) {
    $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', $datosFechaDeposito['fecha']);
    if ($fechaObj !== false) {
        $fechaDeposito = $fechaObj->format('j \d\e F \d\e Y'); // Ejemplo: "6 de julio de 2023"
    } else {
        // Si la conversión falla, puedes dejar $fechaDeposito en blanco o mostrar un mensaje de error
        $fechaDeposito = 'Fecha inválida';
    }
} else {
    $fechaDeposito = '';
}

    // Consulta para obtener codigo de invitacion de la tabla usuario
    $queryCodigo = "SELECT referido FROM usuario WHERE id = {$usuarioId}";
    $resultadoCodigo = mysqli_query($db, $queryCodigo);
    
    $usuarioCodigo = mysqli_fetch_assoc($resultadoCodigo);
    $codigoReferido = isset($usuarioCodigo['referido']) ? $usuarioCodigo['referido'] : '';

    if (!$resultadoFecha) {
        die("Error en la consulta: " . mysqli_error($db));
    }

    $usuarioFecha = mysqli_fetch_assoc($resultadoFecha);
    $fechaRegistro = isset($usuarioFecha['fechaRegistro']) ? $usuarioFecha['fechaRegistro'] : '';
    
    
    $totalQuery = "SELECT COUNT(*) as total FROM usuario JOIN {$nombreTabla} ON usuario.id = {$nombreTabla}.usuario_id";
$totalResult = mysqli_query($db, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalResults = $totalRow['total'];

// Calcula el total de páginas
$totalPages = ceil($totalResults / $resultsPerPage);


} else {
    // Si no hay usuario en sesión, establecemos $cuentaDepositar y $fechaRegistro a cadenas vacías
    $cuentaDepositar = '';
    $fechaRegistro = '';
    $codigoReferido = '';
}

// Verifica si se ha subido un archivo
   if(isset($_POST["submit"])) {
    $userId = $_SESSION['usuarioObj']->id;
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "pdf" ) {
        $uploadOk = 0;
        echo"
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            Lo sentimos, sólo se permiten archivos JPG, JPEG, PNG , GIF y PDF.
        </div>";
    }
    if ($_FILES["fileToUpload"]["size"] > 30000000) {
        $uploadOk = 0;
        echo "
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            Lo sentimos, tu archivo es muy grande.
        </div>";
    }
    if (file_exists($target_file)) {
        $uploadOk = 0;
         echo "
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            Lo sentimos, el archivo ya existe.
        </div>";
    }
    if ($uploadOk == 0) {
         echo "
        <div class='custom-alertError'>
            <i class='fas fa-exclamation-triangle'></i>
            Tu archivo no se subió.
        </div>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
$fileName = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));

echo "
<div class='custom-alert'>
    <i class='fas fa-check-circle'></i>
    El archivo <strong>{$fileName}</strong> ha sido subido con éxito. ¡Gracias por tu Aporte!
</div>
";

            $nombreTabla = $_POST["nombreTabla"];
            
            mysqli_begin_transaction($db);

            $queryComunidad = "UPDATE {$nombreTabla} SET referenciaValidacion = '{$target_file}' WHERE usuario_id = {$userId}";
            $resultadoComunidad = mysqli_query($db, $queryComunidad);

            if ($resultadoComunidad) {
                $queryValidacion = "UPDATE {$tablaValidacion} SET evidencia = '{$target_file}' WHERE usuario_id = {$userId}";
                $resultadoValidacion = mysqli_query($db, $queryValidacion);

                if ($resultadoValidacion) {
                    mysqli_commit($db);
                    
                     header('Location: dashboard.php?actualizacion=3');
            die();
                } else {
                    mysqli_rollback($db);
                    die("Error en la consulta: " . mysqli_error($db));
                }
            } else {
                mysqli_rollback($db);
                die("Error en la consulta: " . mysqli_error($db));
            }
        } else {
            echo "Hubo un error al subir tu archivo.";
        }
    }
}

?>


    <style>
        /* Estilos generales */
       body {
    background-color: #f4f4f4;
    font-weight: bold;
    font-family: 'Roboto', sans-serif;
}
.contenedor-principal {
    grid-template-columns: 1fr 1fr 1fr;
    margin: 0 auto;
}

       .info-card {
    border: 2px solid #05e09c;
    padding: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    background-color: white;
    position: relative;
    border-radius: 15px;
    overflow: hidden;
}

        .info-card:hover {
            box-shadow: 0px 6px 12px rgba(0,0,0,0.15);
        }

        .info-card h1, .info-card h2 {
            margin-bottom: 0px;
        }

        .info-card h1 {
            overflow: hidden;
    word-wrap: break-word;
    color: #05e09c
        }

        .info-card h2 {
            color: black;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn-azul {
            background-color: #33a2ff;
            color: white!important;
        }

        .btn-verde {
            background-color: #05e09c;
            color: white;
        }

        .btn-gris {
            background-color: #05e09c;
            color: white;
        }

        .btn-gris:hover {
            background-color: #03b07c;
            color: white;
        }

        .titulo-menu {
            color: #05e09c;
            text-shadow: 2px 2px 4px #000000;
        }
        
.icon-copy {
    flex-basis: 10%;
    font-size: 28px;
    cursor: pointer;
    transition: transform 0.3s, color 0.3s;
    margin-left:40px;
}

.icon-copy:hover {
    transform: scale(1.1);
    color: #fff;
}


        .card-info {
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    padding: 20px;
    max-width: 500px;
    font-family: roboto;
    margin: 20px auto; /* Centra la tarjeta horizontalmente si se encuentra en un contenedor más grande */
    background-color: #f9f9f9;
}

.card-info h2 {
    border-bottom: 2px solid #05e09c; /* línea divisora */
    padding-bottom: 10px;
    margin-bottom: 15px;
    font-size: 24px;
}

.variable-box {
    flex-basis: 40%;
    border: 2px solid #05e09c;
    border-radius: 25px;
    padding: 5px;
    text-align: center;
    background-color: #fff;
    color: #05e09c;
    font-size: 20px;
    transition: background-color 0.3s, color 0.3s;
}
.variable-box:hover {
    background-color: #03b07c;
    color: #fff;
}
.variable-label, .variable-box {
    margin-right: 10px;
}

.variable-label {
    margin-right: 10px;
    text-align: center;
}

        .h1 {
        font-family: 'Roboto', sans-serif;

        }
       .btn-profesional {
    flex-basis: 40%;
    padding: 22px 10px;
    font-size: 16px;
    background-color: #05e09c;
    color: white!important;
    border: none;
    border-radius: 25px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    text-align: center;
    transition: transform 0.3s, background-color 0.3s;
}

  .btn-profesional:hover {
    transform: translateY(-3px);
    background-color: #03b07c;
    box-shadow: 0px 7px 20px rgba(0, 0, 0, 0.25); /* Sombreado al pasar el ratón */

}
      

    .btn-profesional:active {
        transform: translateY(0px); /* Efecto de pulsado */
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); /* Sombreado al pulsar */
    }
.titulo-bienvenida {
    color: #05e09c;
    font-size: 28px;
    text-align: center;
    margin-bottom: 25px;
}

.titulo-codigo {
    color: #333;
    font-size: 22px;
    flex-basis: 100%;
    margin-right: 450px;
}
.codigo-section {
    display: flex;
    grid-template-columns: 1fr 1fr; /* Dos columnas para escritorio */
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    cursor:pointer;
}

.contenedor4 {
    display: flex;
    align-items: center;
}
.alerta.exito {
    background-color: #d4edda;
    color: #155724;
    padding: 10px 20px;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    max-width: 800px;
    margin: 20px auto 0;
    font-size: 18px;
    text-align: center;
}
.variable-box2 {
    display: block; /* lo hacemos un bloque completo */
    border: 1px solid #05e09c; /* Cambiamos el color del borde para que destaque */
    border-radius: 5px;
    background-color: #fff;
    color: #333;
    text-align:center;
    padding:3px;
    margin-top:3px;
}
    </style>

   <body class="py-3">

<?php if ( $actualizacion === 2 ): ?>
    <p class="alerta exito">dBienvenido a la Red de Donación de $100 pesos, ¡Gracias por Ayudar!</p>
<?php endif; ?>
        
          <div class="container">
    <div class="info-card">
        <div class="contenedor2">
    <div class="info-card">
   <div class="imagen-perfil-container" style="float: left; margin-right: 15px; position: relative;">
    <?php
    if (!empty($imagenPerfil) && file_exists("../uploads/imagenesPerfil/$imagenPerfil")) {
        echo "<img src='../uploads/imagenesPerfil/$imagenPerfil' alt='Imagen de Perfil' width='80'>";
    } else {
        echo "<img src='../src/img/hojitadeabantu.png' alt='Imagen Predeterminada' width='80'>";
    }
    ?> 
    <div class="overlay">
        <!-- Aquí se coloca un input de tipo file pero oculto -->
        <input type="file" id="fileInput" style="display: none;" name="nuevaImagen">
        <button id="triggerFileInput" style="background:transparent;">subir foto</button>
    </div>
</div>

        <h1 style="font-family: roboto;">Tu Red de Donación $<?php echo $montoDonacion; ?></h1>
    </div>
        </div>
        <!-- Estado del usuario: Activo/Inactivo -->

                <h2 style="font-family: roboto;">
                    <strong>Usuario:</strong> 
                    <?php echo (!empty($tipoSubusuario)) ? "Activo" : "Inactivo"; ?>
                </h2>
                        <div class="contenedor4">
                <button id="toggleBtn" class="toggle-button3"> <i class="fas fa-chevron-down" id="toggleIcon"></i> Ocultar Información para Donaciones</button>
    </div>

                <!-- Mostrar cuenta para depositar si el usuario es activo -->
                <?php if (!empty($tipoSubusuario)): ?>
                   <div class="card-info" id="infoUser">
    <h2>Datos para hacer tu Donación Mensual:</h2>
    <span class="variable-box2"><span class="variable-label">Tarjeta:</span> <?php echo $tarjetaBancaria; ?></span>
    <span class="variable-box2"><span class="variable-label">Cuenta:</span> <?php echo $cuentaDepositar; ?></span>
    <span class="variable-box2"><span class="variable-label">CLABE:</span> <?php echo $CLABE; ?></span>
    <span class="variable-box2"><span class="variable-label">Banco:</span> <?php echo $banco; ?></span>
      <h2 style="font-family: roboto;">
                    <span class="variable-label">Fecha de registro:</span>
                    <span class="variable-box"><?php echo $fechaDepositoFormat; ?></span>
                </h2>
                <small style="font-family: roboto; display: block; margin-top: 5px; text-align: center; color:red;">
    Recuerda que la fecha límite para hacer tu donación mensual es el mismo día del mes que te registraste a tu Red de Donación.
</small>
<!-- Coloca el formulario de subida de archivo aquí, fuera de la tabla -->
        <?php if ($_SESSION['usuarioObj']->id): ?>
            <?php if (!empty($referenciaValidacion)): ?>
                <a href="<?php echo $referenciaValidacion; ?>" target="_blank" style="color:blue;"><?php echo basename($referenciaValidacion); ?></a>
            <?php else: ?>
              <form id="uploadForm" method="POST" enctype="multipart/form-data" class="formularioArchivos" action ="">
    <div class="file-upload-wrapper">
        <input type="file" name="fileToUpload" id="fileToUpload" class="file-upload-input">
        <label for="fileToUpload" class="file-upload-label">Adjuntar Evidencia de Donación<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-paperclip" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
  <path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" />
</svg></label>
        <span class="file-name">Ningún archivo seleccionado</span>
    </div>
    <input type="hidden" name="nombreTabla" value="<?php echo $nombreTabla; ?>">
    <input type="submit" value="Enviar Evidencia" name="submit" class="submit-btn2">
</form>
            <?php endif; ?>
        <?php endif; ?>
                    <a href="validaciones.php" class="btn btn-azul float-right">Verificar Donaciones Recibidas</a>
    </div>
     
<?php endif; ?>

            </div>
        </div>
    </div>
    <br>
<div class="contenedor2">
<a href="eligeAportacion.php" class="btn-profesional">Únete a una Red de Donación</a><br>
</div>
<br>

<br>
<div class="contenedor2" style="max-width:60rem; max-height:20rem; padding:1rem;">
   <form action="miRed.php" method="post" id="redForm" style="color:transparent;">
    <label for="nombreTabla" class="tituloFormularioTabla">Tus Redes de Donación</label>
    <input type="hidden" name="nombreTabla" id="nombreTabla" value="">

    <div class="btn-group">
       <button type="button" class="btn btn-red" data-value="comunidad100">Red de Donación $100</button>
    <button type="button" class="btn btn-red" data-value="comunidad500">Red de Donación $500</button>
    <button type="button" class="btn btn-red" data-value="comunidad1000">Red de Donación $1000</button>

    </div>
    
    <button type="submit" class="submit-btn"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-arrow-right" width="80" height="45" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
  <path d="M12 3a9 9 0 1 0 0 18a9 9 0 0 0 0 -18" />
  <path d="M16 12l-4 -4" />
  <path d="M16 12h-8" />
  <path d="M12 16l4 -4" />
</svg></button>
</form>
</div><br>
</body>
            </main>
<div class="contenedor" style="background-color: #05e09c;">
        <!-- Tabla de usuarios -->
       <div class="contenedor4">
    <button class="toggle-btn2" onclick="toggleTableVisibility()">
        <i class="fas fa-chevron-down" id="toggleTableIcon"></i> 
<span id="toggleTableText" data-monto="<?php echo $montoDonacion ?>">Mostrar / Ocultar Tabla General de Donaciones $<?php echo $montoDonacion ?></span>

    </button>
</div>

<div class="contenedor hidden" id="userTable">
        <div class="row py-1">
            <div class="col">
             <div class="table-responsive"> <!-- Agrega esta línea -->
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Código Personal</th>
                            <th>Auspiciador</th>
                            <th>Comprobante de Donación</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultado as $row) {
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nombres']; ?></td>
                                <td><?php echo $row['apellidos']; ?></td>
                                <td><?php echo $row['referido']; ?></td>
                                <td><?php echo $row['auspiciador_id']; ?></td>
                                
<td>
   <?php if (!empty($row['referenciaValidacion'])): ?>
                                        <a href="<?php echo $row['referenciaValidacion']; ?>" target="_blank" style="color:blue;"><?php echo basename($row['referenciaValidacion']); ?></a>
                                    <?php else: ?>
                                        Pendiente de enviar evidencia
                                    <?php endif; ?>
                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
</div>
<div class="contenedor2" style="padding:1rem;">
<?php
echo "<div id='linkTabla'>";
for ($page = 1; $page <= $totalPages; $page++) {
    // Conservar otros parámetros en la URL
    $params = $_GET;
    $params['page'] = $page;
    $params['nombreTabla'] = $nombreTabla;  // Añadir el nombre de la tabla al array de parámetros
    $queryString = http_build_query($params);
    echo "<a class=\"page-linkt\" href=\"?{$queryString}#userTable\">{$page}</a> ";
}
echo "</div>";
?>
</div>

<?php
incluirTemplate('footer');
//    echo "<pre>";
//      var_dump($_SESSION['usuarioObj']);
//      echo "</pre>";
?>
