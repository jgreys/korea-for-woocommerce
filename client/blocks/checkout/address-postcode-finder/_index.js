import { render } from '@wordpress/element';
import AddressFields from './address-fields';

/**
 * Renders the React component once billing/shipping fields are detected.
 */
const renderAddressFields = (type) => {
    const container = document.createElement('div');
    document.body.appendChild(container);
    render(<AddressFields type={type} />, container);
};

// MutationObserver to detect billing/shipping fields being added to the DOM
const observer = new MutationObserver(() => {
    const billingCountry = document.getElementById('billing-country');
    const shippingCountry = document.getElementById('shipping-country');

    if (billingCountry) {
        renderAddressFields('billing');
    }

    if (shippingCountry) {
        renderAddressFields('shipping');
    }

    // Stop observing once fields are found
    if (billingCountry || shippingCountry) {
        observer.disconnect();
    }
});

// Start observing the DOM for changes
observer.observe(document.body, { childList: true, subtree: true });
