<?php
get_header(); // Appelle l'en-tête du thème
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                // Contenu de l'article
                the_content();
            endwhile;
        else :
            // Message si aucun article n'est trouvé
            echo 'Aucun contenu trouvé.';
        endif;
        ?>
    </main>
</div>

<?php
get_footer(); // Appelle le pied de page du thème
?>