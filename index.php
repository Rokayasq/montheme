<?php
get_header(); 
?>
<!-- hero header  -->
<div id="hero-content" class="site-content">
    <main id="main" class="site-main">
        <?php
        // Boucle WordPress pour récupérer les articles du type de contenu "photo"
        $args = array(
            'post_type' => 'photo', // Nom du custom post type
            'posts_per_page' => 1,  // Afficher tous les articles, vous pouvez ajuster cela selon vos besoins
            'orderby' => 'rand', // image aléatoire
        );

        $photo_query = new WP_Query($args);

        if ($photo_query->have_posts()) :
            while ($photo_query->have_posts()) :
                $photo_query->the_post();
        ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
                    <div class="image-container">
                        <div class="image">
                            <?php
                            // Afficher le contenu de la publication
                            the_content();
                            ?>
                            <div class="image-title">
                                    <h2>PHOTOGRAPHE EVENT</h2>
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

        ?>
    </main>
</div>

<!-- 8 premieres photos -->
<div id="content" class="site-content">
<main id="main" class="site-main">
    <?php
    // Boucle WordPress pour récupérer les articles du type de contenu "photo"
    $args = array(
        'post_type' => 'photo', // Nom du custom post type
        'posts_per_page' => 8,  // Afficher tous les articles, vous pouvez ajuster cela selon vos besoins
        'order'          => 'ASC',
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

<!-- Charger plus -->
<?php if (wp_count_posts('photo')->publish) : ?>

    <form 
        action="<?php echo admin_url('admin-ajax.php'); ?>" 
        method="post" 
        class="js-load-photos"
    >
        <input 
            type="hidden" 
            name="posttype" 
            value="photo"
        >
        <input 
            type="hidden" 
            name="nonce" 
            value="<?php echo wp_create_nonce('capitaine_load_photos'); ?>"
        > 
        <input
            type="hidden"
            name="action"   
            value="capitaine_load_photos"
        >

        <button class="photos-load-button">Charger plus</button>

    </form>

    <ul class="photos">
        <!-- Les photos apparaîtront ici -->
    </ul>

<?php endif; ?>

<?php
get_footer(); 
?>