<?php
/**
 * Plugin Name: Forminator Email Domain Blocker
 * Description: Block specific email domains in Forminator forms with an admin-managed list. Targets field name "email-1".
 * Version: 1.1.1
 * Author: Noushad Nipun
 * License: GPL-2.0-or-later
 * Text Domain: forminator-email-domain-blocker
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FEDB_Plugin {
    const OPTION_KEY = 'forminator_blocked_domains';
    const TEXT_DOMAIN = 'forminator-email-domain-blocker';
    const FIELD_NAME  = 'email-1';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_filter( 'forminator_custom_form_submit_errors', [ $this, 'validate_blocked_domains' ], 10, 3 );

        // Ensure option exists
        add_action( 'plugins_loaded', function() {
            if ( get_option( self::OPTION_KEY ) === false ) {
                add_option( self::OPTION_KEY, '' );
            }
        } );
    }

    public function add_settings_page() {
        add_options_page(
            __( 'Forminator Email Blocker', self::TEXT_DOMAIN ),
            __( 'Forminator Email Blocker', self::TEXT_DOMAIN ),
            'manage_options',
            'forminator-email-blocker',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting(
            'fedb_settings_group',
            self::OPTION_KEY,
            [
                'type'              => 'string',
                'sanitize_callback' => [ $this, 'sanitize_domains' ],
                'default'           => '',
            ]
        );

        add_settings_section(
            'fedb_main_section',
            __( 'Blocked domains', self::TEXT_DOMAIN ),
            function() {
                echo '<p>' . esc_html__( 'Enter one domain per line. Example: example.com', self::TEXT_DOMAIN ) . '</p>';
            },
            'fedb_settings_page'
        );

        add_settings_field(
            'fedb_domains_field',
            __( 'Domain list', self::TEXT_DOMAIN ),
            [ $this, 'render_domains_field' ],
            'fedb_settings_page',
            'fedb_main_section'
        );
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Forminator Email Domain Blocker', self::TEXT_DOMAIN ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'fedb_settings_group' );
                do_settings_sections( 'fedb_settings_page' );
                submit_button( __( 'Save Changes', self::TEXT_DOMAIN ) );
                ?>
            </form>
            <hr />
            <h2><?php echo esc_html__( 'How it works', self::TEXT_DOMAIN ); ?></h2>
            <ul>
                <li><strong><?php echo esc_html__( 'Target field:', self::TEXT_DOMAIN ); ?></strong> <?php echo esc_html( self::FIELD_NAME ); ?></li>
                <li><strong><?php echo esc_html__( 'Match rule:', self::TEXT_DOMAIN ); ?></strong> <?php echo esc_html__( 'Exact domain match (case-insensitive).', self::TEXT_DOMAIN ); ?></li>
                <li><strong><?php echo esc_html__( 'Example:', self::TEXT_DOMAIN ); ?></strong> <?php echo esc_html__( 'Blocking example.com will block any email like user@example.com.', self::TEXT_DOMAIN ); ?></li>
            </ul>
        </div>
        <?php
    }

    public function render_domains_field() {
        $value = get_option( self::OPTION_KEY, '' );
        printf(
            '<textarea name="%1$s" rows="10" cols="60" placeholder="example.com&#10;test.com">%2$s</textarea>',
            esc_attr( self::OPTION_KEY ),
            esc_textarea( $value )
        );
    }

    /**
     * Sanitize domains: normalize, strip protocols/whitespace, remove invalid lines.
     */
    public function sanitize_domains( $raw ) {
        if ( ! is_string( $raw ) ) {
            return '';
        }

        // Safer newline split (avoid \R for wider PCRE compatibility)
        $lines   = preg_split( "/\r\n|\r|\n/", $raw );
        $cleaned = [];

        foreach ( $lines as $line ) {
            $line = trim( strtolower( $line ) );

            if ( $line === '' ) {
                continue;
            }

            // Remove protocol and path if pasted accidentally
            $line = preg_replace( '#^https?://#', '', $line );
            $line = preg_replace( '#/.*$#', '', $line );

            // Remove leading @ if provided
            $line = ltrim( $line, '@' );

            // Basic domain validation (no spaces, contains a dot)
            if ( preg_match( '/^[a-z0-9.-]+\.[a-z]{2,}$/', $line ) ) {
                $cleaned[] = $line;
            }
        }

        // Unique + join back to lines
        $cleaned = array_values( array_unique( $cleaned ) );
        return implode( "\n", $cleaned );
    }

    /**
     * Forminator validation: block submissions if email domain is in the admin list.
     */
    public function validate_blocked_domains( $submit_errors, $form_id, $field_data_array ) {
        $blocked_raw = get_option( self::OPTION_KEY, '' );
        if ( empty( $blocked_raw ) ) {
            return $submit_errors;
        }

        // Safer newline split (avoid \R)
        $blocked = array_filter( array_map( 'trim', preg_split( "/\r\n|\r|\n/", strtolower( $blocked_raw ) ) ) );

        foreach ( $field_data_array as $field ) {
            if ( isset( $field['name'] ) && $field['name'] === self::FIELD_NAME ) {
                $email = sanitize_email( isset( $field['value'] ) ? $field['value'] : '' );
                if ( empty( $email ) || strpos( $email, '@' ) === false ) {
                    // Let Forminator handle empty/invalid email; we only care about domain match
                    continue;
                }

                $domain = strtolower( substr( strrchr( $email, '@' ), 1 ) );

                if ( in_array( $domain, $blocked, true ) ) {
                    $submit_errors[ self::FIELD_NAME ] = __( 'This email domain is not allowed.', self::TEXT_DOMAIN );
                }
            }
        }

        return $submit_errors;
    }
}

new FEDB_Plugin();


/**
 * Change the global invalid form message
 */
add_filter( 'forminator_custom_form_invalid_form_message', 'fedb_custom_invalid_message', 10, 2 );

function fedb_custom_invalid_message( $message, $form_id ) {
    // Custom global error message
    return __( 'Submission blocked: Email domain is not allowed.', 'forminator-email-domain-blocker' );
}


/**
 * Cleanup on uninstall: remove option.
 * Use a named function to avoid issues with anonymous callbacks.
 */
if ( function_exists( 'register_uninstall_hook' ) ) {
    register_uninstall_hook( __FILE__, 'fedb_uninstall' );
}

function fedb_uninstall() {
    delete_option( 'forminator_blocked_domains' );
}