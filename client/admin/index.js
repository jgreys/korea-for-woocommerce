import jQuery from 'jquery';
import './style.scss';

jQuery(function ($) {
    'use strict';

    var $body = $(document.body),
        postcode = 'postcode_yn',
        kakaochannel = 'kakaochannel_yn',
        navertalktalk = 'navertalktalk_yn';

    // Function to toggle visibility based on the checkbox state
    var toggleFeature = function (id) {
        $body.on('change', `input[name="${id}"]`, function () {
            var isChecked = $(this).is(':checked');
            $("tr").filter(function() {
                return $(this).find(`.show_if_${id}`).length > 0;
            }).toggle(isChecked);
        });

        // Trigger the change event to set the initial state
        $(`input[name="${id}"]`).trigger('change');
    };

    // Bind events for each feature
    toggleFeature(postcode);
    toggleFeature(kakaochannel);
    toggleFeature(navertalktalk);
});
