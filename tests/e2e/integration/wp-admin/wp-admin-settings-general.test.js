describe( 'Korea for WooCommerce General Settings', function() {

	before(function() {
        cy.visit( '/wp-login.php' );
        cy.wait( 1000 );
        cy.get( '#user_login' ).type( Cypress.env( 'users' ).admin.username );
        cy.get( '#user_pass' ).type( Cypress.env( 'users' ).admin.password );
        cy.get( '#wp-submit' ).click();
    });

    it( 'can update postcode settings', function() {
		cy.visit( '/wp-admin/admin.php?page=wc-settings&tab=integration&section=korea' );

		cy.get( '#woocommerce_korea_postcode_yn' ).check();

		cy.get( '.woocommerce-save-button' ).click();

		cy.get( '#message' ).should( 'have.class', 'updated' );
	} );
} );
