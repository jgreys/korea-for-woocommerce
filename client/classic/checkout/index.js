jQuery(function ($) {
    'use strict';

    if (typeof koreakitAddressAutocomplete === 'undefined') {
        return false;
    }

    const $body = $(document.body);

    const onCountryChange = (event) => {
        const $target = $(event.target);
        const type = $target.attr('name').indexOf('billing') !== -1 ? 'billing' : 'shipping';
        const country = $(`#${type}_country`).val();
        const $postcode = $(`#${type}_postcode`);

        if (country !== 'KR') {
            $postcode.removeAttr('readonly onkeypress');
            $body.find(`#${type}-address-autocomplete`).remove();
            return;
        }

        if (!$body.find(`#${type}-address-autocomplete`).length) {
            $postcode.closest('p').append(
                `
                <div id="${type}-address-autocomplete" style="display: none; position: relative; width: 100%; height: 350px;">
                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" class="address-autocomplete-close"
                        style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1"
                        alt="닫기 버튼"
                    >
                </div>
                `
            );
        }

        $postcode.attr({
            readonly: 'readonly',
            onkeypress: 'return false;',
        });
    };

    const onPostcodeClick = (event) => {
        const $postcode = $(event.target);
        const type = $postcode.attr('name').indexOf('billing') !== -1 ? 'billing' : 'shipping';
        const country = $(`#${type}_country`).val();

        if (country !== 'KR') {
            return;
        }

        new daum.Postcode({
            alwaysShowEngAddr: true,
            hideEngBtn: false,
            theme: koreakitAddressAutocomplete.theme,
            oncomplete: (data) => {
                $body.find(`#${type}_postcode`).val(data.zonecode);
                $body.find(`#${type}_address_1`).val(data.address);
                $body.find(`#${type}_address_2`).focus();
                $body.find(`#${type}_city`).val(data.sido);

                $body.find(`#${type}-address-autocomplete`).hide();
            },
            width: '100%',
            height: '100%',
        }).embed(`${type}-address-autocomplete`);

        $body.find(`#${type}-address-autocomplete`).show();
    };

    const onPostcodeClose = (event) => {
        $(event.target).parent().hide();
    };

    // Event bindings
    $body.find('#billing_country, #shipping_country').on('change', onCountryChange).trigger('change');
    $body.find('#billing_postcode, #shipping_postcode').on('click', onPostcodeClick);
    $body.find('.address-autocomplete-close').on('click', onPostcodeClose);
});
