<?php
// touch(get_stylesheet_directory() . '/js/bookUnit.js');
/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here


/*------------------------------------*\
	Theme Support
\*------------------------------------*/
add_filter('wpcf7_validate', 'send_ga_events', 20, 2);

// add_action('wpcf7cf_validate_step', 'send_ga_events');


add_filter('wpcf7_validate_text', 'alphanumeric_validation_filter', 20, 2);
add_filter('wpcf7_validate_text*', 'alphanumeric_validation_filter', 20, 2);

function sendEvent($category, $action, $label)
{
    // GA curl
    $api_secret = "Hu-HF8VWTIO0-yhMVjF-Aw";
    $$measurement_id = "G-GC6WQH9DPC";
    $url = "https://www.google-analytics.com/mp/collect?measurement_id=G-GC6WQH9DPC&api_secret=$api_secret";

    $req = curl_init($url);
    $postfields = ["client_id" => "1514214584.1654713347", "events" => ["name" => $category]];
    curl_setopt_array($req, array(
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($postfields)
    ));
    // 	var_dump($url);
    // Send the request
    $response = curl_exec($req);
    // 	var_dump($response);
    
}

function send_ga_events($result, $tag)
{
    // 	    $tag = new WPCF7_Shortcode( $tag );
    // var_dump($_POST["_wpcf7cf_steps"]);
    $stepsString = $_POST["_wpcf7cf_steps"];
    $steps = json_decode(stripslashes($stepsString) , true);
    $currentStep = $steps["currentStep"];
    $hasInvalidField = $result->get_invalid_fields();
    //
    if ($currentStep == 1 && !$hasInvalidField)
    {
        // 		var_dump('sending yes');
        sendEvent("RentNow_Step_1", "success", "step1submission");
    }
    if ($currentStep == 2 && !$hasInvalidField)
    {
        // 		var_dump('sending yes');
        sendEvent("RentNow_Step_2", "success", "step2submission");
    }

    if ($currentStep == 3 && !$hasInvalidField)
    {
        // 		var_dump('sending yes');
        sendEvent("Rental_Complete", "success", "rentalcompletion");
    }

    return $result;
}

function alphanumeric_validation_filter($result, $tag)
{
    $tag = new WPCF7_Shortcode($tag);
    //var_dump('hmm', $tag);
    if ('first-name' === $tag->name)
    {
        $firstname = isset($_POST['first-name']) ? trim($_POST['first-name']) : '';
        if (!$firstname)
        {
            $result->invalidate($tag, 'Invalid first name');
        }
    }
    if ('email' === $tag->name)
    {
        //$result->invalidate( $tag, 'Alleen letters zijn toegestaan.' );
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $result->invalidate($tag, 'Alleen letters zijn toegestaan.');

        }
    }

    if ('carddate' === $tag->name)
    {
        $carddate = isset($_POST['carddate']) ? trim($_POST['carddate']) : '';

        $cardDateNumbers = explode("/", $carddate);

        if (count($cardDateNumbers) !== 2)
        {
            $result->invalidate($tag, 'Invalid card date format. Please use MM/YY format.');

        }
        //var_dump($cardDateNumbers);
        if (strlen($cardDateNumbers[0]) !== 2 || strlen($cardDateNumbers[1]) !== 2)
        {
            $result->invalidate($tag, 'Alleen letters zijn toegestaan.');

        }
    }
    if ('cardnumber' === $tag->name)
    {

        function luhn_check($number)
        {

            // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
            $number = preg_replace('/\D/', '', $number);

            // Set the string length and parity
            $number_length = strlen($number);
            $parity = $number_length % 2;

            // Loop through each digit and do the maths
            $total = 0;
            for ($i = 0;$i < $number_length;$i++)
            {
                $digit = $number[$i];
                // Multiply alternate digits by two
                if ($i % 2 == $parity)
                {
                    $digit *= 2;
                    // If the sum is two digits, add them together (in effect)
                    if ($digit > 9)
                    {
                        $digit -= 9;
                    }
                }
                // Total up the digits
                $total += $digit;
            }

            // If the total mod 10 equals 0, the number is valid
            return ($total % 10 == 0) ? true : false;

        }

        $cardnumber = isset($_POST['cardnumber']) ? trim($_POST['cardnumber']) : '';
        //var_dump('got it', luhn_check($cardnumber));
        $validcard = luhn_check($cardnumber);
        if (!$cardnumber || !$validcard)
        {
            $result->invalidate($tag, 'Please enter a valid credit card number.');
            //$result['valid'] = true;
            
        }

    }

    return $result;
}

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 1180, '', true);
    add_image_size('large-2', 1180, 740, true);
    add_image_size('medium', 840, 520, true);
    add_image_size('medium-2', 840, 580, true);
    add_image_size('medium-3', 840, 460, true);
    add_image_size('medium-4', 840, 640, true);
    add_image_size('small', 300, 180, true);
    add_image_size('small-2', 300, 210, true);
    add_image_size('small-3', 300, 300, true);
    add_image_size('small-4', 300, 240, true);
    add_image_size('small-5', 300, 110, true);
    add_image_size('slider', 2000, 500, true);

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// Navigation
function html5blank_nav()
{
    wp_nav_menu(array(
        'theme_location' => 'header-menu',
        'menu' => '',
        'container' => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id' => '',
        'menu_class' => 'menu',
        'menu_id' => '',
        'echo' => true,
        'fallback_cb' => 'wp_page_menu',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'items_wrap' => '<ul>%3$s</ul>',
        'depth' => 0,
        'walker' => ''
    ));
}

// Load Scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin())
    {
        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array() , '2.7.1', true);
        wp_enqueue_script('modernizr');

        wp_register_script('meanmenu', get_template_directory_uri() . '/js/jquery.meanmenu.min.js', array(
            'jquery'
        ) , '2.0.6');
        wp_enqueue_script('meanmenu');

        wp_register_script('menuinit', get_template_directory_uri() . '/js/menuinit.js', array(
            'jquery'
        ) , '1.0.0');
        wp_enqueue_script('menuinit');

        wp_register_script('waypoints', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array(
            'jquery'
        ) , '4.0.1', true);
        wp_enqueue_script('waypoints');

        wp_register_script('sticky', get_template_directory_uri() . '/js/sticky.min.js', array(
            'jquery'
        ) , '4.0.1', true);
        wp_enqueue_script('sticky');

        wp_register_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(
            'jquery'
        ) , '1.8.1', true);
        wp_enqueue_script('slick');

        wp_register_script('lightgallery', get_template_directory_uri() . '/js/lightgallery.min.js', array(
            'jquery'
        ) , '1.6.1', true);
        wp_enqueue_script('lightgallery');

        wp_register_script('tabslet', get_template_directory_uri() . '/js/jquery.tabslet.min.js', array(
            'jquery'
        ) , '1.7.4', true);
        wp_enqueue_script('tabslet');

        wp_register_script('cookie', get_template_directory_uri() . '/js/js.cookie.js', array(
            'jquery'
        ) , '2.2.0', true);
        wp_enqueue_script('cookie');

        wp_register_script('datepicker', get_template_directory_uri() . '/js/datepicker.min.js', array(
            'jquery'
        ) , '2.2.3', true);
        wp_enqueue_script('datepicker');

        wp_register_script('flatpickr', get_template_directory_uri() . '/js/flatpickr.js', array(
            'jquery'
        ) , '4.6.4', true);
        wp_enqueue_script('flatpickr');

        wp_register_script('chosen', get_template_directory_uri() . '/js/chosen.jquery.min.js', array(
            'jquery'
        ) , '1.6.2', true);
        wp_enqueue_script('chosen');

        wp_register_script('simple-accordion', get_template_directory_uri() . '/js/simple.accordion.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('simple-accordion');

        wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array(
            'jquery'
        ) , '1.0.6', true);
        wp_enqueue_script('html5blankscripts');
    }
}

