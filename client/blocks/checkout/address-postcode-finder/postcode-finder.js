import { __ } from '@wordpress/i18n';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

const PostcodeFinder = () => {
    // WooCommerce store hooks
    const { setBillingAddress } = useDispatch(CART_STORE_KEY);
    const { billingAddress, shippingAddress } = useSelect( ( select ) =>
        select( CART_STORE_KEY ).getCustomerData()
    );

    useEffect(() => {
        const billingCountry = document.getElementById('billing-country');
        const billingPostcode = document.getElementById('billing-postcode');

        if ( ! billingCountry || ! billingPostcode ){
            return null;
        }

        // Country change handler
        const onCountryChange = () => {
            if ( billingCountry.value !== 'KR' ) {
                billingPostcode.removeAttribute('readonly');
                billingPostcode.removeAttribute('onkeypress');

                const addressAutocomplete = document.getElementById('billing-address-autocomplete');
                if ( addressAutocomplete ) {
                    addressAutocomplete.remove();
                }

                return null;
            }

            billingPostcode.setAttribute('readonly', 'readonly');
            billingPostcode.setAttribute('onkeypress', 'return false;');

            billingPostcode.closest('div')?.insertAdjacentHTML(
                'beforeend',
                `
                <div id="billing-address-autocomplete" style="display: none; position: relative; width: 100%; height: 350px;">
                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" class="address-autocomplete-close"
                        style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1"
                        alt="${__('Collapse button', 'korea-for-woocommerce')}"
                    >
                </div>
                `
            );
        };

        // Postcode click handler
        const onPostcodeClick = () => {
            if ( billingCountry.value !== 'KR' ) {
                return null;
            }

            const autocompleteElement = document.getElementById('billing-address-autocomplete');

            try {
                // Daum Postcode API call
                new daum.Postcode({
                    alwaysShowEngAddr: true,
                    hideEngBtn: false,
                    oncomplete: (data) => {
                        setBillingAddress({
                            ...billingAddress,
                            postcode: data.zonecode,
                            address_1: data.address,
                            city: data.sido,
                        });

                        document.getElementsByClassName('wc-block-components-address-form__address_2-toggle')[0].click();
                        document.getElementById('billing-address_2')?.focus();

                        // Hide autocomplete element after selection
                        autocompleteElement.style.display = 'none';
                    },
                    width: '100%',
                    height: '100%',
                }).embed('billing-address-autocomplete');
            } catch (error) {
                console.error(error);
            }

            // Display the autocomplete element
            autocompleteElement.style.display = 'block';
        };

        // Attach event listeners
        billingCountry.addEventListener('change', onCountryChange);
        billingPostcode.addEventListener('click', onPostcodeClick);

        // Trigger initial change event
        billingCountry.dispatchEvent(new Event('change'));

        // Cleanup listeners on component unmount
        return () => {
            billingCountry.removeEventListener('change', onCountryChange);
            billingPostcode.removeEventListener('click', onPostcodeClick);
        };
    }, [billingAddress, setBillingAddress]);

    return null; // Return nothing, it's only managing logic
};

export default PostcodeFinder;
