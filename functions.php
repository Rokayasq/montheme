<?php
//header 
function register_my_menu() {
    register_nav_menu( 'main-menu' , __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );

//ajouter le logo
function custom_theme_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 22,
        'width'       => 344.51,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'custom_theme_setup');

//footer 
function register_footer() {
    register_nav_menu( 'footer-menu' , __( 'footer', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_footer' );

//css
function enqueue_custom_styles() {
    wp_enqueue_style('custom-styles', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

//js
function ajouter_script_js() {
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array(),false , true);
}
add_action('wp_enqueue_scripts', 'ajouter_script_js');

//page contact modale
function ajouter_code_sur_page_contact() {
    if (is_page('contact')) {
        get_header(); 
        get_template_part( 'templates_parts/templates_part' );
    }
}
add_action('wp', 'ajouter_code_sur_page_contact');

//script.js
function enqueue_custom_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue your custom script
    wp_enqueue_script(
        'custom-ajax-script',
        get_template_directory_uri() . '/js/script.js',
        array('jquery'), // Dependencies (jQuery in this case)
        '1.0',           // Script version
        true             // Load the script in the footer
    );

    // Localize the script with the appropriate data
    wp_localize_script(
        'custom-ajax-script',
        'customAjaxVars',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('your_custom_nonce'),
        )
    );
}
// Hook the function to the wp_enqueue_scripts action
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

//charger plus
add_action('wp_ajax_capitaine_load_photos', 'capitaine_load_photos');
add_action('wp_ajax_nopriv_capitaine_load_photos', 'capitaine_load_photos');

function capitaine_load_photos() {
    // Vérification de sécurité
    if (
        !isset($_REQUEST['nonce']) or
        !wp_verify_nonce($_REQUEST['nonce'], 'capitaine_load_photos')
    ) {
        wp_send_json_error("Vous n’avez pas l’autorisation d’effectuer cette action.", 403);
    }

    // On vérifie que le type de post a bien été envoyé
    if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
        wp_send_json_error("Le type de post est manquant.", 400);
    }

    // Récupération des données du formulaire
    $post_type = sanitize_text_field($_POST['posttype']);
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    // Requête des photos (custom post type: 'photo')
    $args = array(
        'post_type'      => $post_type, // Use the custom post type 'photo'
        'posts_per_page' => 8,
        'offset'         => $offset,
    );

    $photos_query = new WP_Query($args);

    // Préparer le HTML des photos
    ob_start();

    if ($photos_query->have_posts()) :
        while ($photos_query->have_posts()) :
            $photos_query->the_post();
    ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
    
                <div class="entry-content">
                    <?php
                    // Ajoutez le lien vers l'article complet
                    $post_url = get_permalink();
                    ?>
                    <a href="<?php echo esc_url($post_url); ?>">
                        <?php the_content(); ?>
                    </a>
                </div>
            </article>
    <?php
        endwhile;
        wp_reset_postdata();
    else :
        // Si aucun article n'est trouvé, affiche un message par défaut
        echo 'Aucune photo trouvée.';
    endif;

    $html = ob_get_clean();

    // Envoyer les données au navigateur
    wp_send_json_success($html);
}
