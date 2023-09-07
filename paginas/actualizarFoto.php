<?php
// Iniciar la sesión
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Verificar que el usuario esté autenticado
if(!isset($_SESSION['usuarioObj'])) {
    header("Location: login.php");
    exit;
}

$mensajeError = '';
$mensajeExito = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['nuevaImagen'])) {
    $directorioDestino = "../uploads/imagenesPerfil/";
    $archivoDestino = $directorioDestino . basename($_FILES['nuevaImagen']['name']);
    $extensionImagen = strtolower(pathinfo($archivoDestino, PATHINFO_EXTENSION));

    // Verificar que el archivo es una imagen
    $check = getimagesize($_FILES['nuevaImagen']['tmp_name']);
    if($check !== false) {
        if(move_uploaded_file($_FILES['nuevaImagen']['tmp_name'], $archivoDestino)) {
            // TODO: Actualizar la columna `ImagenPerfil` en la base de datos
            $mensajeExito = "Imagen subida exitosamente!";
        } else {
            $mensajeError = "Error al subir la imagen.";
        }
    } else {
        $mensajeError = "El archivo no es una imagen.";
    }
}
?>

    <?php
    if(!empty($mensajeError)) {
        echo "<div class='error'>{$mensajeError}</div>";
    }
    if(!empty($mensajeExito)) {
        echo "<div class='exito'>{$mensajeExito}</div>";
    }
    ?>
    <form action="actualizarFoto.php" method="POST" enctype="multipart/form-data">
        <label for="nuevaImagen">Seleccionar imagen:</label>
        <input type="file" name="nuevaImagen" id="nuevaImagen" required>
        <input type="submit" value="Subir Imagen">
    </form>
</body>
</html>
