<?php
/**
 * jal_install function
 *
 * This function creates the next tables:
 *
 * - Albums
 * - Songs
 * - Buttons
 */
function jal_install() {
    global $wpdb;
    global $jal_db_version;

    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Albums
    $table_1 = $wpdb->prefix . 'albums';
    $sql  = "CREATE TABLE ". $table_1 . " (";
    $sql .= "    id int(11) AUTO_INCREMENT PRIMARY KEY,";
    $sql .= "    cover varchar(255) NOT NULL,";
    $sql .= "    name varchar(255) NOT NULL";
    $sql .= ") " . $charset_collate . ";";
    dbDelta( $sql );
    // Songs
    $table_2 = $wpdb->prefix . 'songs';
    $sql  = "CREATE TABLE ". $table_2 . " (";
    $sql .= "    id int(11) AUTO_INCREMENT PRIMARY KEY,";
    $sql .= "    name varchar(255) NOT NULL,";
    $sql .= "    album_id int(11),";
    $sql .= "    FOREIGN KEY (album_id) REFERENCES " . $table_1 . "(id) ON DELETE CASCADE";
    $sql .= ") " . $charset_collate . ";";
    dbDelta( $sql );
    // Buttons
    $table_3 = $wpdb->prefix . 'buttons';
    $sql  = "CREATE TABLE ". $table_3 . " (";
    $sql .= "    id int(11) AUTO_INCREMENT PRIMARY KEY,";
    $sql .= "    link varchar(255) NOT NULL,";
    $sql .= "    platform_id int(11) NOT NULL,";
    $sql .= "    album_id int(11),";
    $sql .= "    song_id int(11),";
    $sql .= "    FOREIGN KEY (album_id) REFERENCES " . $table_1 . "(id) ON DELETE CASCADE,";
    $sql .= "    FOREIGN KEY (song_id) REFERENCES " . $table_2 . "(id) ON DELETE CASCADE";
    $sql .= ") " . $charset_collate . ";";
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}
