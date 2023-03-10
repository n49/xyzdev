<?php
/**
** A base module for [multifile] and [multifile*]
**/

/* form_tag handler */

add_action( 'wpcf7_init', 'wpcf7_add_form_tag_multifile', 10, 0 );

function wpcf7_add_form_tag_multifile() {
	wpcf7_add_form_tag( array( 'multifile', 'multifile*' ),
		'wpcf7_multifile_form_tag_handler',
		array(
			'name-attr' => true,
			'file-uploading' => true,
		)
	);
}

function wpcf7_multifile_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$atts = array();

	$atts['size'] = $tag->get_size_option( '40' );
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

	$atts['accept'] = wpcf7_acceptable_filetypes(
		$tag->get_option( 'filetypes' ), 'attr' ); //cf7 original file function

	$atts['multiple'] = 'multiple';

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
		$atts['required'] = 'true';
	}

	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

	$atts['type'] = 'file';
	$atts['name'] = $tag->name . '[]';

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s</span>',
		sanitize_html_class( $tag->name ), $atts, $validation_error
	);

	return $html;
}

/* Validation + upload handling filter */

add_filter( 'wpcf7_validate_multifile', 'wpcf7_multifile_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_multifile*', 'wpcf7_multifile_validation_filter', 10, 2 );

function wpcf7_multifile_validation_filter( $result, $tag ) {

	if (!function_exists('wpcf7_unship_uploaded_file')) { // CF7 5.4 check
		$result->invalidate( $tag, 'CF7 version 5.4 or higher is required' );
		return $result;
	}

	$name = $tag->name;

	$files = [];
	if (isset($_FILES[$name]) && isset($_FILES[$name]['name'])) {
		if (is_array($_FILES[$name]['name'])) {
			// multiple files uploaded
			for( $i=0; $i<count($_FILES[$name]['name']); $i++) {
				$files[] = array(
					'name' => $_FILES[$name]['name'][$i],
					'type' => $_FILES[$name]['type'][$i],
					'tmp_name' => $_FILES[$name]['tmp_name'][$i],
					'error' => $_FILES[$name]['error'][$i],
					'size' => $_FILES[$name]['size'][$i],
				);
			}
		} else {
			// single file uploaded
			$files[] = $_FILES[$name];
		}
	}

	// return error early if this is a required field, and no files were uploaded.
	if (!count($files) && $tag->is_required()) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		return $result;
	}

	/* Multiple files uploaded: validation */
	$num_files = count($files);


    $allowed_size_total = wpcf7cf_multifile_get_limit_total_option($tag);
    $total_size = array_sum(array_column($files,'size'));
    if ( $allowed_size_total < $total_size ) {
        $result->invalidate( $tag, wpcf7_get_message( 'upload_files_too_large' ) );
        return $result;
    }

    $max_files_allowed = wpcf7cf_multifile_get_max_option($tag);
    if ( $max_files_allowed < $num_files ) {
        $result->invalidate( $tag, wpcf7_get_message( 'upload_too_many_files' ) );
        return $result;
    }

    $min_files_required = wpcf7cf_multifile_get_min_option($tag);
    if ( $min_files_required > $num_files ) {
        $result->invalidate( $tag, wpcf7_get_message( 'upload_too_few_files' ) );
        return $result;
    }																												

	$file_type_pattern = wpcf7_acceptable_filetypes( $tag->get_option( 'filetypes' ), 'regex' ); //cf7 original file function
	$file_type_pattern = '/\.(' . $file_type_pattern . ')$/i';

	$allowed_size = $tag->get_limit_option(); // cf7 original file function

	// First make sure all individual files pass validation.

	foreach ($files as $file) {
		if ( ! empty( $file['error'] ) and UPLOAD_ERR_NO_FILE !== $file['error'] ) {
			$result->invalidate( $tag, wpcf7_get_message( 'upload_failed_php_error' ) );
			return $result;
		}

		if ( empty( $file['tmp_name'] ) and $tag->is_required() ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
			return $result;
		}

		if ( empty( $file['tmp_name'] )
		or ! is_uploaded_file( $file['tmp_name'] ) ) {
			return $result;
		}

		/* File type validation */

		if ( empty( $file['name'] )
		or ! preg_match( $file_type_pattern, $file['name'] ) ) {
			$result->invalidate( $tag,
				wpcf7_get_message( 'upload_file_type_invalid' )
			);

			return $result;
		}

		/* Individual file size validation */
		if ( ! empty( $file['size'] ) and $allowed_size < $file['size'] ) {
			$result->invalidate( $tag, wpcf7_get_message( 'upload_file_too_large' ) );
			return $result;
		}
	}

	// All files passed validation. Now save them.

	wpcf7_init_uploads(); // cf7 orginal file function // Confirm upload dir
	$uploads_dir = wpcf7_upload_tmp_dir(); // cf7 orginal file function
	$uploads_dir = wpcf7_maybe_add_random_dir( $uploads_dir ); // cf7 orginal file function

	$moved_files = []; // keeps track of moved files. Needed for rollback.

	foreach($files as $i => $file) {


			wpcf7_unship_uploaded_file($file);
	}

	return $result;
}

