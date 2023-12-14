<?php
//header 
function register_my_menu() {
    register_nav_menu( 'main-menu' , __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );

//ajouter le logo
function custom_theme_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 14,
        'width'       => 216,
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

// LIGHTBOX
function ajouter_lightbox_js() {    
    wp_enqueue_script('lightbox', get_stylesheet_directory_uri() . '/js/lightbox.js', array('jquery'), false, true);
}

add_action('wp_enqueue_scripts', 'ajouter_lightbox_js');

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

    // Passer la valeur de ajaxurl à votre script JavaScript
    wp_localize_script('custom-ajax-script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
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
                    <?php the_content(); ?>
                    <div>
                        <a href="<?php echo esc_url($post_url); ?>" onclick="openLightbox('<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>')">
                            <i class="fa-regular fa-eye fa-2xl"></i>
                        </a>
                        <div class="overlay" id="overlay"></div>
                        <i class="fa-solid fa-expand" id="openModalIcon"></i>
                        <div  class="cat-ref">
                            <div class="categorie">
                                <?php
                                $taxonomy = 'categorie';
                                $post_id = get_the_ID();
                                $categories = get_the_terms($post_id, $taxonomy);
                                if (is_array($categories) && !empty($categories)) {
                                    echo '<p>';
                                    foreach ($categories as $category) {
                                        echo $category->name;
                                    }
                                    echo '</p>';
                                }
                                ?>
                            </div>
                            <div class="reference">
                                <?php
                                $photo_reference = get_field('reference');
                                if ($photo_reference) {
                                    echo '<p>' . esc_html($photo_reference) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
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

//FILTRES


//CATEGORIES
function filter_images_cat() {
    $category_id = $_POST['category_id'];

    // Utilisez WP_Query pour récupérer les images de la catégorie sélectionnée
    $query = new WP_Query(array(
        'post_type' => 'photo', // Assurez-vous que le type de publication est correct
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie', // Assurez-vous que le nom de la taxonomie est correct
                'field' => 'id',
                'terms' => $category_id,
            ),
        ),
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
                <div class="entry-content">
                <?php
                    // Ajoutez le lien vers l'article complet
                    $post_url = get_permalink();
                    ?>
                    <?php 
                    echo '<img class="image-filtre" src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '">'; 
                    echo '<div class="image-overlay"></div>';
                    ?>
                    <div>
                        <a href="<?php echo esc_url($post_url); ?>" onclick="openLightbox('<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>')">
                            <i class="fa-regular fa-eye fa-2xl"></i>
                        </a>
                        <div class="overlay" id="overlay"></div>
                        <i class="fa-solid fa-expand" id="openModalIcon"></i>
                        <div  class="cat-ref">
                            <div class="categorie">
                                <?php
                                $taxonomy = 'categorie';
                                $post_id = get_the_ID();
                                $categories = get_the_terms($post_id, $taxonomy);
                                if (is_array($categories) && !empty($categories)) {
                                    echo '<p>';
                                    foreach ($categories as $category) {
                                        echo $category->name;
                                    }
                                    echo '</p>';
                                }
                                ?>
                            </div>
                            <div class="reference">
                                <?php
                                $photo_reference = get_field('reference');
                                if ($photo_reference) {
                                    echo '<p>' . esc_html($photo_reference) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </article>

            <?php
        }
        wp_reset_postdata();
    } else {
        echo 'Aucune image trouvée.';
    }

    die();
}
add_action('wp_ajax_filter_images_cat', 'filter_images_cat'); // Hook pour les utilisateurs connectés
add_action('wp_ajax_nopriv_filter_images_cat', 'filter_images_cat'); // Hook pour les utilisateurs non connectés



//FORMATS
function filter_images_format() {
    $format_id = $_POST['format_id'];

    // Utilisez WP_Query pour récupérer les images de la catégorie sélectionnée
    $query = new WP_Query(array(
        'post_type' => 'photo', // Assurez-vous que le type de publication est correct
        'tax_query' => array(
            array(
                'taxonomy' => 'format', // Assurez-vous que le nom de la taxonomie est correct
                'field' => 'id',
                'terms' => $format_id,
            ),
        ),
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
                <div class="entry-content">
                <?php
                    // Ajoutez le lien vers l'article complet
                    $post_url = get_permalink();
                    ?>
                    <?php 
                    echo '<img class="image-filtre" src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '">'; 
                    echo '<div class="image-overlay"></div>';
                    ?>                    <div>
                        <a href="<?php echo esc_url($post_url); ?>" onclick="openLightbox('<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>')">
                            <i class="fa-regular fa-eye fa-2xl"></i>
                        </a>
                        <div class="overlay" id="overlay"></div>
                        <i class="fa-solid fa-expand" id="openModalIcon"></i>
                        <div  class="cat-ref">
                            <div class="categorie">
                                <?php
                                $taxonomy = 'categorie';
                                $post_id = get_the_ID();
                                $categories = get_the_terms($post_id, $taxonomy);
                                if (is_array($categories) && !empty($categories)) {
                                    echo '<p>';
                                    foreach ($categories as $category) {
                                        echo $category->name;
                                    }
                                    echo '</p>';
                                }
                                ?>
                            </div>
                            <div class="reference">
                                <?php
                                $photo_reference = get_field('reference');
                                if ($photo_reference) {
                                    echo '<p>' . esc_html($photo_reference) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </article>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo 'Aucune image trouvée.';
    }

    die();
}
add_action('wp_ajax_filter_images_format', 'filter_images_format'); // Hook pour les utilisateurs connectés
add_action('wp_ajax_nopriv_filter_images_format', 'filter_images_format'); // Hook pour les utilisateurs non connectés


// Date
function filter_images_date() {
    $sort_direction = $_POST['filter']; // 'anciennes' or 'recentes'

    if ($sort_direction == 'anciennes') {
        $sort_direction = 'ASC'; // Order by oldest first
    } elseif ($sort_direction == 'recentes') {
        $sort_direction = 'DESC'; // Order by newest first
    }

    // Récupérer les termes de la taxonomie 'trier_par'
    $terms = get_terms(array(
        'taxonomy' => 'trier_par',
        'orderby' => 'name',
        'order' => $sort_direction,
    ));

    if (!empty($terms)) {
        foreach ($terms as $term) {
            // Récupérer les images pour chaque terme
            $args = array(
                'post_type' => 'photo',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'trier_par',
                        'field' => 'id',
                        'terms' => $term->term_id,
                    ),
                ),
                'orderby' => 'date',
                'order' => $sort_direction,
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
                <div class="entry-content">
                <?php
                    // Ajoutez le lien vers l'article complet
                    $post_url = get_permalink();
                    ?>
                    <?php 
                    echo '<img class="image-filtre" src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '">'; 
                    echo '<div class="image-overlay"></div>';
                    ?>                    <div>
                        <a href="<?php echo esc_url($post_url); ?>" onclick="openLightbox('<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>')">
                            <i class="fa-regular fa-eye fa-2xl"></i>
                        </a>
                        <div class="overlay" id="overlay"></div>
                        <i class="fa-solid fa-expand" id="openModalIcon"></i>
                        <div  class="cat-ref">
                            <div class="categorie">
                                <?php
                                $taxonomy = 'categorie';
                                $post_id = get_the_ID();
                                $categories = get_the_terms($post_id, $taxonomy);
                                if (is_array($categories) && !empty($categories)) {
                                    echo '<p>';
                                    foreach ($categories as $category) {
                                        echo $category->name;
                                    }
                                    echo '</p>';
                                }
                                ?>
                            </div>
                            <div class="reference">
                                <?php
                                $photo_reference = get_field('reference');
                                if ($photo_reference) {
                                    echo '<p>' . esc_html($photo_reference) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </article>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo 'Aucune image trouvée pour le terme ' . $term->name;
            }
        }
    } else {
        echo 'Aucun terme trouvé dans la taxonomie "trier_par".';
    }

    die();
}

add_action('wp_ajax_filter_images_date', 'filter_images_date');
add_action('wp_ajax_nopriv_filter_images_date', 'filter_images_date');


// font awesome
function enqueue_font_awesome() {
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css',
        array(),
        '6.2.1'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');
