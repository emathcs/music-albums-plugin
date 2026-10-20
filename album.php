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
 * Loading custom template
 */
require_once plugin_dir_path( __FILE__ ) . 'wp-admin/includes/template.php';

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
    // Album image input field
    add_settings_field(
        'album',               // Field ID
        'Album',               // Field label on the left side
        'albums_callback',     // Callback function to output the input element
        'albums-settings',     // Page slug
        'albums_main_section', // Section ID
        array()
    );
}
add_action( 'admin_init', 'albums_admin_init' );

/**
 * Callback function to render the fields
 */
function albums_callback( $args = array() ) {
    $id = 'albums';
    $name = 'albums';
    $value = esc_attr( get_option(  $id . '_option', '' ) );
    $class = 'albums-form-control';
    $placeholder = '';
    // Generating the inputs
    echo '<div class="albums-col albums-col-sm-12">';
    echo '    <label for="name" class="albums-form-label">Name</label>';
    echo '    <input type="text" id="' . $id . '" name="' . $name . '_option" value="' . $value .  '" class="'. $class . '" placeholder="' . $placeholder . '" />';
    echo "</div>";

}

/**
 * Rendering the fields in the admin section
 */

// Local form function
function albums_form($type = null) {
    echo '<form action="options.php" method="post" class="albums-form albums-row">';
    // Output nonce, action, and option group hidden fields for security
    settings_fields( 'albums_settings_group' );
    // Output all sections registered to this page
    albums_do_settings_sections( 'albums-settings' );
    // Output standard submission button
    echo '    <div class="albums-col albums-col-3 albums-submit">';
    submit_button( 'Save Changes', 'primary', 'submit', false, '');
    echo '    </div>';
    echo '</form>';
}

// Albums admin page
function albums_render_settings_page() {
    // Verify if the user has permission to view this page
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    echo '<div class="albums-wrap">';
    echo '    <div class="albums-container albums-panel">';
    echo '        <div class="albums-row">';
    echo '            <div class="albums-col-xs">';
    echo '                <h1>' . esc_html( get_admin_page_title() ) . '</h1>';
    echo '            </div>';
    echo '            <div class="albums-col-xs">';
    albums_form();
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
