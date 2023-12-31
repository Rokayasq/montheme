<?php
get_header(); 
?>

<!-- HERO HEADER -->
<div id="hero-content" class="site-content">
    <main id="main" class="site-main">
        <?php
        // Boucle WordPress pour récupérer les articles du type de contenu "photo"
        $args = array(
            'post_type' => 'photo', // Nom du custom post type
            'posts_per_page' => 1,
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

<!-- Selects -->
<div class="filtres">
    <!-- CATEGORIES -->
    <div  id="filter-container">
        <?php
        $taxonomy = 'categorie'; 
        $post_id = get_the_ID();

        // Récupérer toutes les catégories de la taxonomie
        $categories = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        if (is_array($categories) && !empty($categories)) {
            echo '<label for="filter-category"> </label>';
            echo '<select id="filter-category" name="filter-category">';
            echo '<option value="all">CATÉGORIES</option>';

            foreach ($categories as $category) {
                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
            }

            echo '</select>';
        } 
    ?>
    </div>

<!-- FORMAT -->
    <div id="filter-container">
        <?php
        $taxonomy = 'format'; 
        $post_id = get_the_ID();

        // Récupérer toutes les catégories de la taxonomie
        $formats = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        if (is_array($formats) && !empty($formats)) {
            echo '<label for="filter-format"> </label>';
            echo '<select id="filter-format" name="filter-format">';
            echo '<option value="all">FORMATS</option>';

            foreach ($formats as $format) {
                echo '<option value="' . $format->term_id . '">' . $format->name . '</option>';
            }

            echo '</select>';
        } 
    ?>
    </div>

    <!-- TRIER PAR -->
    <div class="trier" id="filter-container">
        <?php
        $taxonomy = 'trier_par'; 
        $post_id = get_the_ID();

        // Récupérer toutes les catégories de la taxonomie
        $trier_pars = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        if (is_array($trier_pars) && !empty($trier_pars)) {
            echo '<label for="filter-trier_par"></label>';
            echo '<select id="filter-trier_par" name="filter-trier_par" class="trier-par-select">' ;
            echo '<option value="all">TRIER PAR</option>' ;
            echo '<option value="recentes" class="trier-par-option" data-sort="DESC">Plus récentes</option>' ;
            echo '<option value="anciennes" class="trier-par-option" data-sort="ASC">Plus anciennes</option>' ;
            echo '</select>';
        }
        ?>
    </div>
</div>   

<!-- 8 premieres photos -->
<div class="images">
    <div id="content" class="site-content">
        <main id="main" class="site-main">
    <?php
    // Boucle WordPress pour récupérer les articles du type de contenu "photo"
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8, 
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


        </div>
    </article>


    <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'Aucune photo trouvée.';
    endif;

    ?>
    
        </main>
    </div>

<!-- CHARGER PLUS -->
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

    <div class="photos">
        <!-- Les photos apparaîtront ici -->
    </div>

<?php endif; ?>
</div>


<?php
get_footer(); 
?>