<?php
/**
 * WooCommerce Korea - Admin
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use const Greys\WooCommerce\Korea\PluginPath as PLUGIN_PATH;
use const Greys\WooCommerce\Korea\PluginUrl as PLUGIN_URL;
use Greys\WooCommerce\Korea\Admin\Settings\Controller as SettingsController;

/**
 * Controller class.
 */
class Controller {

    public function __construct() {
        // Enqueue scripts and styles.
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Set up the admin menu.
        add_action( 'admin_menu', [ $this, 'settings_menu' ], 90 );

        // Handle saving settings earlier than load-{page} hook to avoid race conditions in conditional menus.
        add_action( 'wp_loaded', [ $this, 'save_settings' ] );

        // Add the settings page to the WooCommerce menu.
        wc_admin_connect_page(
            [
                'id'        => 'koreakit',
                'screen_id' => 'woocommerce_page_wc-koreakit-settings',
                'title'     => __( 'KoreaKit', 'korea-for-woocommerce' ),
            ]
        );
    }

    /**
     * Enqueue scripts and styles.
     *
     * @param string $hook Hook suffix for the current admin page.
     */
    public function enqueue_scripts( $hook ) {
        $admin_asset_file = PLUGIN_PATH . '/assets/client/admin/index.asset.php';
        if ( file_exists( $admin_asset_file ) ) {
            $admin_asset = require $admin_asset_file;
            wp_enqueue_script( 'wc-koreakit-admin', PLUGIN_URL . '/assets/client/admin/index.js', $admin_asset['dependencies'], $admin_asset['version'], true );
            wp_enqueue_style( 'wc-koreakit-admin', PLUGIN_URL . '/assets/client/admin/style.css', false, $admin_asset['version'] );
        }
    }

    /**
     * Add menu item.
     */
    public function settings_menu() {
        $settings_page = add_submenu_page( 'woocommerce', __( 'KoreaKit', 'korea-for-woocommerce' ), __( 'KoreaKit', 'korea-for-woocommerce' ), 'manage_woocommerce', 'wc-koreakit-settings', array( $this, 'settings_page' ) );

        add_action( 'load-' . $settings_page, [ $this, 'settings_page_init' ] );
    }

    /**
     * Init the settings page.
     */
    public function settings_page() {
        SettingsController::output();
    }

    /**
     * Loads gateways and shipping methods into memory for use within settings.
     */
    public function settings_page_init() {
        // Include settings pages.
        SettingsController::get_settings_pages();

        // Add any posted messages.
        if ( ! empty( $_GET['wc_error'] ) ) { // WPCS: input var okay, CSRF ok.
            SettingsController::add_error( wp_kses_post( wp_unslash( $_GET['wc_error'] ) ) ); // WPCS: input var okay, CSRF ok.
        }

        if ( ! empty( $_GET['wc_message'] ) ) { // WPCS: input var okay, CSRF ok.
            SettingsController::add_message( wp_kses_post( wp_unslash( $_GET['wc_message'] ) ) ); // WPCS: input var okay, CSRF ok.
        }

        do_action( 'woocommerce_koreakit_settings_page_init' );
    }

    /**
     * Handle saving of settings.
     *
     * @return void
     */
    public function save_settings() {
        global $current_tab, $current_section;

        // We should only save on the settings page.
        if ( ! is_admin() || ! isset( $_GET['page'] ) || 'wc-koreakit-settings' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        // Include settings pages.
        SettingsController::get_settings_pages();

        // Get current tab/section.
        $current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( wp_unslash( $_GET['tab'] ) ); // WPCS: input var okay, CSRF ok.
        $current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( wp_unslash( $_REQUEST['section'] ) ); // WPCS: input var okay, CSRF ok.

        // Save settings if data has been posted.
        if ( '' !== $current_section && apply_filters( "woocommerce_koreakit_save_settings_{$current_tab}_{$current_section}", ! empty( $_POST['save'] ) ) ) { // WPCS: input var okay, CSRF ok.
            SettingsController::save();
        } elseif ( '' === $current_section && apply_filters( "woocommerce_koreakit_save_settings_{$current_tab}", ! empty( $_POST['save'] ) ) ) { // WPCS: input var okay, CSRF ok.
            SettingsController::save();
        }
    }

}
