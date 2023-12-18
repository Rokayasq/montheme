// MODALE DE CONTACT

function openContact() {
    // Affichage de la modale de contact
    document.querySelector('#ContactModal .modal-content').style.display = 'block';

    // Affichage de l'overlay en background
    document.getElementById('ContactOverlay').style.display = 'block';

    // Au click sur l'overlay fermer la modal
    document.getElementById('ContactOverlay').addEventListener('click', closeContact);
}

function closeContact() {
    // Fonction pour fermer la modale de contact
    document.getElementById('ContactOverlay').style.display = 'none';
    document.querySelector('#ContactModal .modal-content').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    // Selectionne tout les boutons contact, version mobile et desktop
    contact_buttons = document.querySelectorAll('.menu-item-140')
    
    // Ajout de l'évenement d'ouverture de la modale au click
    contact_buttons.forEach(
        button => {button.addEventListener('click', openContact);}
    )
    
    // Pareil pour le bouton contact dans la page single-photo
    var btnCtct = document.getElementById('btn-ctct');
        if (btnCtct) {
        btnCtct.addEventListener('click', openContact);
    }

    // MENU BURGER
    // Au click sur le burger menu, enlever le scroll pour n'afficher que le menu. Utile pour la version Mobile
    document.getElementById("burger-image").addEventListener("click", function() {
        document.body.style.overflow = "hidden";
    });

    //Version Mobile : Gestion de l'animation d'ouverture et fermeture du menu
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
            burgerImage.src = 'http://localhost:8888/Nathalie-mota/wp-content/themes/montheme/assets/images/State=default.png';
        }
        isImageDefault = !isImageDefault;
    });
});

//CHARGER PLUS
(function ($) {
    $(document).ready(function () {

        // Chargement des photos en Ajax
        $('.js-load-photos').submit(function (e) {

            // Empêcher l'envoi classique du formulaire
            e.preventDefault();

            // Stocker la valeur de 'this' dans une variable
            const $this = $(this);

            // L'URL qui réceptionne les requêtes Ajax dans l'attribut "action" de <form>
            const ajaxurl = $this.attr('action');

            // Les données de notre formulaire
            const data = {
                action: $this.find('input[name=action]').val(),
                nonce: $this.find('input[name=nonce]').val(),
                posttype: $this.find('input[name=posttype]').val(),
                offset: $this.data('offset') || 0, 
            }

            // Requête Ajax en JS natif via Fetch
            fetch(customAjaxVars.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams(data),
            })
                .then(response => response.json())
                .then(body => {
                    // En cas d'erreur
                    if (!body.success) {
                        alert(body.data); // Utilisez body.data au lieu de response.data
                        return;
                    }

                    // Et en cas de réussite
                    $this.hide(); // Cacher le formulaire en utilisant la variable $this
                    $('.photos').append(body.data);
                    Lightbox.init();
                    add_eye_icon();
                    add_full_screen();
                    add_cat_ref();
                });
        });

    });
})(jQuery);


// CATEGORIE
jQuery(document).ready(function($) {
    $('#filter-category').change(function() {
        var category_id = $(this).val();
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl, // WordPress fournit cette variable pour l'URL Ajax
            data: {
                action: 'filter_images_cat',
                category_id: category_id,
            },
            success: function(response) {
                $('.images').html(response);
                Lightbox.init();
                add_eye_icon();
                add_full_screen();
                add_cat_ref();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

// FORMAT
jQuery(document).ready(function($) {
    $('#filter-format').change(function() {
        var format_id = $(this).val();

        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl, 
            data: {
                action: 'filter_images_format',
                format_id: format_id,
            },
            success: function(response) {
                $('.images').html(response);
                Lightbox.init();
                add_eye_icon();
                add_full_screen();
                add_cat_ref();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

// DATE
jQuery(document).ready(function ($) {
    $('#filter-trier_par').change(function () {
        var trier_par_id = $(this).val();

        if (trier_par_id === 'recentes' || trier_par_id === 'anciennes') {
            $.ajax({
                type: 'POST',
                url: myAjax.ajaxurl,
                data: {
                    action: 'filter_images_date',
                    filter: trier_par_id,
                },
                success: function (response) {
                    $('.images').html(response);
                    Lightbox.init();
                    add_eye_icon();
                    add_full_screen();
                    add_cat_ref();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });
});

// MINIATURE
jQuery(document).ready(function($) {
    $('.nav-prev, .nav-next').hover(
        function() {
            var imgSrc = $(this).find('img').attr('src');
            $('.petite-img > img').attr("src", imgSrc)
            $('.petite-img > img').css('opacity', '1');
        },
        function() {
            $('.petite-img > img').css('opacity', '0');
        }
    );
});