// Load Conditional Scripts
function html5blank_conditional_scripts()
{
    if (is_page_template('template-move-out.php'))
    {
        wp_register_script('move_out', get_template_directory_uri() . '/js/move_out.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('move_out');
    }

    if (is_page_template('template-about.php'))
    {
        wp_register_script('timeline', get_template_directory_uri() . '/js/timeline.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('timeline');
    }
    //is_page_template('template-locations-location.php') ||
    if (is_page_template('template-locations.php') || is_page_template('template-storage-sizes.php') || is_page_template('template-storage-2.php') || is_page_template('template-storage.php') || is_page_template('template-pricing.php') || is_page_template('template-contact.php') || is_page_template('template-storage-information.php'))
    {
        wp_register_script('googlemapapi', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBkINJMV7xbxQzzefXijdLJKE7kGxsSgUo&extension=.js&libraries=geometry,places&v=3', array(
            'jquery'
        ));
        wp_enqueue_script('googlemapapi');

        wp_register_script('mixitup', get_template_directory_uri() . '/js/mixitup.min.js', array(
            'jquery'
        ) , '3.3.1', true);
        wp_enqueue_script('mixitup');

        wp_register_script('locations', get_template_directory_uri() . '/js/locations.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('locations');
    }

    if (is_page_template('template-locations-book.php') && false)
    {
        wp_register_script('google_optimize', get_template_directory_uri() . '/js/google_optimize.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('google_optimize');
    }

    if (is_page_template('template-locations-book.php'))
    {
        wp_register_script('bookUnit', get_template_directory_uri() . '/js/bookUnit.js', array(
            'jquery'
        ) , time() , true);
        wp_enqueue_script('bookUnit');
    }

    if (is_page_template('template-storage-2.php'))
    {
        wp_register_script('googlemapapi', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBkINJMV7xbxQzzefXijdLJKE7kGxsSgUo&extension=.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('googlemapapi');

        wp_register_script('map', get_template_directory_uri() . '/js/map.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('map');
    }

    if (is_singular('locations'))
    {
        wp_register_script('flickity', get_template_directory_uri() . '/js/flickity.pkgd.min.js', array(
            'jquery'
        ) , '2.2.1', true);
        wp_enqueue_script('flickity');

        wp_register_style('flickitycss', get_template_directory_uri() . '/css/flickity.css', array() , '2.2.1', 'all');
        wp_enqueue_style('flickitycss');
    }
    if (is_page_template('template-customer-details.php') && !is_page(19403))
    {
        wp_register_script('customer_details', get_template_directory_uri() . '/js/customer_details.js', array(
            'jquery'
        ) , '1.0.3', true);
        wp_enqueue_script('customer_details');
    }
    if (is_page(19403))
    {
        wp_register_script('customer_details', get_template_directory_uri() . '/js/customer_details_pr.js', array(
            'jquery'
        ) , '1.0.3', true);
        wp_enqueue_script('customer_details');
    }
    if (is_page(9048))
    {
        wp_register_script('rent_now', get_template_directory_uri() . '/js/rent_now.js', array(
            'jquery'
        ) , '1.0.1', true);
        wp_enqueue_script('rent_now');
    }
    if (is_page_template('template-rent-now.php'))
    {
        wp_register_script('clipboard', get_template_directory_uri() . '/js/clipboard.min.js', array(
            'jquery'
        ) , '2.0.11', true);
        wp_enqueue_script('clipboard');

        wp_register_script('rentform', get_template_directory_uri() . '/js/rentform.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('rentform');

        wp_register_script('rent_new', get_template_directory_uri() . '/js/rent_now_new.js', array(
            'jquery'
        ) , '1.0.0', true);
        wp_enqueue_script('rent_new');
    }
    if (is_page_template('template-choose-your-space.php'))
    {
        wp_register_script('chooseYourSpace', get_template_directory_uri() . '/js/chooseYourSpace.js', array(
            'jquery'
        ) , '1.0.3', true);
        wp_enqueue_script('chooseYourSpace');
    }
}

// Load Styles
function html5blank_styles()
{
    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array() , '1.0', 'all');
    wp_enqueue_style('normalize');

    wp_register_style('meanmenucss', get_template_directory_uri() . '/css/meanmenu.css', array() , '2.0.6', 'all');
    wp_enqueue_style('meanmenucss');

    wp_register_style('animate', get_template_directory_uri() . '/css/animate.css', array() , '3.5.2', 'all');
    wp_enqueue_style('animate');

    wp_register_style('slickcss', get_template_directory_uri() . '/css/slick.css', array() , '1.4.1', 'all');
    wp_enqueue_style('slickcss');

    wp_register_style('lightgallerycss', get_template_directory_uri() . '/css/lightgallery.css', array() , '1.7.3', 'all');
    wp_enqueue_style('lightgallerycss');

    wp_register_style('tabsletcss', get_template_directory_uri() . '/css/tabslet.css', array() , '1.6.13', 'all');
    wp_enqueue_style('tabsletcss');

    wp_register_style('datepickercss', get_template_directory_uri() . '/css/datepicker.css', array() , '2.2.3', 'all');
    wp_enqueue_style('datepickercss');

    wp_register_style('chosencss', get_template_directory_uri() . '/css/chosen.css', array() , '1.6.2', 'all');
    wp_enqueue_style('chosencss');

    wp_register_style('flatpickrcss', get_template_directory_uri() . '/css/flatpickr.css', array() , '4.6.6', 'all');
    wp_enqueue_style('flatpickrcss');

    wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array() , '1.0.16', 'all');
    wp_enqueue_style('html5blank');
}

// Register Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank') // Main Navigation
        
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home())
    {
        $key = array_search('blog', $classes);
        if ($key > - 1)
        {
            unset($classes[$key]);
        }
    }
    elseif (is_page())
    {
        $classes[] = sanitize_html_class($post->post_name);
    }
    elseif (is_singular())
    {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    register_sidebar(array(
        'name' => __('Sidebar', 'html5blank') ,
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="title">',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)) ,
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')) ,
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');

{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback))
    {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback))
    {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin())
    {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1))
        {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style'])
    {
        $tag = 'div';
        $add_below = 'comment';
    }
    else
    {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ('div' != $args['style']): ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php
    endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['180']); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>') , get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0'): ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php
    endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
		<?php
    printf(__('%1$s at %2$s') , get_comment_date() , get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)') , '  ', '');
?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge($args, array(
        'add_below' => $add_below,
        'depth' => $depth,
        'max_depth' => $args['max_depth']
    ))) ?>
	</div>
	<?php if ('div' != $args['style']): ?>
	</div>
	<?php
    endif; ?>
<?php
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

function create_post_type_html5()
{
    register_post_type('promotions', array(
        'labels' => array(
            'name' => __('Promotions', 'html5blank') ,
            'singular_name' => __('Promotion', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New Promotion', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit Promotion', 'html5blank') ,
            'new_item' => __('New Promotion', 'html5blank') ,
            'view' => __('View Promotion', 'html5blank') ,
            'view_item' => __('View Promotion', 'html5blank') ,
            'search_items' => __('Search Promotion', 'html5blank') ,
            'not_found' => __('No Promotions found', 'html5blank') ,
            'not_found_in_trash' => __('No Promotions found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => false,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ) ,
        'can_export' => true
    ));

    register_post_type('faq', array(
        'labels' => array(
            'name' => __('FAQ', 'html5blank') ,
            'singular_name' => __('FAQ Item', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New FAQ Item', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit FAQ Item', 'html5blank') ,
            'new_item' => __('New FAQ Item', 'html5blank') ,
            'view' => __('View FAQ Item', 'html5blank') ,
            'view_item' => __('View FAQ Item', 'html5blank') ,
            'search_items' => __('Search FAQ Item', 'html5blank') ,
            'not_found' => __('No FAQ Items found', 'html5blank') ,
            'not_found_in_trash' => __('No FAQ Items found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => false,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
        ) ,
        'can_export' => true,
        'taxonomies' => array(
            'faq-category'
        )
    ));

    register_post_type('services', array(
        'labels' => array(
            'name' => __('Services', 'html5blank') ,
            'singular_name' => __('Service', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New Service', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit Service', 'html5blank') ,
            'new_item' => __('New Service', 'html5blank') ,
            'view' => __('View Service', 'html5blank') ,
            'view_item' => __('View Service', 'html5blank') ,
            'search_items' => __('Search Service', 'html5blank') ,
            'not_found' => __('No Services found', 'html5blank') ,
            'not_found_in_trash' => __('No Services found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => false,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
        ) ,
        'can_export' => true
    ));

    register_post_type('locations', array(
        'labels' => array(
            'name' => __('Locations', 'html5blank') ,
            'singular_name' => __('Location', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New Location', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit Location', 'html5blank') ,
            'new_item' => __('New Location', 'html5blank') ,
            'view' => __('View Location', 'html5blank') ,
            'view_item' => __('View Location', 'html5blank') ,
            'search_items' => __('Search Location', 'html5blank') ,
            'not_found' => __('No Locations found', 'html5blank') ,
            'not_found_in_trash' => __('No Locations found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => true,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
        ) ,
        'can_export' => true
    ));

    register_post_type('units', array(
        'labels' => array(
            'name' => __('Storage Units', 'html5blank') ,
            'singular_name' => __('Unit', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New Unit', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit Unit', 'html5blank') ,
            'new_item' => __('New Unit', 'html5blank') ,
            'view' => __('View Unit', 'html5blank') ,
            'view_item' => __('View Unit', 'html5blank') ,
            'search_items' => __('Search Unit', 'html5blank') ,
            'not_found' => __('No Units found', 'html5blank') ,
            'not_found_in_trash' => __('No Units found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ) ,
        'can_export' => true,
        'taxonomies' => array(
            'unit-category'
        )
    ));

    register_post_type('reviews', array(
        'labels' => array(
            'name' => __('Reviews', 'html5blank') ,
            'singular_name' => __('Review', 'html5blank') ,
            'add_new' => __('Add New', 'html5blank') ,
            'add_new_item' => __('Add New Review', 'html5blank') ,
            'edit' => __('Edit', 'html5blank') ,
            'edit_item' => __('Edit Review', 'html5blank') ,
            'new_item' => __('New Review', 'html5blank') ,
            'view' => __('View Review', 'html5blank') ,
            'view_item' => __('View Review', 'html5blank') ,
            'search_items' => __('Search Review', 'html5blank') ,
            'not_found' => __('No Reviews found', 'html5blank') ,
            'not_found_in_trash' => __('No Reviews found in Trash', 'html5blank')
        ) ,
        'public' => true,
        'hierarchical' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ) ,
        'can_export' => true,
    ));
}

add_action('init', 'create_faq_taxonomies', 0);
add_action('init', 'create_unit_taxonomies', 0);

function create_faq_taxonomies()
{
    $labels = array(
        'name' => __('FAQ Categories', 'html5blank') ,
        'singular_name' => __('FAQ Category', 'html5blank') ,
        'search_items' => __('Search FAQ Categories', 'html5blank') ,
        'all_items' => __('All FAQ Categories', 'html5blank') ,
        'parent_item' => __('Parent FAQ Category', 'html5blank') ,
        'parent_item_colon' => __('Parent FAQ Category:', 'html5blank') ,
        'edit_item' => __('Edit FAQ Category', 'html5blank') ,
        'update_item' => __('Update FAQ Category', 'html5blank') ,
        'add_new_item' => __('Add New FAQ Category', 'html5blank') ,
        'new_item_name' => __('New FAQ Category Name', 'html5blank') ,
        'menu_name' => __('FAQ Categories', 'html5blank') ,
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'faq-category',
            'hierarchical' => true
        ) ,
    );

    register_taxonomy('faq-category', array(
        'faq'
    ) , $args);
}

function create_unit_taxonomies()
{
    $labels = array(
        'name' => __('Unit Categories', 'html5blank') ,
        'singular_name' => __('Unit Category', 'html5blank') ,
        'search_items' => __('Search Unit Categories', 'html5blank') ,
        'all_items' => __('All Unit Categories', 'html5blank') ,
        'parent_item' => __('Parent Unit Category', 'html5blank') ,
        'parent_item_colon' => __('Parent Unit Category:', 'html5blank') ,
        'edit_item' => __('Edit Unit Category', 'html5blank') ,
        'update_item' => __('Update Unit Category', 'html5blank') ,
        'add_new_item' => __('Add New Unit Category', 'html5blank') ,
        'new_item_name' => __('New Unit Category Name', 'html5blank') ,
        'menu_name' => __('Unit Categories', 'html5blank') ,
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'unit-category',
            'hierarchical' => true
        ) ,
    );

    register_taxonomy('unit-category', array(
        'units'
    ) , $args);
}

/*------------------------------------*\
	Add First/Last Class to Menu
\*------------------------------------*/

function html5_first_and_last_menu_class($items)
{
    $items[1]->classes[] = 'first';
    $items[count($items) ]->classes[] = 'last';
    return $items;
}
add_filter('wp_nav_menu_objects', 'html5_first_and_last_menu_class');

/*------------------------------------*\
	ACF Options Page
\*------------------------------------*/

if (function_exists('acf_add_options_page'))
{
    $parent = acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'redirect' => 'Theme Settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Global Options',
        'menu_title' => __('Global Options', 'html5blank') ,
        'menu_slug' => "acf-options",
        'parent' => $parent['menu_slug']
    ));

    $languages = array(
        'en',
        'fr'
    );

    foreach ($languages as $lang)
    {
        acf_add_options_sub_page(array(
            'page_title' => 'Options (' . strtoupper($lang) . ')',
            'menu_title' => __('Options (' . strtoupper($lang) . ')', 'html5blank') ,
            'menu_slug' => "options-${lang}",
            'post_id' => $lang,
            'parent' => $parent['menu_slug']
        ));
    }
}

function my_acf_init()
{
    acf_update_setting('google_api_key', 'AIzaSyBkINJMV7xbxQzzefXijdLJKE7kGxsSgUo');
}

add_action('acf/init', 'my_acf_init');

/*------------------------------------*\
	Slick Carousel Shortcode
\*------------------------------------*/

// Remove hook for the default shortcode...
remove_shortcode('gallery', 'gallery_shortcode');
// .. and create a new shortcode with the default WordPress shortcode name (tag) for the gallery
add_shortcode('gallery', function ($atts)
{
    $attrs = shortcode_atts(array(
        'slider' => md5(microtime() . rand()) , // Slider ID (is per default unique)
        'slider_class_name' => '', // Optional slider css class
        'ids' => '', // Comma separated list of image ids
        'size' => 'medium', // Image format (could be an custom image format)
        'slides_to_show' => 1,
        'slides_to_scroll' => 1,
        'dots' => false,
        'infinite' => true,
        'speed' => 300,
        'touch_move' => true,
        'autoplay' => false,
        'autoplay_speed' => 2000,
        'accessibility' => true,
        'arrows' => true,
        'center_mode' => false,
        'center_padding' => '50px',
        'css_ease' => 'ease',
        'dots_class' => 'slick-dots',
        'draggable' => true,
        'easing' => 'linear',
        'fade' => false,
        'focus_on_select' => false,
        'lazy_load' => 'ondemand',
        'on_before_change' => null,
        'pause_on_hover' => true,
        'pause_on_dots_hover' => false,
        'rtl' => false,
        'slide' => 'div',
        'swipe' => true,
        'touch_move' => true,
        'touch_threshold' => 5,
        'use_css' => true,
        'vertical' => false,
        'wait_for_animate' => true
    ) , $atts);

    extract($attrs);

    // Verify if the chosen image format really exist
    if (!in_array($size, get_intermediate_image_sizes()))
    {
        echo 'Image Format <strong>' . $size . '</strong> Not Available!';
        exit;
    }

    // Iterate over attribute array, cleanup and make the array elements JavaScript ready
    foreach ($attrs as $key => $attr)
    {
        // CamelCase the array keys
        $new_key_name = lcfirst(str_replace(array(
            ' ',
            'Css'
        ) , array(
            '',
            'CSS'
        ) , ucwords(str_replace('_', ' ', $key))));

        // Remove unnecessary array elements
        if (in_array($key, array(
            'size',
            'ids',
            'slider_class_name'
        )) || strpos($key, '_') !== false)
        {
            unset($attrs[$key]);
        }

        // Fix the type before passing the array elements to JavaScript
        if (is_numeric($attr))
        {
            $attrs[$new_key_name] = (int)$attr;
        }
        else if (is_bool($attr) || (strpos($attr, 'true') !== false || strpos($attr, 'false') !== false))
        {
            $attrs[$new_key_name] = filter_var($attr, FILTER_VALIDATE_BOOLEAN);
        }
        else if (is_int($attr))
        {
            $attrs[$new_key_name] = filter_var($attr, FILTER_VALIDATE_INT);
        }
    }

    // Create an empty variable for return html content
    $html_output = '';

    // Check if the slider should be unique and do some unique stuff (*optional)
    if (ctype_xdigit($slider) && strlen($slider) === 32)
    {
        $is_unique = true;
    }
    else
    {
        $is_unique = false;
    }

    $count_array = explode(",", $ids);
    $count = count($count_array);

    // Build the html slider structure (open)
    $html_output .= '<div class="' . $slider_class_name . ' ' . $slider . ' slider wp-slick-slider has-lightbox slide-count-' . $count . '">';

    // Iterate over the comma separated list of image ids and keep only the real numeric ids
    foreach (array_filter(array_map(function ($id)
    {
        return (int)$id;
    }
    , explode(',', $ids))) as $media_id)
    {
        $image_full = wp_get_attachment_image_src($media_id, 'full');
        $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);

        // Get the image by media id and build the html div group with the image source, width and height
        if ($image_data = wp_get_attachment_image_src($media_id, $size))
        {
            $html_output .= '<div class="img-wrap"><a href="' . esc_url($image_full[0]) . '"><img src="' . esc_url($image_data[0]) . '" alt="' . $alt . '" /><span class="btn btn-grey">view all photos</span></a></div>';
        }
    }

    // Close the html slider structure and return the html output
    return $html_output . '</div>';
});

/**
 * Completely disable term archives for this taxonomy.
 * @param  string $taxonomy WordPress taxnomy name
 */

add_action('pre_get_posts', 'jb_disable_tax_archive');

function jb_disable_tax_archive($qry)
{
    if (is_admin()) return;

    if (is_tax('faq-category') || is_tax('unit-category'))
    {
        $qry->set_404();
    }
}

/*------------------------------------*\
	Allow Contact Form 7 to prefill cookies
\*------------------------------------*/

function cf7_reserve_unit()
{
    $content = '';

    if (isset($_COOKIE['unit']))
    {
        // 		$unit = json_decode(stripslashes($_COOKIE['unit']), true);
        // 		$unit_id = $unit['unit'];
        // 		$unit_amount = $unit['amount'];
        //         $unit_post = get_post($unit_id);
        //         $content .= 'Unit name: '. $unit_post->post_title . '; Amount: '. $unit_amount;
        $unit_dimensions = $_COOKIE['unitDimensions'];
        $unit_price = $_COOKIE['unitPrice'];
        $location = '';
        if (isset($_COOKIE['unitLocation']))
        {
            $locations = array(
                3 => 'Toronto West',
                4 => 'Etobicoke',
                1 => 'Scarborough',
                2 => 'Mississauga',
                5 => 'Mobile',
                6 => 'Toronto Midtown',
                7 => 'Toronto Downtown'
            );
            $location = 'Location: ' . $locations[intval($_COOKIE['unitLocation']) ] . ', ';
        }

        $content .= $location . 'Unit Dimensions: ' . $unit_dimensions . ', Amount: ' . $unit_price;
    }

    return $content;
}
function cf7_reserve_truck()
{
    $location = '';
    if (isset($_COOKIE['unitLocation']))
    {
        $locations = array(
            3 => 'Toronto West',
            4 => 'Etobicoke',
            1 => 'Scarborough',
            2 => 'Mississauga',
            5 => 'Mobile',
            6 => 'Toronto Midtown',
            7 => 'Toronto Downtown'
        );
        $location = 'Location: ' . $locations[intval($_COOKIE['unitLocation']) ];
    }
    return $location;
}

function location_email_get()
{
    $content = '';
    $locations_email = array(
        3 => 'weston@xyzstorage.com',
        4 => 'lakeshore@xyzstorage.com',
        1 => 'scarborough@xyzstorage.com',
        2 => 'mississauga@xyzstorage.com',
        5 => 'mobile@xyzstorage.com',
        6 => 'laird@xyzstorage.com',
        7 => 'eastern@xyzstorage.com'
    );
    if (isset($_COOKIE['unitLocation']))
    {
        $location_email = $locations_email[intval($_COOKIE['unitLocation']) ];
    }

    return $location_email;
}
function get_quantity()
{
    $content = '';

    if (isset($_COOKIE['unitQuantity']) && isset($_COOKIE['unitType']) && $_COOKIE['unitType'] == '19')
    {
        $content = 'Quantity: ' . $_COOKIE['unitQuantity'];
    }
    return $content;
}

add_shortcode('CF7_RESERVE_UNIT', 'cf7_reserve_unit');
add_shortcode('CF7_RESERVE_TRUCK', 'cf7_reserve_truck');
add_shortcode('RESERVATION_QUANTITY', 'get_quantity');
add_shortcode('LOCATION_EMAIL', 'location_email_get');

add_shortcode("ssm_iframe", 'ssm_iframe_url'); //
add_shortcode("UNIT_SIZE", "unit_size"); //
add_shortcode("UNIT_LOCATION", "unit_location"); //
add_shortcode("UNIT_CLASS", "get_unit_class"); //
add_shortcode("UNIT_RENT", "get_unit_rent"); //
add_shortcode("ADDRESS_1", "get_address_1"); //
add_shortcode("ADDRESS_2", "get_address_2"); //
add_shortcode("LOCATION_PHONE", "get_location_phone"); //
add_shortcode("LOCATION_MAP", "get_location_map"); //
add_shortcode("LOCATION_MAP_CODE", "get_map_code"); //
add_shortcode("UNIT_DISCOUNT", "get_discount");
add_shortcode("UNIT_DISCOUNT_HEADER", "get_promo_header");
add_shortcode("UTM", "get_utm");
add_shortcode("SUB_DATE", "get_sub_date");
add_shortcode("SUB_TIME", "get_sub_time");

add_shortcode("EMAIL_DATE", "email_date");

add_shortcode("RESCHEDULE", "reschedule");
add_shortcode("SCHED", "sched");

function reschedule()
{
    if (isset($_GET['reschedule']))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function sched()
{
    if (isset($_GET['reschedule']))
    {
        return 'rescheduled';
    }
    else
    {
        return 'scheduled';
    }
}

function email_date()
{
    $date = time();
    date_default_timezone_set('America/Toronto');
    return date("Y-m-d, H:i:s", $date);
}

function get_sub_time()
{
    $date = time();
    date_default_timezone_set('America/Toronto');
    return date("H:i:s", $date);
}

function get_sub_date()
{
    $date = time();
    date_default_timezone_set('America/Toronto');
    return date("Y-m-d", $date);
}

function get_utm()
{
    $utm_info = '';
    if (isset($_COOKIE['utm_source']))
    {
        $utm_info .= 'utm_source = ' . $_COOKIE['utm_source'] . "; ";
    }
    if (isset($_COOKIE['utm_campaign']))
    {
        $utm_info .= 'utm_campaign = ' . $_COOKIE['utm_campaign'] . "; ";
    }
    if (isset($_COOKIE['utm_medium']))
    {
        $utm_info .= 'utm_medium = ' . $_COOKIE['utm_medium'] . "; ";
    }
    if (isset($_COOKIE['referrer_url']) && $_COOKIE['referrer_url'] != '')
    {
        $utm_info .= 'referrer_url = ' . $_COOKIE['referrer_url'] . "; ";
    }
    if (isset($_COOKIE['gclid']) && $_COOKIE['gclid'] != '')
    {
        $utm_info .= 'gclid = ' . $_COOKIE['gclid'] . "; ";
    }
    return $utm_info;
}

function get_discount()
{
    $d = '';
    if (isset($_COOKIE['unitDiscounts']))
    {
        $d = $_COOKIE['unitDiscounts'];
    }
    return $d;
}
function get_promo_header()
{
    $h = '';
    if ($_COOKIE['unitDiscounts'] != '')
    {
        $h = 'promotion';
    }
    return $h;
}

function get_map_code()
{
    $locations = array(
        1 => 'UtP6mcTFrJAVMJUP7',
        2 => '1586oLJKNwKgJkz5A',
        3 => 'oHE5jFpzMGL89dsc8',
        4 => 'QDcZXUMnikAX2cow6',
        5 => '1586oLJKNwKgJkz5A',
        6 => 'YYHzMPM4KXjNi7mf9',
        7 => 'oXPc9KiRbLFShbMdA'
    );
    $location = $locations[intval($_COOKIE['unitLocation']) ];
    return $location;
}
function get_this_date()
{
    $date = $_COOKIE['date_xyz1'];
    return $date;
}
function get_location_map()
{
    $map_url_pre = get_site_url() . "/wp-content/uploads/2019/09/xyz_";
    $locations = array(
        1 => 'scarborough_map.png',
        2 => 'mississauga_map.png',
        3 => 'toronto_west_map.png',
        4 => 'etobicoke_map.png',
        5 => 'mississauga_map.png',
        6 => 'toronto_midtown_map.png',
        7 => 'toronto_downtown_map.png'
    );
    return $map_url_pre . $locations[intval($_COOKIE['unitLocation']) ];
}
function get_location_phone()
{
    $locations = array(
        1 => '(416) 208-0188',
        2 => '(905) 206-0035',
        3 => '(416) 604-0404',
        4 => '(416) 201-0101',
        5 => '(416) 253 5353',
        6 => '(416) 203-3331',
        7 => '(416) 463-6363'
    );
    $location = $locations[intval($_COOKIE['unitLocation']) ];
    return $location;
}
function get_address_1()
{
    $locations = array(
        1 => '135 Beechgrove Drive',
        2 => '2480 Stanfield Road',
        3 => '207 Weston Road',
        4 => '2256 Lake Shore Blvd W,',
        5 => 'Mobile Storage',
        6 => '1 Laird Drive',
        7 => '459 Eastern Ave'
    );
    if (isset($_COOKIE['unitLocation']))
    {
        $location = $locations[intval($_COOKIE['unitLocation']) ];
    }
    return $location;
}
function get_address_2()
{
    $locations = array(
        1 => 'Scarborough, ON M1E 3Z3',
        2 => 'Mississauga, ON L4Y 1R6',
        3 => 'Toronto, ON M6N 4Z3',
        4 => 'Etobicoke, ON M8V 1A9',
        5 => '',
        6 => 'Toronto, ON M4G 3S8',
        7 => 'Toronto, ON M4M 1B7'
    );
    $location = $locations[intval($_COOKIE['unitLocation']) ];
    return $location;
}
function get_unit_rent()
{
    $rent;
    if ($_COOKIE['unitPrice'])
    {
        $rent = floatval($_COOKIE['unitPrice']) * 1.13;
        $price_edit = number_format($rent, 2, '.', '');
    }
    return "$" . $price_edit;
}
function get_unit_class()
{
    $unit_length = $_COOKIE['length'];
    $unit_width = $_COOKIE['width'];
    $unitCalculate = intval($unit_length) * intval($unit_width);
    $unitLabel = '';
    if ($unitCalculate == 1)
    {
        $unitLabel = 'parking';
        if ($unit["Height"] == 1) $unitSize = 'Small';
        else $unitSize = 'Large';
    }
    if ($unitCalculate > 2) $unitLabel = "compact";
    if ($unitCalculate >= 25) $unitLabel = "small";
    if ($unitCalculate >= 75) $unitLabel = "medium";
    if ($unitCalculate >= 150) $unitLabel = "large";
    return $unitLabel;

}
function unit_location()
{
    $locations = array(
        3 => 'Toronto West',
        4 => 'Etobicoke',
        1 => 'Scarborough',
        2 => 'Mississauga',
        5 => 'Mobile Storage',
        6 => 'Toronto Midtown',
        7 => 'Toronto Downtown'
    );
    if (isset($_COOKIE['unitLocation']))
    {
        $location = $locations[intval($_COOKIE['unitLocation']) ];
    }
    return $location;
}
function unit_size()
{
    $unit_dimensions = $_COOKIE['unitDimensions'];
    return $unit_dimensions;
}
function ssm_iframe_url()
{
    if ((!isset($_COOKIE['unitRent']) || !isset($_COOKIE['unitLocation']) || !isset($_COOKIE['unitType']) || !isset($_COOKIE['length']) || !isset($_COOKIE['width'])))
    {
        return '<script>window.location.replace("' . get_site_url() . '/storage-locations/");</script>';
    }
    else
    {
        $num = $_COOKIE['unitRent'];
        number_format($num, 2);

        $L_ID = $_COOKIE['unitLocation'];
        $U_ID = $_COOKIE['unitType'];
        $LENGTH = $_COOKIE['length'];
        $BREADTH = $_COOKIE['width'];
        $RENT = number_format($num, 2);
        $height = "0";
        $div_style = "height:110px;";
        $iframe_style = "border: 0;height:0px;width: 100%;";

        $javascript_function = "<script>
											  var iframeheight = 2350;
											  if($(window).width()<600) iframeheight = 3500;
									window.onload = function () {
                    if($(window).width()<600)window.scrollTo(0, 0);
                    $('#holds-the-iframe').height(iframeheight);
									  $('#ssm-iframe-element').height(iframeheight);
									};
								  document.cookie = 'isTesting=false;path=/rent-now'
								</script>";

        $URL = "https://e-storageonline.com/xyzstorage/Contents/RentOnline_TD.aspx" . "?FromPage=LocationDetails&ReqPage=ALL" . "&LocId=" . $L_ID . "&UnitId=" . $U_ID . "&Len=" . $LENGTH . "&Bre=" . $BREADTH . "&UnitRent=" . $RENT;

        return "<div id='ssm-iframe-url' src-data=" . $URL . "></div><div id='holds-the-iframe' style='" . $div_style . "'>
							<div id='loadingMessage' style='text-align:center'>
								<img src='https://xyzstorage.com/wp-content/uploads/2019/09/loading.gif'></img>
							</div>
							<iframe id='ssm-iframe-element' height=0></iframe>
						</div>";
    }

}

// location/date/unit_id/rent/internetspecial/firstname/lastname/email/phone/quantity/details/
add_action('rest_api_init', function ()
{

    register_rest_route('myplugin/v1', '/getAllInsuranceSchemes', array(
        'methods' => 'GET',
        'callback' => 'getAllInsuranceSchemes',
    ));
    register_rest_route('myplugin/v1', '/rentNowSSM', array(
        'methods' => 'POST',
        'callback' => 'rentNowSSM',
    ));
});

function getAllInsuranceSchemes()
{

    $curl = curl_init();

    curl_setopt_array($curl, [CURLOPT_URL => "http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/GetInsuranceSchemes?strCustomerCode=xyzstorage&strCustomerPassword=191820120406&strLocationCode=" . $_COOKIE['unitLocation'], CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["Accept: */*", "User-Agent: Thunder Client (https://www.thunderclient.com)"], ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $res = json_decode($json, true);
    //var_dump('location', $_COOKIE['unitLocation']);
    return ($res["InsuranceSchemes"]);
}

function rentNowSSM($sent_params)
{

    function makePaymentRequest($amount, $ccToken, $firstName, $locationId, $transactionId)
    {
        function generateAPIPasscode($id)
        {
            $passcode = "MTE3Njg1OTc3OjZGNWRCNDdiRkU0NTRlM0RiNmJGYzA5QzQ5NEFiMmIz";
            //var_dump('got the id', $id);
            switch ($id)
            {
                case '1':
                    $passcode = "MTE3Njg1OTc3OjZGNWRCNDdiRkU0NTRlM0RiNmJGYzA5QzQ5NEFiMmIz";
                break;
                case '7':
                    $passcode = "MTE3Njg3MTk2OmQwYjA0YWNFYWE2NzRDMTRBMzVjMzRhOTNiNTEwNjAx";
                break;
                case '6':
                    $passcode = "MTE3Njg2MzYwOjhkMzhlNzVlZjQwRDRkNzY5M0FBZjk2QTQzN2ZBM0E4";
                break;
                case '4':
                    $passcode = "MTE3Njg2MjA2OmExZjc5ZGUxMGE3NTREQWY4Njk5ZDQ3ODM2RTg5MjFF";
                break;
                case '3':
                    $passcode = "MTE3Njg2MjA3OmJFQjJDRjBkNUZEODQzN0M4QzkwMjE2NTg4NTRhNjZB";
                break;
                case '5':
                    $passcode = "MTE3Njg2MzYxOkU2NEFEQjg4QzU2ZjQ3NUFhRWRCODVEOTY5MUMzNTVh";
                break;
            }

            return $passcode;
        }

        $passcode = generateAPIPasscode($locationId);
        $postFields = array(
            "order_number" => $transactionId,
            "amount" => $amount,
            "payment_method" => "token",
            "token" => array(
                "complete" => true,
                "name" => $firstName,
                "code" => $ccToken
            )
        );
        //var_dump("lets see", $passcode, $postFields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.na.bambora.com/v1/payments/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Passcode $passcode",
                "Content-Type: application/json"
            ) ,
            CURLOPT_ENCODING => '',
            CURLOPT_SSL_VERIFYHOST => 0, // don't verify ssl
            CURLOPT_SSL_VERIFYPEER => false, //
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields)
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    function makeProfileRequest($amount, $ccToken, $firstName, $locationId)
    {

        function generatePaymentProfilePasscode($id)
        {
            $passcode = "MTE3Njg1OTc3OjZGNWRCNDdiRkU0NTRlM0RiNmJGYzA5QzQ5NEFiMmIz";
            //var_dump('got the id', $id);
            switch ($id)
            {
                case '1':
                    $passcode = "MTE3Njg1OTc3OjRCRTIyNTc0OEVGODQ2RUY4N0FDRjk4NEI1MjI5RDE3";
                break;
                case '7':
                    $passcode = "MTE3Njg3MTk2OmU4QzE3OTZiZTJCODQ4MjQ4RERFNDhGRGE2RWQ4MkY3";
                break;
                case '6':
                    $passcode = "MTE3Njg2MzYwOkQ4NEI2M0Y1MDNBNDQzQ0NBMUFCQzJGMUI2RTFCNEM0";
                break;
                case '4':
                    $passcode = "MTE3Njg2MjA2OkI0NkU4REMyOERhYTQ2NDQ5NTczRDA1QkI0MTE0MDhh";
                break;
                case '3':
                    $passcode = "MTE3Njg2MjA3OjY0M0JGNEI1QjM2RDQyMDY5MjUxMTFENTQyREZGRkVB";
                break;
                case '5':
                    $passcode = "MTE3Njg2MzYxOjcxMDZCRjMwNDQwQTRCQjM5QUQ1MjMxNzgyQzdDQTc0";
                break;
            }

            return $passcode;
        }
        $passcode = generatePaymentProfilePasscode($locationId);
        $postFields = array(
            "language" => "en",
            "token" => array(
                "name" => $firstName,
                "code" => $ccToken
            )
        );
        //var_dump("lets see", $passcode, $postFields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.na.bambora.com/v1/profiles',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Passcode $passcode",
                "Content-Type: application/json"
            ) ,
            CURLOPT_ENCODING => '',
            CURLOPT_SSL_VERIFYHOST => 0, // don't verify ssl
            CURLOPT_SSL_VERIFYPEER => false, //
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields)
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    function makeBamboraRequest($number, $expmonth, $expyear, $expcvv)
    {
        $postFields = array(
            "number" => $number,
            "expiry_month" => $expmonth,
            "expiry_year" => $expyear,
            "cvd" => $expcvv
        );
        //var_dump('lets see', $postFields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.na.bambora.com/scripts/tokenization/tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields)

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    function makeLeaseRequest($leaseNo, $locationCode, $initial, $signature)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
 <soap:Body>
 <SendLeaseToCustomerByEmail xmlns="http://tempuri.org/">
 <strCustomerCode>xyzstorage</strCustomerCode>
 <strCustomerPassword>191820120406</strCustomerPassword>
 <strLocationCode>' . $locationCode . '</strLocationCode>
 <strLeaseno>' . $leaseNo . '</strLeaseno>
 <strCustomerInitial>' . $initial . '</strCustomerInitial>

<StrSignField>' . $signature . '</StrSignField>
 </SendLeaseToCustomerByEmail>
 </soap:Body>
</soap:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'SOAPAction: http://tempuri.org/SendLeaseToCustomerByEmail'
            ) ,
        ));
        $response = curl_exec($curl);
        //curl_close($curl);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//soapBody ') [0];
        $array = json_decode(json_encode((array)$body) , true);
        return $array["SendLeaseToCustomerByEmailResponse"]["SendLeaseToCustomerByEmailResult"]["LeaseDocument"]["LeaseAgreementFileUrl"];

    }

    function makeUTMRequest($lease_number, $location_id, $utm, $time)
    {
        $utm_params = array(
            'strCustomerCode' => 'xyzstorage',
            'strCustomerPassword' => '191820120406',
            'strLocationCode' => $location_id,
            'strLeaseNumber' => $lease_number,
            'strUTMCode' => $utm,
            'strWebsiteCurrentDateTime' => $time,
            'ReservationNumber' => '',
        );
        // var_dump($utm_params);
        $endpoint = 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/';

        $utmUrl = $endpoint . "SaveUTMInformation" . '?' . http_build_query($utm_params);
        $curl = curl_init($utmUrl);

        //var_dump('url is herer', $noteUrl);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $utmUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ) ,
        ));
        //var_dump('hmm', $curl);
        $response = curl_exec($curl);
        // 							var_dump('response ----->>', $response);
        //if($show) {
        //var_dump('response ----->>', $response);
        //}
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        return $res;
    }

    function makeNoteRequest($note, $location_id, $lease_number)
    {
        $note_params = array(
            'strCustomerCode' => 'xyzstorage',
            'strCustomerPassword' => '191820120406',
            'strLocationCode' => $location_id,
            'strLeaseNumber' => $lease_number,
            'strNotes' => $note,
            'blnCollectionCall' => 'false',
            'blnDisplayAlert' => 'false',
            'intUserId' => 1
        );

        $endpoint = 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/';

        $noteUrl = $endpoint . "AddUnitNotes" . '?' . http_build_query($note_params);
        $curl = curl_init($noteUrl);

        //var_dump('url is herer', $noteUrl);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $noteUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ) ,
        ));
        //var_dump('hmm', $curl);
        $response = curl_exec($curl);
        //var_dump('response ----->>', $response);
        //if($show) {
        //}
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        return $res;
    }

    function makeRequest($url, $show = false)
    {
        $curl = curl_init($url);

        // 		var_dump('url is herer', $url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ) ,
        ));
        $response = curl_exec($curl);
        if ($show)
        {
            //var_dump('response ----->>', $response);
            
        }
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        return $res;
    }

    $inputs = json_decode($sent_params->get_body());
    function getCardType($cc, $location_id)
    {
        // 		var_dump('hmmm', $cc);
        //todo: get the correct card type from ssm endpoint
        // 		return '2';
        $note_params = array(
            'strCustomerCode' => 'xyzstorage',
            'strCustomerPassword' => '191820120406',
            'strLocationCode' => $location_id,
            'strCardType' => $cc
        );

        $endpoint = 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/';

        $noteUrl = $endpoint . "GetCardTypeIDFromName" . '?' . http_build_query($note_params);
        $curl = curl_init($noteUrl);

        //var_dump('url is herer', $noteUrl);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $noteUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ) ,
        ));
        //var_dump('hmm', $curl);
        $response = curl_exec($curl);
        //var_dump('response ----->>', $response);
        //if($show) {
        //}
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        // 		var_dump('hmmokay', $res["CardTypeonNamedetials"]["CardTypeId"]);
        return $res["CardTypeonNamedetials"]["CardTypeId"];

    }
	
	    function encryptString($value)
    {
        $params = array(
            'strCustomerCode' => 'xyzstorage',
            'strCustomerPassword' => '191820120406',
            'CardNumber' => $value,
        );
        $endpoint = 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/';

        $encryptUrl = $endpoint . "EncryptString" . '?' . http_build_query($params);
        //var_dump('got url', $encryptUrl);
        $curl = curl_init($encryptUrl);

        // 		var_dump('url is herer', $url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $encryptUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ) ,
        ));
        $response = curl_exec($curl);
        if (curl_error($curl))
        {
            //var_dump(curl_error($curl));
            
        }
        //var_dump('response ----->>', $response);
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);
        return $res[0];

    }
	
	  function getScheme($amount, $locationId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [CURLOPT_URL => "http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/GetInsuranceSchemes?strCustomerCode=xyzstorage&strCustomerPassword=191820120406&strLocationCode=" . $locationId, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["Accept: */*", "User-Agent: Thunder Client (https://www.thunderclient.com)"], ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $json = json_encode($xml);
        $res = json_decode($json, true);

        //var_dump($res["InsuranceSchemes"]);
        setcookie("InsuranceSchemes", json_encode($res["InsuranceSchemes"]));
        $insSchemes = $res["InsuranceSchemes"]["Scheme"];
        foreach ($insSchemes as $ins)
        {
            if ($ins["PremiumAmount"] == $amount)
            {
                return $ins["SchemeId"];
            }

        }

    }

    function getValue($array, $value)
    {
        $key = 'name';
        $objects = array();

        $value = str_replace('_', '-', $value);
        $value = str_replace('$', '', $value);

        foreach ($array as $object)
        {
            if ($object->$key == $value)
            {
                $objects[] = $object;
            }
        }
        return $objects[0]->value;
    }

    $first_name = getValue($inputs, 'first_name');
    $last_name = getValue($inputs, '$last_name');
    $phone = getValue($inputs, '$phone');
    $email = getValue($inputs, 'email');
    $date = getValue($inputs, 'date12');
    $businessName = getValue($inputs, "business");
    $insurance = getValue($inputs, 'insurance');
    $postal = getValue($inputs, 'postal');
    $unitRent = getValue($inputs, 'unitRent');
    $driverid = getValue($inputs, 'driverid');
    $birthday = getValue($inputs, 'birthday');
    $address = getValue($inputs, 'address');
    $unitAmount = getValue($inputs, 'unitAmount');
    $city = getValue($inputs, 'city');
    $privacy_promotions = getValue($inputs, 'privacy-promotions[]');
    $province = getValue($inputs, 'province');
    $cardname = getValue($inputs, 'cardname');
    $cardnumber = getValue($inputs, 'cardnumber');
    $cardcvv = getValue($inputs, 'cardcvv');
    $carddate = getValue($inputs, 'carddate');
    $initials = getValue($inputs, 'initials');
    $signature = getValue($inputs, 'signature');
    $unit_price = getValue($inputs, 'unit-price');
    $location_id = getValue($inputs, 'locationCode');
    $unitTypeId = getValue($inputs, 'unitType');
    $ccAmount = getValue($inputs, 'creditCardAmount');
    $autoPayEnabled = getValue($inputs, 'privacy-autopay[]');
    $signedLease = true;
    $last4CC = substr($cardnumber, -4);
    $insurance = str_replace('$', '', $insurance);
    $insurance = explode(" ", $insurance) [0];

    // call bambora first
    //$passedNotes = makeNoteRequest('testnote', $location_id, 4569);
    //var_dump('notes', $passedNotes);
    

    //return;
    //var_dump('werwerwe',$_COOKIE);
    $curl = curl_init();
    $endpoint = 'http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/';
    $CUSTOMER_CODE = 'xyzstorage';
    $CUSTOMER_PASS = '191820120406';



    $trans_params = array(
        'strCustomerCode' => $CUSTOMER_CODE,
        'strCustomerPassword' => $CUSTOMER_PASS,
        'strLocationCode' => $location_id,
        'strTenantId' => '1',
        'strAmount' => str_replace('$', '', $unitAmount)
    );

    $transactionUrl = $endpoint . "GetTransactionOrderIDForRental" . '?' . http_build_query($trans_params);

    //var_dump('params', $transactionUrl);
    $cardDateNumbers = explode("/", $carddate);

    //$leaseDetails = makeLeaseRequest();
    //var_dump($leaseDetails);
    //return;
    if (count($cardDateNumbers) !== 2)
    {
        $res = array(
            "type" => "error",
            "message" => "Invalid credit card date format please use format MM/DD"
        );
        return $res;
    }
    $cardMonth = $cardDateNumbers[0];
    $cardYear = $cardDateNumbers[1];
    //var_dump('hmm', count($cardMonth), $cardYear);
    if (strlen($cardMonth) !== 2 || strlen($cardYear) !== 2)
    {
        $res = array(
            "type" => "error",
            "message" => "Invalid credit card date format please use format MM/DD"
        );
        return $res;
    }
    $transactionId = makeRequest($transactionUrl) ["ID"];
    //var_dump('Transaction ID -->', $transactionId);
    $strAccountId = makeBamboraRequest($cardnumber, $cardMonth, $cardYear, $cardcvv);
    $strAccountId1 = makeBamboraRequest($cardnumber, $cardMonth, $cardYear, $cardcvv);

    $ccToken = json_decode($strAccountId)->token;
    $ccToken1 = json_decode($strAccountId1)->token;
    //var_dump('cc token -->', $ccToken);
    //return;
    //return;
    // once we get the token we need to create a payment  for this user
  

    $available_units_params = array(
        'strCustomerCode' => $CUSTOMER_CODE,
        'strCustomerPassword' => $CUSTOMER_PASS,
        'strLocationCode' => $location_id,
        'strUnitTypeId' => $unitTypeId,
        'UnitTypeRent' => $unitRent,

    );

    //var_dump('rental charges' , $available_units_params);
    $availableUnitsEndpoint = $endpoint . "GetAvailableUnits" . '?' . http_build_query($available_units_params);

    $availableUnits = makeRequest($availableUnitsEndpoint);
    //var_dump('hm', $availableUnits);
    if (!$availableUnits)
    {
        $res = array(
            "type" => "error",
            "message" => "This unit is not available anymore"
        );
        return $res;
    }
    if ($availableUnits["Unit"][0])
    {
        $returnedUnitId = $availableUnits["Unit"][0]["UnitId"];
        $returnedUnitNumber = $availableUnits["Unit"][0]["UnitNumber"];

    }
    else
    {
        $returnedUnitId = $availableUnits["Unit"]["UnitId"];
        $returnedUnitNumber = $availableUnits["Unit"]["UnitNumber"];

    }
    //$returnedUnitId = $availableUnits["Unit"][0]["UnitId"];
    //var_dump('available units', $returnedUnitId);
    //return;
    $encrypted = encryptString('Card Number', $cardnumber);
    //var_dump('hmm', $encrypted);
    //	return;
    //var_dump('ok', $location_id);
    //return;
    $rentalcharge_params = array(
        'strCustomerCode' => $CUSTOMER_CODE,
        'strCustomerPassword' => $CUSTOMER_PASS,
        'strLocationCode' => $location_id,
        'strUnitTypeId' => $unitTypeId,
        'strInsuranceSchemeId' => getScheme($insurance, $location_id) ,
        'strWebsiteCurrentDateTime' => getValue($inputs, "submission-date") ,
        'strMoveInDate' => $date,
        'strMerchandiseInfo' => '',
        'UnitTypeRent' => $unitRent,
        'lngTempID' => rand(100000, 999999) ,
        'intUnitId' => $returnedUnitId,
        'CalculateforfirstPeriod' => 'true',
        'strOptionalFeeIds' => '',
        'optionalIsextended' => 'false'
    );
    $rentChargesEndpoint = $endpoint . "GetRentalCharges_API" . '?' . http_build_query($rentalcharge_params);

    $rentCharges = makeRequest($rentChargesEndpoint);

    //var_dump('these are the charges', $rentCharges["Details"]);
    $returnedTotalCost = $rentCharges["Details"]["TotalCostToMovin"];
    $returnedUnitNumber = $rentCharges["Details"]["UnitNumber"];
    $returnedUnitId = $rentCharges["Details"]["UnitId"];
    //var_dump('okay', substr($carddate, 3,4));
    //var_dump('okay gain', substr($carddate, 0, 2));
    $cardDateNumbers = explode("/", $carddate);
    if (count($cardDateNumbers) !== 2)
    {
        $res = array(
            "code" => 200,
            "type" => "error",
            "message" => "Incorrect credit card date"
        );
        return $res;

    }
    //var_dump($cardDateNumbers);
    if (strlen($cardDateNumbers[0]) !== 2 || strlen($cardDateNumbers[1]) !== 2)
    {
        $res = array(
            "code" => 200,
            "type" => "error",
            "message" => "Incorrect credit card date"
        );
        return $res;

    }
    //var_dump($cardDateNumbers);
    //return;
    //return;
    //var_dump('checks', $returnedTotalCost);
    $paymentRequest = makePaymentRequest($returnedTotalCost, $ccToken, $first_name, $location_id, $transactionId);
    if ($autoPayEnabled)
    {
        $profileRequest = makeProfileRequest($returnedTotalCost, $ccToken1, $first_name, $location_id);
        $profileResponse = json_decode($profileRequest);
        $paymentAccountId = $profileResponse->customer_code;
        // 	var_dump("get this", $profileResponse);
        
    }
    $paymentResponse = json_decode($paymentRequest);
    // 		var_dump($paymentResponse);
    //var_dump('hmm', $paymentResponse);
    //return;
    $cardType = $paymentResponse
        ->card->card_type;
    if (!$paymentResponse->id || !$paymentResponse->auth_code || !($paymentResponse->message && $paymentResponse->message == "Approved"))
    {
        $res = array(
            "code" => 100,
            "type" => "error",
            "message" => "Credit card declined"
        );
        return $res;
    };

    $ccAuthorizationCode = $paymentResponse->id;
    $ccAuthCode = $paymentResponse->auth_code;

    $params = array(
        'strCustomerCode' => $CUSTOMER_CODE,
        'strCustomerPassword' => $CUSTOMER_PASS,
        'strLocationCode' => $location_id,
        'strUnitTypeId' => $unitTypeId,
        'strInsuranceSchemeId' => getScheme($insurance, $location_id) ,
        'strWebsiteCurrentDateTime' => getValue($inputs, "submission-date") ,
        'strMoveInDate' => $date,
        'strFirstName' => $first_name,
        'strLastName' => $last_name,
        'strAddress' => $address,
        'strCity' => $city,
        'strStateCode' => $province,
        'strZipCode' => $postal,
        'strEmail' => $email,
        'strHomePhone' => $phone,
        'strCellPhone' => $phone,
        'strBusinessPhone' => $phone,
        'strDiscountIds' => 0,
        'strFaxNumber' => '',
        'strCompanyName' => $businessName ? $businessName : 'N/A',
        'strDriversLicenseNumber' => encryptString($driverid) ,
        'strMarketingInfo' => $privacy_promotions,
        'strCreditCardAmount' => $returnedTotalCost,
        'strCreditCardNumber' => encryptString($cardnumber) ,
        'strCreditCardType' => encryptString(getCardType($cardType, $location_id)) ,
        'strCCNameOnCard' => encryptString($cardname) ,
        'strCCExpiryYear' => encryptString($cardDateNumbers[1]) ,
        'strCCExpiryMonth' => encryptString($cardDateNumbers[0]) ,
        'strCSCNo' => encryptString($cardcvv) ,
        'strCCBillingAddress' => encryptString($address) ,
        'strCCZipCode' => encryptString($postal) ,
        'strRequestAutoCCPayment' => $autoPayEnabled ? true : false,
        'strRequestOrigin' => 'W',
        'strUserId' => '1',
        'strMerchandiseInfo' => '',
        'strDOB' => $birthday,
        'strInsuranceStatus' => '1',
        'strUnitTypeRent' => $unitRent,
        'strTempId' => rand(100000, 999999) ,
        'strUnitId' => $returnedUnitId,
        'strUnitNum' => $returnedUnitNumber,
        'UnitTypeRent' => $unitRent,
        'strCCTransId' => $transactionId,
        'strCCResponseMessage' => 'Approved',
        'strOLMAuthCode' => $ccAuthCode,
        'strCCAuthorizationCode' => $ccAuthorizationCode,
        'strPaymentAccountId' => $paymentAccountId ? $paymentAccountId : ''
    );
    // 	var_dump('params', $params);
    // 	return;
    $rentUrl = $endpoint . "SubmitOnlineRentalForNewTenants_TD" . '?' . http_build_query($params);
    try
    {
        $rentalId = makeRequest($rentUrl, true);

    }
    catch(Exception $e)
    {
        $res = array(
            "type" => "error",
            "message" => $e->getMessage()
        );
        return $res;
    }

    //var_dump('got rent charges', $rentalId);
    if ($rentalId["SaveSuccessful"] == "FALSE")
    {
        $res = array(
            "type" => "error",
            "message" => $rentalId["ErrorMessage"]
        );
        return $res;
    }
    if ($rentalId["SaveSuccessful"] == "TRUE")
    {
        $leaseNumber = $rentalId["LeaseNumber"];
        //var_dump($autoPayEnabled, $signedLease, $last4CC);
        $autoPayString = '';
        if ($autoPayEnabled)
        {
            $note1 = "Online Rental: Unit - $returnedUnitNumber successfully updated for AutoPay $last4CC/Card";
            $autoPayString = "AutoPay";
            // 			makeNoteRequest($note1, $location_id, $leaseNumber);
            
        }
        $note2 = "Tenant signed the lease agreement electronically and submitted to store";
        makeNoteRequest($note2, $location_id, $leaseNumber);

        $note3 = "Online Rental: Unit - $returnedUnitNumber successfully setup for $autoPayString $last4CC/Card";
        makeNoteRequest($note3, $location_id, $leaseNumber);

        $utm = "UTM " . getValue($inputs, "referrerInfo");
        //makeNoteRequest($note4, $location_id, $leaseNumber);
        // 		var_dump($note3);
        $time = getValue($inputs, "submission-date");
        makeUTMRequest($leaseNumber, $location_id, $utm, $time);
        //$passedNotes = makeNoteRequest('testnote', $location_id, 4569);
        // 			$leaseRequestParams = array(
        // 		'strCustomerCode' => $CUSTOMER_CODE,
        // 		'strCustomerPassword' => $CUSTOMER_PASS,
        // 		'strLocationCode' => $location_id,
        // 		'strLeaseno'=> $leaseNumber,
        // 		'strCustomerInitial' => getValue($inputs, "initials"),
        // 		'StrSignField' => getValue($inputs, "signature")
        // 		);
        //$getLeaseEndpoint = $endpoint . "SendLeaseToCustomerByEmail" . '?' . http_build_query($leaseRequestParams);
        try
        {
            $leaseFile = makeLeaseRequest($leaseNumber, $location_id, getValue($inputs, "initials") , getValue($inputs, "signature"));
            $res = array(
                "type" => "success",
                "lease" => $leaseFile,
                "message" => "Successful Rental"
            );
            return $res;
        }
        catch(Exception $e)
        {
            $res = array(
                "type" => "error",
                "message" => $e->getMessage()
            );
            return $res;
        }
        //var_dump('got the endpoint', $getLeaseEndpoint);
        //var_dump('final charges', $leaseDetails);
        //var_dump('lets see', $rentalId);
        
    }
    $res = array(
        "type" => "error",
        "message" => "Something went wrong with ssm rental endpoint"
    );
    return $res;
    // 	return $input->first_name;
    // 	return $sent_params;
    
}

