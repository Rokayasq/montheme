<?php
/**
 * Template Name: Single Photo
 * Description: Custom template for displaying a single 'photo' post.
 */

// Inclure l'en-tête du site WordPress
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        // Début de la boucle WordPress
        while (have_posts()) :
            the_post();

            // Contenu de la publication
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php
                    // Afficher le contenu de la publication
                    the_content();

                    // Afficher les champs personnalisés
                    $photo_type = get_field('type');
                    $photo_reference = get_field('reference');
        
                    if ($photo_type) {
                        echo '<p>Type: ' . esc_html($photo_type) . '</p>';
                    }
        
                    if ($photo_reference) {
                        echo '<p>Référence: ' . esc_html($photo_reference) . '</p>';
                    }

                
                    // Récupérer et afficher les catégories personnalisées (taxonomies)
                    // CATEGORIES
                    echo '<h2>Catégories</h2>';
                    // Remplacez 'nom_de_la_taxonomie' par le nom de votre taxonomie
                    $categorie = 'categorie';
                    // Remplacez 'nom_du_post_type' par le nom de votre type de publication personnalisé (CPT)
                    $post_type = 'photo';
                    // Remplacez $post_id par l'ID de votre article personnalisé
                    $post_id = get_the_ID();
                    // Récupérer les termes de taxonomie associés à l'article
                    $categories = get_terms(array(
                        'taxonomy' => $categorie,
                    ));
                    if (is_array($categories) && !empty($categories)) {
                        echo '<ul>';
                        foreach ($categories as $category) {
                            echo '<li>' . $category->name . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo 'Aucune catégorie trouvée.';
                    }

                    // FORMATS
                    echo '<h2>Formats</h2>';
                    $format = 'format';
                    $post_type = 'photo';
                    $post_id = get_the_ID();
                    $formats = get_terms(array(
                        'taxonomy' => $format,
                        'object_ids' => $post_id,
                        'hide_empty' => false,
                    ));
                    if (is_array($formats) && !empty($formats)) {
                        echo '<ul>';
                        foreach ($formats as $format) {
                            echo '<li>' . $format->name . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo 'Aucun format trouvé.';
                    }
                    
                    // TRIER PAR
                    echo '<h2>Trier par</h2>';
                    $trier_par = 'trier_par';
                    $post_type = 'photo';
                    $post_id = get_the_ID();
                    $trier_par = get_terms(array(
                        'taxonomy' => $trier_par,
                        'object_ids' => $post_id,
                        'hide_empty' => false,
                    ));
                    if (is_array($trier_par) && !empty($trier_par)) {
                        echo '<ul>';
                        foreach ($trier_par as $term) {
                            echo '<li>' . $term->name . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo 'Aucune date trouvé.';
                    }
                    ?>
                </div>


            </article>

        <?php endwhile; // Fin de la boucle WordPress. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
// Inclure la barre latérale et le pied de page du site WordPress
// get_sidebar();
get_footer();
?>
