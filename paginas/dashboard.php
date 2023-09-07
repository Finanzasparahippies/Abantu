<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);



require 'usuario.php';

require '../includes/config/database.php';
include '../includes/funciones.php';


session_start();


incluirTemplate('header');

$db = conectarDB();

$actualizacion = isset($_GET['actualizacion']) ? intval($_GET['actualizacion']) : 0;
$nombres = $_SESSION['usuarioObj']->nombres;
$apellidos = $_SESSION['usuarioObj']->apellidos;
$nombreTabla = 'comunidad100';
$usuarioObjRecuperado = null;
$cuentaDepositar = '';
$fechaRegistro = '';
$validacionMensual = '';
$montoDonacion = null;
$imagenPerfil = '';
$id = $_SESSION['usuarioObj']->id;
$fechaDepositoFormat = '';
$mensajeAdvertencia = '';


//print_r($_SESSION['usuarioObj']);

if (isset($_SESSION['usuarioObj'])) {
    $usuarioObjRecuperado = ($_SESSION['usuarioObj']);
}

$allowedTables = ['comunidad100', 'comunidad500', 'comunidad1000'];

if (isset($_POST['nombreTabla']) && in_array($_POST['nombreTabla'], $allowedTables)) {
    $nombreTabla = $_POST['nombreTabla'];
     $montoDonacion = "100";
    if($nombreTabla === 'comunidad500') {
        $montoDonacion = "500";
    } else if($nombreTabla === 'comunidad1000') {
        $montoDonacion = "1000";
    }
} else if (isset($_SESSION['nombreTabla']) && in_array($_SESSION['nombreTabla'], $allowedTables)) {
    $nombreTabla = $_SESSION['nombreTabla'];
     $montoDonacion = "100";
    if($nombreTabla === 'comunidad500') {
        $montoDonacion = "500";
    } else if($nombreTabla === 'comunidad1000') {
        $montoDonacion = "1000";
    }
} else {
    $nombreTabla = 'comunidad100'; // valor por defecto
}


//$_SESSION['usuarioObjeto']->nombreTabla = $nombreTabla;


// Obtén la página actual. Si no se proporciona ningún número de página, se establece en 1
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Define cuántos resultados te gustaría mostrar por página
$resultsPerPage = 15;

// Calcula la cláusula OFFSET para la consulta SQL
$offset = ($currentPage - 1) * $resultsPerPage;

// Modifica la consulta para incluir las cláusulas LIMIT y OFFSET
$query = "SELECT usuario.id, nombres, apellidos, usuario.correo, referido, {$nombreTabla}.referenciaValidacion, {$nombreTabla}.validacionMensual, fechaRegistro, {$nombreTabla}.tipoSubusuario, auspiciador_id, {$nombreTabla}.nivel, {$nombreTabla}.cuentaDepositar
          FROM usuario
          JOIN {$nombreTabla} ON usuario.id = {$nombreTabla}.usuario_id
          ORDER BY usuario.id ASC
          LIMIT {$resultsPerPage} OFFSET {$offset}";

$resultado = mysqli_query($db, $query);


if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($db));
}