add_action('rest_api_init', function ()
{
    $regex_location = '/(?P<location>\w+)';
    $regex_date = '/(?P<date>[a-zA-Z0-9-\%-\-]+)';
    $regex_unit_id = '/(?P<unit>\d+)';
    $regex_rent = '/(?P<rent>[0-9-\.]+)';
    $regex_internet_special = '/(?P<internet_special>\d+)';
    $regex_first_name = '/(?P<firstname>[a-zA-Z0-9-\%]+)';
    $regex_last_name = '/(?P<lastname>[a-zA-Z0-9-\%]+)';
    $regex_email = '/(?P<email>[a-zA-Z0-9-\%-\.-_]+)';
    $regex_phone = '/(?P<phone>[0-9-\%-\-]+)';
    $regex_quantity = '/(?P<quantity>\d+)';
    $regex_details = '/(?P<details>[a-zA-Z0-9\%-\.-\,-\-_\/: =]+)';
    $regex_business_name = '/(?P<businessname>[a-zA-Z0-9-\%]+)';

    register_rest_route('myplugin/v1', '/reserve' . $regex_location . $regex_date . $regex_unit_id . $regex_rent . $regex_first_name . $regex_last_name . $regex_email . $regex_phone . $regex_quantity . $regex_business_name . $regex_details, array(
        'methods' => 'GET',
        'callback' => 'ssm_reserve_api',
        'args' => array(
            'location' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            ) ,
            'firstname' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            ) ,
            'unit' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            )
        )
    ));
});

