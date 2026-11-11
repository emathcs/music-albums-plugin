<?php
/**
 * Plugin Name:       Albums
 * Plugin URI:        https://www.github.com/emathcs/albums
 * Description:       Plugin to manage albums.
 * Author:            Carlos E. Alvarez
 * Author URI:        https://www.emathcs.com
 * Version:           1.0.0
 * Tested up to:      7.0
 * Requires at least: 6.3
 * Requires PHP:      8.2.0
 * License:           GNU General Public License v3.0 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Powered by:        https://www.aomath.com
 */

// Prevent direct file access for security
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin version
if ( ! defined( 'AP_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'AP_VERSION', '1.0.0' );
}

// Plugin URL
define( 'ALBUM_URL', plugin_dir_url( __FILE__ ) );

/**
 * Create the tables into the database
 */
global $jal_db_version;
$jal_db_version = '1.0';
require_once plugin_dir_path( __FILE__ ) . 'jal-install.php';
register_activation_hook( __FILE__, 'jal_install' );

/**
 * Loading custom template
 */
require_once plugin_dir_path( __FILE__ ) . 'wp-admin/includes/template.php';

/**
 * Registering the plugin into the admin section
 */
function albums_admin_menu() {
    /**
     * Albums
     */

    // List
    add_menu_page(
        'Albums',                      // Page title
        'Albums',                      // Menu title
        'manage_options',              // Required user capability
        'albums-settings',             // URL slug
        'albums_render_settings_page', // Function that outputs the HTML page
        'dashicons-format-gallery',    // Left menu icon
        '81'                           // The left menu link appears after the Settings link
    );

    // Add
    add_submenu_page(
        '',                    // Parent menu slug
        'Add a new album',     // Page title
        '',                    // Menu title
        'manage_options',      // Capability
        'albums-add-settings', // Menu slug
        'albums_add_html'      // Callback function
    );

    // View
    add_submenu_page(
        '',                     // Parent menu slug
        'View album',           // Page title
        '',                     // Menu title
        'manage_options',       // Capability
        'albums-view-settings', // Menu slug
        'albums_view_html'      // Callback function
    );

    // Edit
    add_submenu_page(
        '',                     // Parent menu slug
        'Edit album',           // Page title
        '',                     // Menu title
        'manage_options',       // Capability
        'albums-edit-settings', // Menu slug
        'albums_edit_html'      // Callback function
    );

    /**
     * Songs
     */

    // Add
    add_submenu_page(
        '',                   // Parent menu slug
        'Songs',              // Page title
        '',                   // Menu title
        'manage_options',     // Capability
        'songs-add-settings', // Menu slug
        'songs_add_html'      // Callback function
    );

    // View
    add_submenu_page(
        '',                    // Parent menu slug
        'Songs',               // Page title
        '',                    // Menu title
        'manage_options',      // Capability
        'songs-view-settings', // Menu slug
        'songs_view_html'      // Callback function
    );

    // Edit
    add_submenu_page(
        '',                    // Parent menu slug
        'Songs',               // Page title
        '',                    // Menu title
        'manage_options',      // Capability
        'songs-edit-settings', // Menu slug
        'songs_edit_html'      // Callback function
    );

    /**
     * Buttons
     */

    // Add
    add_submenu_page(
        '',                     // Parent menu slug
        'Buttons',              // Page title
        '',                     // Menu title
        'manage_options',       // Capability
        'buttons-add-settings', // Menu slug
        'buttons_add_html'      // Callback function
    );

    // View
    add_submenu_page(
        '',                      // Parent menu slug
        'Buttons',               // Page title
        '',                      // Menu title
        'manage_options',        // Capability
        'buttons-view-settings', // Menu slug
        'buttons_view_html'      // Callback function
    );

    // Edit
    add_submenu_page(
        '',                      // Parent menu slug
        'Buttons',               // Page title
        '',                      // Menu title
        'manage_options',        // Capability
        'buttons-edit-settings', // Menu slug
        'buttons_edit_html'      // Callback function
    );
}
add_action( 'admin_menu', 'albums_admin_menu' );

/**
 * Initializing and registering the settings via API settings
 */

// Default values for the option
function albums_local_args() {
    return array(
        'default'           => '',
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
    );
}

// Registering the settings.
function albums_admin_init() {
    add_settings_section(
        'albums_main_section', // Section ID
        'Albums',              // Section Title
        '',                    // Callback function to output section text
        'albums-settings'      // Page slug where this section goes
    );

    // Registering the Album setting option in the wp_options table
    register_setting(
        'albums_settings_group', // Option group name
        'albums_option',         // Actual option key name
        albums_local_args()
    );
}
add_action( 'admin_init', 'albums_admin_init' );

/**
 * Loading HTML elements library
 */
require_once plugin_dir_path( __FILE__ ) . 'html-elements/Form.php';
require_once plugin_dir_path( __FILE__ ) . 'html-elements/Input.php';
require_once plugin_dir_path( __FILE__ ) . 'html-elements/Select.php';

/**
 * MVC
 */
require_once plugin_dir_path( __FILE__ ) . 'mvc/Core.php';

/**
 * Rendering the fields in the admin section
 */

// Albums admin page
function albums_render_settings_page() {
    $controller = new AlbumController();
    $controller->list();
}

// Add a new album
function albums_add_html() {
    $controller = new AlbumController();
    $controller->add();
}

// View album
function albums_view_html() {
    $album_id = isset( $_GET['album'] ) ? $_GET['album'] : 0 ;
    $controller = new AlbumController();
    $controller->view( $album_id );
}

// Edit album
function albums_edit_html() {
    $album_id = isset( $_GET['album'] ) ? $_GET['album'] : 0 ;
    if ( ! $album_id ) {
        return;
    }
    $controller = new AlbumController();
    $controller->edit( $album_id );
}

// Songs functions
require_once plugin_dir_path( __FILE__ ) . 'song.php';

// Buttons functions
require_once plugin_dir_path( __FILE__ ) . 'button.php';

/**
 * Enqueue admin scripts and styles
 */
function albums_admin_enqueue_scripts( $hook ) {
    $sections = [
        'toplevel_page_albums-settings'   => true,
        'admin_page_albums-add-settings'  => true,
        'admin_page_albums-edit-settings' => true,
        'admin_page_albums-view-settings' => true,
        // Song
        'admin_page_songs-settings'      => true,
        'admin_page_songs-add-settings'  => true,
        'admin_page_songs-view-settings' => true,
        'admin_page_songs-edit-settings' => true,
        // Button
        'admin_page_buttons-settings'      => true,
        'admin_page_buttons-add-settings'  => true,
        'admin_page_buttons-view-settings' => true,
        'admin_page_buttons-edit-settings' => true,
    ];
    // Load scripts only on your specific plugin settings page
    if ( ! ( isset( $sections[$hook] ) && $sections[$hook] ) ) {
        return;
    }
    // Call the core WordPress media files
    wp_enqueue_media();
    // Loading the assets
    wp_enqueue_script('albums-media-library-js', plugins_url('/assets/js/admin-gallery.js', __FILE__), false , AP_VERSION, true);
    wp_enqueue_style( 'albums_bootstrap_min_css', plugins_url('/assets/css/bootstrap.min.css', __FILE__), array(), AP_VERSION );
    wp_enqueue_style( 'albums_admin_css', plugins_url('/assets/css/admin.css', __FILE__), array(), AP_VERSION );
}
add_action('admin_enqueue_scripts', 'albums_admin_enqueue_scripts');

/**
 * Shortcut section.
 */
require_once plugin_dir_path( __FILE__ ) . 'shortcut.php';
