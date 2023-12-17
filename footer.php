<!-- Modale de contact -->
<?php get_template_part('templates-parts/templates_part'); ?>


<footer>
    <div class="footer-content">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'container'      => false,
            'fallback_cb'    => false, // Pour éviter d'afficher le menu par défaut
        ));
        ?>
    </div>
</footer>

<?php wp_footer(); ?>