add_action('rest_api_init', function ()
{
    $regex_location = '/(?P<location>\w+)';
    $regex_date = '/(?P<date>[a-zA-Z0-9-\%-\-]+)';
    $regex_unit_id = '/(?P<unit>\d+)';
    $regex_rent = '/(?P<rent>[0-9-\.]+)';
    $regex_internet_special = '/(?P<internet_special>\d+)';
    $regex_first_name = '/(?P<firstname>[a-zA-Z0-9-\%]+)';
    $regex_last_name = '/(?P<lastname>[a-zA-Z0-9-\%]+)';
    $regex_email = '/(?P<email>[a-zA-Z0-9-\%-\.-_]+)';
    $regex_phone = '/(?P<phone>\d+)';
    $regex_quantity = '/(?P<quantity>\d+)';
    $regex_details = '/(?P<details>[a-zA-Z0-9\%-\.-\,-\-_\/: =]+)';

    register_rest_route('myplugin/v1', '/reserve2' . $regex_location . $regex_date . $regex_unit_id . $regex_rent . $regex_first_name . $regex_last_name . $regex_email . $regex_phone . $regex_quantity . $regex_details, array(
        'methods' => 'GET',
        'callback' => 'ssm_reserve_api2',
        'args' => array(
            'location' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            ) ,
            'firstname' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            ) ,
            'unit' => array(
                'validate_callback' => function ($param, $request, $key)
                {
                    return true;
                }
            )
        )
    ));
});

