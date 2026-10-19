<?php
/**
 * Custom Template WordPress Administration API.
 *
 * A Big Mess. Also some neat functions that are nicely written.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Prints out the settings fields for a particular settings section.
 *
 * Part of the Settings API. Use this in a settings page to output
 * a specific section. Should normally be called by do_settings_sections()
 * rather than directly.
 *
 * @since 2.7.0
 *
 * @global array $wp_settings_fields Storage array of settings fields and their pages/sections.
 *
 * @param string $page Slug title of the admin page whose settings fields you want to show.
 * @param string $section Slug title of the settings section whose fields you want to show.
 */
function albums_do_settings_fields( $page, $section ) {
    global $wp_settings_fields;

    if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
        return;
    }

    foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
        $class = '';

        if ( ! empty( $field['args']['class'] ) ) {
            $class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
        }

        if ( ! empty( $field['args']['label_for'] ) ) {
            // echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label>';
        } else {
            // echo $field['title'];
        }

        call_user_func( $field['callback'], $field['args'] );
    }
}

/**
 * Prints out all settings sections added to a particular settings page.
 *
 * Part of the Settings API. Use this in a settings page callback function
 * to output all the sections and fields that were added to that $page with
 * add_settings_section() and add_settings_field()
 *
 * @since 2.7.0
 *
 * @global array $wp_settings_sections Storage array of all settings sections added to admin pages.
 * @global array $wp_settings_fields Storage array of settings fields and info about their pages/sections.
 *
 * @param string $page The slug name of the page whose settings sections you want to output.
 */
function albums_do_settings_sections( $page ) {
    global $wp_settings_sections, $wp_settings_fields;

    if ( ! isset( $wp_settings_sections[ $page ] ) ) {
        return;
    }

    foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
        if ( '' !== $section['before_section'] ) {
            if ( '' !== $section['section_class'] ) {
                echo wp_kses_post( sprintf( $section['before_section'], esc_attr( $section['section_class'] ) ) );
            } else {
                echo wp_kses_post( $section['before_section'] );
            }
        }

        if ( $section['title'] ) {
            $unique_id = wp_unique_id( 'wp-settings-section-' . $section['id'] . '-' );
            echo '<h2 id="' . esc_attr( $unique_id ) . '">' . $section['title'] . "</h2>\n";
        }

        if ( $section['callback'] ) {
            call_user_func( $section['callback'], $section );
        }

        if ( isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
            // echo '<div class="robin-row">';
            custom_do_settings_fields( $page, $section['id'] );
            // echo '</div>';
        }

        if ( '' !== $section['after_section'] ) {
            echo wp_kses_post( $section['after_section'] );
        }
    }
}
