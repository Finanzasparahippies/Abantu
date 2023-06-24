<?php

require 'app.php';

function incluirTemplate( $nombre ) {

    // echo TEMPLATES_URL . "/${nombre}.php";
    include TEMPLATES_URL . "/$nombre.php";

}