/*

http://xyz.dev.pop.ca/wp-json/myplugin/v1/testing/1/12-07-2019/22/eric/fleith/eric%40n49.com/6479631427/6/testing

*/

function syncMailchimp($data)
{
    $apiKey = '3975f58a33ad76122fbee9c45566171a-us20';
    $listId = 'ec67685e56';

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode(['email_address' => $data['email'], 'status' => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
    'merge_fields' => ['FNAME' => $data['firstname'], 'LNAME' => $data['lastname']]]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $result;
}

function ssm_reserve_api($data)
{

    $mailchimp_data = ['email' => urldecode($data['email']) , 'status' => 'subscribed', 'firstname' => $data['firstname'], 'lastname' => $data['lastname']];

    // subscribe to mailchimp
    /*if(isset($data['_mc4wp_subscribe_contact-form-7']) && $data['_mc4wp_subscribe_contact-form-7'] == 1) {
    // subscribe mail chimp api on campaign
    syncMailchimp($mailchimp_data);
    }*/
    $this_location_code = 1;
    $curl = curl_init();
    $url_date = str_replace("-", "/", $data['date']);
    $quantity = '';
    if ($data['location'] == '5')
    {
        $quantity = 'Quantity:' . $data['quantity'] . ',';
    }
    $details = urldecode(urldecode($data['details']));
    $ssm_url = "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/SaveReservationDetails_WEB?strCustomerCode=xyzstorage&strCustomerPassword=991852130418" . "&strLocationCode=" . $data['location'] . "&strReservationDateTime=" . $url_date . "&strDateRequired=" . $url_date . "&strUnitTypeId=" . $data['unit'] . "&strFirstName=" . $data['firstname'] . "&strLastName=" . $data['lastname'] . "&strAddress=0" . "&strCity=0" . "&strStateCode=0" . "&strZipCode=0" . "&strEMail=" . urldecode($data['email']) . "&strHomePhone=" . $data['phone'] . "&strBusinessPhone=" . $data['phone'] . "&strCellPhone=" . $data['phone'] . "&strFaxNumber=" . $data['phone'] . "&strDiscountIds=0&strMarketingInfo=0" . "&strCustomerNotes=" . $quantity . curl_escape($curl, $details) . "&strChkReservThresholdLimit=0" . "&strApplyInternetPrice=0" . "&strRequestOrigin=W&intUserId=1" . "&UnitTypeRent=" . $data['rent'] . "&strCompanyName=" . $data['businessname'];
    curl_setopt_array($curl, array(
        CURLOPT_URL => $ssm_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ) ,
    ));
    $response = curl_exec($curl);
    $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $api_response = json_decode($json, true);

    return $api_response;

}

