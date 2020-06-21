<?php
/**
 * WooCommerce Korea - Postcode Finder
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Postcode class.
 */
class WC_Korea_Postcode {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->settings = get_option( 'woocommerce_korea_settings' );

		if ( ! isset( $this->settings['postcode_yn'] ) || ! wc_string_to_bool( $this->settings['postcode_yn'] ) ) {
			return;
		}

		$this->overlay            = isset( $settings['postcode_displaymode'] ) && ! empty( $settings['postcode_displaymode'] ) ? $settings['postcode_displaymode'] : 'overlay';
		$this->bgcolor            = isset( $settings['postcode_bgcolor'] ) && ! empty( $settings['postcode_bgcolor'] ) ? $settings['postcode_bgcolor'] : '#ececec';
		$this->searchbgcolor      = isset( $settings['postcode_searchbgcolor'] ) && ! empty( $settings['postcode_searchbgcolor'] ) ? $settings['postcode_searchbgcolor'] : '#ffffff';
		$this->contentbgcolor     = isset( $settings['postcode_contentbgcolor'] ) && ! empty( $settings['postcode_contentbgcolor'] ) ? $settings['postcode_contentbgcolor'] : '#ffffff';
		$this->pagebgcolor        = isset( $settings['postcode_pagebgcolor'] ) && ! empty( $settings['postcode_pagebgcolor'] ) ? $settings['postcode_pagebgcolor'] : '#fafafa';
		$this->textcolor          = isset( $settings['postcode_textcolor'] ) && ! empty( $settings['postcode_textcolor'] ) ? $settings['postcode_textcolor'] : '#333333';
		$this->querytxtcolor      = isset( $settings['postcode_querytxtcolor'] ) && ! empty( $settings['postcode_querytxtcolor'] ) ? $settings['postcode_querytxtcolor'] : '#222222';
		$this->postalcodetxtcolor = isset( $settings['postcode_postalcodetxtcolor'] ) && ! empty( $settings['postcode_postalcodetxtcolor'] ) ? $settings['postcode_postalcodetxtcolor'] : '#fa4256';
		$this->emphtxtcolor       = isset( $settings['postcode_emphtxtcolor'] ) && ! empty( $settings['postcode_emphtxtcolor'] ) ? $settings['postcode_emphtxtcolor'] : '#008bd3';
		$this->outlinecolor       = isset( $settings['postcode_outlinecolor'] ) && ! empty( $settings['postcode_outlinecolor'] ) ? $settings['postcode_outlinecolor'] : '#e0e0e0';

		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'woocommerce_get_country_locale', array( $this, 'wc_get_country_locale' ) );
	}

	/**
	 * Add inline styling in the footer
	 */
	public function wp_footer() {
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}
		?>
		<style type="text/css">
			#postcode_form.overlay {
				position: fixed;
				width: 100%;
				height: 100%;
				border: 1px solid #e7e7e7;
				top: 0;
				left: 0;
				z-index: 99998;
			}

			#postcode_form.embed {
				position: relative;
				width: 100%;
				height: 395px;
				border: 1px solid #e7e7e7;
			}
		</style>
		<?php
	}

	/**
	 * Enqueue Daum Postcode + Korea for WooCommerce Postcode scripts
	 */
	public function wp_enqueue_scripts() {
		// We do not enqueue the script if it's not required.
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-daum-postcode', 'https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js', array(), WC_KOREA_VERSION, true );
		wp_enqueue_script( 'wc-korea-postcode', WC_KOREA_PLUGIN_URL . '/assets/js/wc-korea-postcode.js', array(), WC_KOREA_VERSION, true );
		wp_localize_script(
			'wc-korea-postcode',
			'_postcode',
			array(
				'theme' => array(
					'displaymode'        => $this->displaymode,
					'bgcolor'            => $this->bgcolor,
					'searchbgcolor'      => $this->searchbgcolor,
					'contentbgcolor'     => $this->contentbgcolor,
					'pagebgcolor'        => $this->pagebgcolor,
					'textcolor'          => $this->textcolor,
					'querytxtcolor'      => $this->querytxtcolor,
					'postalcodetxtcolor' => $this->postalcodetxtcolor,
					'emphtxtcolor'       => $this->emphtxtcolor,
					'outlinecolor'       => $this->outlinecolor,
				),
			)
		);
	}

	/**
	 * Change priority & requirement for korean checkout fields
	 *
	 * @param array $fields Checkout fields.
	 */
	public function wc_get_country_locale( $fields ) {
		$fields['KR']['postcode']['priority'] = 40;
		$fields['KR']['postcode']['required'] = true;
		$fields['KR']['country']['priority']  = 30;

		return $fields;
	}

}

return new WC_Korea_Postcode();
