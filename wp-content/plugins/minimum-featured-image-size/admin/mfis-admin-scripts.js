jQuery(function ($) {

    /**
     * Initialise some variables
     */
    var mfis_value = -1,
        previous_value = '';


    /**
     * Check the image via ajax
     * @param {number} image_id
     */
    function mfis_do_ajax(image_id) {

        if (image_id != -1) {

            var data = {
                action: 'mfis_get_size_from_id',
                image_id: image_id,
                post_type: mfis.post_type,
                editor: 'classic',
            };

            $.post(ajaxurl, data, function (response) {

                var json = JSON.parse(response);

                if (json.image_check == 'fail') {

                    // Add error message
                    var mfis_publishing_disabled_message = '<div class="mfis_publishing_disabled"><span class="mfis_icon">!</span><span class="mfis_error_message">' + json.error_message + '</span></div>';

                    if (!$('#postimagediv .inside img').hasClass('mfis_is_disabled')) {
                        $('#postimagediv .inside img, #major-publishing-actions').after(mfis_publishing_disabled_message).addClass('mfis_is_disabled');
                        $('.mfis_publishing_disabled').hide().fadeIn();
                    }

                } else {

                    // Image passed, so let's reset things
                    mfis_reset();

                }

            });

        }

    }


    /**
     * Reset all things mfis
     */
    function mfis_reset() {

        if (mfis.disable_publishing == 'publishing_disabled') $('#publishing-action input[type="submit"]').removeAttr('disabled').show();

        $('.mfis_publishing_disabled').fadeOut(500, function () {
            $('.mfis_publishing_disabled').remove();
            $('#postimagediv .inside img').removeClass('mfis_is_disabled');
        });

        mfis_value = '';

    }


    /**
     * Do the mfis check
     */
    function mfis_check_image() {

        // Check if there is an input with the image ID
        if ($('#_thumbnail_id').length > 0 && $('#_thumbnail_id').val() >= 0) {

            // Compare it to the previous value
            mfis_value = $('#_thumbnail_id').val();

            if (mfis_value != previous_value) {

                // Value has changed, so let's disable the Publish button and do the ajax
                if (mfis.disable_publishing == 'publishing_disabled') $('#publishing-action input[type="submit"]').attr('disabled', 'disabled').show();

                mfis_do_ajax(mfis_value);

            }

            previous_value = mfis_value;

        } else {

            // There's no image ID input present, so reset things
            mfis_reset();

        }

    }



    if (mfis.disable_ajax != 'ajax_disabled') {

        // Reset mfis if 'Remove featured image' link is clicked
        $('#remove-post-thumbnail').on('click', function () {

            $('#_thumbnail_id').val(-1);

            mfis_reset();

        });

        // Do initial check on page load, and then keep checking
        mfis_check_image();
        setInterval(mfis_check_image, 200);

    }

});