function ssm_reserve_api2($data)
{

    $mailchimp_data = ['email' => urldecode($data['email']) , 'status' => 'subscribed', 'firstname' => $data['firstname'], 'lastname' => $data['lastname']];
    $this_location_code = 1;
    $curl = curl_init();
    $url_date = str_replace("-", "/", $data['date']);
    $quantity = '';
    if ($data['location'] == '5')
    {
        $quantity = 'Quantity:' . $data['quantity'] . ',';
    }
    $details = urldecode(urldecode($data['details']));
    // 	$details = urlencode($details);
    $ssm_url = "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/SaveReservationDetails_WEB?strCustomerCode=xyzstorage&strCustomerPassword=991852130418" . "&strLocationCode=" . $data['location'] . "&strReservationDateTime=" . $url_date . "&strDateRequired=" . $url_date . "&strUnitTypeId=" . $data['unit'] . "&strFirstName=" . $data['firstname'] . "&strLastName=" . $data['lastname'] . "&strAddress=0" . "&strCity=0" . "&strStateCode=0" . "&strZipCode=0" . "&strEMail=" . urldecode($data['email']) . "&strHomePhone=" . $data['phone'] . "&strBusinessPhone=" . $data['phone'] . "&strCellPhone=" . $data['phone'] . "&strFaxNumber=" . $data['phone'] . "&strCompanyName=acss&strDiscountIds=0&strMarketingInfo=0" . "&strCustomerNotes=" . $quantity . curl_escape($curl, $details) . "&strChkReservThresholdLimit=0" . "&strApplyInternetPrice=0" . "&strRequestOrigin=W&intUserId=1" . "&UnitTypeRent=" . $data['rent'];
    curl_setopt_array($curl, array(
        CURLOPT_URL => $ssm_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ) ,
    ));
    $response = curl_exec($curl);
    $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $api_response = json_decode($json, true);

    return $api_response;

}

