<?php
function zox_news_child_enqueue_styles() {

    $parent_style = 'mvp-custom-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'fontawesome-child', get_stylesheet_directory_uri() . '/font-awesome/css/font-awesome.css' );
    wp_enqueue_style( 'mvp-custom-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'zox_news_child_enqueue_styles' );
function nuovo_widget() {
    register_sidebar(
        array(
            "name" => "ADV home dopo prima evidenza",
            "description" => "Posizione banner in home page",
            "id" => "adv-home-evidenza",
            "before_widget" => "<div>",
            "after_widget" => "</div>",
            "before_title" => "<h2 class=\"rounded\">",
            "after_title" => "</h2>"
        )
    );
}
add_action( "widgets_init", "nuovo_widget");

function my_data_update () {
    GLOBAL $post;
    wp_set_post_tags( $post->ID, 'copertina', true );
}
add_action('publish_post', 'my_data_update');
add_action('save_post', 'my_data_update');
 
?>