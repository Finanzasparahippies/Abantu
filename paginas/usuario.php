<?php
class Usuario {
  public $id;
  public $nombres;
  public $apellidos;
  public $RFC;
  public $telefono;
  public $correo;
  public $eWallet;
  public $eWallet2;
  public $cuentaBancaria;
  public $cuentaBancaria2;
  public $referido;
  public $fechaRegistro;
  public $nivel100;
  public $nivel500;
  public $nivel1000;
  public $auspiciadorDirecto;
  public $auspiciador2;
  public $auspiciador3;
  public $auspiciador100;
  public $auspiciador500;
  public $auspiciador1000;
  public $tipoSubusuario;
  public $tipoSubusuario100;
  public $tipoSubusuario500;
  public $tipoSubusuario1000;
  public $referencia100;
  public $referencia500;
  public $referencia1000;
  public $validacion100;
  public $validacion500;
  public $validacion1000;
  public $montoSeleccionado;
  public $comunidad100;
  public $comunidad500;
  public $comunidad1000;
  public $cuentaDepositar100;
  public $cuentaDepositar500;
  public $cuentaDepositar1000;
  public $banco100;
  public $banco500;
  public $banco1000;
  public $usuariosNivel0;
  public $usuariosNivel1;
  public $usuariosNivel2;
  public $usuariosNivel3;
  public $usuariosNivel4;
  public $usuariosNivel5;
  public $imagenPerfil;
  
  // Constructor de la clase Usuario
  public function __construct(
    $id, $nombres, $apellidos, $RFC, $telefono, $correo, $eWallet, $eWallet2, 
    $cuentaBancaria, $cuentaBancaria2, int $referido, $fechaRegistro, $nivel100, 
    $nivel500, $nivel1000, $auspiciadorDirecto, $auspiciador2, $auspiciador3, int $auspiciador100, int $auspiciador500, 
    int $auspiciador1000, string $tipoSubusuario, string $tipoSubusuario100, string $tipoSubusuario500, 
    string $tipoSubusuario1000, $referencia100, $referencia500, $referencia1000,
    $validacion100, $validacion500, $validacion1000, $montoSeleccionado, 
    $comunidad100, $comunidad500, $comunidad1000, string $cuentaDepositar100, $cuentaDepositar500, $cuentaDepositar1000, $banco100, $banco500, $banco1000, $usuariosNivel0, 
    $usuariosNivel1, $usuariosNivel2, $usuariosNivel3, $usuariosNivel4, $usuariosNivel5, $imagenPerfil
  ) {
    $this->id = $id;
    $this->nombres = $nombres;
    $this->apellidos = $apellidos;
    $this->RFC = $RFC;
    $this->telefono = $telefono;
    $this->correo = $correo;
    $this->eWallet = $eWallet;
    $this->eWallet2 = $eWallet2;
    $this->cuentaBancaria = $cuentaBancaria;
    $this->cuentaBancaria2 = $cuentaBancaria2;
    $this->referido = $referido;
    $this->fechaRegistro = $fechaRegistro;
    $this->nivel100 = $nivel100;
    $this->nivel500 = $nivel500;
    $this->nivel1000 = $nivel1000;
    $this->auspiciadorDirecto = $auspiciadorDirecto;
    $this->auspiciador2 = $auspiciador2;
    $this->auspiciador3 = $auspiciador3;
    $this->auspiciador100 = $auspiciador100;
    $this->auspiciador500 = $auspiciador500;
    $this->auspiciador1000 = $auspiciador1000;
    $this->tipoSubusuario = $tipoSubusuario;
    $this->tipoSubusuario100 = $tipoSubusuario100;
    $this->tipoSubusuario500 = $tipoSubusuario500;
    $this->tipoSubusuario1000 = $tipoSubusuario1000;
    $this->referencia100 = $referencia100;
    $this->referencia500 = $referencia500;
    $this->referencia1000 = $referencia1000;
    $this->validacion100 = $validacion100;
    $this->validacion500 = $validacion500;
    $this->validacion1000 = $validacion1000;
    $this->montoSeleccionado = $montoSeleccionado;
    $this->comunidad100 = $comunidad100;
    $this->comunidad500 = $comunidad500;
    $this->comunidad1000 = $comunidad1000;
    $this->cuentaDepositar100 = $cuentaDepositar100;
    $this->cuentaDepositar500 = $cuentaDepositar500;
    $this->cuentaDepositar1000 = $cuentaDepositar1000;
    $this->banco100 = $banco100;
    $this->banco500 = $banco500;
    $this->banco1000 = $banco1000;
    $this->usuariosNivel0 = $usuariosNivel0;
    $this->usuariosNivel1 = $usuariosNivel1;
    $this->usuariosNivel2 = $usuariosNivel2;
    $this->usuariosNivel3 = $usuariosNivel3;
    $this->usuariosNivel4 = $usuariosNivel4;
    $this->usuariosNivel5 = $usuariosNivel5;
    $this->imagenPerfil = $imagenPerfil;
  }

