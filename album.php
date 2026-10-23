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
 * Database tables
 */
global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
    global $wpdb;
    global $jal_db_version;

    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table_1 = $wpdb->prefix . 'albums';

    $sql  = "CREATE TABLE ". $table_1 . " (";
    $sql .= "    id int(11) AUTO_INCREMENT PRIMARY KEY,";
    $sql .= "    name varchar(255) NOT NULL";
    $sql .= ") " . $charset_collate . ";";

    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );

    $table_2 = $wpdb->prefix . 'songs';

    $sql  = "CREATE TABLE ". $table_2 . " (";
    $sql .= "    id int(11) AUTO_INCREMENT PRIMARY KEY,";
    $sql .= "    name varchar(255) NOT NULL,";
    $sql .= "    album_id int(11),";
    $sql .= "    FOREIGN KEY (album_id) REFERENCES " . $table_1 . "(id) ON DELETE CASCADE";
    $sql .= ") " . $charset_collate . ";";

    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install' );

// Select
function select($table = null) {
    if ( !$table ) {
        return [];
    }
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$table}");
}

// Insert
function insert() {
    $welcome_name = 'Pamela Robin';
    $table_name = $wpdb->prefix . 'albums';
    $wpdb->insert(
        $table_name,
        array(
            'name' => $welcome_name,
        )
    );
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

    // Add
    add_submenu_page(
        '',                    // Parent menu slug
        'Add a new album',     // Page title
        '',                    // Menu title
        'manage_options',      // Capability
        'albums-add-settings', // Menu slug
        'albums_add_html'   // Callback function
    );

    // Add
    add_submenu_page(
        '',                     // Parent menu slug
        'Edit album',           // Page title
        '',                     // Menu title
        'manage_options',       // Capability
        'albums-settings-edit', // Menu slug
        'edit_album_html'       // Callback function
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
    $class = 'form-control';
    $placeholder = '';
    // Generating the inputs
    echo '<div class="albums-col col-sm-12">';
    echo '    <label for="name" class="form-label">Name</label>';
    echo '    <input type="text" id="' . $id . '" name="' . $name . '_option" value="' . $value .  '" class="'. $class . '" placeholder="' . $placeholder . '" />';
    echo "</div>";

}

/**
 * Rendering the fields in the admin section
 */

// Local form function
function albums_form($type = null) {
    echo '<form action="options.php" method="post" class="form row">';
    // Output nonce, action, and option group hidden fields for security
    settings_fields( 'albums_settings_group' );
    // Output all sections registered to this page
    albums_do_settings_sections( 'albums-settings' );
    // Output standard submission button
    echo '    <div class="albums-col albums-submit">';
    submit_button( 'Save Changes', 'primary', 'submit', false, '');
    echo '    </div>';
    echo '</form>';
}

// Open the panel
function panelOpen($title = '') {
    $render  = '<div class="albums-wrap">';
    $render .= '    <div class="container albums-panel">';
    $render .= '        <div class="row">';
    $render .= '            <div class="col-xs">';
    $render .= '                <h1>' . esc_html( $title ) . '</h1>';
    $render .= '            </div>';
    $render .= '            <div class="col-xs">';
    return $render;
}

// Close the panel
function panelClose() {
    $render  = '            </div>';
    $render .= '        </div>';
    $render .= '    </div>';
    $render .= '</div>';
    return $render;
}

// Albums admin page
function albums_render_settings_page() {
    // Verify if the user has permission to view this page
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    global $wpdb;
    $records = select( $wpdb->prefix . 'robin_albums' );
    $total = count( $records );

    echo panelOpen( get_admin_page_title() );
    echo '<a href="?page=albums-add-settings" class="btn btn-sm btn-primary">Add a new album</a>';

    echo '<table class="table table-sm table-striped table-hover table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">No.</th>';
    echo '<th scope="col">Name</th>';
    echo '<th scope="col" colspan="3" class="col-actions">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    for ($i = 0; $i < $total; $i++) {
        echo '<tr>';
        echo '<th scope="row">' . ($i + 1) . '</th>';
        echo '<td>' . $records[$i]->name . '</td>';
        echo '<td class="col-action"><button class="btn btn-sm btn-secondary">View</button></td>';
        echo '<td class="col-action"><a href="?page=albums-settings-edit&album=' . $records[$i]->id . '" class="btn btn-sm btn-warning">Edit</a></td>';
        echo '<td class="col-action"><button class="btn btn-sm btn-danger">Delete</button></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo panelClose();
}

// Album form
function album_form() {
    $id = 'cover';
    $name = 'cover';
    $value = esc_attr( get_option(  $id . '_option', '' ) );
    $class = 'form-control';
    $placeholder = '';
    echo '<form action="options.php" method="post">';
    settings_fields( 'albums_settings_group' );
    echo '<div class="albums-col col-sm-12">';
    echo '    <label for="name" class="form-label">Cover</label>';
    echo '    <input type="text" id="' . $id . '" name="' . $name . '_option" value="' . $value .  '" class="'. $class . '" placeholder="' . $placeholder . '" />';
    echo "</div>";

    $id = 'name';
    $name = 'name';
    echo '<div class="albums-col col-sm-12">';
    echo '    <label for="name" class="form-label">Name</label>';
    echo '    <input type="text" id="' . $id . '" name="' . $name . '_option" value="' . $value .  '" class="'. $class . '" placeholder="' . $placeholder . '" />';
    echo "</div>";
    echo '    <div class="albums-col albums-submit">';

    submit_button( 'Save Changes', 'primary', 'submit', false, '');
    echo '    </div>';
    echo '</form>';
}

// Add a new album
function albums_add_html() {
    echo panelOpen( 'Add a new album' );
    album_form();
    echo panelClose();
}

// Edit album
function edit_album_html() {
    echo panelOpen( 'Edit album' );
    echo panelClose();
}

/**
 * Enqueue admin scripts and styles
 */
function albums_admin_enqueue_scripts( $hook ) {
    $sections = [
        'toplevel_page_albums-settings'   => true,
        'admin_page_albums-add-settings'  => true,
        'admin_page_albums-settings-edit' => true,
    ];
    // Load scripts only on your specific plugin settings page
    if ( ! ( isset( $sections[$hook] ) && $sections[$hook] ) ) {
        return;
    }
    // Loading the assets
    wp_enqueue_style( 'albums_admin_css', plugins_url('/assets/css/admin.css', __FILE__), array(), AP_VERSION );
    wp_enqueue_style( 'albums_bootstrap_css', plugins_url('/assets/css/bootstrap.css', __FILE__), array(), AP_VERSION );
}
add_action('admin_enqueue_scripts', 'albums_admin_enqueue_scripts');
