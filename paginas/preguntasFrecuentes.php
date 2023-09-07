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

<style>
    .faq-container {
        margin: 20px auto;
        max-width: 800px;
        font-family: Arial, sans-serif;
    }

    .faq-item {
        border-bottom: 1px solid #ccc;
        padding: 10px 0;
    }

    .faq-question {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .faq-answer {
        margin-left: 20px;
        line-height: 1.5;
        color: #05e09c;
    }
</style>

<div class="faq-container">
        <h1><strong>Preguntas Frecuentes:</strong></h1>
    <div class="faq-item">
        <div class="faq-question"><p> ¿Qué es ABANTU?</p></div>
        <div class="faq-answer"><p style = "color: #black;">La comunidad ABANTU es una organización social sin fines de lucro que utiliza la tecnología para contribuir a la igualdad, promoviendo el trabajo en equipo, cooperación y lealtad. Por lo que ha desarrollado una plataforma en línea al servicio de sus usuarios que crea un flujo de capital para el beneficio de la comunidad.</p></div>
    </div>
    <div class="faq-item">
        <div class="faq-question"><p> ¿Cómo envío mi Donación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Las donaciones se hacen a través de una transferencia bancaria, depósito en banco o tiendas de autoservicio. Cada usuario puede consultar la cuenta de banco, número de tarjeta y CLABE interbancaria a la que enviará la donación dentro de la plataforma.</p></div>
    </div>
    <div class="faq-item">
        <div class="faq-question"><p> ¿De cuánto es la donación que se envía?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Cada usuario de la plataforma queda de acuerdo en enviar una donación de $100, $500 ó $1,000 pesos al mes de acuerdo de la Red de Donación a la que pertenezca, a través de una transferencia de banco. Es necesario contar con una cuenta bancaria para formar parte de la plataforma.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p>  ¿De cuánto es la donación que se recibe?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Cada usuario en la plataforma recibe donaciones de $100 pesos por cada usuario en su Red de Donación, limitados hasta $3,000, $15,000 ó $30,000 pesos mensuales de acuerdo a la Red de Donación a la que pertenece. Cada usuario recibe donaciones desde el primer usuario que se integra en su Red de Donación. La cantidad total de donaciones que se reciben aumenta progresivamente conforme su Red de Donación crece hasta llegar a 30 usuarios.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Qué pasa si no envío mi donación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">El usuario que no envíe su donación y mande evidencia será dado de baja automáticamente de la plataforma. Sin embargo el usuario tiene hasta 5 días para hacer su donación y enviar evidencia, la cual será considerada como donación tardía. Si el usuario acumula 3 donaciones tardías en un periodo de 12 meses será dado de baja automáticamente de la plataforma.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Cuándo tengo que enviar mi donación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">La fecha de donación mensual es el mismo día del mes en que el usuario se da de alta en una Red de Donación. Las fechas de donación de cada Red de Donación ($100, $500, ó $1,000) pueden ser diferentes.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Cuál es el procedimiento para validar las donaciones?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Cada usuario es responsable de enviar evidencia de su donación antes de la fecha límite de donación en la plataforma. Las evidencias serán visibles en la tabla de validaciones de cada usuario
Las evidencias enviadas deberán ser validadas dentro de los primeros 5 días de haberse recibido, de lo contrario las evidencias se marcan como válidas automáticamente por la plataforma. 
Si la evidencia no cumple los requisitos, se le mandará una notificación al donante. Después de tres notificaciones el usuario que no está mandando sus evidencias correctamente se le dará de baja de la plataforma. 
Si el beneficiario marca como invalida una evidencia que cumple con los requisitos, se le mandará una notificación al beneficiario. Después de tres notificaciones el beneficiario se le dará de baja de la plataforma. 
La evidencia de donación pueden ser foto del depósito bancario, foto del ticket de tienda de autoservicio, comprobante emitido por la app del banco, pantallazo de la transferencia en el celular, etc. 
Sin embargo, una forma más segura de comprobar una transferencia bancaria es a través de la clave de rastreo que emite <a href="banxico.org.mx" tarjet="_blank" style="color:#05e09c;">banxico.org.mx</a>. La comunidad Abantu promueve usar este método en la medida de lo posible.
</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Cómo agrego miembros a mi Red de Donación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Cada usuario tiene un código de invitación, el cual puede compartir con las personas que quieran unirse a su Red de Donación. El código es el mismo para las diferentes Redes de Donación ($100, $500 ó $1,000).
Con este código el usuario puede invitar a más personas, a quienes la plataforma las acomodará en las posiciones de su Red de Donación restantes. De esta forma el usuario puede completar su Red de Donación más rápido y al mismo tiempo ayudar a los miembros de su Red con los referidos que deben donar. </p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Qué pasa si no agrego nuevos miembros a la Red de Donación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Nada. La plataforma sólo da de baja a los usuarios que no mandan evidencia o no cumplen con los requisitos de evidencia de su donación mensual. Hay que tener en mente que el objetivo de la plataforma se logra sólo agregando nuevos miembros a la comunidad.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Me puedo dar de alta si no tengo un código de invitación?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Sí. Después de registrarse a la plataforma los usuarios pueden darse de alta a cualquier Red de Donación sin un código de invitación, la plataforma los asignará al lugar disponible de mayor antigüedad. Así se apoyan a los usuarios que tienen tiempo en la plataforma sin poder agregar miembros nuevos.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Me puedo dar de baja de la plataforma?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Sí. Todos los usuarios tienen la opción de darse de baja de la plataforma en cualquier momento.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Qué pasa si un usuario se da de baja?</p></div>
        <div class="faq-answer"><p style = "color: #black;">En caso de que un usuario quede dado de baja de la plataforma, las donaciones a esa persona se suspenden y la posición queda disponible para que sea ocupada por un nuevo usuario aleatoriamente que se registre sin código de invitación. El nuevo usuario se queda con la Red de Donación que se había formado.</p></div>
    </div>
 <div class="faq-item">
        <div class="faq-question"><p> ¿Qué organismo nos regula?</p></div>
        <div class="faq-answer"><p style = "color: #black;">Las donaciones son reguladas por el código civil estatal. ABANTU es una comunidad sin fines de lucro, no presta un servicio financiero, tampoco vende un servicio o producto.</p></div>
    </div>
    </div>

<?php

incluirTemplate('footer');

?>