add_filter( 'wpcf7_mail_tag_replaced_multifile', 'wpcf7_multifile_mail_tag', 10, 4 );
add_filter( 'wpcf7_mail_tag_replaced_multifile*', 'wpcf7_multifile_mail_tag', 10, 4 );

function wpcf7_multifile_mail_tag( $replaced, $submitted, $html, $mail_tag ) {

	
	$submission = WPCF7_Submission::get_instance();
	$uploaded_files = $submission->uploaded_files();
	$name = $mail_tag->field_name();

	$replaced = [];

	if (isset($uploaded_files[$name])) {
		$replaced = array_map('basename', $uploaded_files[$name]);
	}

	return implode(', ', $replaced);
}


/* Messages */

add_filter( 'wpcf7_messages', 'wpcf7_multifile_messages', 10, 1 );

function wpcf7_multifile_messages( $messages ) {
	return array_merge( $messages, array(
		'upload_failed' => array(
			'description' => __( "Uploading a file fails for any reason", 'cf7-conditional-fields' ),
			'default' => __( "There was an unknown error uploading the file.", 'cf7-conditional-fields' )
		),

		'upload_file_type_invalid' => array(
			'description' => __( "Uploaded file is not allowed for file type", 'cf7-conditional-fields' ),
			'default' => __( "You are not allowed to upload files of this type.", 'cf7-conditional-fields' )
		),

		'upload_file_too_large' => array(
			'description' => __( "Uploaded file is too large", 'cf7-conditional-fields' ),
			'default' => __( "The file is too big.", 'cf7-conditional-fields' )
		),

		'upload_files_too_large' => array(
			'description' => __( "Total size of the uploaded files is too large", 'cf7-conditional-fields' ),
			'default' => __( "The total size of the uploaded files is too large.", 'cf7-conditional-fields' )
		),

		'upload_too_many_files' => array(
			'description' => __( "Too many files files selected", 'cf7-conditional-fields' ),
			'default' => __( "You are not allowed to upload this many files.", 'cf7-conditional-fields' )
		),

		'upload_too_few_files' => array(
			'description' => __( "Too few files files selected", 'cf7-conditional-fields' ),
			'default' => __( "You must select more files.", 'cf7-conditional-fields' )
		),

		'upload_failed_php_error' => array(
			'description' => __( "Uploading a file fails for PHP error", 'cf7-conditional-fields' ),
			'default' => __( "There was an error uploading the file.", 'cf7-conditional-fields' )
		)
	) );
}


/* Tag generator */

add_action( 'wpcf7_admin_init', 'wpcf7_add_tag_generator_multifile', 60, 0 );

function wpcf7_add_tag_generator_multifile() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'multifile', __( 'CF Multifile', 'cf7-conditional-fields' ),
		'wpcf7_tag_generator_multifile' );
}

