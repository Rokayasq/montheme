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
                    $categories = get_the_terms(get_the_ID(), 'categorie');
                    if ($categories && !is_wp_error($categories)) {
                        echo '<p>Catégories: ';
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>, ';
                        }
                        echo '</p>';
                    }

                    // Récupérer et afficher les formats personnalisés
                    $formats = get_the_terms(get_the_ID(), 'format');
                    if ($formats && !is_wp_error($formats)) {
                        echo '<p>Formats: ';
                        foreach ($formats as $format) {
                            echo esc_html($format->name) . ', ';
                        }
                        echo '</p>';
                    }

                    // Récupérer et afficher les valeurs de la taxonomie 'trier_par'
                    $trier_par = get_the_terms(get_the_ID(), 'trier_par');
                    if ($trier_par && !is_wp_error($trier_par)) {
                        echo '<p>Trier par: ';
                        foreach ($trier_par as $term) {
                            echo esc_html($term->name) . ', ';
                        }
                        echo '</p>';
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