// MySQLi devuelve un objeto mysqli_result. Para obtener un array asociativo, utilizamos mysqli_fetch_all con el flag MYSQLI_ASSOC.
$datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($datos){
    
    $usuarioId = $_SESSION['usuarioObj']->id;

    // Consulta para obtener cuentaDepositar de la tabla $nombreTabla
    $queryCuenta = "SELECT cuentaDepositar, TarjetaBancaria, CLABE, tipoSubusuario FROM {$nombreTabla} WHERE usuario_id = {$usuarioId}";
    $resultadoCuenta = mysqli_query($db, $queryCuenta);

    if (!$resultadoCuenta) {
    die("Error en la consulta: " . mysqli_error($db) . ". Consulta: " . $query);
    }

    $usuarioCuenta = mysqli_fetch_assoc($resultadoCuenta);
    $cuentaDepositar = isset($usuarioCuenta['cuentaDepositar']) ? $usuarioCuenta['cuentaDepositar'] : '';
    $tarjetaBancaria = isset($usuarioCuenta['TarjetaBancaria']) ? $usuarioCuenta['TarjetaBancaria'] : '';
    $CLABE = isset($usuarioCuenta['CLABE']) ? $usuarioCuenta['CLABE'] : '';
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
   if(isset($_POST["submit"])) {
    $userId = $_SESSION['usuarioObj']->id;
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "pdf" ) {
        echo "Lo sentimos, solo se permiten archivos JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Lo sentimos, tu archivo es muy grande.";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "Lo sentimos, el archivo ya existe.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Lo sentimos, tu archivo no se subió.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "El archivo ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " ha sido subido.";

            $nombreTabla = $_POST["nombreTabla"];
            $tablaValidacion = str_replace('comunidad', 'validacion', $nombreTabla);
            
            mysqli_begin_transaction($db);

            $queryComunidad = "UPDATE {$nombreTabla} SET referenciaValidacion = '{$target_file}' WHERE usuario_id = {$userId}";
            $resultadoComunidad = mysqli_query($db, $queryComunidad);

            if ($resultadoComunidad) {
                $queryValidacion = "UPDATE {$tablaValidacion} SET evidencia = '{$target_file}' WHERE usuario_id = {$userId}";
                $resultadoValidacion = mysqli_query($db, $queryValidacion);

                if ($resultadoValidacion) {
                    mysqli_commit($db);
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
    display: flex;
    justify-content: center;
    align-items: center;
    padding:2rem;
}

       .info-card {
    border: none;
    padding: 40px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    background-color: white;
    position: relative;
    border-radius: 20px;
    width: 100%; /* Controla el ancho máximo de la tarjeta */
    transition: transform 0.3s, box-shadow 0.3s;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
}


        .info-card h1, .info-card h2 {
            margin-bottom: 0px;
            margin-right: 0rem;
        }

        .info-card h1 {
            overflow: hidden;
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
    margin-left:25px;
    color: white;

}

.icon-copy:hover {
    transform: scale(1.1);
    color: #0055cc;
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
    display: flex;
    align-items: center;
    gap: 10px;
    border: none;
    padding: 10px 20px;
    background: linear-gradient(135deg, #05e09c, #03b07c);
    color: white;
    font-size: 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: transform 0.3s, filter 0.3s;
}

.variable-box:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
}

.variable-label, .variable-box {
    margin-right: 10px;
}

.variable-label {
    font-weight: bold;
    margin-right: 10px;
    text-align: center;
}

        .h1 {
        font-family: 'Roboto', sans-serif;

        }
      .btn-profesional {
    padding: 12px 30px;
    font-size: 16px;
    background: linear-gradient(135deg, #05e09c, #03b07c);
    color: white!important;
    border: none;
    border-radius: 25px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    text-align: center;
    transition: transform 0.3s, filter 0.3s;
}
    .btn-profesional9 {
    padding: 12px 30px;
    font-size: 16px;
    background: linear-gradient(135deg, #05e09c, #03b07c);
    color: white;
    border: none;
    border-radius: 25px;
    text-transform: none;
    font-weight: 600;
    letter-spacing: 1px;
    text-align: center;
    transition: transform 0.3s, filter 0.3s;
}
    #referido {
    color: white;
    font-weight: bold; /* Añade negritas para resaltar más */
    font-size: 1.5em; /* Aumenta un poco el tamaño del texto */
    text-decoration: none; /* Elimina cualquier subrayado */
    border-bottom: 2px dashed #0080FF; /* Añade una línea punteada debajo para resaltar más */
    transition: color 0.3s, border-color 0.3s; /* Transición suave al cambiar de color */
}
   #referido:hover {
    color: #0055cc; /* Un tono de azul más oscuro al pasar el ratón */
    border-color: #0055cc; /* El mismo tono para la línea debajo */
}

.btn-profesional:hover {
    transform: translateY(-3px);
    filter: brightness(1.1);
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
}

.btn-profesional9:hover {
    transform: translateY(-3px);
    filter: brightness(1.1);
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
    color:black;
}

.btn-profesional:active {
    transform: translateY(0px);
    box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.2);
}
.btn-profesional9:active {
    transform: translateY(0px);
    box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.2);
}
.titulo-bienvenida {
    color: #03b07c;
    font-size: 4rem;
    text-align: center;
    padding-bottom:2rem;
}

.titulo-codigo {
    color:white;
    font-size: 22px;
    text-align: center;
    margin-bottom: 0px;
}

.codigo-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px; /* Espaciado entre elementos */
    cursor: pointer;

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

<?php if ($actualizacion === 2): ?>
    <div class="custom-alert">
        <p>Bienvenido a la Red de Donación de $100 pesos, ¡Gracias por Ayudar!</p>
    </div>
<?php endif; ?>

<?php if ($actualizacion === 3): ?>
    <div class="custom-alert">
        <p>Evidencia enviada, ¡Gracias por Ayudar!</p>
    </div>
<?php endif; ?>


<div class="container">
    <div class="info-card">
        
        <h1 class="titulo-bienvenida">Hola, <strong><?php echo $nombres . ' ' . $apellidos; ?></strong></h1>

        <div class="codigo-section">
            <span class="btn-profesional9" id="btn-profesional9">
                <small class="titulo-codigo">Tu Código de Invitación es </small>
                <a id="referido"><?php echo $codigoReferido; ?></a>
                <i class="fas fa-copy icon-copy" id="copyButton"></i>
            </span>
            
            <a href="eligeAportacion.php" class="btn-profesional" id="unirse">Unirse a Red de Donación</a>
            <a href="perfil.php" class="btn-profesional">EDITAR PERFIL</a>
        </div>
    </div>
</div>


</body>

<br>
<div class="contenedor2">

</div>
<br>
  <!-- Formulario para seleccionar tabla -->
  
<div class="contenedor2" style="max-width:69rem; max-height:20rem; padding:1rem;">
   <form action="miRed.php" method="post" id="redForm" style="color:#05e09c;">
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
</div>
<br>


<?php
incluirTemplate('footer');
//    echo "<pre>";
//      var_dump($_SESSION['usuarioObj']);
//      echo "</pre>";
?>
