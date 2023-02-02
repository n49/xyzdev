<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>

        <table class="form-table">
            <tbody>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'cf7-conditional-fields' ) ); ?></label></th>
                <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-min' ); ?>"><?php echo esc_html( __( 'Min', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Min','wpcf7cf') ?>" data-default="<?php _e('Show','wpcf7cf') ?>" name="min" class="oneline option" id="<?php echo esc_attr( $args['content'] . '-min' ); ?>" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-max' ); ?>"><?php echo esc_html( __( 'Max', 'wpcf7cf' ) ); ?></label></th>
                <td><input type="text" placeholder="<?php _e('Max','wpcf7cf') ?>" data-default="<?php _e('Hide','wpcf7cf') ?>" name="max" class="oneline option" id="<?php echo esc_attr( $args['content'] . '-max' ); ?>" /></td>
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
</div>