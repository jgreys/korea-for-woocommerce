<?php
/**
 * Healy Support Center - Settings
 *
 * @package WooCommerce\Admin
 */

namespace Greys\WooCommerce\Korea\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use const Greys\WooCommerce\Korea\Basename as BASENAME;
use Greys\WooCommerce\Korea\Abstracts\SettingsPage;

/**
 * Addons class.
 */
class Addons extends SettingsPage {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'addons';
        $this->label = __( 'Addons', 'korea-for-woocommerce' );

        add_action( 'woocommerce_koreakit_admin_field_addons', array( $this, 'addons_field' ) );

        parent::__construct();
    }

    /**
     * Get own sections.
     *
     * @return array
     */
    protected function get_own_sections() {
        $sections = array(
            '' => __( 'Addons', 'korea-for-woocommerce' )
        );

        return $sections;
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    protected function get_settings_for_default_section() {
        $settings =
            array(
                array(
                    'id'    => 'woocommerce_koreakit_addons_settings',
                    'title' => __( 'Addons', 'korea-for-woocommerce' ),
                    'type'  => 'title',
                ),

                array( 'type' => 'addons' ),

                array(
                    'type' => 'sectionend',
                    'id'   => 'woocommerce_koreakit_addons_settings',
                ),
            );

        return apply_filters( 'woocommerce_koreakit_addons_settings', $settings );
    }

    /**
     * Output the settings.
     */
    public function output() {
        global $hide_save_button;

        $hide_save_button = true;
        parent::output();
    }

    /**
     * Outputs addons.
     */
    public function addons_field() {
        $addons = apply_filters(
            'woocommerce_koreakit_addons',
            array(
                'woocommerce-gateway-inicis' => [
                    'available'   => false,
                    'title'       => __( 'KG INICIS Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'KG INICIS', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-inicis/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-kcp' => [
                    'available'   => false,
                    'title'       => __( 'NHN KCP Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'NHN KCP', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kcp/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-lguplus' => [
                    'available'   => false,
                    'title'       => __( 'LG U+ Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'LG U+', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-lguplus/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-easypay' => [
                    'available'   => false,
                    'title'       => __( 'EasyPay Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'EasyPay', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-easypay/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-nicepay' => [
                    'available'   => false,
                    'title'       => __( 'NICEPAY Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'NICEPAY', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-nicepay/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-kakaopay' => [
                    'available'   => false,
                    'title'       => __( 'KakaoPay Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'KakaoPay', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kakaopay/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-naverpay' => [
                    'available'   => false,
                    'title'       => __( 'NaverPay Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'NaverPay', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-naverpay/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-payco' => [
                    'available'   => false,
                    'title'       => __( 'Payco Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'Payco', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-payco/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-gateway-tosspay' => [
                    'available'   => false,
                    'title'       => __( 'TossPay Gateway', 'korea-for-woocommerce' ),
                    'description' => sprintf(
                        /* translators: 1) plugin description, 2) plugin name */
                        __( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
                        __( 'TossPay', 'korea-for-woocommerce' )
                    ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-tosspay/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-kakaotalk-notifications' => [
                    'available'   => false,
                    'title'       => __( 'KakaoTalk Notifications', 'korea-for-woocommerce' ),
                    'description' => __( 'Send KakaoTalk order notifications to admins and customers for your WooCommerce store.', 'korea-for-woocommerce' ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-kakaotalk-notifications/', 'korea-for-woocommerce' ),
                    'price'       => __( 'Free', 'korea-for-woocommerce' ),
                ],
                'woocommerce-kras' => [
                    'available'   => true,
                    'title'       => __( 'Korean Authentication Services', 'korea-for-woocommerce' ),
                    'description' => __( 'Korean Authentification Services (i-PIN, mobile phone) for WooCommerce', 'korea-for-woocommerce' ),
                    'permalink'   => __( 'https://greys.co/plugin/woocommerce-kras/', 'korea-for-woocommerce' ),
                    'price'       => __( '$249', 'korea-for-woocommerce' ),
                ],
            )
        );

        ?>
        <div class="wc_koreakit_addons_wrap">
            <?php foreach ( $addons as $id => $addon ) { ?>
                <div class="addon">
                    <h2 class="title"><?php echo wp_kses_post( $addon['title'] ); ?></h2>
                    <?php if ( is_plugin_active( "{$id}/{$id}.php" ) ) { ?>
                        <p class="desc">Your license key expires soon! It expires on %1$s. %2$sRenew your license key%3$s.</p>
                    <?php } else { ?>
                        <p class="desc"><?php echo wp_kses_post( $addon['description'] ); ?></p>
                    <?php } ?>
                    <div class="bottom">
                        <?php if ( is_plugin_active( "{$id}/{$id}.php" ) ) { ?>
                            <div class="cta">
                                <a href="#"><?php esc_html_e( 'Deactivate', 'korea-for-woocommerce' ); ?></a>
                            </div>
                        <?php } else { ?>
                            <?php if ( 'Free' !== $addon['price'] ) { ?>
                                <div class="price">
                                    <span>From <?php echo $addon['price']; ?></span>
                                </div>
                            <?php } else { ?>
                                <div class="price">
                                    <span><?php echo $addon['price']; ?></span>
                                </div>
                            <?php } ?>
                            <?php if ( $addon['available'] ) { ?>
                                <div class="cta">
                                    <a href="mailto:contact@greys.co" class="button button-primary"><?php esc_html_e( 'Contact us', 'korea-for-woocommerce' ); ?></a>
                                </div>
                            <?php } else { ?>
                                <div class="cta">
                                    <span class="button button-secondary"><?php esc_html_e( 'Coming Soon', 'korea-for-woocommerce' ); ?></span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

}
