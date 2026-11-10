<?php
/**
 *
 * This file contains the Shortcut to display the Album image gallery.
 *
 */

/**
 * The column function.
 *
 * Accepts an id, a type, the number of the field and a class and will generate an image gallery item.
 *
 * @param Array  $id    Column id     Default null
 * @param String $type  Column type   Default null
 * @param String $i     Column number Default 0
 * @param String $class Column class  Default 'albums-col-sm-3'
 * @return string Image Gallery item
 */
function albums_col($id = null, $type = null, $i = 0, $class = 'albums-col-sm-3') {
    $content  = '';
    // Checking it the id is valid
    if ($id) {
        // Assuming the slug field contains an url
        $url = $type ? get_option(  $type. '_slug_' . $i . '_option', '' ) : '';
        // Checking if the url is not an url
        if ( ! str_contains( $url, 'http' ) && strlen( $url ) ) {
            // Generating an url
            $page_slug = $url;
            $page_obj = get_page_by_path( $page_slug );
            $url = $page_obj ? get_permalink($page_obj->ID) : '';
        }
        $content .= '<div class="albums-col albums-col-12 ' . $class . '" data-id="' . $id . '">';
        $content .= strlen( $url ) ? '<a href="' . $url . '">' : '';
        $content .= '    <img src="' . wp_get_attachment_image_url( $id , 'medium') . '" class="albums-gallery-image-item-img">';
        $content .= strlen( $url ) ? '</a>' : '';
        $content .= '</div>';
    }
    return $content;
}

/**
 * The [albums_image_galery] shortcode.
 *
 * Accepts a type, skip id and a column class and will generate an image gallery.
 *
 * @param Array  $atts    Shortcode attributes Default empty.
 * @param String $content Shortcode content   Default null.
 * @param String $tag     Shortcode tag (name) Default empty.
 * @return String Shortcode output.
 */
function albums_image_galery( $atts = [], $content = null , $tag = '' ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'albums';
    $records = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name")
    );
    // Getting the parameters
    $type  = isset( $atts['type'] ) ? $atts['type'] : 'album';
    $skip  = isset( $atts['skip'] ) ? $atts['skip'] : '';
    $class = isset( $atts['class'] ) ? $atts['class'] : 'albums-col-sm-6 albums-col-md-4 albums-col-lg-4';
    // Total
    $total = count($records);
    // Generating the gallery
    $local_content = '<div class="albums-row">';
    for ($i = 0; $i < $total; $i++) {
        // Image id
        $id = $i + 1 == $skip ? null : $records[$i]->cover;
        // Generating an item
        $local_content .= albums_col( $id , $type, $i, $class);
    }
    $local_content .= '</div>';
    return $local_content . $content;
}

/**
 * Central location to create all shortcodes.
 */
function albums_image_galery_init() {
    add_shortcode( 'albums_image_galery', 'albums_image_galery' );
}
add_action( 'init', 'albums_image_galery_init' );

/**
 * Enqueue scripts and styles.
 */
function albums_wp_enqueue_scripts() {
    wp_enqueue_style( 'albums_grid_css', plugins_url('/assets/css/bootstrap-grid.css', __FILE__), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'albums_wp_enqueue_scripts' );
