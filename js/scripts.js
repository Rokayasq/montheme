// MODALE DE CONTACT

function openContact() {
    document.getElementById('ContactOverlay').style.display = 'block';
    document.querySelector('#ContactModal .modal-content').style.display = 'block';
    document.getElementById('ContactOverlay').addEventListener('click', closeContact);
    
}

function closeContact() {
    document.getElementById('ContactOverlay').style.display = 'none';
    document.querySelector('#ContactModal .modal-content').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
        // Attach a click event listener to the icon
        contact_buttons = document.querySelectorAll('.menu-item-140')
        contact_buttons.forEach(
            button => {button.addEventListener('click', openContact);}
        )
        
        document.getElementById('btn-ctct').addEventListener('click', openContact);
});

document.addEventListener("DOMContentLoaded", function() {
    // Attache un gestionnaire d'événements de clic à l'élément avec l'ID "burger-image"
    document.getElementById("burger-image").addEventListener("click", function() {
        // À chaque clic sur l'élément avec l'ID "burger-image", exécute le code suivant
        document.body.style.overflow = "hidden";
    });
});


//Menu Burger    
var defaultImagePath = 'http://localhost:8888/Nathalie-mota/wp-content/themes/montheme/assets/images/State=default.png';

document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.querySelector(".menu-toggle");
    const menuHeader = document.getElementById('menu-header-1')
    const header = document.querySelector('header');
    const menuItems = document.getElementById('menu-items');
    const burgerImage = document.getElementById('burger-image');
    
    let isImageDefault = true; 

    menuToggle.addEventListener("click", function() {
        if (header.classList.contains("menu-opened")) {
            header.classList.add('menu-closing');
                setTimeout(() => {
                header.classList.remove('menu-closing');
            }, 300); 
            menuItems.classList.add('menu-closing');
                setTimeout(() => {
                    menuItems.classList.remove('menu-closing');
            }, 400); 
            menuHeader.classList.add('menu-closing');
                setTimeout(() => {
                    menuHeader.classList.remove('menu-closing');
            }, 400); 

        }
        else {
            header.classList.add('menu-opening');
                setTimeout(() => {
                header.classList.remove('menu-opening');
            }, 300); 
            menuItems.classList.add('menu-opening');
                setTimeout(() => {
                    menuItems.classList.remove('menu-opening');
            }, 400); 
            menuHeader.classList.add('menu-opening');
                setTimeout(() => {
                    menuHeader.classList.remove('menu-opening');
            }, 400);
        }

        menuItems.classList.toggle("open");
        header.classList.toggle("menu-opened");
        
        
        if (isImageDefault) {
            burgerImage.src = 'http://localhost:8888/Nathalie-mota/wp-content/themes/montheme/assets/images/Menu.png';
        } else {
            burgerImage.src = defaultImagePath;
        }
        isImageDefault = !isImageDefault;
    });
});

