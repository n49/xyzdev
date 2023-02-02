<div class="control-box">

    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ), $description ); ?></legend>
        <p>Optional: use the <em>Summary</em> tab to create your own summary template</p>
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