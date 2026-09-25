<?php
/**
 * Order View - Virtual Bank Information
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-vbank.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.1.0
 *
 * @var array $account_fields Fields to display, each with a label and a value.
 */

defined( 'ABSPATH' ) || exit;

$account_fields = array_filter(
	(array) $account_fields,
	function ( $field ) {
		return ! empty( $field['value'] );
	}
);

if ( empty( $account_fields ) ) {
	return;
}

wp_enqueue_style( 'wc-korea-order-vbank' );
?>
<section class="woocommerce-vbank-details">
	<h2 class="woocommerce-vbank-details__title"><?php esc_html_e( 'Virtual Bank details', 'korea-for-woocommerce' ); ?></h2>
	<dl class="woocommerce-vbank-details__list">
		<?php foreach ( $account_fields as $field_key => $field ) : ?>
			<div class="woocommerce-vbank-details__item woocommerce-vbank-details__item--<?php echo esc_attr( $field_key ); ?>">
				<dt class="woocommerce-vbank-details__label"><?php echo wp_kses_post( wptexturize( $field['label'] ) ); ?></dt>
				<dd class="woocommerce-vbank-details__value"><?php echo wp_kses_post( wptexturize( $field['value'] ) ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
</section>
