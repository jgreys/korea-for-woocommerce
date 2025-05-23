import { useEffect } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
import { handleCountryChange, handlePostcodeClick } from './handlers';
import { CART_STORE_KEY } from './constants';

/**
 * Provides functions and data to update the WooCommerce billing/shipping address.
 */
export const useAddressUpdater = (prefix) => {
    const { setBillingAddress } = useDispatch(CART_STORE_KEY);
    const { billingAddress, shippingAddress } = useSelect((select) => select(CART_STORE_KEY).getBillingAddress());

    // You can expand this to handle 'shipping' by adding logic for the prefix.
    const setAddress = setBillingAddress;
    const currentAddress = billingAddress;

    return { currentAddress, setAddress };
};

/**
 * React component for handling address fields (billing/shipping).
 */
const AddressFields = ({ prefix }) => {
    const { currentAddress, setAddress } = useAddressUpdater(prefix);

    useEffect(() => {
        const countryField = document.getElementById(`${prefix}-country`);
        const postcodeField = document.getElementById(`${prefix}-postcode`);

        if (!countryField || !postcodeField) return;

        const onCountryChange = () => handleCountryChange(countryField, postcodeField, prefix);
        const onPostcodeClick = () => handlePostcodeClick(countryField, postcodeField, setAddress, currentAddress, prefix);

        countryField.addEventListener('change', onCountryChange);
        postcodeField.addEventListener('click', onPostcodeClick);

        // Trigger initial change event
        countryField.dispatchEvent(new Event('change'));

        // Cleanup listeners on component unmount
        return () => {
            countryField.removeEventListener('change', onCountryChange);
            postcodeField.removeEventListener('click', onPostcodeClick);
        };
    }, [currentAddress, setAddress]);

    return null;
};

export default AddressFields;
