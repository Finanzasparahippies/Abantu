document.addEventListener ('DOMContentLoaded', function() {

    eventListeners ();

    darkMode ();
    
    navegacionResponsive ();
});

function darkMode() {

       const prefiereDarkMode = window.matchMedia('(prefers-color-scheme:dark)');

       // console.log(prefiereDarkMode.matches);
       if(prefiereDarkMode.matches) {
                document.body.classList.add('dark-mode');
        } else {
               document.body.classList.remove('dark-mode');
       }
}


        
prefiereDarkMode.addEventListener('change', function() {
        if(prefiereDarkMode.matches) {
                document.body.classList.add('dark-mode');
        } else {
               document.body.classList.remove('dark-mode');
        }

});

        const botonDarkMode = document.querySelector('.dark-mode-boton');
        const formularios = document.querySelectorAll('form');  // Seleccionamos todos los formularios

        if(botonDarkMode) {

        botonDarkMode.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                // Ahora recorremos todos los formularios para añadir o quitar la clase 'dark-mode'
        formularios.forEach(form => {
            form.classList.toggle('dark-mode');
        });
        });
}



function eventListeners() {
        const mobileMenu = document.querySelector('.mobile-menu');
        mobileMenu.addEventListener ('click', navegacionResponsive);
}

function navegacionResponsive() {
        const navegacion = document.querySelector ('.navegacion');

        navegacion.classList.toggle('mostrar')

        // if(navegacion.classList.contains('mostrar')) {  ---esto es lo mismo que toogle
        //     navegacion.classList.remove('mostrar');
        // } else {
        //     navegacion.classList.add('mostrar');
        // }
       
}




