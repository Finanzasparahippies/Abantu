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

$nombres = $_SESSION['usuarioObj']->nombres;
$apellidos = $_SESSION['usuarioObj']->apellidos;
$auspiciadorDirecto = $_SESSION['usuarioObj']->auspiciadorDirecto;
$nombreTabla = '';
$usuarioObjRecuperado = null;

if (isset($_SESSION['usuarioObj'])) {
    $usuarioObjRecuperado = ($_SESSION['usuarioObj']);
}

$allowedTables = ['comunidad100', 'comunidad500', 'comunidad1000'];

// Comprueba si se ha enviado una solicitud GET para cambiar la tabla
if (isset($_GET['nombreTabla']) && in_array($_GET['nombreTabla'], $allowedTables)) {
    $nombreTablaComunidad = $_GET['nombreTabla'];
    $nombreTablaValidacion = str_replace("comunidad", "validacion", $nombreTablaComunidad);
    $referido = $_SESSION['usuarioObj']->referido;
} else {
    $nombreTablaComunidad = 'comunidad100'; // valor por defecto
    $nombreTablaValidacion = str_replace("comunidad", "validacion", $nombreTablaComunidad);
}



$referido = $_SESSION['usuarioObj']->referido; // Recuperamos el codigo de referido del usuario de la sesión

$query = "SELECT {$nombreTablaValidacion}.usuario_id, {$nombreTablaValidacion}.donado_id, {$nombreTablaValidacion}.fecha, {$nombreTablaValidacion}.evidencia, {$nombreTablaValidacion}.validado
          FROM {$nombreTablaValidacion}
          JOIN {$nombreTablaComunidad} ON {$nombreTablaValidacion}.usuario_id = {$nombreTablaComunidad}.usuario_id
          WHERE {$nombreTablaValidacion}.donado_id = {$referido} 
          AND NOT ({$nombreTablaComunidad}.tipoSubusuario IN ('B', 'D') AND {$nombreTablaComunidad}.nivel = 1)
          AND NOT ({$nombreTablaComunidad}.tipoSubusuario IN ('A', 'C') AND {$nombreTablaComunidad}.nivel = 2)
          AND NOT ({$nombreTablaComunidad}.tipoSubusuario IN ('A', 'C') AND {$nombreTablaComunidad}.nivel = 3)
          AND NOT ({$nombreTablaComunidad}.tipoSubusuario IN ('A', 'C') AND {$nombreTablaComunidad}.nivel = 4)
          ORDER BY {$nombreTablaValidacion}.usuario_id ASC";



$resultado = mysqli_query($db, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($db));
}

$datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop a través de todos los usuarios que necesitan validación
    foreach ($datos as $dato) {
        $usuario_id = $dato['usuario_id'];
        $validado = isset($_POST['validaciones'][$usuario_id]) ? 1 : 0; // Comprueba si la casilla está marcada

        // Captura el comentario del usuario desde el formulario
        $comentario = mysqli_real_escape_string($db, $_POST['comentarios'][$usuario_id]);

        // Aquí puedes actualizar las columnas 'validado' y 'comentarios' en la base de datos para este usuario
        $query = "UPDATE {$nombreTablaValidacion} SET validado = {$validado}, comentarios = '{$comentario}' WHERE usuario_id = {$usuario_id}";
        mysqli_query($db, $query);
    }
}

?>

<style>
    
    .custom-container {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 5px;
    background-color: #05e09c;
}

.switch-table-btn {
    margin-left: 10px;
    background-color: #4CAF50;
    color: white;
}

.submit-report-btn {
    background-color: #FF5733;
    color: white;
}

.custom-table th, .custom-table td {
    padding: 10px 0px;
    text-align: center;

    #formularioCuadrado {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background: #05e09c!important;
    border-radius:0px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1)!important;
}
}

</style>

<div class="custom-container">
    <h1><strong>Evidencias de Donaciones Recibidas</strong></h1>
    <strong style="display:block; color:white; text-align:center;">Recuerda marcar sólo las evidencias que no cumplen los requisitos de donación.</strong>
    
    <!-- Formulario GET para seleccionar la tabla -->
    <div class="form-section">
        <form method="get" action="" id="formularioCuadrado"></h2>
            <label for="nombreTabla">Seleccione una tabla:</label>
            <select name="nombreTabla" id="nombreTabla">
                <option value="comunidad100" <?php echo $nombreTablaComunidad === 'comunidad100' ? 'selected' : ''; ?>>comunidad 100</option>
                <option value="comunidad500" <?php echo $nombreTablaComunidad === 'comunidad500' ? 'selected' : ''; ?>>comunidad 500</option>
                <option value="comunidad1000" <?php echo $nombreTablaComunidad === 'comunidad1000' ? 'selected' : ''; ?>>comunidad 1000</option>
            </select>
            <input type="submit" value="Cambiar tabla" class="btnV switch-table-btn">
        </form>
    </div>
    
    <!-- Formulario POST para enviar validaciones y comentarios -->
    <div class="form-section">
        <form method="post" style="background-color:#05e09c!;">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Id de tu Donador</th>
                        <th>Fecha</th>
                        <th>Comprobante de Donación</th>
                        <th>Error</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $dato) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dato['usuario_id']);?></td>
                            <td><?php echo htmlspecialchars($dato['fecha'] ?? ''); ?></td>
                            <td>
                                <?php if (!empty($dato['evidencia'])): ?>
                                    <a href="<?php echo htmlspecialchars($dato['evidencia']); ?>" target="_blank">Ver Evidencia</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input id="checkbox<?php echo htmlspecialchars($dato['usuario_id']); ?>" class="styled-checkbox2" type="checkbox" name="validaciones[<?php echo htmlspecialchars($dato['usuario_id']); ?>]" value="1" <?php echo intval($dato['validado']) === 1 ? 'checked' : ''; ?>> 
                                <label for="checkbox<?php echo htmlspecialchars($dato['usuario_id']); ?>"></label>
                            </td>
                            <td>
                                <input type="text" name="comentarios[<?php echo htmlspecialchars($dato['usuario_id']); ?>]" placeholder="Escribe un comentario...">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="submit" value="Enviar reporte de errores" class="btnV submit-report-btn">
        </form>
    </div>
</div>



<?php
incluirTemplate('footer');
?>
