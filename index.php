<?php
get_header(); 
?>

<div id="content" class="site-content">
    <main id="main" class="site-main">
        <?php
        // Boucle WordPress pour récupérer les articles du type de contenu "photo"
        $args = array(
            'post_type' => 'photo', // Nom du custom post type
            'posts_per_page' => -1,  // Afficher tous les articles, vous pouvez ajuster cela selon vos besoins
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
    </main>
</div>

<?php
get_footer(); 
?>
