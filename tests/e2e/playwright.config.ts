/**
 * External dependencies
 */
import { defineConfig } from '@playwright/test';

/**
 * WordPress dependencies
 */
const baseConfig = require( '@wordpress/scripts/config/playwright.config.js' );

export default defineConfig( {
	...baseConfig,
	testDir: './specs',
	webServer: {
		...baseConfig.webServer,
		command: 'npm run env:start',
	},
} );
