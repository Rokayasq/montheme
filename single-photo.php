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
                        <div class="entry-header">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        </div>

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
                            $categorie = 'categorie';
                            $post_type = 'photo';
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
                <div class="bloc">
                    <div class="bloc3">
                        <p> Cette photo vous intéresse ? </p>   
                        <div>
                            <button id="btn-ctct" class="ctct-bouton">Contact</button>
                        </div>
                    </div>
                    <div class="bloc31">  
                        <div class="petite-img">
                            <img>
                            <?php
                            // Afficher le contenu de la publication
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            // Obtenir l'URL de l'image pour l'article précédent
                            $prev_image_url = $prev_post ? get_the_post_thumbnail_url($prev_post->ID) : '';
                            // Obtenir l'URL de l'image pour l'article suivant 
                            $next_image_url = $next_post ? get_the_post_thumbnail_url($next_post->ID) : '';
                            ?>
                        </div>
                        <div class="navigation-arrows">
                            <div class="nav-prev"><?php previous_post_link('%link', '<img src="' . esc_url($prev_image_url) . '" style="display:none">'); ?></div>
                            <div class="nav-next"><?php next_post_link('%link', '<img src="' . esc_url($next_image_url) . '" style="display:none">'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="apparenté">
                    <p> Vous aimerez AUSSI</p>   
                    <div class="deuxphotos">
                    <?php
                    $current_photo_id = get_the_ID();
                    $current_photo_categories = wp_get_post_terms(get_the_ID(), 'categorie', array('fields' => 'ids'));
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
                        'post__not_in' => array($current_photo_id), // Exclure la photo actuelle
                    );

                    $photo_query = new WP_Query($args);

                    if ($photo_query->have_posts()) :
                        while ($photo_query->have_posts()) :
                            $photo_query->the_post();
                    ?>
                            <article class="images" id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>

                                <div class="entry-content">
                                    <?php
                                    // Ajoutez le lien vers l'article complet
                                    $post_url = get_permalink();
                                    ?>
                                        <?php the_content(); ?>
                                        <div>
                                            <a href="<?php echo esc_url($post_url); ?>">
                                                <i class="fa-regular fa-eye fa-2xl"></i>
                                            </a>
                                            <div class="overlay" id="overlay"></div>
                                            <i class="fa-solid fa-expand" id="openModalIcon"></i>
                                            <div  class="cat-ref">
                                                <div class="categorie">
                                                <?php
                                                    $taxonomy = 'categorie';
                                                    $post_id = get_the_ID();

                                                    // Récupérer toutes les catégories de la taxonomie associées à cet article
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
                                                    // Afficher les champs personnalisés        
                                                    $photo_reference = get_field('reference');
                                                    if ($photo_reference) {
                                                        echo '<p>' . esc_html($photo_reference) . '</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
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
                    <a href="http://localhost:8888/Nathalie-mota/">
                        <button class="ttes-photos">Toutes les photos</button>
                    </a>
                    </div>
                </div>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php

get_footer();
?>
