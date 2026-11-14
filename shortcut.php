<?php
/**
 *
 * This file contains the Shortcut to display the Album image gallery.
 *
 */

/**
 * Generate buttons
 *
 * @param Object  $button Button record Default null
 * @param Boolean $label  Display label Default true
 * @return String Platform button
 */
function albums_button( $button = null, $label = true ) {
    $local_content  = '';
    // Checking it the id is valid
    if ( $button ) {
        $platforms = [
            '1' => [
                'icon'  => 'apple-music-streaming',
                'label' => 'Apple Music'
            ],
            '2' => [
                'icon'  => 'spotify-streaming',
                'label' => 'Spotify'
            ],
            '3' => [
                'icon'  => 'amazon-streaming',
                'label' => 'Amazon Music'
            ]
        ];
        $local_content .= '<a href="' . $button->link . '" target="_blank" class="btn btn-link btn-platform link-secondary">';
        $local_content .= '    <i class="bi bi-' . $platforms[$button->platform_id]['icon']. '"></i>';
        $local_content .=      $label ? ' <span class="label">' . $platforms[$button->platform_id]['label'] . '</span>' : '';
        $local_content .= '</a> ';
    }
    return $local_content;
}

/**
 * Generate a column
 *
 * @param Array  $image_id Image ID  Default 0
 * @param String $post_id  Post ID   Default 0
 * @param String $class    CSS class Default 'albums-col-sm-3'
 * @return String Gallery item
 */
function albums_col( $image_id = null, $post_id = 0, $class = 'albums-col-sm-3' ) {
    $local_content  = '';
    // Checking it the id is valid
    if ( $image_id ) {
        // Not a valid Post ID
        $url = '';
        // Check if the Post ID > 0
        if ( $post_id > 0 ) {
            // Generate the url
            $url = get_permalink( $post_id );
        }
        $local_content .= '<div class="albums-col albums-col-12 ' . $class . '" data-id="' . $image_id . '">';
        $local_content .=      strlen( $url ) ? '<a href="' . esc_url( $url ) . '">' : '';
        $local_content .= '        <img src="' . wp_get_attachment_image_url( $image_id , 'medium' ) . '" class="albums-gallery-image-item-img">';
        $local_content .=      strlen( $url ) ? '</a>' : '';
        $local_content .= '</div>';
    }
    return $local_content;
}

/**
 * Generate a song
 *
 * @param Integer $li List item         Default 0
 * @param Object  $song Song record     Default null
 * @param Boolean $label  Display label Default true
 * @return String Song list item
 */
function albums_song( $li = 0, $song = null ) {
    $local_content = '';
    if ( $song ) {
        global $wpdb;
        // Get the Buttons
        $id = $song->id;
        $table_name = $wpdb->prefix . 'buttons';
        $buttons = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name WHERE song_id=%d", $id )
        );
        // Total
        $total = count( $buttons );
        // Generate the song and buttons
        $local_content .= '<div class="row albums-song">';
        $local_content .= '    <div class="col-7">';
        $local_content .= '        <p>' . ($li < 10 ? '&nbsp;' : '') . $li . '&nbsp;&nbsp;&nbsp;&nbsp;' . $song->name. '</p>';
        $local_content .= '    </div>';
        $local_content .= '    <div class="col-5 text-end">';
        for ( $i = 0; $i < $total; $i++ ) {
            // Generate the buttons
            $local_content .= albums_button( $buttons[$i] , false );
        }
        $local_content .= '    </div>';
        $local_content .= '</div>';
    }
    return $local_content;
}

/**
 *
 * Shortcuts
 *
 */

