<?php
//header 
function register_my_menu() {
    register_nav_menu( 'main-menu' , __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );

//ajouter le logo
function custom_theme_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 22,
        'width'       => 344.51,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'custom_theme_setup');

//footer 
function register_footer() {
    register_nav_menu( 'footer-menu' , __( 'footer', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_footer' );

//css
function enqueue_custom_styles() {
    wp_enqueue_style('custom-styles', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

//js
function ajouter_script_js() {
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array(),false , true);
}
add_action('wp_enqueue_scripts', 'ajouter_script_js');

//page contact modale
function ajouter_code_sur_page_contact() {
    if (is_page('contact')) {
        get_header(); 
        get_template_part( 'templates_part' );
    }
}
add_action('wp', 'ajouter_code_sur_page_contact');

