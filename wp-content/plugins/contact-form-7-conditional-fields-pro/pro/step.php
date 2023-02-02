<?php
class WPCF7CF_Step {

	private $stepStatus; // will contain info about the current step

	function __construct() {

		// Register shortcodes
		add_action('wpcf7_init', array(__CLASS__, 'add_shortcodes'));

		// Tag generator
		add_action('admin_init', array(__CLASS__, 'tag_generator'), 581);


		// This action is called in 2 cases.
		// 1. DISPLAYING THE FRONT-END FORM: generate the step HTML code
		// 2. POSTING THE FORM:   - validation: only validate the current step
		//                        - email: do some stuff too.
		add_action( 'wpcf7_contact_form', [ $this, 'generate_step_html'] );

		add_filter( 'wpcf7cf_validate', [ $this, 'skip_validation_for_hidden_steps' ], 10, 2 );

		add_action( 'wp_ajax_wpcf7cf_validate_step', [$this, 'ajax_wpcf7cf_validate_step'] );
		add_action( 'wp_ajax_nopriv_wpcf7cf_validate_step', [$this, 'ajax_wpcf7cf_validate_step'] );

	}

	public static function add_shortcodes() {

		// the callback function 'shortcode_handler' will never get called.
		// The reason is that we are using the 'wpcf7_contact_form_properties' hook first, to generate the HTML in the generate_step_html function.
		// In generate_step_html we generate the actual HTML, so no [step] tags will be present if we are finished. That's why the shortcode_handler won't be called.

		if (function_exists('wpcf7_add_form_tag'))
			wpcf7_add_form_tag('step', array(__CLASS__, 'shortcode_handler'), array( 'name-attr' => true ));
		else if (function_exists('wpcf7_add_shortcode')) {
			wpcf7_add_shortcode('step', array(__CLASS__, 'shortcode_handler'), true);
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

		wpcf7_add_tag_generator('step',
			__('CF Step', 'wpcf7cf'),
			'wpcf7-tg-pane-step',
			array(__CLASS__, 'tg_pane')
		);

		do_action('wpcf7cf_tag_generator');
	}

	static function tg_pane( $contact_form, $args = '' ) {
		$args = wp_parse_args( $args, array() );
		$type = 'step';

		$description = __( "Generate a step tag. Do not close it with [/step]. Step will implicitly be closed when the next [step] tag is detected", 'wpcf7cf' );

		include 'tg_pane_step.php';
	}

	function generate_step_html($contact_form) {

		$this->init_steps();

		$posting_form = isset($_POST['_wpcf7cf_options']);

		if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) { // TODO: kind of hacky. maybe find a better solution. Needed because otherwise the step tags will be replaced in the editor as well.

			$form = $contact_form->prop( 'form' );
			$mail   = $contact_form->prop( 'mail' );
			$mail_2 = $contact_form->prop( 'mail_2' );

			$form_parts = preg_split('/(\[step(?:\]|\s.*?\])|\[\/step\])/',$form, -1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			$num_form_parts  = count($form_parts);

			if ($num_form_parts <= 1) { return $contact_form; } // no steps in the form

			$next_button_default_text = __("Next step", 'wpcf7cf');
			$prev_button_default_text = __("Previous step", 'wpcf7cf');
			$next_button_text = $next_button_default_text;
			$prev_button_text = $prev_button_default_text;

			$step_i = 0;

			
			$before_form = '';
			$after_form = '';

			$first = $form_parts[0];
			$last = $form_parts[$num_form_parts-1];
			$last_but_one = $form_parts[$num_form_parts-2];

			if ($last_but_one == '[/step]') {
				$after_form = $last;
			}

			if (substr($first,0,5) != '[step') {
				$before_form = $first;
			}

			ob_start();

			foreach ($form_parts as $i_form_part => $form_part) {

				if (substr($form_part,0,5) != '[step') {
					continue;
				}

				$step_i++;

				$tag_parts = str_getcsv( rtrim( $form_part, ']' ), ' ' );

				array_shift( $tag_parts );

				// at this point the '[step' and ']' values are removed from the array.
				// $tag_parts will look something like this: ['1', 'title', 'First step', 'next', 'Go to next step', 'previous', 'Go to previous one']

				$tag_id        = "step-".$step_i;
				$tag_html_data = array();
				$step_title = "Step ".$step_i;

				foreach ( $tag_parts as $i => $tag_part ) {
					$tag_part_arr = explode( ':', $tag_part );
					if ( $tag_part == 'next' ) {
						$next_button_text = $tag_parts[ $i + 1 ];
						next( $tag_parts );
						continue;
					} else if ( $tag_part == 'title' ) {
						$step_title = $tag_parts[ $i + 1 ];
						next( $tag_parts );
						continue;
					} else if ( $tag_part == 'previous' ) {
						$prev_button_text = $tag_parts[ $i + 1 ];
						next( $tag_parts );
						continue;
					} else if ( $tag_part_arr[0] == 'class' && isset( $tag_part_arr[1] ) ) {
						$tag_html_data[] = 'class="' . $tag_part_arr[1] . '"';
					}
				}

				echo '  <div class="wpcf7cf_step" data-title="' . htmlentities($step_title) . '" data-id="' . $tag_id . '" ' . implode( ' ', $tag_html_data ) . '>
  							<div class="wpcf7cf_step_inner">
  								<div class="step-title">'.$step_title.'</div>
  				';


				// echo the next element in the array
				// TODO: maybe check for evil users adding [step][step] ?
				// TODO: What if the form ends with [step]

				echo            $form_parts[$i_form_part+1];

				// close the step

				echo '		</div>
						</div>';
				continue;
			}

			$form = '	<div class="wpcf7cf_multistep">
							<div class="wpcf7cf_steps-dots"></div>
							<div class="wpcf7cf_steps">'.ob_get_clean().'</div>
							<div class="wpcf7cf_step_controls">
								<span class="wpcf7cf_prev-container"><button type="button" class="wpcf7cf_prev">' . $prev_button_text . '</button></span>
								<span class="wpcf7cf_next-container"><button type="button" class="wpcf7cf_next">' . $next_button_text . '</button></span>
							</div>
						</div>
					';

			if ($posting_form) {

				$mail['body']   = $this->generate_mail( $mail['body'], $contact_form );
				if (isset($mail_2['body'])) {
					$mail_2['body'] = $this->generate_mail( $mail_2['body'], $contact_form );
				}
			}

			$contact_form->set_properties( array(
				'form'   => $before_form.$form.$after_form,
				'mail'   => $mail,
				'mail_2' => $mail_2
			));
		}
	}

	function generate_mail($mail_body, $contact_form) {

		$posted_data = $_POST;

		$matches = array();
		preg_match_all('/\[step (.*?)[\s\]]/', $contact_form->prop('form'), $matches);
		$step_names = $matches[1];


		$mail_body = preg_replace_callback(WPCF7CF_REGEX_MAIL_GROUP, function ( $matches ) use ($step_names, $posted_data, $mail_body) {
			$name = $matches[1];
			$content = $matches[2];
			if (in_array($name, $step_names)) {

				$sub_matches = array();
				$preg_result = preg_match('/\['.$name.'\](.*?)\[\/'.$name.'\]/s',$mail_body,$sub_matches);
				if (count($sub_matches) < 1) return $matches[0];
				$inner_template = $sub_matches[1];

				ob_start();

				$num_subs = $posted_data[$name.'_count'];

				for ($i=1; $i<=$num_subs; $i++) {
					echo str_replace(']','__'.$i.']',$inner_template);
				}

				return ob_get_clean();

			} else  {
				return $matches[0];
			}
		}, $mail_body);

		return $mail_body;
	}

	    /**
     * Remove validation requirements for fields that are hidden at the time of form submission.
     * Called using add_filter( 'wpcf7cf_validate_[tag_type]', array($this, 'skip_validation_for_hidden_steps'), 2, 2 );
     * where the priority of 2 causes this to kill any validations with a priority higher than 2
     *
     * @param $result
     * @param $tag
     *
     * @return mixed
     */
    function skip_validation_for_hidden_steps($result, $tags) {

		// bail early if we are not validating a single step
		if (!isset($_GET['action']) || $_GET['action']!='wpcf7cf_validate_step') {
			return $result;
		}

        $return_result = new WPCF7_Validation();

        $invalid_fields = $result->get_invalid_fields();

        if (!is_array($invalid_fields) || count($invalid_fields) == 0) return $result;

        foreach ($invalid_fields as $invalid_field_key => $invalid_field_data) {
            if (
				in_array($invalid_field_key, $this->stepStatus->fieldsInCurrentStep) ||
				in_array($invalid_field_key.'[]', $this->stepStatus->fieldsInCurrentStep)
			) {
                // the invalid field is not a hidden field, so we'll add it to the final validation result
                $return_result->invalidate($invalid_field_key, $invalid_field_data['reason']);
            }
        }

        return $return_result;
	}
	
	function init_steps() {
		$this->stepStatus = (object) [];
		if ( isset($_POST['_wpcf7cf_steps']) ) {
			
			$step_data = json_decode(stripslashes($_POST['_wpcf7cf_steps']));

			$this->stepStatus = $step_data;

		}
	}


	function ajax_wpcf7cf_validate_step() {
		global $wpdb;

		// Compatibility with cf7-image-captcha plugin
		remove_filter('wpcf7_validate','cf7ic_check_if_spam', 99, 2);
	
		// $this->init_steps();
	
		if (isset($_POST['_wpcf7'])) {
			$id = (int) $_POST['_wpcf7'];
			$unit_tag = wpcf7_sanitize_unit_tag($_POST['_wpcf7_unit_tag']);
	
			$spam = false;
			if ($contact_form = wpcf7_contact_form($id)) {
				if (WPCF7_VERIFY_NONCE && ! wpcf7_verify_nonce($_POST['_wpnonce'], $contact_form->id())) {
					$spam = true;
					exit(__('Spam detected'));
				} else {
					$items = array(
						'mailSent' => false,
						'into' => '#' . $unit_tag,
						'captcha' => null );
					/* Begin validation */
					require_once WPCF7_PLUGIN_DIR . '/includes/validation.php';
					$result = new WPCF7_Validation();

					$contact_form->validate_schema(
						array(
							'text' => true,
							'file' => false,
							'field' => array(),
						),
						$result
					);
			
					$tags = $contact_form->scan_form_tags();
	
					foreach ($tags as $tag) {
						if ($tag->basetype === 'file') {


							$file = empty( $_FILES[$tag->name] ) ? [] : $_FILES[$tag->name];

							$args = array(
								'tag' => $tag,
								'name' => $tag->name,
								'required' => $tag->is_required(),
								'filetypes' => $tag->get_option( 'filetypes' ),
								'limit' => $tag->get_limit_option(),
							);

							if (!function_exists('wpcf7_unship_uploaded_file')) { // CF7 5.4 check
								$return = [
									'success' => false,
									'message' => 'CF7 version 5.4 or higher is required'
								];
								$json = json_encode($return);
								exit($json);
							}

							$new_files = wpcf7_unship_uploaded_file( $file, $args );

							$result = apply_filters(
								"wpcf7_validate_{$tag->type}",
								$result, $tag,
								array(
									'uploaded_files' => $new_files,
								)
							);

						} else {
							$result = apply_filters('wpcf7_validate_' . $tag->type, $result, $tag);
						}
					}
					$result = apply_filters('wpcf7_validate', $result, $tags);
	
					$invalid_fields = $result->get_invalid_fields();
					$return = array('success' => $result->is_valid(), 'invalid_fields' => $invalid_fields);
					if ($return['success'] == false) {
						$return['message'] = $contact_form->prop('messages')['validation_error'];
						if (empty($return['message'])) {
							$default_messages = wpcf7_messages();
							$return['message'] = $default_messages['validation_error']['default'];
						}
					} else {
						$return['message'] = '';
					}

					if ($return['success'] === true) {
						do_action('wpcf7cf_step_completed', $return);
					}

					$json = json_encode($return);
					exit($json);
				}
			}
		}
	}

}

new WPCF7CF_Step;



