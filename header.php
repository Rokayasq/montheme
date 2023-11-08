<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>


<header>
    <!--logo -->
    <div id="logo">
    <?php
    if (function_exists('the_custom_logo')) {
        the_custom_logo();
    }
    ?>
    </div>
    
    <!--menu -->
    <div class="header-menu">
    <?php
    wp_nav_menu([
        'theme_location' => 'main-menu',
        'container'      => false // On retire le conteneur généré par WP
        
    ]);
    ?>
    </div>

</header>




