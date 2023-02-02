<?php

class WPCF7CF_ToggleButton {

    public static function register() {
        // Register shortcodes
        add_action('wpcf7_init', array(__CLASS__, 'add_shortcode'));

        // Tag generator
	    add_action('admin_init', array(__CLASS__, 'tag_generator'), 570);

    }

    public static function shortcode_handler($tag) {
        //$tag = new WPCF7_Shortcode($tag);
	    $tag = new WPCF7_FormTag($tag);

        if (empty($tag->name))
            return '';

        $class = wpcf7_form_controls_class($tag->type, 'wpcf7cf-togglebutton');

        $atts = array();

        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_option('id', 'id', true);
        $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);
        $atts['type'] = 'button';

        if ($tag->has_option('readonly'))
            $atts['readonly'] = 'readonly';

        if ($tag->is_required())
            $atts['aria-required'] = 'true';

        $values = $tag->values;
        if (!is_array($values) || count($values) < 1) {
            $values = array(
                0 => __('Show','wpcf7cf'),
                1 => __('Hide','wpcf7cf')
            );
        }


        if (wpcf7_is_posted() && isset($_POST[$tag->name]))
            $value = stripslashes_deep($_POST[$tag->name]);

        $dpOptions = array();
        $dpOptions['value'] = $tag->get_option('value', '', true);

        $atts['type'] = 'button';
        $atts['name'] = $tag->name;

        $atts['data-val-1'] = $values[0];
        $atts['data-val-2'] = $values[1];

        $atts = wpcf7_format_atts($atts);

        $html = '<button '.$atts.' value="'.$values[0].'">'.$values[0].'</button>';

        return $html;
    }

    public static function tag_generator() {
        if (! class_exists( 'WPCF7_TagGenerator' ))
            return;

        $tag_generator = WPCF7_TagGenerator::get_instance();
        $tag_generator->add( 'togglebutton', __( 'CF Togglebutton', 'cf7-conditional-fields' ),
            array(__CLASS__, 'tg_pane') );
    }

    public static function tg_pane($contact_form, $args = '') {
        $args = wp_parse_args( $args, array() );
        $type = 'togglebutton';
        $description = __('Create a Togglebutton. It looks like a button but acts like a checkbox.', 'wpcf7cf');


	    require_once dirname(__FILE__) . '/tg_pane_togglebutton.php';
    }

    public static function add_shortcode() {
        if (function_exists('wpcf7_add_form_tag')) {
            wpcf7_add_form_tag(array('togglebutton', 'togglebutton*'), array(__CLASS__, 'shortcode_handler'), true);
        }
    }
}

WPCF7CF_ToggleButton::register();
