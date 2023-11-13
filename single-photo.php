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
                <div class="entry-content">
                    <div class="description">
                        <header class="entry-header">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        </header>

                        <div class="champs">
                            <?php
                            // Afficher les champs personnalisés        
                            $photo_reference = get_field('reference');
                            if ($photo_reference) {
                                echo '<p>Référence : ' . esc_html($photo_reference) . '</p>';
                            }
                            ?>
                        </div>

                        <div class="taxonomies">
                            <?php
                            // Récupérer et afficher les catégories personnalisées (taxonomies)
                            // CATEGORIES
                            // Remplacez 'nom_de_la_taxonomie' par le nom de votre taxonomie
                            $categorie = 'categorie';
                            // Remplacez 'nom_du_post_type' par le nom de votre type de publication personnalisé (CPT)
                            $post_type = 'photo';
                            // Remplacez $post_id par l'ID de votre article personnalisé
                            $post_id = get_the_ID();
                            // Récupérer les termes de taxonomie associés à l'article
                            $categories = get_terms(array(
                                'taxonomy' => $categorie,
                                'object_ids' => $post_id,
                                'hide_empty' => false,
                            ));
                            if (is_array($categories) && !empty($categories)) {
                                foreach ($categories as $category) {
                                    echo '<p>Catégories : ' . $category->name . '</p>';
                                }
                            } else {
                                echo 'Aucune catégorie trouvée.';
                            }
                            ?>

                            <?php
                            // FORMATS
                            $format = 'format';
                            $post_type = 'photo';
                            $post_id = get_the_ID();
                            $formats = get_terms(array(
                                'taxonomy' => $format,
                                'object_ids' => $post_id,
                                'hide_empty' => false,
                            ));
                            if (is_array($formats) && !empty($formats)) {
                                foreach ($formats as $format) {
                                    echo '<p>Formats : ' . $format->name . '</p>';
                                }
                            } else {
                                echo 'Aucun format trouvé.';
                            }
                            ?>

                        <div class="champs">
                            <?php
                            // Afficher les champs personnalisés        
                            $photo_type = get_field('type');
                            if ($photo_type) {
                                echo '<p>Type : ' . esc_html($photo_type) . '</p>';
                            }
                            ?>
                        </div>

                            <?php
                            // TRIER PAR
                            $trier_par = 'trier_par';
                            $post_type = 'photo';
                            $post_id = get_the_ID();
                            $trier_par = get_terms(array(
                                'taxonomy' => $trier_par,
                                'object_ids' => $post_id,
                                'hide_empty' => false,
                            ));
                            if (is_array($trier_par) && !empty($trier_par)) {
                                foreach ($trier_par as $term) {
                                    echo '<p>Année : ' . $term->name . '</p>';
                                }
                            } else {
                                echo 'Aucune date trouvé.';
                            }
                            ?>

                        </div>
                    </div>
                    <div class="image">
                        <?php
                        // Afficher le contenu de la publication
                        the_content();
                        ?>
                    </div>

                </div>
                <div class="bloc3">
                    <p> Cette photo vous intéresse ? </p>   
                    <div>
                        <button class="ctct-bouton">Contact</button>
                    </div>
                </div>

                <div class="apparenté">
                    <p> Vous aimerez AUSSI</p>   
                    <div class="deuxphotos">
                    <?php
                    // Get the current photo's ID
                    $current_photo_id = get_the_ID();
                    // Get the current photo's categories
                    $current_photo_categories = wp_get_post_terms(get_the_ID(), 'categorie', array('fields' => 'ids'));
                    // Boucle WordPress pour récupérer les articles du type de contenu "photo" dans la même catégorie
                    $args = array(
                        'post_type' => 'photo',
                        'posts_per_page' => 2,  
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'categorie',
                                'field' => 'id',
                                'terms' => $current_photo_categories,
                            ),
                        ),
                        'post__not_in' => array($current_photo_id), // Exclude the current photo
                    );

                    $photo_query = new WP_Query($args);

                    if ($photo_query->have_posts()) :
                        while ($photo_query->have_posts()) :
                            $photo_query->the_post();
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

                    ?>
                    </div>
                    <div class="bouton">
                        <button class="ttes-photos">Toutes les photos</button>
                    </div>
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
