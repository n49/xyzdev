<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>

        <table class="form-table">
            <tbody>

            <p>Note: tag generator will only work after <a href="https://wordpress.org/support/topic/proposal-for-change-in-tag-generator-can-i-make-a-pull-request/">this change</a> has been made in CF7 core</a></p>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'cf7-conditional-fields' ) ); ?></label></th>
                <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-title' ); ?>"><?php echo esc_html( __( 'title', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Title','wpcf7cf') ?>" data-default="<?php _e('Title','wpcf7cf') ?>" name="title" class="oneline txtOption" id="<?php echo esc_attr( $args['content'] . '-title' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-next' ); ?>"><?php echo esc_html( __( 'next', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Next','wpcf7cf') ?>" data-default="<?php _e('Next','wpcf7cf') ?>" name="next" class="oneline txtOption" id="<?php echo esc_attr( $args['content'] . '-next' ); ?>" /></td>
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
</div>

<script type="text/javascript">
    jQuery(function($){
        $(document).on('change', 'select', function(){
            var $this = $(this),
                value = $this.val();

            if (! value)
                return;

            $('input[name="'+$this.attr('id')+'"]').val(value).trigger('change');
        });
    });

    // overwrite taggen.compose

    // wpcf7.taggen.compose = function( tagType, $form ) {
	// 	var name = $form.find( 'input[name="name"]' ).val();
	// 	var scope = $form.find( '.scope.' + tagType );

	// 	if ( ! scope.length ) {
	// 		scope = $form;
	// 	}

	// 	var options = [];

	// 	scope.find( 'input.option' ).not( ':checkbox,:radio' ).each( function( i ) {
	// 		var val = $( this ).val();

	// 		if ( ! val ) {
	// 			return;
	// 		}

	// 		if ( $( this ).hasClass( 'filetype' ) ) {
	// 			val = val.split( /[,|\s]+/ ).join( '|' );
	// 		}

	// 		if ( $( this ).hasClass( 'color' ) ) {
	// 			val = '#' + val;
	// 		}

	// 		if ( 'class' == $( this ).attr( 'name' ) ) {
	// 			$.each( val.split( ' ' ), function( i, n ) {
	// 				options.push( 'class:' + n );
	// 			} );
	// 		} else {
	// 			options.push( $( this ).attr( 'name' ) + ' ' + val );
	// 		}
	// 	} );

	// 	scope.find( 'input:checkbox.option' ).each( function( i ) {
	// 		if ( $( this ).is( ':checked' ) ) {
	// 			options.push( $( this ).attr( 'name' ) );
	// 		}
	// 	} );

	// 	scope.find( 'input:radio.option' ).each( function( i ) {
	// 		if ( $( this ).is( ':checked' ) && ! $( this ).hasClass( 'default' ) ) {
	// 			options.push( $( this ).attr( 'name' ) + ':' + $( this ).val() );
	// 		}
	// 	} );

	// 	if ( 'radio' == tagType ) {
	// 		options.push( 'default:1' );
	// 	}

	// 	options = ( options.length > 0 ) ? options.join( ' ' ) : '';

	// 	var value = '';

	// 	if ( scope.find( ':input[name="values"]' ).val() ) {
	// 		$.each(
	// 			scope.find( ':input[name="values"]' ).val().split( "\n" ),
	// 			function( i, n ) {
	// 				value += ' "' + n.replace( /["]/g, '&quot;' ) + '"';
	// 			}
	// 		);
	// 	}

	// 	var components = [];

	// 	$.each( [ tagType, name, options, value ], function( i, v ) {
	// 		v = $.trim( v );

	// 		if ( '' != v ) {
	// 			components.push( v );
	// 		}
	// 	} );

	// 	components = $.trim( components.join( ' ' ) );
	// 	components = '[' + components + ']';

	// 	var content = scope.find( ':input[name="content"]' ).val();
	// 	content = $.trim( content );

	// 	if ( content ) {
	// 		components += ' ' + content + ' [/' + tagType + ']';
	// 	}

	// 	return components;
	// };

</script>