/**
 * The [albums_image_gallery] shortcode
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_image_gallery( $atts = [], $content = null , $tag = '' ) {
    $local_content = '';
    global $wpdb;
    // Get the Albums
    $type = isset( $atts['type'] ) ? $atts['type'] : '';
    $table_name = $wpdb->prefix . 'albums';
    if ( 'albums' == $type || 'singles' == $type ) {
        $types = [
            'albums'  => 1,
            'singles' => 2
        ];
        $records = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$table_name} WHERE type_id=%d", $types[$type])
        );
    }
    else {
        $records = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$table_name}")
        );
    }
    if ( count( $records ) ) {
        // Get the parameters
        $skip  = isset( $atts['skip'] ) ? $atts['skip'] : '';
        $class = isset( $atts['class'] ) ? $atts['class'] : 'albums-col-sm-6 albums-col-md-4 albums-col-lg-4';
        // Total
        $total = count( $records );
        // Start the gallery
        $local_content .= '<div class="albums-row">';
        for ( $i = 0; $i < $total; $i++ ) {
            // Post ID
            $post_id = $records[$i]->post_id;
            // Image ID
            $image_id = $i + 1 == $skip ? null : $records[$i]->image_id;
            // Generate an item
            $local_content .= albums_col( $image_id , $post_id, $class );
        }
        $local_content .= '</div>';
    }
    return $local_content . $content;
}

/**
 * The [albums_cover_buttons] shortcode
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_cover_buttons( $atts = [], $content = null , $tag = '' ) {
    $local_content = '';
    $id = isset( $atts['id'] ) ? $atts['id'] : 0;
    if ( $id > 0 ) {
        global $wpdb;
        // Get an Album
        $table_name = $wpdb->prefix . 'albums';
        $album = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id=%d", $id )
        );
        if ( $album ) {
            // Get the Buttons
            $id = $album->id;
            $table_name = $wpdb->prefix . 'buttons';
            $buttons = $wpdb->get_results(
                $wpdb->prepare( "SELECT * FROM $table_name WHERE album_id=%d", $id )
            );
            // Get the parameters
            $class = isset( $atts['class'] ) ? $atts['class'] : '';
            // Total
            $total = count( $buttons );
            // Generate the gallery
            $local_content .= '<div class="albums-row">';
            $local_content .=      albums_col( $album->image_id, 0, $class );
            $local_content .= '    <div class="albums-col albums-col-12">';
            for ( $i = 0; $i < $total; $i++ ) {
                // Gererate the buttons
                $local_content .= albums_button( $buttons[$i] );
            }
            $local_content .= '    </div>';
            $local_content .= '</div>';
        }
    }
    return $local_content . $content;
}

/**
 * The [albums_song_list] shortcode
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_song_list( $atts = [], $content = null , $tag = '' ) {
    $local_content = '';
    $id = isset( $atts['id'] ) ? $atts['id'] : 0;
    if ( $id > 0 ) {
        global $wpdb;
        // Song
        $table_name = $wpdb->prefix . 'songs';
        $songs = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$table_name} WHERE album_id=%d", $id )
        );
        // Get the parameters
        $class = isset( $atts['class'] ) ? $atts['class'] : '';
        // Total
        $total = count( $songs );
        // Generate the gallery
        for ( $i = 0; $i < $total; $i++ ) {
            $local_content .=  albums_song( $i + 1, $songs[$i] );
        }
    }
    return $local_content . $content;
}

/**
 * The [albums_cover_list] shortcode
 *
 * @param Array  $atts    Shortcode attributes Default empty
 * @param String $content Shortcode content    Default null
 * @param String $tag     Shortcode tag (name) Default empty
 * @return String Shortcode output.
 */
function albums_cover_list( $atts = [], $content = null , $tag = '' ) {
    $local_content  = '';
    $id = isset( $atts['id'] ) ? $atts['id'] : 0;
    if ( $id > 0 ) {
        $container_class = isset( $atts['container_class'] ) ? $atts['container_class'] : '';
        $local_content .= '<div class="albums-row ' . $container_class . '">';
        $local_content .= '    <div class="col-12 col-lg-6">';
        $local_content .=          albums_cover_buttons( $atts, '', $tag );
        $local_content .= '    </div>';
        $local_content .= '    <div class="col-12 col-lg-6 albums-song-list">';
        $local_content .=          albums_song_list( $atts, '', $tag );
        $local_content .= '    </div>';
        $local_content .= '</div>';
    }
    return $local_content . $content;
}

/**
 * Central location to create all shortcodes
 */
function albums_image_galery_init() {
    add_shortcode( 'albums_image_gallery', 'albums_image_gallery' );
    add_shortcode( 'albums_cover_buttons', 'albums_cover_buttons' );
    add_shortcode( 'albums_song_list', 'albums_song_list' );
    add_shortcode( 'albums_cover_list', 'albums_cover_list' );
}
add_action( 'init', 'albums_image_galery_init' );

/**
 * Enqueue scripts and styles.
 */
function albums_wp_enqueue_scripts() {
    wp_enqueue_style( 'albums_grid_css', plugins_url('/assets/css/bootstrap-grid.css', __FILE__), array(), '1.0.0' );
    wp_enqueue_style( 'albums_icons_css', plugins_url('/assets/css/bootstrap-icons.min.css', __FILE__), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'albums_wp_enqueue_scripts' );
