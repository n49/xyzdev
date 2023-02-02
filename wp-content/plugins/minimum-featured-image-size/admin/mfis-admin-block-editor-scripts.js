jQuery(function($) {

    /**
     * Initialise some variables
     */
    var mfis_value = -1,
        previous_value = '',
        mfis_image_passed = true,
        mfis_do_check = true,
        mfis_dismiss_error_messages = false,
        mfis_publishing_disabled_message = '';


    /**
     * Reset all things mfis in the block editor
     */
    function mfis_reset_block_editor () {

        // Renable publishing
        if(mfis.disable_publishing == 'publishing_disabled') {
            $('.editor-post-publish-button').removeAttr('disabled').show();
            wp.data.dispatch('core/editor').unlockPostSaving('mfis_lock');
        }

        // Remove error message from below image
        $('.mfis_publishing_disabled_block_editor').fadeOut(500, function() {
            $('.mfis_publishing_disabled_block_editor').remove();
            $('.editor-post-featured-image img').removeClass('mfis_is_disabled');
        });

        // Remove the error message from top of editor
        wp.data.dispatch('core/notices').removeNotice('mfis_dismissible_error_message');

        mfis_value = '';
        mfis_do_check = false;

    }


    /**
     * Check the image via ajax
     * @param {number} image_id
     */
    function mfis_do_ajax_block_editor (image_id) {

        if(image_id > 0) {

            var data = {
                action: 'mfis_get_size_from_id',
                image_id: image_id,
                post_type: mfis.post_type,
                editor: 'block',
            };

            $.post(ajaxurl, data, function(response) {

                response += ""; // Make sure this is a string we're dealing with

                var json = JSON.parse(response);

                if(json.image_check == 'fail') {

                    // Add error message
                    mfis_publishing_disabled_message = '<div class="mfis_publishing_disabled_block_editor"><span class="mfis_icon">!</span><span class="mfis_error_message">' + json.error_message + '</span></div>';

                    if(!$('.editor-post-featured-image img').hasClass('mfis_is_disabled')) {

                        $('.editor-post-featured-image img').after(mfis_publishing_disabled_message).addClass('mfis_is_disabled');
                        $('.mfis_publishing_disabled_block_editor').hide().fadeIn();

                    }

                    // Display an error message at top of editor
                    if(!mfis_dismiss_error_messages) {

                        wp.data.dispatch('core/notices').createInfoNotice(json.error_message, {
                            id: 'mfis_dismissible_error_message'
                        });

                        mfis_dismiss_error_messages = true;

                    }

                    // Disable publishing
                    if(mfis.disable_publishing == 'publishing_disabled') {

                        // Disable the Publish button
                        $('.editor-post-publish-button').attr('disabled', 'disabled').show();

                        // Adding in the gutenberg lockPostSaving, though it doesn't actually lock
                        // saving, just adds an aria disabled attr, so the onclick event still works
                        wp.data.dispatch('core/editor').lockPostSaving('mfis_lock');
                    }

                    // Set mfis_do_check to false
                    mfis_do_check = false;
                    mfis_image_passed = false;

                } else {

                    // Image passed, so let's reset things
                    mfis_reset_block_editor();

                }

            });

        }

        else {
            // There is no image, so let's reset things
            mfis_reset_block_editor();
        }

    }


    /**
     * Perform the image check
     */
    function mfis_check_image_block_editor () {

        if(mfis_do_check) {
            mfis_do_ajax_block_editor(mfis_value);
        }

    }


    /**
     * Subscribe to the featured_image data, to detect changes
     */
    wp.data.subscribe(function() {

        wp.domReady(function() {

            mfis_value = wp.data.select('core/editor').getEditedPostAttribute('featured_media');
            mfis_do_check = true;

        });

    });


    // Do initial check on page load, and then keep checking
    mfis_check_image_block_editor();
    setInterval(mfis_check_image_block_editor, 200);

});