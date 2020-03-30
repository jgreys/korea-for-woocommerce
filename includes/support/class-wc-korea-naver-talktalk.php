<?php
/**
 * WooCommerce Korea - Naver TalkTalk
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Naver_TalkTalk {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		if ( ! isset($this->settings['navertalktalk_yn']) || 'yes' !== $this->settings['navertalktalk_yn'] ) {
			return;
		}

		$this->pc_productkey     = isset($this->settings['navertalktalk_pc_productkey']) && !empty($this->settings['navertalktalk_pc_productkey']) ?: '';
		$this->mobile_productkey = isset($this->settings['navertalktalk_mobile_productkey']) && !empty($this->settings['navertalktalk_mobile_productkey']) ?: '';

		// Load JS Library
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		// Shortcodes
		add_shortcode( 'navertalktalk', array($this, 'shortcode_button') );

		// Add Naver TalkTalk snippet
		add_action( 'wp_footer', array($this, 'add_navertalktalk_snippet') );
	}

	/**
     * Load payment scripts.
     */
	public function frontend_scripts() {
		wp_enqueue_script( 'wc-korea-naver-talktalk', 'https://partner.talk.naver.com/banners/script', [], WC_KOREA_VERSION );
	}

	public function add_navertalktalk_snippet() {
		global $post;

		$id  = wp_is_mobile() ? $this->mobile_product_key : $this->pc_product_key;
		$ref = urlencode(get_permalink($post->ID));
		
		ob_start(); ?>
		<style type="text/css">
			#navertalktalk-button {
				position: fixed;
				z-index : 9999;
				bottom  : 20px;
				right   : 30px;
			}
		</style>
		<div id="navertalktalk-button" class="talk_banner_div" data-id="<?php echo esc_attr($this->settings['navertalktalk_id']); ?>" data-ref="<?php echo urlencode(get_permalink($post->ID)); ?>"></div>
		<?php echo ob_get_clean();
	}

	public function shortcode_button() {
		$id  = wp_is_mobile() ? $this->mobile_productkey : $this->pc_productkey;
		$ref = urlencode(get_permalink($post->ID));
		
		ob_start(); ?>
		<style type="text/css">
			#navertalktalk-button {
				position: fixed;
				z-index : 9999;
				bottom  : 20px;
				right   : 30px;
			}
		</style>
		<div id="navertalktalk-button" class="talk_banner_div" data-id="<?php echo esc_attr($id); ?>" data-ref="<?php echo urlencode(get_permalink($post->ID)); ?>"></div>
		<?php echo ob_get_clean();
	}

}

new WC_Korea_Naver_TalkTalk();