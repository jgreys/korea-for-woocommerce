<?php
/**
 * WooCommerce Korea - Naver Analytics
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Naver_Analytics {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		if ( ! isset($this->settings['woocommerce_korea_naver_analytics']) || empty($this->settings['woocommerce_korea_naver_analytics']) ) {
			return;
		}

		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		add_action('wp_footer', array($this, 'wp_footer'));
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_script('wc-korea-naver-analytics', '//wcs.naver.net/wcslog.js', null, WC_KOREA_VERSION);
	}

	public function wp_footer() {
		ob_start(); ?>
		<script type="text/javascript">
			if ( !wcs_add ) {
				var wcs_add = {};
			}
			wcs_add["wa"] = "<?php echo $this->settings['woocommerce_korea_naver_analytics']; ?>";
			wcs_do();
		</script>
		<?php echo ob_get_clean();
	}

}

return new WC_Korea_Naver_Analytics();