<?php
session_start();

// require 'config/database.php';
require 'public/funciones.php';
require 'config/database.php';
  incluirTemplate('header');


 $db = new dataBase();
 $pdo = $db->getPdo();
 if ($db) {
     var_dump($db);
 }

$comando = $pdo->query("SELECT id, nombres, apellidos, correo, referido, telefono, fechaRegistro FROM usuario ORDER BY id ASC");

// $comando->execute();
 $resultado = $comando ->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head> -->

<body class="py-3">
    <main class="container">
        <div class="row">
            <div class="col">
                <h4>Usuarios</h4>
                <a href="nuevo.php" class="btn btn-primary float-right">Nuevo</a>
            </div>
        </div>
        <div class="row py-3">
            <div class="col">
                <table class="table table-border">
                <thead>
                    <tr>
                        <!-- <th>#</th> -->
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>correo</th>
                        <th>Codigo Personal</th>
                        <th>Telefono</th>
                    </tr>
                </thead>

                    <tbody>
                        <?php
                        foreach($resultado AS $row) {
                        
                        ?>
                        <tr>
                            <!-- <td><?php echo $row['#']; ?></td> -->
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombres']; ?></td>
                            <td><?php echo $row['apellidos']; ?></td>
                            <td><?php echo $row['correo']; ?></td>
                            <td><?php echo $row['referido']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </main>



<?php

incluirTemplate('footer');

?>

<!-- 
</html> -->

