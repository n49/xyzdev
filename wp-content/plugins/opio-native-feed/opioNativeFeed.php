<?php
/*
    Plugin Name: OPIO Native Feed
    Description: Wordpress plugin to install native review feed for your entity
    Author: Dhiraj Timalsina
    Version: 1.0.0
*/
    class Opio_Native_feed {

        public function __construct($entity_id) {
          $this->entity_id = $entity_id;
          add_shortcode( 'opiofeed', array( $this, 'setup_shortcode' ) );
          add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
      }

      public function create_plugin_settings_page() {
    	// Add the menu item and page
         $page_title = 'OPIO Native Feed Configuration';
         $menu_title = 'OPIO Native Feed';
         $capability = 'manage_options';
         $slug = 'opio_native_feed';
         $callback = array( $this, 'plugin_settings_page_content' );
         $icon = 'dashicons-admin-comments';
         $position = 100;
         add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
     }

     public function plugin_settings_page_content() {
        if( isset($_POST['updated']) && $_POST['updated'] === 'true' ){
            $this->handle_form();
        } ?>
        <div class="wrap">
          <h2>OPIO Native Feed Configuration</h2>
          <form method="POST">
            <input type="hidden" name="updated" value="true" />
            <?php wp_nonce_field( 'awesome_update', 'awesome_form' ); ?>
            <table class="form-table">
               <tbody>
                <tr>
                  <th><label for="username">Business Name: </label></th>
                  <td><input name="username" id="username" type="text" value="<?php echo get_option('entity_name'); ?>" class="regular-text" /></td>
              </tr>
              <tr>
                  <th><label for="entity_id">Entity ID:</label></th>
                  <td><input name="entity_id" id="entity_id" type="text" value="<?php echo get_option('entity_id'); ?>" class="regular-text" /></td>
                  <p>
                    Your Entity ID can be received from your OPIO Dashboard
                </p>
            </tr>
            <tr>
              <th><label for="entity_id">Shortcode Status:</label></th>
              <td><?php if(shortcode_exists('opiofeed')) {
                 echo 'Working. Use [opiofeed] shortcode anywhere in your posts / pages';
             }
             else {
                echo 'Not working';
            }
            ?></tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Activate Feed!">
    </p>
</form>
</div> <?php
}

public function handle_form() {
    if( ! isset( $_POST['awesome_form'] ) || ! wp_verify_nonce( $_POST['awesome_form'], 'awesome_update' ) ){ ?>
     <div class="error">
         <p>Sorry, your nonce was not correct. Please try again.</p>
         </div> <?php
         exit;
     } else {
        $username = sanitize_text_field( $_POST['username'] );
        $entityid =  sanitize_text_field( $_POST['entity_id'] );
        if(strlen($entityid) === 17) {
            update_option( 'entity_name', $username );
            update_option( 'entity_id', $entityid );?>
            <div class="updated">
                <p>Your [opiofeed] shortcode is ready</p>
                </div> <?php
            }
            else { ?>
                <div class="error">
                 <p>Your entity ID is incorrect.</p>
                 </div> <?php
             }
             
         }
     }
     
     public function setup_shortcode($atts =[], $content = null, $tag= '') {
		 $feed = wp_remote_get( "http://34.225.94.59/reviewFeed?entityid={$atts['entity']}" ,
             array(
            'headers' => array( 'allow-native-index' => true ) 
             ));
        return $feed['body'];
    } 
}
new Opio_Native_feed(get_option('entity_id'));