  // Aquí podrías añadir métodos getter para cada propiedad
  public function getId() {
    return $this->id;
  }

  public function getNombres() {
    return $this->nombres;
  }

public function actualizarAuspiciadorComunidad($db, $nombreTabla, $auspiciadorTabla, $id)
{
    
    // Define la propiedad del auspiciador dependiendo de la tabla
    if (substr($nombreTabla, -4) === '1000') {
    $auspiciadorProperty = 'auspiciador' . substr($nombreTabla, -4);
} else {
    $auspiciadorProperty = 'auspiciador' . substr($nombreTabla, -3);
}
    $this->$auspiciadorProperty = $auspiciadorTabla;


    // Actualizar 'subusuario'
    $queryUpdateSubusuario = "UPDATE subusuario SET {$auspiciadorProperty} = ? WHERE usuario_id = ?";
    $stmtUpdateSubusuario = mysqli_prepare($db, $queryUpdateSubusuario);
    mysqli_stmt_bind_param($stmtUpdateSubusuario, "ii", $auspiciadorTabla, $id);
    mysqli_stmt_execute($stmtUpdateSubusuario);

    if (mysqli_stmt_affected_rows($stmtUpdateSubusuario) <= 0) {
        die("Error en la consulta de subusuario: " . mysqli_error($db));
    }

    // Actualizar $nombreTabla
    $queryUpdate = "UPDATE {$nombreTabla} SET auspiciador_id = ? WHERE usuario_id = ?";
    $stmtUpdate = mysqli_prepare($db, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "ii", $auspiciadorTabla, $id);
    mysqli_stmt_execute($stmtUpdate);

    if (mysqli_stmt_affected_rows($stmtUpdate) <= 0) {
        die("Error en la consulta: " . mysqli_error($db));
    }

    //echo "Auspiciador actualizado con éxito.";
   //echo "Las tablas que se actualizaron son subusuario y " . $nombreTabla;
    return true;
}



public function obtenerNombreTabla($montoSeleccionado)
    {
        switch ($montoSeleccionado) {
            case 100:
                return 'comunidad100';
            case 500:
                return 'comunidad500';
            case 1000:
                return 'comunidad1000';
            default:
                return false;
        }
    }
    
