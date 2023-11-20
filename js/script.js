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
                offset: $this.data('offset') || 0, // Assurez-vous que cette ligne est correcte
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
                    $('.photos').append(body.data); // Ajouter le HTML au lieu de remplacer
                });
        });

    });
})(jQuery);