function wpcf7_tag_generator_multifile( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = 'multifile';

	$description = __( "Generate a form-tag for a file uploading field that supports multiple file uploads. For more details, see %s.", 'cf7-conditional-fields' );

	$desc_link = wpcf7_link( __( 'https://conditional-fields-cf7.bdwm.be/multifile/', 'wpcf7cf' ), __( 'Multiple file uploads and attachment', 'wpcf7cf' ) );

    ?>
    <div class="control-box">
    <fieldset>
    <legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

    <table class="form-table">
    <tbody>
        <tr>
        <th scope="row"><?php echo esc_html( __( 'Field type', 'cf7-conditional-fields' ) ); ?></th>
        <td>
            <fieldset>
            <legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'cf7-conditional-fields' ) ); ?></legend>
            <label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'cf7-conditional-fields' ) ); ?></label>
            </fieldset>
        </td>
        </tr>

        <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'cf7-conditional-fields' ) ); ?></label></th>
        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
        </tr>

        <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-limit' ); ?>"><?php echo esc_html( __( "File size limit (bytes)", 'cf7-conditional-fields' ) ); ?></label></th>
        <td><input type="text" name="limit" class="filesize oneline option" id="<?php echo esc_attr( $args['content'] . '-limit' ); ?>" /></td>
        </tr>

        <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-filetypes' ); ?>"><?php echo esc_html( __( 'Acceptable file types', 'cf7-conditional-fields' ) ); ?></label></th>
        <td><input type="text" name="filetypes" class="filetype oneline option" id="<?php echo esc_attr( $args['content'] . '-filetypes' ); ?>" /></td>
        </tr>

        <tr>
        <th scope="row"><?php echo esc_html( __( 'Multiple file uploads', 'cf7-conditional-fields' ) ); ?></th>
        <td>
            <fieldset>
                <legend class="screen-reader-text"><?php echo esc_html( __( 'Options', 'cf7-conditional-fields' ) ); ?></legend>
                <label><?php echo esc_html( __( 'Min files', 'cf7-conditional-fields' ) ); ?> <input type="number" min="0" name="min" class="numeric option" /></label>
                &ndash; 
                <label><?php echo esc_html( __( 'Max files', 'cf7-conditional-fields' ) ); ?> <input type="number" min="1" name="max" class="numeric option" /></label><br />
                <label><?php echo esc_html( __( 'Total file size limit (bytes)', 'cf7-conditional-fields' ) ); ?> <input type="text" name="limit_total" class="filesize oneline option" /></label>
            </fieldset>
        </td>
        </tr>

        <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'cf7-conditional-fields' ) ); ?></label></th>
        <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
        </tr>

        <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'cf7-conditional-fields' ) ); ?></label></th>
        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
        </tr>

    </tbody>
    </table>
    </fieldset>
    </div>

    <div class="insert-box">
        <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

        <div class="submitbox">
        <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'cf7-conditional-fields' ) ); ?>" />
        </div>

        <br class="clear" />

        <p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To attach the file uploaded through this field to mail, you need to insert the corresponding mail-tag (%s) into the File Attachments field on the Mail tab.", 'cf7-conditional-fields' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
    </div>
    <?php
}

function wpcf7cf_multifile_get_limit_total_option($tag) {
    $default = MB_IN_BYTES;

    $pattern = '/^limit_total:([1-9][0-9]*)([kKmM]?[bB])?$/';

    $matches = $tag->get_first_match_option( $pattern );

    if ( $matches ) {
        $size = (int) $matches[1];

        if ( ! empty( $matches[2] ) ) {
            $kbmb = strtolower( $matches[2] );

            if ( 'kb' == $kbmb ) {
                $size *= KB_IN_BYTES;
            } elseif ( 'mb' == $kbmb ) {
                $size *= MB_IN_BYTES;
            }
        }

        return $size;
    }

    return (int) $default;
}

function wpcf7cf_multifile_get_max_option($tag) {

    $default = 10;
    
    $option = $tag->get_option( 'max', 'int', true );

    if ( $option ) {
        return (int) $option;
    }

    $matches_a = $tag->get_all_match_options(
        '%^(?:[0-9]*x?[0-9]*)?/([0-9]+)$%' );

    foreach ( (array) $matches_a as $matches ) {
        if ( isset( $matches[1] ) && '' !== $matches[1] ) {
            return (int) $matches[1];
        }
    }

    return $default;

}

function wpcf7cf_multifile_get_min_option($tag) {

    $default = 0;
    
    $option = $tag->get_option( 'min', 'int', true );

    if ( $option ) {
        return (int) $option;
    }

    $matches_a = $tag->get_all_match_options(
        '%^(?:[0-9]*x?[0-9]*)?/([0-9]+)$%' );

    foreach ( (array) $matches_a as $matches ) {
        if ( isset( $matches[1] ) && '' !== $matches[1] ) {
            return (int) $matches[1];
        }
    }

    return $default;

}