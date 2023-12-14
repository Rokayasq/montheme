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
    
    <!--menu-burger -->
    <nav id="site-navigation" class="main-navigation">
        <div class="nowrap">
            <span class="burger">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <img src='<?php echo get_stylesheet_directory_uri() . "/assets/images/State=default.png" ?>' id="burger-image">
                </button>
            </span> 
        </div>
        
        <ul id="menu-items">
            <div class="T5-container">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main-menu',
                    'container'      => false // On retire le conteneur généré par WP
                ]);
                ?>
            </div>  
        </ul>
    </nav>

</header>


<?php wp_footer(); ?>
</body> 
</html>