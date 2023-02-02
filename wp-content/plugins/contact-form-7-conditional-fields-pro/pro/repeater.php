<?php
class WPCF7CF_Repeater {

	private $repeaters = array(); // will contain info about all the repeaters.

	function __construct() {

		// Register shortcodes
		add_action('wpcf7_init', array(__CLASS__, 'add_shortcodes'));

		// Tag generator
		add_action('admin_init', array(__CLASS__, 'tag_generator'), 580);

		// This action is called in 2 cases.
		// 1. DISPLAYING THE FRONT-END FORM: generate the repeater HTML code
		// 2. POSTING THE FORM:   - validation: change the expected posted form, so CF7 validation can do it's job
		//                        - email messages: Replace and add sub-repeater email tags (__i) where necessary
		add_action( 'wpcf7_contact_form', array( $this, 'generate_repeater_html' ) );

	}

	public static function add_shortcodes() {

		// the callback function 'shortcode_handler' will never get called.
		// The reason is that we are using the 'wpcf7_contact_form_properties' hook first, to generate the HTML in the generate_repeater_html function.
		// In generate_repeater_html we generate the actual HTML, so no [repeater] tags will be present if we are finished. That's why the shortcode_handler won't be called.
		// For some reason, wpcf7_add_form_tag always passes an empty $content. So it's a useless filter, because we need to access the content between
		// the start [repeater] and end [/repeater] tags. Same story for [group][/group]

		if (function_exists('wpcf7_add_form_tag'))
			wpcf7_add_form_tag('repeater', array(__CLASS__, 'shortcode_handler'), array( 'name-attr' => true ));
		else if (function_exists('wpcf7_add_shortcode')) {
			wpcf7_add_shortcode('repeater', array(__CLASS__, 'shortcode_handler'), true);
		} else {
			throw new Exception('functions wpcf7_add_form_tag and wpcf7_add_shortcode not found.');
		}

	}

	public static function shortcode_handler($tag) {
		// this function will never get called. See comment inside add_shortcodes() method above.
		// But we leave it here as a reference.
		$tag = new WPCF7_FormTag($tag);
		return $tag->content; // this is always empty :(
	}


	public static function tag_generator() {
		if (! function_exists( 'wpcf7_add_tag_generator'))
			return;

		wpcf7_add_tag_generator('repeater',
			__('CF Repeater', 'wpcf7cf'),
			'wpcf7-tg-pane-repeater',
			array(__CLASS__, 'tg_pane')
		);

		do_action('wpcf7cf_tag_generator');
	}

	static function tg_pane( $contact_form, $args = '' ) {
		$args = wp_parse_args( $args, array() );
		$type = 'repeater';

		$description = __( "Generate a repeater tag. The fields within can be repeated", 'wpcf7cf' );

		include 'tg_pane_repeater.php';
	}

	function generate_repeater_html($contact_form) {

		$posting_form = isset($_POST['_wpcf7cf_options']);

		if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) { // TODO: kind of hacky. maybe find a better solution. Needed because otherwise the repeater tags will be replaced in the editor as well.

			$form = $contact_form->prop( 'form' );
			$mail   = $contact_form->prop( 'mail' );
			$mail_2 = $contact_form->prop( 'mail_2' );

			//$form_parts = preg_split('/(\[\/?repeater(?:\]|\s.*?\]))/',$form, -1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			$form_parts = preg_split('/(\[\/?repeater(?:\]|\s.*?\]))/',$form, -1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			//\w+|"(?:\\"|[^"])+"

			$add_button_default_text = "Add";
			$remove_button_default_text = "Remove";
			$add_button_text = [];
			$remove_button_text = [];
			$repeater_stack = [];

			ob_start();

			$stack = array();

			foreach ($form_parts as $form_part) {
				if (substr($form_part,0,10) == '[repeater ') {
					//$tag_parts = explode(' ',rtrim($form_part,']'));
					$tag_parts = str_getcsv(rtrim($form_part,']'),' ');

					array_shift($tag_parts);

					// at this point the '[repeater' and ']' values are removed from the array.
					// $tag_parts will look something like this: ['repeater-1', 'min:1', 'max:5', 'add', 'Add a new item', 'remove', 'Remove item"]

					$tag_id = $tag_parts[0];
					$tag_html_type = 'div';
					$tag_html_data = array();

					array_push($this->repeaters,$tag_id);
					array_push($repeater_stack,$tag_id);

					$add_button_text[] = $add_button_default_text;
					$remove_button_text[] = $remove_button_default_text;

					foreach ($tag_parts as $i => $tag_part) {
						$tag_part_arr = explode(':', $tag_part);
						if ($i==0) continue;
						else if ($tag_part == 'add') {
							array_pop($add_button_text);
							$add_button_text[] = $tag_parts[$i+1];
							next($tag_parts);
							continue;
						} else if ($tag_part == 'remove')  {
							array_pop($remove_button_text);
							$remove_button_text[] = $tag_parts[$i+1];
							next($tag_parts);
							continue;
						} else if ($tag_part == 'inline') $tag_html_type = 'span';
						else if ($tag_part == 'clear_on_hide') $tag_html_data[] = 'data-clear_on_hide';
						else if ($tag_part == 'disable_on_hide') $tag_html_data[] = 'data-disable_on_hide';
						else if ($tag_part_arr[0] == 'class' && isset($tag_part_arr[1])) $tag_html_data[] = 'class="'.$tag_part_arr[1].'"';
						else if (isset($tag_part_arr[1])) $tag_html_data[] = 'data-'.$tag_part_arr[0].'="'.$tag_part_arr[1].'"';
					}

					array_push($stack,$tag_html_type);

					echo '<'.$tag_html_type.' class="wpcf7cf_repeater" data-id="'.$tag_id.'" data-orig_data_id="'.$tag_id.'" '.implode(' ',$tag_html_data).'><div class="wpcf7cf_repeater_sub" data-repeater_sub_suffix="">';
				} else if ($form_part == '[/repeater]') {
					$tag_id = array_pop($repeater_stack);
					echo '</div><div class="wpcf7cf_repeater_controls"><input type="hidden" name="'.$tag_id.'_count" value=""><span class="wpcf7cf_add-container"><button type="button" class="wpcf7cf_add">'.array_pop($add_button_text).'</button></span><span class="wpcf7cf_remove-container"><button type="button" class="wpcf7cf_remove">'.array_pop($remove_button_text).'</button></span></div></'.array_pop($stack).'>';
				} else {

					if ($posting_form && end($repeater_stack)) {
						$this->replace_content($form_part, $repeater_stack);
					} else {
						echo $form_part;
					}
				}
			}

			$form = ob_get_clean();

			$contact_form->set_properties( array(
				'form'   => $form,
				'mail'   => $mail,
				'mail_2' => $mail_2
			));
		}
	}

	public function replace_content($content, $repeater_stack, $suf='') {
		$rep_id = array_shift($repeater_stack);
		if (!$rep_id) {
			$replaced_content = preg_replace('/\[([^\s]*)\s*([^\s^\]]*)/', '[\1 \2'.$suf, $content);
			return $replaced_content;
		}
		$num_subs = sanitize_text_field($_POST[$rep_id.$suf.'_count'] ?? '0');
		for ($i = 1; $i <= $num_subs; $i++) {
			$replaced_content = $this->replace_content($content, $repeater_stack, $suf.'__'.$i);
			echo $replaced_content;
		}
	}
}

new WPCF7CF_Repeater;