import { __ } from '@wordpress/i18n';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect, useRef, useState } from '@wordpress/element';

if (!window.wp || !window.wp.element) {
    console.error('wp.element is not available. Ensure that React and ReactDOM are enqueued properly.');
}

// Accessing store keys from the global wcBlocksData
const { CART_STORE_KEY } = window.wc.wcBlocksData || {};
const ADDRESS_AUTOCOMPLETE_DATA = window.koreakitAddressAutocomplete || {};

/**
 * KoreanAddressAutocomplete Component
 * @param {Object} props
 * @param {'billing' | 'shipping'} props.addressType - Type of address to handle
 */
const KoreanAddressAutocomplete = ({ addressType }) => {
    const [showAddressAutocomplete, setShowAddressAutocomplete] = useState(false);

    const { setBillingAddress, setShippingAddress } = useDispatch(CART_STORE_KEY);
    const { billingAddress, shippingAddress } = useSelect((select) =>
        select(CART_STORE_KEY).getCustomerData() || {}
    );

    const koreanAddressAutocompleteRef = useRef(null);
    const countryRef = useRef(null);
    const postcodeRef = useRef(null);

    // Determine IDs based on addressType
    const countryId = `${addressType}-country`;
    const postcodeId = `${addressType}-postcode`;

    useEffect(() => {
        // Select the country and postcode input elements based on addressType
        countryRef.current = document.getElementById(countryId);
        postcodeRef.current = document.getElementById(postcodeId);

        if (!countryRef.current || !postcodeRef.current) {
            return;
        }

        // Handle changes to the country
        const onCountryChange = () => {
            if (countryRef.current.value !== 'KR') {
                postcodeRef.current.removeAttribute('readonly');
                postcodeRef.current.removeAttribute('onkeypress');
                setShowAddressAutocomplete(false);
            } else {
                // Make read-only if KR
                postcodeRef.current.setAttribute('readonly', 'readonly');
                postcodeRef.current.setAttribute('onkeypress', 'return false;');
            }
        };

        // Handle clicks on the postcode field
        const onPostcodeClick = () => {
            if (countryRef.current.value === 'KR') {
                setShowAddressAutocomplete(true);
            }
        };

        // Attach the event listeners
        countryRef.current.addEventListener('change', onCountryChange);
        postcodeRef.current.addEventListener('click', onPostcodeClick);

        // Fire an initial check in case the page loads with KR selected
        countryRef.current.dispatchEvent(new Event('change'));

        // Cleanup event listeners on component unmount
        return () => {
            if (countryRef.current && onCountryChange) {
                countryRef.current.removeEventListener('change', onCountryChange);
            }
            if (postcodeRef.current && onPostcodeClick) {
                postcodeRef.current.removeEventListener('click', onPostcodeClick);
            }
        };
    }, [countryId, postcodeId, addressType]);

    useEffect(() => {
        if (!showAddressAutocomplete || !koreanAddressAutocompleteRef.current) {
            return;
        }

        try {
            new daum.Postcode({
                alwaysShowEngAddr: true,
                hideEngBtn: false,
                theme: ADDRESS_AUTOCOMPLETE_DATA.theme || {},
                oncomplete: (data) => {
                    if (addressType === 'billing') {
                        setBillingAddress({
                            ...billingAddress,
                            postcode: data.zonecode,
                            address_1: data.address,
                            city: data.sido,
                        });
                    } else if (addressType === 'shipping') {
                        setShippingAddress({
                            ...shippingAddress,
                            postcode: data.zonecode,
                            address_1: data.address,
                            city: data.sido,
                        });
                    }

                    // Hide the autocomplete once a postcode is selected
                    setShowAddressAutocomplete(false);
                },
                width: '100%',
                height: '100%',
            }).embed(koreanAddressAutocompleteRef.current);
        } catch (error) {
            console.error(`Error embedding Daum Postcode for ${addressType}:`, error);
        }
    }, [
        showAddressAutocomplete,
        addressType,
        setBillingAddress,
        setShippingAddress,
        billingAddress,
        shippingAddress,
    ]);

    return showAddressAutocomplete ? (
        <div
            id={`${addressType}-address-autocomplete`}
            ref={koreanAddressAutocompleteRef}
            style={{ position: 'relative', width: '100%', height: '350px' }}
        >
            <svg
                onClick={() => setShowAddressAutocomplete(false)}
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="24"
                height="24"
                style={{
                    cursor: 'pointer',
                    position: 'absolute',
                    right: 0,
                    top: '-1px',
                    zIndex: 1,
                    backgroundColor: '#000',
                    color: '#fff',
                }}
                role="img"
                aria-label={__('Close postcode finder', 'korea-for-woocommerce')}
            >
                <path
                    fill="currentColor"
                    d="M18.3 5.71a1 1 0 0 0-1.42 0L12 10.59
                    7.11 5.7a1 1 0 1 0-1.42 1.42L10.59 12l-4.9
                    4.9a1 1 0 1 0 1.42 1.4L12 13.41l4.9
                    4.9a1 1 0 0 0 1.4-1.42L13.41 12l4.9-4.9a1
                    1 0 0 0 0-1.42z"
                />
            </svg>
        </div>
    ) : null;
};

/**
 * Function to render the KoreanAddressAutocomplete component
 * @param {'billing' | 'shipping'} addressType - Type of address to handle
 */
const renderAddressAutocomplete = (addressType) => {
    const postcodeId = `${addressType}-postcode`;
    const postcodeEl = document.getElementById(postcodeId);

    if (!postcodeEl) {
        return;
    }

    // Check if a container already exists for this address type
    const existingContainer = document.querySelector(`#koreakit-address-autocomplete-${addressType}`);
    if (existingContainer) {
        return;
    }

    // Create a unique container
    const container = document.createElement('div');
    container.id = `koreakit-address-autocomplete-${addressType}`;
    container.className = `koreakit-address-autocomplete`;
    postcodeEl.parentNode.appendChild(container);

    // Render the KoreanAddressAutocomplete component using wp.element.render
    wp.element.render(<KoreanAddressAutocomplete addressType={addressType} />, container);
};

/**
 * Initializes a MutationObserver to watch for billing-postcode and shipping-postcode fields.
 */
const initPostcodeObserver = () => {
    // Flags to track if KoreanAddressAutocomplete has been rendered for each address type
    let renderedBilling = false;
    let renderedShipping = false;

    const observerCallback = () => {
        const billingCountry = document.getElementById('billing-country');
        const billingPostcode = document.getElementById('billing-postcode');
        const shippingCountry = document.getElementById('shipping-country');
        const shippingPostcode = document.getElementById('shipping-postcode');

        // If billing-postcode is found and not yet rendered
        if (billingCountry && billingPostcode && !renderedBilling) {
            renderAddressAutocomplete('billing');
            renderedBilling = true;
        }

        // If shipping-postcode is found and not yet rendered
        if (shippingCountry && shippingPostcode && !renderedShipping) {
            renderAddressAutocomplete('shipping');
            renderedShipping = true;
        }

        // If both have been rendered, disconnect the observer
        if (renderedBilling && renderedShipping) {
            observer.disconnect();
        }
    };

    const observer = new MutationObserver(observerCallback);

    // Start observing the body for changes in the DOM
    observer.observe(document.body, { childList: true, subtree: true });
};

// Initialize the MutationObserver when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initPostcodeObserver();
});
