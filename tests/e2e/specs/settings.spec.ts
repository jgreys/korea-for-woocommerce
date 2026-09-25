/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';

const SETTINGS_URL = '/wp-admin/admin.php?page=wc-settings&tab=integration&section=korea';

test.describe( 'Korea for WooCommerce settings', () => {
	test( 'the settings page loads without PHP errors', async ( { page } ) => {
		const missingAssets: string[] = [];
		page.on( 'response', ( response ) => {
			if ( response.status() >= 400 && response.url().includes( '/plugins/korea-for-woocommerce/' ) ) {
				missingAssets.push( response.url() );
			}
		} );

		await page.goto( SETTINGS_URL );
		await page.waitForLoadState( 'networkidle' );

		expect( missingAssets ).toEqual( [] );

		await expect( page.locator( '#woocommerce_korea_postcode_yn' ) ).toBeVisible();
		await expect( page.locator( 'body' ) ).not.toContainText( /(Fatal error|Warning|Notice|Deprecated):/ );
	} );

	test( 'postcode options follow the postcode checkbox', async ( { page } ) => {
		await page.goto( SETTINGS_URL );

		const postcode = page.locator( '#woocommerce_korea_postcode_yn' );
		const option = page.locator( 'tr' ).filter( { has: page.locator( '.show_if_postcode' ) } ).first();

		await postcode.setChecked( true );
		await expect( option ).toBeVisible();

		await postcode.setChecked( false );
		await expect( option ).toBeHidden();
	} );

	test( 'can update the postcode settings', async ( { page } ) => {
		await page.goto( SETTINGS_URL );

		const postcode = page.locator( '#woocommerce_korea_postcode_yn' );
		const wasChecked = await postcode.isChecked();

		await postcode.setChecked( ! wasChecked );
		await page.locator( '.woocommerce-save-button' ).click();

		await expect( page.locator( '#message.updated' ) ).toBeVisible();
		await expect( postcode ).toBeChecked( { checked: ! wasChecked } );

		// Restore the setting.
		await postcode.setChecked( wasChecked );
		await page.locator( '.woocommerce-save-button' ).click();
		await expect( page.locator( '#message.updated' ) ).toBeVisible();
	} );
} );
