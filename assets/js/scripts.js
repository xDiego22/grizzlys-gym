/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    $("body").addClass("sb-nav-fixed");
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        const mobileScreenWidth = 768; // Definir el ancho máximo para considerar un dispositivo móvil

        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            
            if (window.innerWidth > mobileScreenWidth) {
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            }
        });

        
    }

    const year = new Date().getFullYear();

    if (document.querySelector('#year')) {
        
        document.querySelector('#year').innerText = year;
    }

});
