<div class="control-box">

    <fieldset>

        <legend><?php echo sprintf( esc_html( $description ), $description ); ?></legend>

        <table class="form-table">
            <tbody>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'cf7-conditional-fields' ) ); ?></label></th>
                <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-value-1' ); ?>"><?php echo esc_html( __( 'Value 1', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Show','wpcf7cf') ?>" data-default="<?php _e('Show','wpcf7cf') ?>" name="value-1" class="oneline" id="<?php echo esc_attr( $args['content'] . '-value-1' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-value-2' ); ?>"><?php echo esc_html( __( 'Value 2', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Hide','wpcf7cf') ?>" data-default="<?php _e('Hide','wpcf7cf') ?>" name="value-2" class="oneline" id="<?php echo esc_attr( $args['content'] . '-value-2' ); ?>" /></td>
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

    <p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'cf7-conditional-fields' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
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
</script>