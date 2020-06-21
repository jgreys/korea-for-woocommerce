<?php
/**
 * WooCommerce Korea - Daum SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Daum_SEP class.
 */
class WC_Korea_Daum_SEP {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->settings = get_option( 'woocommerce_korea_settings' );

		if ( ! isset( $this->settings['daum_shopping_ep'] ) || 'yes' !== $this->settings['daum_shopping_ep'] ) {
			return;
		}

		add_action( 'template_include', array( $this, 'template_include' ) );
	}

	/**
	 * Daum SEP output
	 *
	 * @param  string $original_template Original template.
	 * @return string
	 */
	public function template_include( $original_template ) {
		$wc_sep = get_query_var( 'wc-sep' );

		if ( ! $wc_sep ) {
			return $original_template;
		}

		if ( 'daum' !== $wc_sep ) {
			return $original_template;
		}

		$products = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => -1,
			)
		);

		if ( ! $products->have_posts() ) {
			return;
		}

		ob_start();

		while ( $products->have_posts() ) {
			$products->the_post();

			global $product;

			if ( empty( $product ) || ! $product->is_visible() ) {
				return;
			}

			$categories = get_the_terms( get_the_ID(), 'product_cat' );
			foreach ( $categories as $category ) {
				$category_id   = $category->ID;
				$category_name = $category->name;
			}

			$lt    = '<<<';
			$gt    = '>>>';
			$class = 'U';

			/**
			 * Verify with variations
			 */
			if ( $product->get_stock_quantity() === 0 ) {
				$class = 'D';
			}

			$values   = array();
			$values[] = '<<<begin>>>';
			$values[] = '<<<mapid>>>' . intval( get_the_ID() );
			$values[] = '<<<price>>>' . esc_html( get_post_meta( get_the_ID(), '_regular_price', true ) );
			$values[] = '<<<class>>>U';
			$values[] = '<<<utime>>>' . esc_html( get_the_modified_date( 'H:i:s' ) );
			$values[] = '<<<pname>>>' . esc_html( get_the_title() );
			$values[] = '<<<pgurl>>>' . esc_url( get_the_permalink() );
			$values[] = '<<<igurl>>>' . esc_url( get_the_post_thumbnail_url( get_the_ID() ) );
			$values[] = '<<<ftend>>>';

			$i = 1;
			foreach ( $categories as $category ) {
				$values[] = '<<<cate' . $i . '>>>' . intval( $category->ID );
				$values[] = '<<<caid' . $i . '>>>' . esc_html( $category->name );
				++$i;
			}

			$values[] = '<<<deliv>>>0';

			echo implode( PHP_OEL, $values ) . PHP_OEL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

}

return new WC_Korea_Daum_SEP();