  public function actualizarTipoSubusuarioComunidad($db, $nombreTabla, $auspiciadorDirecto, $id)
{
    // Obtener el número de usuarios ya registrados bajo este auspiciadorDirecto
    $queryConteoTipo = "SELECT COUNT(*) as count FROM {$nombreTabla} WHERE auspiciador_id = $auspiciadorDirecto";
    $resultadoTipos = mysqli_query($db, $queryConteoTipo);
    if (!$resultadoTipos) {
        die("Error en la consulta: " . mysqli_error($db));
    }
    
    $countTipos = mysqli_fetch_assoc($resultadoTipos)['count'];

    // Consulta para obtener el valor de la columna compartirCuenta
    $queryRed = "SELECT compartirCuenta as count FROM $nombreTabla WHERE codigoReferido = $auspiciadorDirecto";
    $resultadoRed = mysqli_query($db, $queryRed);
    if (!$resultadoRed) {
        die("Error en la consulta: " . mysqli_error($db));
    }

    $countRed = mysqli_fetch_assoc($resultadoRed)['count'];

    // Tipos a asignar
    $tipos = ['A', 'B', 'C', 'D'];

    if ($countRed > 2 && $countRed <= 6) {
        $queryUpdateNivel = "UPDATE {$nombreTabla} SET nivelAsignado = 2 WHERE usuario_id = $id";
        $resultadoUpdateNivel = mysqli_query($db, $queryUpdateNivel);
        if (!$resultadoUpdateNivel) {
            die("Error en la consulta: " . mysqli_error($db));
        }
        echo "Nivel actualizado con éxito.";
        return true;
    } elseif ($countRed <= 2) {
        $tipoSubusuarioAsignado = $tipos[($countTipos - 1) % 4];
        
        $nombreTabla = mysqli_real_escape_string($db, $nombreTabla);
        $usuario_id = mysqli_real_escape_string($db, $id);

        // Actualizar el tipo de subusuario y el nivel
        $query2 = "UPDATE {$nombreTabla} SET tipoSubusuario = '{$tipoSubusuarioAsignado}', nivelAsignado = 1 WHERE usuario_id = $id";
        $resultado2 = mysqli_query($db, $query2);
        if (!$resultado2) {
            die("Error en la consulta: " . mysqli_error($db));
        }

        // Almacenar el tipo de subusuario en la propiedad de la instancia de la clase Usuario en la sesión
        if (isset($_SESSION['usuarioObj'])) {
            $auspiciadorProperty = 'tipoSubusuario' . substr($nombreTabla, -3);
            $_SESSION['usuarioObj']->$auspiciadorProperty = $tipoSubusuarioAsignado;
        }

        return true;
    } else {
        // Aquí maneja el caso en que $countRed sea mayor o igual a 6
        echo "No es posible unirse debido a que el usuario que elegiste ya compartió 6 veces su código de Invitación.";
        exit;
    }
}


private function buscarAuspiciador($db, $auspiciadorDirecto)
{
    $query = "SELECT id FROM usuario WHERE referido = {$auspiciadorDirecto}";
    $resultado = mysqli_query($db, $query);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($db));
    }

    while ($row = mysqli_fetch_assoc($resultado)) {
        $queryCount = "SELECT COUNT(*) as count FROM usuario WHERE referido = {$row['usuario_id']}";
        $resultadoCount = mysqli_query($db, $queryCount);
        if (!$resultadoCount) {
            die("Error en la consulta: " . mysqli_error($db));
        }

        $count = mysqli_fetch_assoc($resultadoCount)['count'];
        if ($count < 4) {
            // Si el usuario tiene menos de 4 usuarios directos, lo devolvemos
            return $row['usuario_id'];
        } else {
            // De lo contrario, buscamos en sus usuarios directos
            $newAuspiciadorId = $this->buscarAuspiciador($db, $row['usuario_id']);
            if ($newAuspiciadorId) {
                return $newAuspiciadorId;
            }
        }
    }

    // No encontramos un auspiciador adecuado, devolvemos false
    return false;
}

