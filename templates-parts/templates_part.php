<!-- The Modal -->
<div class="contact-overlay" id="ContactOverlay"></div>
<div id="ContactModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content" >
        <div class="modal-header">
            <img src='<?php echo get_stylesheet_directory_uri() ."/assets/images/Contact-header.png" ?>' >
        </div>

        <?php
        echo do_shortcode('[contact-form-7 id="7deb8fa" title="Formulaire de contact 1"]');
        ?>
    </div>
 </div>


 