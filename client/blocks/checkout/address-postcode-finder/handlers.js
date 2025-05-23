import { __ } from '@wordpress/i18n';

/**
 * Handles country change for either billing or shipping fields.
 */
export const handleCountryChange = (countryField, postcodeField, prefix) => {
    if (countryField.value !== 'KR') {
        postcodeField.removeAttribute('readonly');
        postcodeField.removeAttribute('onkeypress');
        const autocompleteElement = document.getElementById(`${prefix}-address-autocomplete`);
        if (autocompleteElement) autocompleteElement.remove();
        return;
    }

    postcodeField.setAttribute('readonly', 'readonly');
    postcodeField.setAttribute('onkeypress', 'return false;');

    const autocompleteHTML = `
        <div id="${prefix}-address-autocomplete" class="${prefix}-address-autocomplete" style="display: none; position: relative; width: 100%; height: 350px;">
            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" class="address-autocomplete-close"
                style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1" alt="Collapse button">
        </div>
    `;
    postcodeField.closest('div').insertAdjacentHTML('beforeend', autocompleteHTML);
};

/**
 * Handles postcode click event, shows Daum Postcode API.
 */
export const handlePostcodeClick = (countryField, postcodeField, setAddress, currentAddress, prefix) => {
    if (countryField.value !== 'KR') return;

    const autocompleteElement = document.getElementById(`${prefix}-address-autocomplete`);

    // Daum Postcode API integration
    new daum.Postcode({
        alwaysShowEngAddr: true,
        hideEngBtn: false,
        oncomplete: (data) => {
            setAddress({
                ...currentAddress,
                postcode: data.zonecode,
                address_1: data.address,
                city: data.sido,
            });

            postcodeField.value = data.zonecode;
            document.getElementById(`${prefix}-address_1`).value = data.address;
            document.getElementById(`${prefix}-city`).value = data.sido;

            postcodeField.dispatchEvent(new Event('input'));
            document.getElementById(`${prefix}-address_1`).dispatchEvent(new Event('input'));
            document.getElementById(`${prefix}-city`).dispatchEvent(new Event('input'));

            autocompleteElement.style.display = 'none';
        },
        width: '100%',
        height: '100%',
    }).embed(`${prefix}-address-autocomplete`);

    autocompleteElement.style.display = 'block';
};
