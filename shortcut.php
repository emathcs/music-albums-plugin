<?php
/**
 *
 * This file contains the Shortcut to display the Album image gallery.
 *
 */

/**
 * The buttons function.
 *
 * @param Object  $button Button record Default null
 * @param Boolean $label  Display label Default true
 * @return String Platform button
 */
function albums_button($button = null, $label = true) {
    $content  = '';
    // Checking it the id is valid
    if ($button) {
        $platforms = [
            '1' => [
                'icon'  => 'apple-music',
                'label' => 'Apple Music'
            ],
            '2' => [
                'icon'  => 'spotify',
                'label' => 'Spotify'
            ],
            '3' => [
                'icon'  => 'amazon',
                'label' => 'Amazon Music'
            ]
        ];
        $content .= '<a href="' . $button->link . '" target="_blank" class="btn btn-link ' . ($label ? '' : 'btn-sm') . '">';
        $content .= '    <i class="bi bi-' . $platforms[$button->platform_id]['icon']. '"></i>';
        $content .=      $label ? ' ' . $platforms[$button->platform_id]['label'] : '';
        $content .= '</a> ';
    }
    return $content;
}

/**
 * The column function.
 *
 * @param Array  $id    Column id     Default null
 * @param String $type  Column type   Default null
 * @param String $i     Column number Default 0
 * @param String $class Column class  Default 'albums-col-sm-3'
 * @return String Gallery item
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
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_image_galery( $atts = [], $content = null , $tag = '' ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'albums';
    $records = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name")
    );
    if ( ! count($records) ) {
        return $content;
    }
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
 * The [albums_cover_buttons] shortcode.
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_cover_buttons( $atts = [], $content = null , $tag = '' ) {
    $id = $atts['id'] ? $atts['id'] : 0;
    if ( !$id ) {
        return $content;
    }
    global $wpdb;
    // Album
    $table_name = $wpdb->prefix . 'albums';
    $album = $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM $table_name WHERE id=%d", $id)
    );
    if ( ! $album ) {
        return $content;
    }
    // Buttons
    $id = $album->id;
    $table_name = $wpdb->prefix . 'buttons';
    $buttons = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name WHERE album_id=%d", $id)
    );
    // Getting the parameters
    $class = isset( $atts['class'] ) ? $atts['class'] : '';
    // Total
    $total = count($buttons);
    // Generating the gallery
    $local_content  = '<div class="albums-row">';
    $local_content .=      albums_col( $album->cover, null, 0, $class );
    $local_content .= '    <div class="albums-col albums-col-12">';
    for ($i = 0; $i < $total; $i++) {
        $local_content .= albums_button($buttons[$i]);
    }
    $local_content .= '    </div>';
    $local_content .= '</div>';
    return $local_content . $content;
}

/**
 * The song function.
 *
 * @param Object  $song Song record     Default null
 * @param Boolean $label  Display label Default true
 * @return String Song list item
 */
function albums_song($song = null) {
    $content = '';
    if ( $song ) {
        global $wpdb;
        // Buttons
        $id = $song->id;
        $table_name = $wpdb->prefix . 'buttons';
        $buttons = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name WHERE song_id=%d", $id)
        );
        // Total
        $total = count( $buttons );
        $content .= '<div class="row album-song">';
        $content .= '    <div class="col-6">';
        $content .= '        <p>' . $song->name. '</p>';
        $content .= '    </div>';
        $content .= '    <div class="col-6 text-end">';
        for ($i = 0; $i < $total; $i++) {
            $content .= albums_button( $buttons[$i] , false);
        }
        $content .= '    </div>';
        $content .= '</div>';
    }
    return $content;
}

/**
 * The [albums_song_list] shortcode.
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_song_list( $atts = [], $content = null , $tag = '' ) {
    $id = $atts['id'] ? $atts['id'] : 0;
    if ( !$id ) {
        return $content;
    }
    global $wpdb;
    // Song
    $table_name = $wpdb->prefix . 'songs';
    $songs = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name WHERE album_id=%d", $id)
    );
    // Getting the parameters
    $class = isset( $atts['class'] ) ? $atts['class'] : '';
    // Total
    $total = count($songs);
    // Generating the gallery
    $local_content  = '<div class="albums-row">';
    for ($i = 0; $i < $total; $i++) {
        $local_content .=  albums_song( $songs[$i] );
    }
    $local_content .= '</div>';
    return $local_content . $content;
}

/**
 * Central location to create all shortcodes.
 */
function albums_image_galery_init() {
    add_shortcode( 'albums_image_galery', 'albums_image_galery' );
    add_shortcode( 'albums_cover_buttons', 'albums_cover_buttons' );
    add_shortcode( 'albums_song_list', 'albums_song_list' );
}
add_action( 'init', 'albums_image_galery_init' );

/**
 * Enqueue scripts and styles.
 */
function albums_wp_enqueue_scripts() {
    wp_enqueue_style( 'albums_grid_css', plugins_url('/assets/css/bootstrap-grid.css', __FILE__), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'albums_wp_enqueue_scripts' );
