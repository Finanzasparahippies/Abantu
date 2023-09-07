<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require 'usuario.php';
require '../includes/config/database.php';
include '../includes/funciones.php';

session_start();

incluirTemplate('header');
?>

<div class="containerP">
    <h1 class="privacy-policy-title"><strong>Política de Privacidad</strong></h1>
    
    <div class="privacy-policy-section">
        <h2>Introducción</h2>
        <p>En ABANTU, valoramos y respetamos la importancia de la privacidad en línea y estamos comprometidos a salvaguardar la privacidad de nuestros usuarios. Esta política describe cómo tratamos la información personal que recolectamos y recibimos de los usuarios.</p>
    </div>

    <div class="privacy-policy-section">
        <h2>Recopilación de información</h2>
        <p>Recogemos información personal que nos proporcionas, como tu nombre, dirección de correo electrónico y cualquier otra información que nos envíes. También recogemos información automáticamente sobre tu interacción con nuestros servicios, como tu dirección IP y el tipo de dispositivo que utilizas.</p>
    </div>

    <div class="privacy-policy-section">
        <h2>Uso de la información</h2>
        <p>Usamos la información que recopilamos para:</p>
        <ul>
            <li><p>Proveerte los servicios que ofertamos.</p></li>
            <li><p>Personalizar y mejorar nuestros servicios.</p></li>
            <li><p>Responder a tus preguntas y solucionar problemas.</p></li>
            <li><p>Enviar comunicaciones de marketing, si nos has dado tu consentimiento.</p></li>
        </ul>
        <p>No vendemos ni compartimos tu información personal con terceros para fines de marketing sin tu consentimiento.</p>
    </div>

    <!-- Más secciones aquí -->

    <div class="privacy-policy-section">
        <h2>Contacto</h2>
        <p>Si tienes alguna pregunta sobre nuestra política de privacidad, puedes ponerte en contacto con nosotros en felicesdeayudar@abantu.mx.</p>
    </div>
</div>

<?php
incluirTemplate('footer');
?>