function testing()
{
    return 'it works';
}

add_action('rest_api_init', function ()
{

    register_rest_route('myplugin/v1', '/testing', array(
        'methods' => 'GET',
        'callback' => 'testing',
    ));
});

function opio_reviews($data)
{

    $API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
    $BASE_URL = 'https://op.io/api/entities/';
    $ARGS = '?scopes=Review&subSkip=0&subLimit=25';

    $locations_opioID = array(
        3 => "jhrvg39uijr5q9mo3",
        4 => "jhrtgyf3izoyc0rih",
        1 => "jhrtqujzw0ystxyfk",
        5 => "jhmaw5br30o4p7w5g",
        6 => "jhrur79akaghyr692",
        7 => "jwjbbu0fdl26ixz0v",
        2 => "ji6b4k14upi10t2pc"
    );

    $this_location_opio = $locations_opioID[$data['id']];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $BASE_URL . $this_location_opio . $ARGS,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            $API_KEY
        ) ,
        CURLOPT_VERBOSE => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 20
    ));
    $response = curl_exec($curl);
    $json = json_decode($response, true);
    if ($data['object'] == 'rating')
    {
        $myObj = array(
            "rating" => $json[0]['aggregateRating']['5734f48a0b64d7382829fdf7']["average"]
        );
        return $myObj;
    }
    $video_review = array();
    foreach ($json[0]['reviews'] as $rev)
    {
        $video_review = $rev;
        break;
    }
    if ($data['object'] == 'video')
    {
        return $video_review;
    }
    if ($data['object'] == 'both')
    {
        $myObj = array(
            'rating' => $json[0]['aggregateRating']['5734f48a0b64d7382829fdf7']["average"],
            'video' => $video_review,
            "url" => $BASE_URL . $this_location_opio . $ARGS
        );
        return $myObj;
    }
    else return false;
}

add_action('rest_api_init', function ()
{

    register_rest_route('myplugin/v1', '/opio-rating/(?P<id>\d+)/(?P<object>\w+)', array(
        'methods' => 'GET',
        'callback' => 'opio_reviews',
    ));
});

function video_review($data)
{

    $API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
    $BASE_URL = 'https://op.io/api/entities/video-review/';

    $locations_opioID = array(
        3 => "jhrvg39uijr5q9mo3",
        4 => "jhrtgyf3izoyc0rih",
        1 => "jhrtqujzw0ystxyfk",
        5 => "jhmaw5br30o4p7w5g",
        6 => "jhrur79akaghyr692",
        7 => "jwjbbu0fdl26ixz0v",
        2 => "ji6b4k14upi10t2pc"
    );

    $this_location_opio = $locations_opioID[$data['id']];

    $response = wp_remote_get($BASE_URL . $this_location_opio, array(
        "headers" => $API_KEY,
        "timeout" => 20
    ));
    $json = json_decode($response['body'], true);

    $video_review = array();
    foreach ($json['reviews'] as $rev)
    {
        if ($rev['totalVideos'] > 0)
        {
            $video_review = $rev;
            break;
        }
    }
    return array(
        'rating' => $json['aggregateRating']['5734f48a0b64d7382829fdf7']["average"],
        'video' => $video_review,
        "url" => $BASE_URL . $this_location_opio
    );
}

add_action('rest_api_init', function ()
{

    register_rest_route('opio', '/video-review/(?P<id>\d+)/', array(
        'methods' => 'GET',
        'callback' => 'video_review',
    ));
});

function unit_discounts($data)
{
    $curl = curl_init();
    $url = "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitTypeDiscounts" . "?strCustomerCode=xyzstorage" . "&strCustomerPassword=991852130418" . "&strLocationCode=" . $data['location'] . "&strUnitTypeId=" . $data['id'];
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ) ,
    ));
    $response = curl_exec($curl);
    $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $api_unit_details = json_decode($json, true);
    $temp = array();
    if (count($api_unit_details) < 1) return $api_unit_details;
    if ($api_unit_details['Discount']['DiscountType']) return array(
        $api_unit_details['Discount']
    );
    else $api_unit_details = $api_unit_details["Discount"];
    foreach ($api_unit_details as $unit)
    {
        if ($unit["DonotDisplayOnWebsite"] == "False")
        {
            array_push($temp, $unit);
        }
    }
    return $temp;
}
add_action('rest_api_init', function ()
{

    register_rest_route('myplugin/v1', '/unit-discounts/(?P<location>\d+)/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'unit_discounts',
    ));
});
/*
https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitTypeDetailsForGivenUnitType?strCustomerCode=xyzstorage&strCustomerPassword=991852130418'.
            '&strLocationCode='.$this_location_code.
            '&strLength='.$f_unit['length'].
            '&strWidth='.$f_unit['width'].
            '&strUnitTypeId='.$f_unit['unit_id'].
            '&strAlreadySelectedUnitTypes=0&strReservationThresholdValue=0'.
            '&UnitTypeRent='.$f_unit['rent'];

location/unitId/length/width/rent

*/
function unit_details($data)
{
    $curl = curl_init();
    $url = "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitTypeDetailsForGivenUnitType" . "?strCustomerCode=xyzstorage&strCustomerPassword=991852130418" . "&strLocationCode=" . $data['location'] . '&strLength=' . $data['length'] . '&strWidth=' . $data['width'] . "&strUnitTypeId=" . $data['unitid'] . '&strAlreadySelectedUnitTypes=0&strReservationThresholdValue=0' . '&UnitTypeRent=' . $f_unit['rent'];

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ) ,
    ));
    $response = curl_exec($curl);
    $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $api_unit_details = json_decode($json, true);
    return $api_unit_details["UnitType"];
}

add_action('rest_api_init', function ()
{
    $regex_location = '/(?P<location>\d+)';
    $regex_length = '/(?P<length>\d+)';
    $regex_width = '/(?P<width>\d+)';
    $regex_unit_id = '/(?P<unitid>\d+)';
    $regex_rent = '/(?P<rent>[a-zA-Z0-9\%\.\,\-]+)';

    register_rest_route('myplugin/v1', '/unit-details' . $regex_location . $regex_unit_id . $regex_width . $regex_length . $regex_rent, array(
        'methods' => 'GET',
        'callback' => 'unit_details',
    ));
});

function ga_rental_confirmation($data)
{
    $info = "info:" . $data['info'];
    $location = ", location:" . $data['location'];
    $length = ", length:" . $data['length'];
    $width = ", width:" . $width['width'];
    $unit_id = ", id:" . $data['unit_id'];
    $value = ", value:" . $data['value'];
    $return_string = $info . $location . $length . $width . $unit_id . $value;
    return $return_string;
}

add_action('rest_api_init', function ()
{
    $regex_info = '/(?P<info>[a-zA-Z0-9\%\.\,\-]+)';
    $regex_location = '/(?P<location>\d+)';
    $regex_length = '/(?P<length>\d+)';
    $regex_width = '/(?P<width>\d+)';
    $regex_unit_id = '/(?P<unitid>\d+)';
    $regex_value = '/(?P<value>[a-zA-Z0-9\%\.\,\-]+)';
    $regex = $regex_info . $regex_location . $regex_length . $regex_width . $regex_unit_id . $regex_value;
    register_rest_route('myplugin/v1', '/ssm_rental_confirmation' . $regex, array(
        'methods' => 'GET',
        'callback' => 'ga_rental_confirmation',
    ));
});

add_action('rest_api_init', function ()
{
    $regex_details = '/(?P<details>[a-zA-Z0-9\%-\.-\,-\-_\-=]+)';
    $regex_location = '/(?P<location>[a-zA-Z0-9\%-\.-\,-\-_]+)';
    $regex_value = '/(?P<value>[a-zA-Z0-9\%\.\,\-]+)';

    $regex = $regex_location . $regex_value . $regex_details;

    register_rest_route('myplugin/v1', '/rental_email' . $regex, array(
        'methods' => 'GET',
        'callback' => 'email_rental',
    ));
});

add_action('rest_api_init', function ()
{
    register_rest_route('myplugin/v1', '/hubspotemail2', array(
        'methods' => 'GET',
        'callback' => 'hubspotemail2',
    ));
});

function hubspotemail2($data)
{
    $error = $data["errorMessage"] || "There was an error while submitting to hubspot";
    // 	var_dump('got it', $error);
    $to = 'hubspot_errors@xyzstorage.com';
    $subject = 'Error while submitting to hubspot';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers . 'From: info@xyzstorage.com' . "\r\n" . 'Reply-To: info@xyzstorage.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    $message = '<html><h3>XYZ popup error while submitting to hubspot</h3>
	<p>Message: ' . urldecode($data['errorMessage']) . '</p>';

    wp_mail($to, $subject, $message, $headers);

    return "email sent.";

}

function email_rental($data)
{

    $to = 'eric@n49.com, ricksilver@n49.com';
    $subject = 'New Rental ' . urldecode($data['location']);
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers . 'From: info@xyzstorage.com' . "\r\n" . 'Reply-To: info@xyzstorage.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $message = '<html><h3>New Rental</h3>
	<p>Location: ' . urldecode($data['location']) . '</p>
	<p>Value: $' . $data['value'] . '</p>
	<p>UTM/Source:' . urldecode($data['details']) . '</p></html>';

    wp_mail($to, $subject, $message, $headers);

    return "email sent.";
}

add_action('rest_api_init', function ()
{
    $regex_details = '/(?P<details>[a-zA-Z0-9\%-\.-\,-\-_\-=]+)';
    $regex_unit = '/(?P<unit>[a-zA-Z0-9\%-\.-\,-\-_\'-\-=]+)';
    $regex_location = '/(?P<location>[a-zA-Z0-9\%-\.-\,-\-_]+)';
    $regex_value = '/(?P<value>[a-zA-Z0-9\%\.\,\-]+)';

    $regex = $regex_location . $regex_value . $regex_unit . $regex_details;

    register_rest_route('myplugin/v1', '/rental_email2', array(
        'methods' => 'GET',
        'callback' => 'email_rental2',
    ));
});
function email_rental2($data)
{
    $t = time();
    date_default_timezone_set("America/New_York");
    $time = date("Y-m-d H:i", $t);
    $location_name = urldecode($data['location']);
    $locations_email = array(
        "Scarborough" => 'scarborough@xyzstorage.com',
        "Undefined" => 'mississauga@xyzstorage.com',
        "Toronto West" => 'weston@xyzstorage.com',
        "Etobicoke" => 'lakeshore@xyzstorage.com',
        "Mobile Storage" => '',
        "Toronto Midtown" => 'laird@xyzstorage.com',
        "Toronto Downtown" => 'eastern@xyzstorage.com'
    );
    $location_email = $locations_email[$location_name];
    $to2 = "eric@n49.com";
    $to = 'confirmation@xyzstorage.com, dhiraj@n49.com, clients@n49.com, ' . $location_email;
    $subject = 'New Rental ' . $location_name . " - " . $time;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers . 'From: info@xyzstorage.com' . "\r\n" . 'Reply-To: info@xyzstorage.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $message = '<html><h3>New Rental </h3>
	<p>Location: ' . $data["locationName"] . '</p>
	<p>Value: $' . $data['rentAmount'] . '</p>
	<p>Size: ' . urldecode($data['dimensions']) . '</p>
	<p>UTM/Source:' . urldecode(urldecode($data['referrerInfo'])) . '</p>
	<p>Confirmation Page: ' . urldecode($data['confirmation']) . '</p></html>';

    wp_mail($to, $subject, $message, $headers);

    return "email sent.";
}