public function actualizarCuentaDepositar($db, $id, $nombreTabla, $auspiciadorDirecto) {
    $shouldUpdateOriginalInfo = true; // Esta variable determinará si actualizamos la información bancaria original o no.
    $auspiciador2 = null; // Definir antes del bloque if


     // 1. Verificar el contador de compartir del auspiciador
    $queryCounter = "SELECT compartirCuenta FROM $nombreTabla WHERE codigoReferido = ?";
    $stmtCounter = $db->prepare($queryCounter);
    $stmtCounter->bind_param('i', $auspiciadorDirecto);
    $stmtCounter->execute();
    $resultCounter = $stmtCounter->get_result();
    $dataCounter = $resultCounter->fetch_assoc();

    if ($dataCounter['compartirCuenta'] >= 30) {
        // Si el contador ya es 30 o mayor, detén la ejecución y devuelve un error.
        // Puedes adaptar el mensaje de error según lo que quieras mostrar al usuario
        //echo "Has excedido el límite para compartir tu información bancaria.";
        return false;
    }
    
    // Primero, verificamos el tipo de subusuario para el usuario_id dado en la tabla $nombreTabla
    $queryCheck = "SELECT tipoSubusuario, nivelAsignado FROM {$nombreTabla} WHERE usuario_id = ?";
    $stmtCheck = $db->prepare($queryCheck);
    $stmtCheck->bind_param('i', $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $dataCheck = $resultCheck->fetch_assoc();

    // Si tipoSubusuario es B o D y el nivel es 
    if (isset($dataCheck['tipoSubusuario']) && ($dataCheck['tipoSubusuario'] == 'B' || $dataCheck['tipoSubusuario'] == 'D') && ($dataCheck['nivelAsignado'] == '1')) {
    // Registra el error para depuración
    $queryUpdateNivel = "UPDATE {$nombreTabla} SET nivelAsignado = 2 WHERE usuario_id = $id";
    $resultadoUpdateNivel = mysqli_query($db, $queryUpdateNivel);
        
    // Busca el auspiciador_id en la tabla $nombreTabla con el valor $auspiciadorDirecto en la columna codigoReferido
    $queryAuspiciador2 = "SELECT auspiciador_id FROM {$nombreTabla} WHERE codigoReferido = $auspiciadorDirecto";
$resultadoAuspiciador2 = mysqli_query($db, $queryAuspiciador2);

$rowAuspiciador2 = mysqli_fetch_assoc($resultadoAuspiciador2);

if (!$resultadoAuspiciador2) {
    die("Error en la consulta: " . mysqli_error($db));
}

if ($rowAuspiciador2 || isset($rowAuspiciador2['auspiciador_id'])) {
    $auspiciador2 = $rowAuspiciador2['auspiciador_id'];
    $_SESSION['usuarioObj']->auspiciador2 = $auspiciador2;


$queryUpdateAuspiciador2 = "UPDATE {$nombreTabla} SET auspiciador2_id = ? WHERE usuario_id = ?";
$stmtUpdateAuspiciador2 = $db->prepare($queryUpdateAuspiciador2);
$stmtUpdateAuspiciador2->bind_param('ii', $auspiciador2, $id);
$resultadoUpdateAuspiciador2 = $stmtUpdateAuspiciador2->execute();

if (!$resultadoUpdateAuspiciador2) {
    die("Error en la consulta: " . mysqli_error($db));
}

    // Obtener los datos bancarios del usuario identificado por $auspiciador2
    $queryDatosBancarios = "SELECT cuentaBancaria, TarjetaBancaria, CLABE, cuentaBancaria2 FROM usuario WHERE referido = ?";
    $stmtDatosBancarios = $db->prepare($queryDatosBancarios);
    $stmtDatosBancarios->bind_param('i', $auspiciador2);
    $stmtDatosBancarios->execute();
    $resultDatosBancarios = $stmtDatosBancarios->get_result();
    $dataDatosBancarios = $resultDatosBancarios->fetch_assoc();

    // Actualizar los datos bancarios en la tabla comunidad para el usuario en sesión
    $queryUpdateComunidad = "UPDATE {$nombreTabla} SET cuentaBancaria2 = ?, tarjetaBancaria2 = ?, CLABE2 = ?, Banco2 = ? WHERE usuario_id = ?";
    $stmtUpdateComunidad = $db->prepare($queryUpdateComunidad);
    $stmtUpdateComunidad->bind_param('sssi', 
        $dataDatosBancarios['cuentaBancaria'],
        $dataDatosBancarios['TarjetaBancaria'],
        $dataDatosBancarios['CLABE'],
        $dataDatosBancarios['Banco2'],
        $_SESSION['usuarioObj']->id  // Suponiendo que tienes un objeto de usuario en la sesión y que tiene una propiedad "id"
    );

    if ($stmtUpdateComunidad->execute()) {
        $queryUpdateCounter = "UPDATE $nombreTabla SET compartirCuenta = compartirCuenta + 1 WHERE codigoReferido = ?";
        $stmtUpdateCounter = $db->prepare($queryUpdateCounter);
        $stmtUpdateCounter->bind_param('i', $auspiciador2);
        $stmtUpdateCounter->execute();

    return $stmtUpdateCounter->affected_rows > 0;
        // Si quieres, aquí puedes poner algún mensaje indicando que la actualización fue exitosa
        echo "Datos bancarios actualizados con éxito.";
        $shouldUpdateOriginalInfo = false; // No actualizar la información original ya que hemos actualizado con auspiciador2

    } else {
        // Mensaje de error en caso de que no se pueda realizar la actualización
        die("Error al actualizar datos bancarios: " . $stmtUpdateComunidad->error);
    }
}
}    if ($shouldUpdateOriginalInfo) {

    // Obtener la cuenta bancaria del auspiciador
    $query = "SELECT cuentaBancaria, TarjetaBancaria, CLABE, cuentaBancaria2 FROM usuario WHERE referido = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $auspiciadorDirecto);
    $stmt->execute();
    $result = $stmt->get_result();
    $auspiciador = $result->fetch_assoc();
  
    if (!$auspiciador) {
        return false;  // No se encontró el auspiciador
    }

    $cuentaBancariaAuspiciador = $auspiciador['cuentaBancaria'];
    $tarjetaBancaria = $auspiciador['TarjetaBancaria'];
    $CLABE = $auspiciador['CLABE'];
    $banco = $auspiciador['cuentaBancaria2'];

    // Guardar en la subpropiedad correspondiente de 'usuarioObj' en la sesión
   // $_SESSION['usuarioObj']->$nombreTabla = $cuentaBancariaAuspiciador;
   // $_SESSION['usuarioObj']->tarjetaBancaria = $tarjetaBancaria;
   // $_SESSION['usuarioObj']->CLABE = $CLABE;
   

   // Actualizar las columnas cuentaDepositar, TarjetaBancaria y CLABE del usuario en la tabla correspondiente
$query = "UPDATE {$nombreTabla} SET cuentaDepositar = ?, TarjetaBancaria = ?, CLABE = ?, Banco = ? WHERE usuario_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('sssi', $cuentaBancariaAuspiciador, $tarjetaBancaria, $CLABE, $banco, $id);
$stmt->execute();
    
    
 // Después de compartir exitosamente la información, incrementa el contador
    $queryUpdateCounter = "UPDATE $nombreTabla SET compartirCuenta = compartirCuenta + 1 WHERE codigoReferido = ?";
    $stmtUpdateCounter = $db->prepare($queryUpdateCounter);
    $stmtUpdateCounter->bind_param('i', $auspiciadorDirecto);
    $stmtUpdateCounter->execute();

    return $stmt->affected_rows > 0;
    
}
}

function InsertarEnRed($db, $nombreTabla, $correo, $id, $auspiciadorDirecto) {
     // Reemplazar el nombre de la tabla antes de la consulta para obtener el tipoSubusuario
    $nombreTablaValidacion = str_replace("comunidad", "validacion", $nombreTabla);
    
    $queryTipoRed = "SELECT tipoSubusuario FROM {$nombreTabla} WHERE usuario_id = ?";
$stmtTipoRed = $db->prepare($queryTipoRed);
$stmtTipoRed->bind_param('i', $id);
$stmtTipoRed->execute();
$resultTipoRed = $stmtTipoRed->get_result();
$dataTipoRed = $resultTipoRed->fetch_assoc();

    // Verificar que la tabla sea una de las tres tablas permitidas
    if ($nombreTablaValidacion !== 'validacion100' && $nombreTablaValidacion !== 'validacion500' && $nombreTablaValidacion !== 'validacion1000') {
        throw new Exception('Nombre de tabla inválido');
    }

    // Obtener la fecha actual
    $fechaActual = date("Y-m-d H:i:s"); // Formato YYYY-MM-DD HH:MM:SS  
    
   // echo 'fecha de registro:'. $fechaActual;

// Verificar si se encontró el tipoSubusuario en la consulta
if ($resultTipoRed && isset($dataTipoRed['tipoSubusuario'])) {
    $tipoSubusuarioValue = $dataTipoRed['tipoSubusuario'];

    // Si el tipoSubusuario seleccionado es B o D, establecemos $auspiciadorDirecto como null para la columna donado_id
    if ($tipoSubusuarioValue === 'B' || $tipoSubusuarioValue === 'D') 
    {
       // if (isset($_SESSION['usuarioObj']->auspiciador2)) {
       //     $auspiciadorDirecto = $_SESSION['usuarioObj']->auspiciador2;
      //  }
      
      $auspiciadorDirecto = null;
    }
} else {
    // Manejar el caso en el que no se encontró el tipoSubusuario
 $aspiciadorDirecto = null;
}
   // Crear la consulta SQL para insertar el registro en la tabla de validación correcta
    $sql = "INSERT INTO $nombreTablaValidacion (usuario_id, correo, donado_id, fecha) VALUES (?, ?, ?, NOW())";

    // Preparar la consulta SQL
    $stmt = $db->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param('iss', $id, $correo, $auspiciadorDirecto); 


    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Dependiendo de la tabla, modificamos la propiedad adecuada en la sesión
        switch ($nombreTablaValidacion) {
            case 'validacion100':
                $_SESSION['usuarioObj']->fecha100 = $fechaActual;
                break;

            case 'validacion500':
                $_SESSION['usuarioObj']->fecha500 = $fechaActual;
                break;

            case 'validacion1000':
                $_SESSION['usuarioObj']->fecha1000 = $fechaActual;
                break;
        }
        return true;
    } else {
        throw new Exception('Error al insertar en la tabla');
    }
  }




