<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preregistro Abantu</title>
    <link rel="stylesheet" href="/Abantu/build/css/app.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&;700display=swap"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body >
    <header class="header">
    <div class="contenido-header"> 
        <div class="logotype"> 
        <a class="logotipo" href="/Abantu/index.php">
        <img class="header__Logo" src="/public_html/build/img/logoabantutransparente.png" alt="logoabantu">
            <h1 class="titulo">Felices de Ayudar</h1>
        </a>
        </div>
        
           
        <div class="derecha">
          <img class="dark-mode-boton" src="/public_html/build/img/dark-mode.svg" alt="dark mode">
        </div>
        
          <div class="barra">
            <div class="mobile-menu">
              <svg xmlns="http://www.w3.org/2000/svg" class="iconsinternet" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#05e09c" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 6l16 0" />
                  <path d="M4 12l16 0" />
                  <path d="M4 18l16 0" />
                </svg>
          </div>
            
        <nav class="navegacion">
            <a href="/Abantu/nosotros.php">Quienes Somos</a>
            <a href="/Abantu/dashboard.php">Dashboard</a> 
            <a href="/Abantu/registro.php">Registrate</a>
            <a href="/Abantu/login.php">Iniciar Sesión</a>
            <a href="/Abantu/logout.php">Cerrar Sesión</a>
          </nav>

        </div> <!--.barra-->

    </div>
        </header>