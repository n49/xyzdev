<?php

// define constants
define('WPCF7CF_DEFAULT_REGEX_NUMERIC', "^[0-9]+$");
define('WPCF7CF_DEFAULT_REGEX_ALPHABETIC', "^[a-zA-Z]+$");
define('WPCF7CF_DEFAULT_REGEX_ALPHANUMERIC', "^[a-zA-Z0-9]+$");
define('WPCF7CF_DEFAULT_REGEX_DATE', "^(0?[1-9]|1[012])[- .](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2}$");
define('WPCF7CF_DEFAULT_REGEX_EMAIL', "^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$");
define('WPCF7CF_DEFAULT_REGEX_NUMERIC_LABEL', "numeric");
define('WPCF7CF_DEFAULT_REGEX_ALPHABETIC_LABEL', "alphabetic");
define('WPCF7CF_DEFAULT_REGEX_ALPHANUMERIC_LABEL', "alphanumeric");
define('WPCF7CF_DEFAULT_REGEX_DATE_LABEL', "date");
define('WPCF7CF_DEFAULT_REGEX_EMAIL_LABEL', "email");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_1_LABEL', "custom 1");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_2_LABEL', "custom 2");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_3_LABEL', "custom 3");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_4_LABEL', "custom 4");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_5_LABEL', "custom 5");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_1', "");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_2', "");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_3', "");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_4', "");
define('WPCF7CF_DEFAULT_REGEX_CUSTOM_5', "");

require_once WPCF7CF_PLUGIN_DIR.'/pro/togglebutton.php';
require_once WPCF7CF_PLUGIN_DIR.'/pro/multifile.php';
require_once WPCF7CF_PLUGIN_DIR.'/pro/repeater.php';
require_once WPCF7CF_PLUGIN_DIR.'/pro/step.php'; 
require_once WPCF7CF_PLUGIN_DIR.'/pro/summary.php'; 

add_filter('wpcf7cf_get_operators', 'wpcf7cfpro_get_operators', 10, 2);
function wpcf7cfpro_get_operators($original_operators) {
    return array_merge($original_operators, array(
        'equals (regex)',
        'not equals (regex)',
        'greater than',
        'greater than or equals',
        'less than',
        'less than or equals',
        'is empty',
        'not empty',
        'function',
    ));
}

add_filter('wpcf7cf_default_options', 'wpcf7cfpro_default_options', 10, 1);
function wpcf7cfpro_default_options($wpcf7cfpro_default_options) {
    return array_merge($wpcf7cfpro_default_options, array(
        'license_key' => '',
        'regex_numeric' => WPCF7CF_DEFAULT_REGEX_NUMERIC,
        'regex_alphabetic' => WPCF7CF_DEFAULT_REGEX_ALPHABETIC,
        'regex_alphanumeric' => WPCF7CF_DEFAULT_REGEX_ALPHANUMERIC,
        'regex_date' => WPCF7CF_DEFAULT_REGEX_DATE,
        'regex_email' => WPCF7CF_DEFAULT_REGEX_EMAIL,
        'regex_numeric_label' => WPCF7CF_DEFAULT_REGEX_NUMERIC_LABEL,
        'regex_alphabetic_label' => WPCF7CF_DEFAULT_REGEX_ALPHABETIC_LABEL,
        'regex_alphanumeric_label' => WPCF7CF_DEFAULT_REGEX_ALPHANUMERIC_LABEL,
        'regex_date_label' => WPCF7CF_DEFAULT_REGEX_DATE_LABEL,
        'regex_email_label' => WPCF7CF_DEFAULT_REGEX_EMAIL_LABEL,
        'regex_custom_1' => '',
        'regex_custom_2' => '',
        'regex_custom_3' => '',
        'regex_custom_4' => '',
        'regex_custom_5' => '',
        'regex_custom_1_label' => WPCF7CF_DEFAULT_REGEX_CUSTOM_1_LABEL,
        'regex_custom_2_label' => WPCF7CF_DEFAULT_REGEX_CUSTOM_2_LABEL,
        'regex_custom_3_label' => WPCF7CF_DEFAULT_REGEX_CUSTOM_3_LABEL,
        'regex_custom_4_label' => WPCF7CF_DEFAULT_REGEX_CUSTOM_4_LABEL,
        'regex_custom_5_label' => WPCF7CF_DEFAULT_REGEX_CUSTOM_5_LABEL,
    ));
}



add_action('wpcf7cf_after_animation_settings', 'wpcf7cfpro_after_animation_settings', 10, 0);
function wpcf7cfpro_after_animation_settings() {

    echo '<h2>License key (Pro)</h2>';
    wpcf7cf_input_fields_wrapper_start();
    wpcf7cf_input_field('license_key', array(
        'label' => 'license key',
        'description' => "Don't have one? <a target=\"_blank\" href=\"http://bdwm.be/wpcf7cf/contact-form-7-conditional-fields-pro/\">Get your License key here</a>",
        'label_editable' => false,
    ));
    wpcf7cf_input_fields_wrapper_end();


    echo '<h2>Regular Expressions (Pro)</h2>';
?>
    <p>
        You can add up to 10 regular expressions here that can be used as a quick refence while creating conditions.<br>
        <strong>Note</strong>: You can use other regular expressions than the ones defined here, by just typing/pasting them in the condition's <em>if</em> field.

    </p>
<?php

    wpcf7cf_input_fields_wrapper_start();

    wpcf7cf_input_field('regex_numeric', array(
        'label' => 'numeric',
        'description' => 'Match if the input consists of only digits',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_alphabetic', array(
        'label' => 'alphabetic',
        'description' => 'Match if the input consists of alphabetic characters',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_alphanumeric', array(
        'label' => 'alphanumeric',
        'description' => 'Match if the input consists of only alphanumeric characters',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_date', array(
        'label' => 'date',
        'description' => 'Match if the input is a valid date',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_email', array(
        'label' => 'email',
        'description' => 'Match if the input is a valid email address',
        'label_editable' => true,

    ));
    wpcf7cf_input_field('regex_custom_1', array(
        'label' => 'custom 1',
        'description' => 'Your own custom regex. Make sure to start your regex with <code>^</code> and end it with <code>$</code>',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_custom_2', array(
        'label' => 'custom 2',
        'description' => 'Your own custom regex',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_custom_3', array(
        'label' => 'custom 3',
        'description' => 'Your own custom regex',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_custom_4', array(
        'label' => 'custom 4',
        'description' => 'Your own custom regex',
        'label_editable' => true,
    ));
    wpcf7cf_input_field('regex_custom_5', array(
        'label' => 'custom 5',
        'description' => 'Your own custom regex',
        'label_editable' => true,
    ));

    wpcf7cf_input_fields_wrapper_end();


?>
<?php
    submit_button();

}

add_action( 'wp_ajax_wpcf7cf_get_summary', 'wpcf7cf_print_summary_json' );
add_action( 'wp_ajax_nopriv_wpcf7cf_get_summary', 'wpcf7cf_print_summary_json' );

function wpcf7cf_print_summary_json() {

    $posted_data = $_POST;
    $posted_data = array_merge($posted_data,array_map(function($file) {
        return $file['name'];
    },$_FILES));

    $summary = WPCF7CF_Summary::wpcf7cf_get_summary_arr($posted_data);

    echo json_encode($summary);
    exit;
}