function buscarNuevoAuspiciador($db, $nombreTabla, $auspiciadorDirecto, $id) {
    // Modificar la consulta para incluir las columnas tipoSubusuario y nivel
    $queryBuscarAuspiciador = "SELECT auspiciador_id, tipoSubusuario, nivel FROM $nombreTabla WHERE codigoReferido = $auspiciadorDirecto ORDER BY RAND() LIMIT 1";
    $resultadoBuscarAuspiciador = mysqli_query($db, $queryBuscarAuspiciador);

    if (!$resultadoBuscarAuspiciador) {
        die("Error en la consulta: " . mysqli_error($db));
    }

    $rowAuspiciador = mysqli_fetch_assoc($resultadoBuscarAuspiciador);
     // Verificar si tipoSubusuario es B y nivel es 1
        if ($rowAuspiciador['tipoSubusuario'] == 'B' || $rowAuspiciador['tipoSubusuario'] == 'D' && $rowAuspiciador['nivel'] == 1) {

    if ($rowAuspiciador) {
        $auspiciadorNuevo = $rowAuspiciador['auspiciador_id'];

       $queryUpdateAuspiciador2 = "UPDATE {$nombreTabla} SET auspiciador2_id = ? WHERE usuario_id = ?";
        $stmtUpdateAuspiciador2 = $db->prepare($queryUpdateAuspiciador2);
        $stmtUpdateAuspiciador2->bind_param('ii', $auspiciador2, $id);
        $resultadoUpdateAuspiciador2 = $stmtUpdateAuspiciador2->execute();


        if (!$resultadoActualizarAuspiciador) {
            die("Error en la consulta: " . mysqli_error($db));
        }

            // Obtener información de cuenta del nuevo auspiciador
            $queryInfoAuspiciador = "SELECT cuentaBancaria, TarjetaBancaria, CLABE FROM usuario WHERE id = $auspiciadorNuevo";
            $resultadoInfoAuspiciador = mysqli_query($db, $queryInfoAuspiciador);
            $dataAuspiciador = mysqli_fetch_assoc($resultadoInfoAuspiciador);

            // Actualizar las columnas cuentaBancaria2, TarjetaBancaria2 y CLABE2
            $queryUpdateInfo = "UPDATE $nombreTabla SET cuentaBancaria2 = '{$dataAuspiciador['cuentaBancaria']}', TarjetaBancaria2 = '{$dataAuspiciador['TarjetaBancaria']}', CLABE2 = '{$dataAuspiciador['CLABE']}' WHERE usuario_id = $id";
            $resultadoUpdateInfo = mysqli_query($db, $queryUpdateInfo);
            if (!$resultadoUpdateInfo) {
                die("Error al actualizar la información de cuenta: " . mysqli_error($db));
            }
        }

        echo "Se ha asignado un nuevo auspiciador para tu registro.";
    } else {
        echo "No se encontró un nuevo auspiciador disponible.";
    }
}



}


