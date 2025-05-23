<?php
/**
 * WooCommerce Korea - Postcode Finder
 */

namespace Greys\WooCommerce\Korea\Checkout;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils;
use const Greys\WooCommerce\Korea\MainFile as MAIN_FILE;
use const Greys\WooCommerce\Korea\PluginUrl as PLUGIN_URL;
use const Greys\WooCommerce\Korea\PluginPath as PLUGIN_PATH;
use const Greys\WooCommerce\Korea\Basename as BASENAME;
use const Greys\WooCommerce\Korea\Version as VERSION;


/**
 * Postcode class.
 */
class Postcode {

    /**
     * Is enabled?
     *
     * @var bool
     */
    private $enabled;

    /**
     * Display mode
     *
     * @var string
     */
    private $displaymode;

    /**
     * Background color
     *
     * @var string
     */
    private $bgcolor;

    /**
     * Search background color
     *
     * @var string
     */
    private $searchbgcolor;

    /**
     * Content background color
     *
     * @var string
     */
    private $contentbgcolor;

    /**
     * Page background color
     *
     * @var string
     */
    private $pagebgcolor;

    /**
     * Text color
     *
     * @var string
     */

    private $textcolor;

    /**
     * Query text color
     *
     * @var string
     */
    private $querytxtcolor;

    /**
     * Postal code text color
     *
     * @var string
     */
    private $postalcodetxtcolor;

    /**
     * Emphasis text color
     *
     * @var string
     */
    private $emphtxtcolor;

    /**
     * Outline color
     *
     * @var string
     */
    private $outlinecolor;

    /**
     * Initialize
     */
    public function __construct() {
        $settings      = get_option( 'woocommerce_korea_settings' );
        $this->enabled = isset( $settings['postcode_yn'] ) && ! empty( $settings['postcode_yn'] ) ? 'yes' === $settings['postcode_yn'] : false;

        $this->displaymode        = isset( $settings['postcode_displaymode'] ) && ! empty( $settings['postcode_displaymode'] ) ? $settings['postcode_displaymode'] : 'overlay';
        $this->bgcolor            = isset( $settings['postcode_bgcolor'] ) && ! empty( $settings['postcode_bgcolor'] ) ? $settings['postcode_bgcolor'] : '#ececec';
        $this->searchbgcolor      = isset( $settings['postcode_searchbgcolor'] ) && ! empty( $settings['postcode_searchbgcolor'] ) ? $settings['postcode_searchbgcolor'] : '#ffffff';
        $this->contentbgcolor     = isset( $settings['postcode_contentbgcolor'] ) && ! empty( $settings['postcode_contentbgcolor'] ) ? $settings['postcode_contentbgcolor'] : '#ffffff';
        $this->pagebgcolor        = isset( $settings['postcode_pagebgcolor'] ) && ! empty( $settings['postcode_pagebgcolor'] ) ? $settings['postcode_pagebgcolor'] : '#fafafa';
        $this->textcolor          = isset( $settings['postcode_textcolor'] ) && ! empty( $settings['postcode_textcolor'] ) ? $settings['postcode_textcolor'] : '#333333';
        $this->querytxtcolor      = isset( $settings['postcode_querytxtcolor'] ) && ! empty( $settings['postcode_querytxtcolor'] ) ? $settings['postcode_querytxtcolor'] : '#222222';
        $this->postalcodetxtcolor = isset( $settings['postcode_postalcodetxtcolor'] ) && ! empty( $settings['postcode_postalcodetxtcolor'] ) ? $settings['postcode_postalcodetxtcolor'] : '#fa4256';
        $this->emphtxtcolor       = isset( $settings['postcode_emphtxtcolor'] ) && ! empty( $settings['postcode_emphtxtcolor'] ) ? $settings['postcode_emphtxtcolor'] : '#008bd3';
        $this->outlinecolor       = isset( $settings['postcode_outlinecolor'] ) && ! empty( $settings['postcode_outlinecolor'] ) ? $settings['postcode_outlinecolor'] : '#e0e0e0';

        //if ( ! $this->enabled ) {
        //    return;
        //}

        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 90 );
    }

    /**
     * Enqueue Daum Postcode + Korea for WooCommerce Postcode scripts
     */
    public function wp_enqueue_scripts() {
        // We do not enqueue the script if it's not required.
        if ( ! is_account_page() && ! is_checkout() ) {
            return;
        }

        // Enqueue Daum Postcode API
        wp_enqueue_script( 'wc-koreakit-daum-postcode', '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', array(), null, true );

        if ( CartCheckoutUtils::is_checkout_block_default() ) {
            $blocks_asset_file = PLUGIN_PATH . '/build/blocks.asset.php';
            if ( file_exists( $blocks_asset_file ) ) {
                $blocks_asset = require $blocks_asset_file;
                wp_enqueue_script( 'wc-koreakit-address-autocomplete', PLUGIN_URL . '/build/blocks.js', $blocks_asset['dependencies'], $blocks_asset['version'], true );
            }
        } else {
            wp_enqueue_script( 'wc-koreakit-address-autocomplete', PLUGIN_URL . '/build/classic.js', array( 'jquery' ), VERSION, true );
        }

        wp_localize_script(
            'wc-koreakit-address-autocomplete',
            'koreakitAddressAutocomplete',
            [
                'displaymode' => $this->displaymode,
                'theme'       => [
                    'bgColor'            => $this->bgcolor,
                    'searchBgColor'      => $this->searchbgcolor,
                    'contentBgColor'     => $this->contentbgcolor,
                    'pageBgColor'        => $this->pagebgcolor,
                    'textColor'          => $this->textcolor,
                    'queryTextColor'     => $this->querytxtcolor,
                    'postcodeTextColor'  => $this->postalcodetxtcolor,
                    'emphTextColor'      => $this->emphtxtcolor,
                    'outlineColor'       => $this->outlinecolor,
                ]
            ]
        );
    }
}