// https://xyzstorage.com/wp-json/myplugin/v1/get_item/
// HAS TO BE HTTPS OR IT WONT WORK


/*

http://xyz.dev.pop.ca/wp-json/myplugin/v1/testing/1/12-07-2019/22/eric/fleith/eric%40n49.com/6479631427/6/testing

*/
/* Reservation error notification STARTS */

function error_notification(WP_REST_Request $request)
{

    $params = $request->get_body();
    $arguments = explode("&", $params);
    $args = array();

    foreach ($arguments as $v)
    {
        $temp = explode("=", $v);
        $args[$temp[0]] = urldecode($temp[1]);
    }

    $to = 'eric@n49.com';
    $subject = 'Error: ' . $args['error_name'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers . 'From: info@xyzstorage.com' . "\r\n" . 'Reply-To: info@xyzstorage.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $message = '<html><h3>Error</h3>
	<p>SSM response: ' . $args['SSM_response'] . '</p>
	<p>Full Message: ' . $args['fullMessage'] . '</p>
	<p>Location: ' . $args['location'] . '</p>
	<p>Value: $' . $args['value'] . '</p>
	<p>Unit: ' . $args['unit'] . '</p>
	<p>email: ' . $args['client_email'] . '</p>
	<p>URL attemped: ' . $args['url'] . '</p></html>';

    wp_mail($to, $subject, $message, $headers);

    return $args;
}

add_action('rest_api_init', function ()
{
    register_rest_route('myplugin/v1', '/error_notification', array(
        'methods' => 'POST',
        'callback' => 'error_notification',
    ));
});

function reservation_successful(WP_REST_Request $request)
{

    $params = $request->get_body();
    $arguments = explode("&", $params);
    $args = array();

    foreach ($arguments as $v)
    {
        $temp = explode("=", $v);
        $args[$temp[0]] = urldecode($temp[1]);
    }

    $to = 'eric@n49.com, ricksilver@n49.com, corey@n49.com';
    $subject = 'Reservation Success: ' . $args['name'] . ' ' . $args['date'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers . 'From: info@xyzstorage.com' . "\r\n" . 'Reply-To: info@xyzstorage.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $message = '<html><h3>Reservation Success</h3>
	<p>location: ' . $args['location'] . '</p>
	<p>email: ' . $args['client_email'] . '</p>
	<p>value: ' . $args['value'] . '</p>
	<p>unit: ' . $args['unit'] . '</p>
	<p>name: ' . $args['name'] . '</p>
	<p>last name: ' . $args['lastname'] . '</p>
	<p>utm: ' . urldecode($args['utm']) . '</p>
	<p>URL attemped: ' . $args['url'] . '</p></html>';

    wp_mail($to, $subject, $message, $headers);

    return $args;
}

add_action('rest_api_init', function ()
{
    register_rest_route('email', '/reservation_successful', array(
        'methods' => 'POST',
        'callback' => 'reservation_successful',
    ));
});

/* Reservation error notification ENDS */

function debugme($data)
{

    $post = get_post('541');
    $post_meta = get_post_meta($post->ID);
    $return = array(
        "post" => $post,
        "meta" => $post_meta
    );
    return $return;
}

add_action('rest_api_init', function ()
{
    register_rest_route('debugme', '/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'debugme',
    ));
});

/* testing confirmation email */

include 'email.php';

add_action('rest_api_init', function ()
{
    register_rest_route('email', '/test', array(
        'methods' => 'POST',
        'callback' => 'email_test',
    ));
});

add_action('rest_api_init', function ()
{
    register_rest_route('email', '/test2', array(
        'methods' => 'POST',
        'callback' => 'email_test2',
    ));
});

function get_location_post_id($id)
{
    $location_post_ids = array(
        1 => '541',
        2 => '540',
        3 => '269',
        4 => '542',
        5 => '430',
        6 => '3151',
        7 => '544'
    );
    return $location_post_ids[$id];
}
function get_location_map_img($id)
{
    $map_url_pre = get_site_url() . "/wp-content/uploads/2019/09/xyz_";
    $location_imgs = array(
        1 => 'scarborough_map.png',
        2 => 'mississauga_map.png',
        3 => 'toronto_west_map.png',
        4 => 'etobicoke_map.png',
        5 => 'mississauga_map.png',
        6 => 'toronto_midtown_map.png',
        7 => 'toronto_downtown_map.png'
    );
    return $map_url_pre . $location_imgs[$id];
}
function get_location_map_code($id)
{
    $location_maps = array(
        1 => 'UtP6mcTFrJAVMJUP7',
        2 => '1586oLJKNwKgJkz5A',
        3 => 'oHE5jFpzMGL89dsc8',
        4 => 'QDcZXUMnikAX2cow6',
        5 => '1586oLJKNwKgJkz5A',
        6 => 'YYHzMPM4KXjNi7mf9',
        7 => 'oXPc9KiRbLFShbMdA'
    );
    return $location_maps[$id];
}

function email_test(WP_REST_Request $request)
{
    $params = $request->get_body();
    $arguments = explode("&", $params);
    $args = array();

    foreach ($arguments as $v)
    {
        $temp = explode("=", $v);
        $args[$temp[0]] = urldecode($temp[1]);
    }

    $args['first_name'] = explode(' ', $args['full_name']) [0];

    $l_post_id = get_location_post_id($args['location_id']);
    $location_post = get_post($l_post_id);
    $location_post_meta = get_post_meta($location_post->ID);

    $args['location'] = $location_post->post_title;

    $l_address_full = unserialize($location_post_meta['location_address'][0]) ['address'];
    $l_address_split = explode(",", $l_address_full, 2);
    $args['address_1'] = $l_address_split[0];
    $args['address_2'] = $l_address_split[1];

    $args['location_phone'] = $location_post_meta['location_phone'][0];
    $args['location_email'] = $location_post_meta['location_email'][0];
    $args['location_map'] = get_location_map_img($args['location_id']);
    $args['location_map_code'] = get_location_map_code($args['location_id']);

    $args['size'] = strtolower($args['size']);
    $dimensions = explode('x', $args['size']);
    $area = intval($dimensions[0]) * intval($dimensions[1]);

    $email_html = email_html($args);

    // return $email_html;
    $to = $args['email'];
    $subject = 'Booking Confirmation: ' . $args['first_name'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: info@self-storage.xyz' . "\r\n" . 'Reply-To: info@self-storage.xyz' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $success = wp_mail($to, $subject, $email_html, $headers);
    if ($success) return "successful";
    else return "failed";

}

function email_test2(WP_REST_Request $request)
{
    $params = $request->get_body();
    $arguments = explode("&", $params);
    $args = array();

    foreach ($arguments as $v)
    {
        $temp = explode("=", $v);
        $args[$temp[0]] = urldecode($temp[1]);
    }

    $args['first_name'] = explode(' ', $args['full_name']) [0];

    $l_post_id = get_location_post_id($args['location_id']);
    $location_post = get_post($l_post_id);
    $location_post_meta = get_post_meta($location_post->ID);

    $args['location'] = $location_post->post_title;

    $l_address_full = unserialize($location_post_meta['location_address'][0]) ['address'];
    $l_address_split = explode(",", $l_address_full, 2);
    $args['address_1'] = $l_address_split[0];
    $args['address_2'] = $l_address_split[1];

    $args['location_phone'] = $location_post_meta['location_phone'][0];
    $args['location_email'] = $location_post_meta['location_email'][0];
    $args['location_map'] = get_location_map_img($args['location_id']);
    $args['location_map_code'] = get_location_map_code($args['location_id']);

    $args['size'] = strtolower($args['size']);
    $dimensions = explode('x', $args['size']);
    $area = intval($dimensions[0]) * intval($dimensions[1]);

    $email_html = email_html($args);

    // return $email_html;
    $to = $args['email'];
    $subject = 'Booking Confirmation: ' . $args['first_name'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: info@self-storage.xyz' . "\r\n" . 'Reply-To: info@self-storage.xyz' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $email_status = wp_mail($to, $subject, $email_html, $headers);

    if ($email_status)
    {
        return "success";
    }
    else
    {
        return "failed";
    }
}

function get_location_slug_by_id($id)
{
    $locations = array(
        1 => 'scarborough',
        3 => 'toronto-west',
        4 => 'etobicoke',
        6 => 'toronto-midtown',
        7 => 'toronto-downtown'
    );
    return $locations[$id];
}

function opio_reviews_home($data)
{
    $opio_id = $data['id'];
    $API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
    $BASE_URL = 'https://op.io/api/entities/multibusinesses?entityIds=';
    $url = $BASE_URL . $opio_id;
    $response = wp_remote_get($url, array(
        "headers" => $API_KEY,
        "timeout" => 5
    ));

    if ($response->errors)
    {
        return false;
    }
    if ($response['body'])
    {
        return json_decode($response['body'], true);
    }
    else
    {
        return $response;
    }
};

add_action('rest_api_init', function ()
{

    register_rest_route('myplugin/v1', '/opio-rating-new/(?P<id>[a-zA-Z0-9_,]+)', array(
        'methods' => 'GET',
        'callback' => 'opio_reviews_home',
    ));
});

function get_unit_type_prices($location)
{

    $url = "https://www.secure.selfstoragemanager.com/SSMAPIRMXYZStorage/RevenueManagement.asmx/GetUnitInfo_v3?strCustomerCode=XYZStorageRM&strCustomerPassword=28anb9y0pzatpc1&intFacilityId=__LOCATION_ID__&Date=__DATE__";

    $url = str_replace("__LOCATION_ID__", $location, $url);

    $date = time();
    date_default_timezone_set('America/Toronto');
    $date = date("Y-m-d", $date);

    $url = str_replace("__DATE__", $date, $url);
    try
    {

        $response = wp_remote_get($url);
// 		dd($response);
    }
    catch(Exception $e)
    {

        return [];
    }
    //return [];
    //var_dump('hmm', $response);
    if (is_wp_error(!$response))
    {
        return [];
    }

    if (!is_array($response))
    {
        return [];
    }

    $xml = simplexml_load_string($response["body"], 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $json = json_encode($xml);
    $json = json_decode($json, true);

    $response = $json["GetUnitInfo_v3"];

    $response = array_filter($response, function ($v, $k)
    {
        return $v["UnitStatus"] == "Vacant";
    }
    , ARRAY_FILTER_USE_BOTH);

    $unit_type_prices = array();

    /**
     * unit_type_prices = (
     * 	"186" => array(
     * 			"100.00" => 1,
     * 			"130.00" => 4
     * 		)
     * )
     */

    foreach ($response as $unit)
    {
        $type_id = $unit["UnitTypeId"];
        $level_price = $unit["UnitLevelPrice"];

        if (!$unit_type_prices[$type_id])
        {
            // unit type not yet in our array
            $unit_type_prices[$type_id] = array(
                $level_price => 1
            );

        }
        else if (!$unit_type_prices[$type_id][$level_price])
        {
            // unit type in array but we don't have this price
            $unit_type_prices[$type_id][$level_price] = 1;

        }
        else
        {
            // unit type is in array and we have this price, so just increment count
            $unit_type_prices[$type_id][$level_price] += 1;
        }
    }

// 	dd($unit_type_prices);
    return $unit_type_prices;

}

add_shortcode('OPIO_NATIVE_DOWNTOWN', 'opio_native_downtown');
function opio_native_downtown()
{
    return file_get_contents('http://34.225.94.59/allReviewFeed?entId=jwjbbu0fdl26ixz0v');
}

add_shortcode('OPIO_NATIVE_FEED', 'opio_native_feed');
function opio_native_feed()
{
    return file_get_contents('http://34.225.94.59/multiReviewFeed/allReviews?orgId=jhs0krj2kdzgwej4s');
}

?>