class UsuarioArbol extends Usuario {
    private $db; // Una instancia de conexión a la base de datos, por ejemplo.

    public function agregarUsuarioNivel($nivel, $tipo) {
        // Aquí puedes definir lógica para agregar un usuario a la estructura de árbol
        // en la base de datos según su nivel y tipo
        switch($nivel) {
            case '100':
                // Lógica para agregar usuario de nivel 100
                break;
            case '500':
                // Lógica para agregar usuario de nivel 500
                break;
            // ... y así sucesivamente para cada nivel
        }
    }
}

class UsuarioArbol2 extends UsuarioArbol {
    // Esta clase puede tener operaciones específicas adicionales o diferentes a UsuarioArbol
    
    public function algunaOtraOperacion() {
        // Lógica específica de UsuarioArbol2
    }
}


function optimizarImagen($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

function renderBetaErrorMessage() {
    echo '
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #f5c6cb; background-color: #05e09c; border-radius: 5px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">
        <h2 style="color: white; text-align: center; font-size: 24px; margin-bottom: 20px;"><strong>¡Importante!</strong></h2>
        <p style="color: white; font-size: 16px; line-height: 1.5; text-align: justify;"><strong>ABANTU</strong> aún se encuentra en fase beta, por lo que la información para hacer tu donación puede tardar en actualizarse. Revisa esta información constantemente, si no se actualiza dentro de 2 días, contáctanos. Tu donación no se considerará tardía el primer mes.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="miRed.php#infoUser" style="padding: 10px 20px; background-color: #d4edda; color: #155724; border-radius: 3px; text-decoration: none; font-weight: bold; transition: background-color 0.3s;">Volver a la Red</a>
        </div>
    </div>
    ';
}
function renderRedErrorMessage() {
    echo '
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #f5c6cb; background-color: #05e09c; border-radius: 5px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">
        <h2 style="color: white; text-align: center; font-size: 24px; margin-bottom: 20px;"><strong>¡Importante!</strong></h2>
        <p style="color: white; font-size: 16px; line-height: 1.5; text-align: justify;"><strong>Lo sentimos</strong> no se agrego correctamente la información a la Red, intentalo mas tarde.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="miRed.php#infoUser" style="padding: 10px 20px; background-color: #d4edda; color: #155724; border-radius: 3px; text-decoration: none; font-weight: bold; transition: background-color 0.3s;">Volver a la Red</a>
        </div>
    </div>
    ';
}
function renderErrorMessage() {
    return '
    <div class="error-message">
        <h2 style="color:black;"><strong>¡Ups!</strong></h2>
        <p>No seleccionaste una Red de Donación válida. Por favor, intenta de nuevo.</p>
        <a href="dashboard.php#redForm">Volver a tu Red</a>
    </div>
    
    ';
}

function renderErrorMessageEligeAportacion() {
    echo '
        <div class="error-message">
            <h2 style="color:black;"><strong>¡Ups!</strong></h2>
            <p>No puedes registrarte a una Red de Donacion con tu propio código, debes usar el código de alguien más.</p>
            <a href="miRed.php#infoUser">Volver a la Red</a>
        </div>
    ';
}


?>