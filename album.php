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

/**
 * Registering the plugin into the admin section
 */
function albums_admin_menu() {
    add_menu_page(
        'Albums',                      // Page title
        'Albums',                      // Menu title
        'manage_options',              // Required user capability
        'albums-settings',             // URL slug
        'albums_render_settings_page', // Function that outputs the HTML page
        'dashicons-format-gallery',    // Left menu icon
        '81'                           // The left menu link appears after the Settings link
    );
}
add_action( 'admin_menu', 'albums_admin_menu' );

/**
 * Albums admin page
 */
function albums_render_settings_page() {
    // Verify if the user has permission to view this page
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    echo '<div class="albums-wrap">';
    echo '    <div class="albums-container">';
    echo '        <div class="albums-row">';
    echo '            <div class="albums-col-xs albums-panel">';
    echo '                <h1>' . esc_html( get_admin_page_title() ) . '</h1>';
    echo '            </div>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
}

/**
 * Enqueue admin scripts and styles
 */
function albums_admin_enqueue_scripts( $hook ) {
    // Load scripts only on your specific plugin settings page
    if ($hook !== 'toplevel_page_albums-settings') {
        return;
    }
    // Loading the assets
    wp_enqueue_style( 'albums_admin_css', plugins_url('/assets/css/admin.css', __FILE__), array(), AP_VERSION );
    wp_enqueue_style( 'albums_bootstrap_grid_css', plugins_url('/assets/css/bootstrap-grid.css', __FILE__), array(), AP_VERSION );
}
add_action('admin_enqueue_scripts', 'albums_admin_enqueue_scripts');
