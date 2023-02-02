<?php
/*
Plugin Name: Minimum Featured Image Size
Description: Set the minimum size required for featured images used in standard and custom posts.
Version:     2.0.3
Author:      corgux
Author URI:  https://corgux.github.io/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;


/**
 * Enqueue css for settings page
 * @since 1.0
 */
function mfis_enqueue_admin_css()
{

    # admin css
    wp_enqueue_style('mfis-admin-css', plugins_url('admin/mfis-admin-styles.min.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'mfis_enqueue_admin_css');


/**
 * Enqueue js for ajax functionality
 * @since 1.0
 */
function mfis_enqueue_admin_ajax()
{

    # admin javascript
    wp_register_script('mfis-admin-js', plugins_url('admin/mfis-admin-scripts.js', __FILE__), array('jquery'), false, true);

    # Localize the script and pass in the post type
    $current_screen = get_current_screen();
    $post_type = $current_screen->post_type;
    $translation_array = array(
        'post_type' => $post_type,
        'disable_publishing' => get_option('mfis_disable_publishing'),
        'disable_ajax' => get_option('mfis_ajax_disable')
    );

    wp_localize_script('mfis-admin-js', 'mfis', $translation_array);
    wp_enqueue_script('mfis-admin-js');
}

if (get_option('mfis_ajax_disable') != 'ajax_disabled') {

    add_action('admin_enqueue_scripts', 'mfis_enqueue_admin_ajax');
}


/**
 * Enqueue the js for the block editor, and remove the classic editor scripts
 * @since 2.0
 */
function mfis_enqueue_admin_block_editor_ajax()
{

    # admin javascript
    wp_register_script('mfis-adminblock-editor-js', plugins_url('admin/mfis-admin-block-editor-scripts.js', __FILE__), array('jquery'), false, true);

    # Localize the script and pass in the post type
    $current_screen = get_current_screen();
    $post_type = $current_screen->post_type;
    $translation_array = array(
        'post_type' => $post_type,
        'disable_publishing' => get_option('mfis_disable_publishing'),
        'disable_ajax' => get_option('mfis_ajax_disable')
    );

    wp_localize_script('mfis-adminblock-editor-js', 'mfis', $translation_array);
    wp_enqueue_script('mfis-adminblock-editor-js');

    # Remove the previously enqueued classic editor scripts
    remove_action('admin_enqueue_scripts', 'mfis_enqueue_admin_ajax');
}

add_action('enqueue_block_editor_assets', 'mfis_enqueue_admin_block_editor_ajax');


/**
 * Add an admin options page for MFIS settings
 * @since 1.0
 */
function mfis_admin_menu()
{

    add_options_page('Minimum Featured Image Size Settings', 'Minimum Featured Image Size', 'manage_options', 'mfis_settings', 'mfis_plugin_options');
}


/**
 * Output the settings markup
 * @since 1.0
 */
function mfis_plugin_options()
{

    if (!current_user_can('manage_options')) :

        wp_die(__('You do not have sufficient permissions to access this page.'));

    endif;

    # Default error message
    $mfis_default_error_message = 'Publishing disabled! The Featured Image you\'ve chosen is not large enough, it must be at least [width] x [height]';

    # Custom post types
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects'); ?>

    <div class="wrap mfis_wrap">
        <h1>Minimum Featured Image Size - Settings</h1>
        <form method="post" action="options.php">
            <?php

            settings_fields('mfis_option_group');
            do_settings_sections('mfis_option_group');

            # Get previously set options
            $mfis_min_width  = get_option('mfis_min_width');
            $mfis_exclude_pages  = get_option('mfis_exclude_pages');
            $mfis_min_height = get_option('mfis_min_height');
            $mfis_error_message = get_option('mfis_error_message'); ?>

            <table class="form-table" id="mfis_options_table">

                <tr valign="top">
                    <th scope="row">Minimum width (px)</th>
                    <td>
                        <input type="number" name="mfis_min_width" class="mfis_min_width" value="<?php echo empty($mfis_min_width) ? '' : esc_attr($mfis_min_width); ?>" /> px
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Minimum height (px)</th>
                    <td>
                        <input type="number" name="mfis_min_height" class="mfis_min_height" value="<?php echo empty($mfis_min_height) ? '' : esc_attr($mfis_min_height); ?>" /> px
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Exact dimensions</th>
                    <td>
                        <label for="mfis_exact_dimensions">
                            <input type="checkbox" id="mfis_exact_dimensions" name="mfis_exact_dimensions" class="mfis_exact_dimensions" value="use_exact_dimensions" <?php checked('use_exact_dimensions', get_option('mfis_exact_dimensions')); ?> />
                            Use exact dimensions?
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Post publishing</th>
                    <td>
                        <label for="mfis_disable_publishing">
                            <input type="checkbox" id="mfis_disable_publishing" name="mfis_disable_publishing" class="mfis_disable_publishing" value="publishing_disabled" <?php checked('publishing_disabled', get_option('mfis_disable_publishing')); ?> />
                            Disable publishing? <em><small>When the featured image isn't the required size</small></em>
                        </label><br>

                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Error message</th>
                    <td>
                        <textarea name="mfis_error_message" class="mfis_error_message"><?php echo empty($mfis_error_message) ? $mfis_default_error_message : esc_attr($mfis_error_message); ?></textarea><br><em><small>Use [width] and [height] to display your settings</em></small>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Ajax</th>
                    <td>
                        <label for="mfis_ajax_disable">
                            <input type="checkbox" id="mfis_ajax_disable" name="mfis_ajax_disable" class="mfis_ajax_disable" value="ajax_disabled" <?php checked('ajax_disabled', get_option('mfis_ajax_disable')); ?> />
                            Disable Ajax? <small><em>Classic editor only</em></small>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Pages</th>
                    <td>
                        <label for="mfis_exclude_pages">
                            <input type="checkbox" id="mfis_exclude_pages" name="mfis_exclude_pages" class="mfis_exclude_pages" value="exclude_pages" <?php checked('exclude_pages', get_option('mfis_exclude_pages')); ?> />
                            Disable on pages? <small><em>Don't check featured images on pages</em></small>
                        </label>
                    </td>
                </tr>

                <?php if (!empty($post_types)) : ?>

                    <tr valign="top">
                        <th valign="top" scope="row">Custom Post Types</th>
                        <td valign="top">
                            <label for="mfis_custom_post_types">
                                <input type="checkbox" name="mfis_custom_post_types" id="mfis_custom_post_types" class="mfis_custom_post_types" value="mfis_custom_post_types" <?php checked('mfis_custom_post_types', get_option('mfis_custom_post_types')); ?> />
                                Choose sizes for custom post types?
                            </label>
                        </td>
                    </tr>

                <?php endif; ?>

            </table>

            <?php if (!empty($post_types)) : ?>

                <table class="form-table" id="mfis_custom_posts_options_table">

                    <tr valign="top">
                        <th valign="top" scope="row">Custom Post Type</th>
                        <td valign="top">
                            <table>
                                <tr valign="top">
                                    <th valign="top" scope="row">Minimum width (px)</th>
                                    <th valign="top" scope="row">Minimum height (px)</th>
                                    <th valign="top" scope="row">
                                        Use exact dimensions?<br>
                                        <small><a href="#" class="dimensions_select_all">Select all</a></small>
                                    </th>
                                    <th valign="top" scope="row">
                                        Error message<small> (Optional)<br>
                                            <em>
                                                <small>
                                                    Use [width] and [height] to display your settings
                                                </small>
                                            </em>
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr> <?php

                            # Custom post types
                            $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

                            foreach ($post_types as $post_type) {

                                $mfis_min_width  = get_option('mfis_min_width_' . $post_type->name);
                                $mfis_min_height = get_option('mfis_min_height_' . $post_type->name);
                                $mfis_exact_dimensions = get_option('mfis_exact_dimensions_' . $post_type->name);
                                $mfis_error_message = get_option('mfis_error_message_' . $post_type->name); ?>

                        <tr valign="top">
                            <th valign="top" scope="row"><?php echo $post_type->labels->singular_name; ?></th>
                            <td valign="top">
                                <table>
                                    <tr>
                                        <td valign="top">
                                            <input type="number" name="mfis_min_width_<?php echo $post_type->name; ?>" class="mfis_min_width" value="<?php echo empty($mfis_min_width) ? '' : esc_attr($mfis_min_width); ?>" /> px
                                        </td>
                                        <td valign="top">
                                            <input type="number" name="mfis_min_height_<?php echo $post_type->name; ?>" class="mfis_min_width" value="<?php echo empty($mfis_min_height) ? '' : esc_attr($mfis_min_height); ?>" /> px
                                        </td>
                            </td>
                            <td valign="top">
                                <input type="checkbox" name="mfis_exact_dimensions_<?php echo $post_type->name; ?>" class="mfis_exact_dimensions_cpt" value="mfis_exact_dimensions_<?php echo $post_type->name; ?>" <?php checked('mfis_exact_dimensions_' . $post_type->name, get_option('mfis_exact_dimensions_' . $post_type->name)); ?> />
                            </td>
                            <td valign="top">
                                <textarea name="mfis_error_message_<?php echo $post_type->name; ?>" class="mfis_error_message"><?php echo empty($mfis_error_message) ? '' : esc_attr($mfis_error_message); ?></textarea>
                            </td>
                        </tr>
                    </table>
                    </td>
                    </tr> <?php

                    } ?>

                </table> <?php

                    endif;

                    submit_button(); ?>

        </form>
    </div>

    <script>
        jQuery(function($) {

            $('#mfis_custom_post_types').on('change', function() {

                if ($(this).is(':checked')) {

                    $('#mfis_custom_posts_options_table').show();

                } else {

                    $('#mfis_custom_posts_options_table').hide();

                }

            });

            if ($('#mfis_custom_post_types').is(':checked')) {

                $('#mfis_custom_posts_options_table').show();

            }

            $('.dimensions_select_all').on('click', function(e) {

                e.preventDefault();

                if ($(this).hasClass('all-checkboxes-selected')) {

                    $('.mfis_exact_dimensions_cpt').prop('checked', false);
                    $(this).text('Select all').removeClass('all-checkboxes-selected');

                } else {

                    $('.mfis_exact_dimensions_cpt').prop('checked', true);
                    $(this).text('Deselect all').addClass('all-checkboxes-selected');

                }

            });

        });
    </script>

<?php
}


/**
 * Register the MFIS settings
 * @since 1.0
 */
function mfis_register_settings()
{

    register_setting('mfis_option_group', 'mfis_min_width');
    register_setting('mfis_option_group', 'mfis_min_height');
    register_setting('mfis_option_group', 'mfis_exact_dimensions');
    register_setting('mfis_option_group', 'mfis_disable_publishing');
    register_setting('mfis_option_group', 'mfis_error_message');
    register_setting('mfis_option_group', 'mfis_ajax_disable');
    register_setting('mfis_option_group', 'mfis_exclude_pages');
    register_setting('mfis_option_group', 'mfis_custom_post_types');

    $post_types = get_post_types(array('_builtin' => false), 'names');
    foreach ($post_types as $post_type) {

        register_setting('mfis_option_group', 'mfis_min_width_' . $post_type);
        register_setting('mfis_option_group', 'mfis_min_height_' . $post_type);
        register_setting('mfis_option_group', 'mfis_exact_dimensions_' . $post_type);
        register_setting('mfis_option_group', 'mfis_error_message_' . $post_type);
    }
}

if (is_admin()) {

    add_action('admin_menu', 'mfis_admin_menu');
    add_action('admin_init', 'mfis_register_settings');
}

# Set a default value for the mfis_allow_publishing setting
add_option('mfis_disable_publishing', 'publishing_disabled');


/**
 * Check featured image size meets minimum or exact requirements
 * @since 1.0
 * @param {number} $image_id
 * @param {string} $post_type
 */
function mfis_image_size_ok($image_id, $post_type = 'post')
{

    $image_data = wp_get_attachment_image_src($image_id, "Full");

    if (!$image_data)
        return true; # bail if no image at all,


    if( $post_type == 'page' && get_option('mfis_exclude_pages') === 'exclude_pages')
        return true; # bail if pages are excluded

    $image_width = $image_data[1];
    $image_height = $image_data[2];

    if ($post_type === 'post' || $post_type === 'page') :

        $min_width = get_option('mfis_min_width');
        $min_height = get_option('mfis_min_height');
        $exact_dimensions = get_option('mfis_exact_dimensions');

    else :

        $min_width = get_option('mfis_custom_post_types') === 'mfis_custom_post_types' ? get_option('mfis_min_width_' . $post_type) : get_option('mfis_min_width');
        $min_height = get_option('mfis_custom_post_types') === 'mfis_custom_post_types' ? get_option('mfis_min_height_' . $post_type) : $min_height = get_option('mfis_min_height');
        $exact_dimensions = get_option('mfis_custom_post_types') === 'mfis_custom_post_types' ? get_option('mfis_exact_dimensions_' . $post_type) : get_option('mfis_exact_dimensions');

    endif;

    # Use exact dimensions?
    if ($exact_dimensions != '') {

        if ($image_width == $min_width && $image_height == $min_height) :

            return true; # image is exact size

        else :

            return false; # image is not exact size

        endif;
    } else {

        if ($image_width < $min_width || $image_height < $min_height) :

            return false; # image is too small

        else :

            return true; # image is big enough

        endif;
    }
}


/**
 * Get the error message based on post type
 * @since 2.0
 * @param {string} $post_type
 */
function mfis_get_error_message($post_type)
{

    # Default error message
    $mfis_default_error_message = 'Publishing disabled! The Featured Image you\'ve chosen is not large enough, it must be at least [width] x [height]';

    if ($post_type === 'post' || $post_type === 'page') :

        $min_width = get_option('mfis_min_width');
        $min_height = get_option('mfis_min_height');
        $mfis_error_message = get_option('mfis_error_message');

    else :

        $min_width = get_option('mfis_custom_post_types') === 'mfis_custom_post_types' ? get_option('mfis_min_width_' . $post_type) : get_option('mfis_min_width');
        $min_height = get_option('mfis_custom_post_types') === 'mfis_custom_post_types' ? get_option('mfis_min_height_' . $post_type) : $min_height = get_option('mfis_min_height');
        $mfis_error_message = get_option('mfis_error_message_' . $post_type);

    endif;

    $mfis_error_message = empty($mfis_error_message) ? str_replace(array('[width]',  '[height]'), array($min_width, $min_height), $mfis_default_error_message) : str_replace(array('[width]',  '[height]'), array($min_width, $min_height), $mfis_error_message);

    return $mfis_error_message;
}


/**
 * Check the size of an image via an ajax call
 * @since 1.0
 */
function mfis_get_size_from_id()
{

    $image_id = $_POST['image_id'];
    $post_type = $_POST['post_type'];

    # Default error message
    $mfis_default_error_message = 'Publishing disabled! The Featured Image you\'ve chosen is not large enough, it must be at least [width] x [height]';

    if (!mfis_image_size_ok($image_id, $post_type)) :

        $mfis_ajax_error_message = mfis_get_error_message($post_type);

        $returned_data = '{"image_check":"fail", "error_message":"' . $mfis_ajax_error_message . '"}';

        die($returned_data);

    else :

        die('{"image_check":"pass"}');

    endif;
}
add_action('wp_ajax_mfis_get_size_from_id', 'mfis_get_size_from_id');


/**
 * If publishing and the ajax is disabled or the user gets
 * past the javascript, check the image on post saving
 * @since 1.0
 */
function mfis_after_save($new_status, $old_status, $post)
{

    $run_on_statuses = array('publish', 'pending', 'future');

    if (!in_array($new_status, $run_on_statuses))
        return;

    $post_id = $post->ID;

    if (wp_is_post_revision($post_id))
        return;

    if (!function_exists('get_current_screen'))
        return;

    $image_id = get_post_thumbnail_id($post_id);
    $current_screen = get_current_screen();
    $post_type = $current_screen->post_type;

    if (!mfis_image_size_ok($image_id, $post_type)) {

        $reverted_status = in_array($old_status, $run_on_statuses) ? 'draft' : $new_status;

        $mfis_error_message = mfis_get_error_message($post_type);

        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => $reverted_status,
        ));
    }
}
if (is_admin() && get_option('mfis_disable_publishing') === 'publishing_disabled') add_action('transition_post_status', 'mfis_after_save', 10, 3);


/**
 * Update error message on post save if publishing and the
 * ajax is disabled or the user gets past the javascript
 * @since 2.0
 */
function mfis_output_error_message($new_status, $old_status, $post)
{

    $post_id = $post->ID;

    if (wp_is_post_revision($post_id))
        return;

    if (!function_exists('get_current_screen'))
        return;

    $image_id = get_post_thumbnail_id($post_id);
    $current_screen = get_current_screen();
    $post_type = $current_screen->post_type;

    if (!mfis_image_size_ok($image_id, $post_type)) {

        $mfis_error_message = mfis_get_error_message($post_type);

        update_option('mfis_editor_error_message', '<span class="mfis_error"><span class="mfis_icon">!</span> <span class="mfis_error_message">' . esc_html($mfis_error_message) . '</span></span>');
    }
}
if (is_admin()) add_action('transition_post_status', 'mfis_output_error_message', 10, 3);


/**
 * Output error message
 * @since 1.0
 */
function mfis_notice()
{

    if (get_option('mfis_editor_error_message')) : ?>

        <div class="notice notice-error mfis-notice-error">
            <p><?php echo get_option('mfis_editor_error_message'); ?></p>
        </div>

    <?php endif;

delete_option('mfis_editor_error_message');
}
add_action('admin_notices', 'mfis_notice');


/**
 * Removes the 'Post updated. View post' message
 * @since 1.0
 */
function mfis_remove_notice($messages)
{

    if (get_option('mfis_editor_error_message')) :

        return array();

    endif;

    return $messages;
}
add_filter('post_updated_messages', 'mfis_remove_notice');


/**
 * Function to check if we are in the MFIS settings page
 * @since    1.0.0
 */

function mfis_settings_screen()
{

    $page = isset($_GET['page']) ? $_GET['page'] : '';

    if ($page === 'mfis_settings') :

        /**
         * Output a message in the footer on the settings page
         * 
         * @since    1.0.0
         * @param    string 	$text 	Original footer markup
         */

        function mfis_footer($text)
        {

            ob_start(); ?>

            <span class="mfis_footer"> <?php

                                        $me_url = 'https://corgmo.github.io/';
                                        $beer_url = 'https://www.paypal.me/corgdesign/5';

                                        $allowed = array(
                                            'a' => array(
                                                'href' => array(),
                                                'target' => array(),
                                                'rel' => array()
                                            ),
                                            'span' => array(
                                                'class' => array()
                                            )
                                        );

                                        $text = sprintf(wp_kses(__('Made with <span class="dashicons dashicons-heart"><span>love</span></span> by <a href="%s" target="_blank" rel="noopener noreferrer">Martin Stewart</a> for the WordPress community.', 'woohoo'), $allowed), esc_url($me_url));


                                        echo $text;    ?>

            </span>

            <?php
            $text = ob_get_clean();

            return $text;
        }

        add_filter('admin_footer_text', 'mfis_footer');

    endif;
}
add_action('current_screen', 'mfis_settings_screen');
