<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\CombineJs;
use WpAssetCleanUp\OptimiseAssets\DynamicLoadedAssets;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;

/**
 * Class Main
 * @package WpAssetCleanUp
 */
class Main
{
	/**
	 *
	 */
	const START_DEL_ENQUEUED  = 'BEGIN WPACU PLUGIN JSON ENQUEUED';

	/**
	 *
	 */
	const END_DEL_ENQUEUED    = 'END WPACU PLUGIN JSON ENQUEUED';

	/**
	 *
	 */
	const START_DEL_HARDCODED = 'BEGIN WPACU PLUGIN JSON HARDCODED';

	/**
	 *
	 */
	const END_DEL_HARDCODED   = 'END WPACU PLUGIN JSON HARDCODED';

	/**
     * Option "Plugin Usage Preferences" -> "Manage in the Dashboard" -> "Select a retrieval way:" ('Direct' or 'WP Remote Post')
     *
	 * @var string
	 */
	public static $domGetType = 'direct';

	/**
	 * @var string
	 */
	public $assetsRemoved = '';

	/**
	 * Record them for debugging purposes when using /?wpacu_debug
	 *
	 * @var array
	 */
	public $allUnloadedAssets = array( 'styles' => array(), 'scripts' => array() );

	/**
	 * @var array
	 */
	public $globalUnloaded = array();

	/**
	 * @var array
	 */
	public $loadExceptionsPageLevel = array( 'styles' => array(), 'scripts' => array(), '_set' => false );

	/**
	 * @var array[]
	 */
	public $loadExceptionsPostType = array( 'styles' => array(), 'scripts' => array(), '_set' => false );

	/**
	 * Rule that applies site-wide: if the user is logged-in
	 *
	 * @var array
	 */
	public $loadExceptionsLoggedInGlobal = array( 'styles' => array(), 'scripts' => array(), '_set' => false );

	/**
	 * @var
	 */
	public $fetchUrl;

    // [wpacu_lite]
    /**
     * @var
     */
    public $isUpdateable = true;
	// [/wpacu_lite]

    /**
	 * @var int
	 */
	public $currentPostId = 0;

	/**
	 * @var array
	 */
	public $currentPost = array();

	/**
	 * @var array
	 */
	public static $vars = array( 'woo_url_not_match' => false, 'is_woo_shop_page' => false, 'for_type' => '', 'current_post_id' => 0, 'current_post_type' => '' );

	/**
	 * This is set to `true` only if "Manage in the Front-end?" is enabled in plugin's settings
	 * and the logged-in administrator with plugin activation privileges
	 * is outside the Dashboard viewing the pages like a visitor
	 *
	 * @var bool
	 */
	public $isFrontendEditView = false;

	/**
	 * @var array
	 */
	public $stylesInHead = array();

	/**
	 * @var array
	 */
	public $scriptsInHead = array();

	/**
	 * @var array
	 */
	public $assetsInFooter = array( 'styles' => array(), 'scripts' => array() );

	/**
	 * @var array
	 */
	public $wpAllScripts = array();

	/**
	 * @var array
	 */
	public $wpAllStyles = array();

	/**
	 * @var array
	 */
	public $ignoreChildren = array();

	/**
	 * @var array
	 */
	public $ignoreChildrenHandlesOnTheFly = array();

	/**
	 * @var int
	 */
	public static $wpStylesSpecialDelimiters = array(
		'start' => '<!--START-WPACU-SPECIAL-STYLES',
		'end'   => 'END-WPACU-SPECIAL-STYLES-->'
	);

	/**
	 * @var array
	 */
	public $postTypesUnloaded = array();

	/**
	 * @var array
	 */
	public $settings = array();

	/**
	 * @var bool
	 */
	public $isAjaxCall = false;

	/**
	 * Fetch CSS/JS list from the Dashboard
	 *
	 * @var bool
	 */
	public $isGetAssetsCall = false;

	/**
	 * Populated in the Parser constructor
	 *
	 * @var array
	 */
	public $skipAssets = array( 'styles' => array(), 'scripts' => array() );

	/**
     * For these handles, it's strongly recommended to use 'Ignore dependency rule and keep the "children" loaded'
     * if any of them are unloaded in any page
     *
	 * @var \string[][]
	 */
	public $keepChildrenLoadedForHandles = array(
		'css' => array(
            'elementor-icons'
        ),
        'js'  => array(
            'swiper',
            'elementor-waypoints',
            'share-link'
        )
    );

	/**
	 * @var Main|null
	 */
	private static $singleton;

	/**
	 * @return null|Main
	 */
	public static function instance()
    {
		if ( self::$singleton === null ) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}

	/**
	 * Parser constructor.
	 */
	public function __construct()
    {
		$this->skipAssets['styles'] = array_merge(
            array(
                'admin-bar',
                // The top admin bar
                'yoast-seo-adminbar',
                // Yoast "WordPress SEO" plugin
                'autoptimize-toolbar',
                'query-monitor',
                'wp-fastest-cache-toolbar',
                // WP Fastest Cache plugin toolbar CSS
                'litespeed-cache',
                // LiteSpeed toolbar
                'siteground-optimizer-combined-styles-header'
                // Combine CSS in SG Optimiser (irrelevant as it made from the combined handles)
            ),
			// Own Scripts (for admin use only)
			OwnAssets::getOwnAssetsHandles('styles')
        );

		$this->skipAssets['scripts'] = array_merge(
            array(
                'admin-bar',            // The top admin bar
                'autoptimize-toolbar',
                'query-monitor',
                'wpfc-toolbar'          // WP Fastest Cache plugin toolbar JS
		    ),
			// Own Scripts (for admin use only)
			OwnAssets::getOwnAssetsHandles('scripts')
        );

	    // Filter before triggering the actual unloading through "wp_deregister_script", "wp_dequeue_script", "wp_deregister_style", "wp_dequeue_style"
	    $this->fallbacks();

		$this->isAjaxCall      = ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' );
		$this->isGetAssetsCall = isset( $_REQUEST[ WPACU_LOAD_ASSETS_REQ_KEY ] ) && $_REQUEST[ WPACU_LOAD_ASSETS_REQ_KEY ];

		if ( $this->isGetAssetsCall ) {
			$currentTheme = strtolower(wp_get_theme());
			$noRocketInit = true;

			if (strpos($currentTheme, 'uncode') !== false) {
				$noRocketInit = false; // make exception for the "Uncode" Theme as it doesn't check if the get_rocket_option() function exists
			}

			if ($noRocketInit) {
			    add_filter('rocket_cache_reject_uri', function($urls) {
				    $urls[] = '/?wpassetcleanup_load=1';
			        return $urls;
                });
			    }

			// Do not output Query Monitor's information as it's irrelevant in this context
			if ( class_exists( '\QueryMonitor' ) && class_exists( '\QM_Plugin' ) ) {
				add_filter( 'user_has_cap', static function( $user_caps ) {
					$user_caps['view_query_monitor'] = false;
					return $user_caps;
				}, 10, 1 );
			}

			add_filter( 'style_loader_tag', static function( $styleTag, $tagHandle ) {
				// This is used to determine if the LINK is enqueued later on
				// If the handle name is not showing up, then the LINK stylesheet has been hardcoded (not enqueued the WordPress way)
				return str_replace( '<link ', '<link data-wpacu-style-handle=\'' . $tagHandle . '\' ', $styleTag );
			}, PHP_INT_MAX, 2 ); // Trigger it later in case plugins such as "Ronneby Core" plugin alters it

			add_filter( 'script_loader_tag', static function( $scriptTag, $tagHandle ) {
				// This is used to determine if the SCRIPT is enqueued later on
				// If the handle name is not showing up, then the SCRIPT has been hardcoded (not enqueued the WordPress way)
				$reps = array( '<script ' => '<script data-wpacu-script-handle=\'' . $tagHandle . '\' ' );

				return str_replace( array_keys( $reps ), array_values( $reps ), $scriptTag );
			}, PHP_INT_MAX, 2 );
		}

	    // "File Size:" value from the asset row
	    add_filter('wpacu_get_asset_file_size', array($this, 'getAssetFileSize'), 10, 2);

	    // Triggers only if the administrator is logged in ('wp_ajax_nopriv' is not required)
	    // Used to determine the total size of an external loaded assets (e.g. a CSS file from Google APIs)
	    add_action('wp_ajax_'.WPACU_PLUGIN_ID.'_get_external_file_size', array($this, 'ajaxGetExternalFileSize'));

		// Early Triggers
		$wpacuAction = Misc::isElementorMaintenanceModeOn() ? 'template_redirect' : 'wp';

		if ($wpacuAction === 'wp') {
			add_action( 'wp', array( $this, 'setVarsBeforeUpdate' ), 8 );
			add_action( 'wp', array( $this, 'setVarsAfterAnyUpdate' ), 10 );
		} else {
			add_action( 'template_redirect', array( $this, 'setVarsBeforeUpdate' ), 12 ); // over 11 which is set in Elementor's maintenance-mode.php
			add_action( 'template_redirect', array( $this, 'setVarsAfterAnyUpdate' ), 13 );
        }

		// Fetch Assets AJAX Call? Make sure the output is as clean as possible (no plugins interfering with it)
		// It can also be used for debugging purposes (via /?wpacu_clean_load) when you want to view all the CSS/JS
		// that are loaded in the HTML source code before they are unloaded or altered in any way
		if ( $this->isGetAssetsCall || isset($_GET['wpacu_clean_load']) ) {
			$wpacuCleanUp = new CleanUp();
			$wpacuCleanUp->cleanUpHtmlOutputForAssetsCall();
		}

		// "Direct" AJAX call or "WP Remote Post" method used?
		// Do not trigger the admin bar as it's not relevant
		if ( $this->isAjaxCall || $this->isGetAssetsCall ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}

		// This is triggered AFTER "saveSettings" from 'Settings' class
		// In case the settings were just updated, the script will get the latest values
		add_action( 'init', array( $this, 'triggersAfterInit' ), 10 );

		// Front-end View - Unload the assets
		// If there are reasons to prevent the unloading in case 'test mode' is enabled,
		// then the prevention will trigger within filterStyles() and filterScripts()

		if ( ! is_admin() ) {
			$this->alterWpStylesScriptsObj();
		}

		/*
		 * [START] /?wpassetcleanup_load=1 is called
		 */
	    if ( $this->isGetAssetsCall && ! is_admin() ) {
	        // These actions are also called when the page is loaded without query string (regular load)
            // This time, the CSS/JS will not be unloaded, but the CSS/JS marked for unload will be collected
            // and passed to the AJAX call for the option "Group by loaded or unloaded status"
		    if ( get_option( 'siteground_optimizer_combine_css' ) ) {
			    add_action( 'wp_print_styles',     array( $this, 'filterStyles' ), 9 ); // priority should be below 10
		    }
		    add_action( 'wp_print_styles',         array( $this, 'filterStyles' ), 100000 );
		    add_action( 'wp_print_scripts',        array( $this, 'filterScripts' ), 100000 );
		    add_action( 'wp_print_footer_scripts', array( $this, 'onPrintFooterScriptsStyles' ), 1 );
	    }
	    /*
		 * [END] /?wpassetcleanup_load=1 is called
		 */

	    /*
	     * [START] Front-end page visited (e.g. by the admin or a guest visitor)
	     */
		if ( ! $this->isGetAssetsCall && ! is_admin() ) {
			// [START] Unload CSS/JS on page request (for debugging)
			add_filter( 'wpacu_ignore_child_parent_list', array( $this, 'filterIgnoreChildParentList' ) );
			// [END] Unload CSS/JS on page request (for debugging)

			// SG Optimizer Compatibility: Unload Styles - HEAD (Before pre_combine_header_styles() from Combinator)
			if ( get_option( 'siteground_optimizer_combine_css' ) ) {
				add_action( 'wp_print_styles',     array( $this, 'filterStyles' ), 9 ); // priority should be below 10
			}

			$this->filterStylesSpecialCases(); // e.g. CSS enqueued in a different way via Oxygen Builder

			add_action( 'wp_print_styles',         array( $this, 'filterStyles' ), 100000 ); // Unload Styles  - HEAD
			add_action( 'wp_print_scripts',        array( $this, 'filterScripts' ), 100000 ); // Unload Scripts - HEAD

            add_action( 'wp_print_styles',         array($this, 'printAnySpecialCss'), PHP_INT_MAX );

			// Unload Styles & Scripts - FOOTER
			// Needs to be triggered very soon as some old plugins/themes use wp_footer() to enqueue scripts
			// Sometimes styles are loaded in the BODY section of the page
			add_action( 'wp_print_footer_scripts', array( $this, 'onPrintFooterScriptsStyles' ), 1 );

			// Preloads
			add_action( 'wp_head', static function() {
				if ( Plugin::preventAnyFrontendOptimization() || self::isTestModeActive() ) {
					return;
				}

				// Only place the market IF there's at least one preload OR combine JS is activated
				$preloadsClass = new Preloads();

				if ( isset( $preloadsClass->preloads[ 'styles' ] ) && ! empty( $preloadsClass->preloads[ 'styles' ] ) ) {
					echo Preloads::DEL_STYLES_PRELOADS;
				}

				if ( (isset( $preloadsClass->preloads[ 'scripts' ] ) && ! empty( $preloadsClass->preloads[ 'scripts' ] )) || CombineJs::proceedWithJsCombine() ) {
					echo Preloads::DEL_SCRIPTS_PRELOADS;
				}
			}, 1 );

			add_filter( 'style_loader_tag', static function( $styleTag, $tagHandle ) {
				if ( Plugin::preventAnyFrontendOptimization() ) {
					return $styleTag;
				}

				// Preload the plugin's CSS for assets management layout (for faster content paint if the user is logged-in and manages the assets in the front-end)
				// For a better admin experience
				if ( $tagHandle === WPACU_PLUGIN_ID . '-style' ) {
					$styleTag = str_ireplace(
						array( '<link ', 'rel=\'stylesheet\'', 'rel="stylesheet"', 'id=\'', 'id="' ),
						array(
							'<link rel=\'preload\' as=\'style\' data-wpacu-preload-it-async=\'1\' ',
							'onload="this.onload=null;this.rel=\'stylesheet\'"',
							'onload="this.rel=\'stylesheet\'"',
							'id=\'wpacu-preload-',
							'id="wpacu-preload-'
						),
						$styleTag
					);
				}

				// Irrelevant for Critical CSS as the top admin bar is for logged-in users
                // and if it's not included in the critical CSS it would cause a flash of unstyled content which is not pleasant for the admin
				if ( $tagHandle === 'admin-bar' ) {
					$styleTag = str_replace( '<link ', '<link data-wpacu-skip-preload=\'1\' ', $styleTag );
                }

				if ( Plugin::preventAnyFrontendOptimization() || self::isTestModeActive() ) {
					return $styleTag;
				}

				// Alter for debugging purposes; triggers before anything else
				// e.g. you're working on a website and there is no Dashboard access and you want to determine the handle name
				// if the handle name is not showing up, then the LINK stylesheet has been hardcoded (not enqueued the WordPress way)
				if ( isset($_GET['wpacu_show_handle_names']) ) {
					$styleTag = str_replace( '<link ', '<link data-wpacu-debug-style-handle=\'' . $tagHandle . '\' ', $styleTag );
				}

				if ( strpos( $styleTag, 'data-wpacu-style-handle' ) === false ) {
					$styleTag = str_replace( '<link ', '<link data-wpacu-style-handle=\'' . $tagHandle . '\' ', $styleTag );
				}

				return $styleTag;
			}, PHP_INT_MAX, 2 ); // Trigger it later in case plugins such as "Ronneby Core" plugin alters it

			add_filter( 'script_loader_tag', static function( $scriptTag, $tagHandle ) {
				if ( Plugin::preventAnyFrontendOptimization() ) {
					return $scriptTag;
				}

				// Alter for debugging purposes; triggers before anything else
				// e.g. you're working on a website and there is no Dashboard access and you want to determine the handle name
				// if the handle name is not showing up, then the SCRIPT has been hardcoded (not enqueued the WordPress way)
				if ( isset($_GET['wpacu_show_handle_names']) ) {
					$scriptTag = str_replace( '<script ', '<script data-wpacu-debug-script-handle=\'' . $tagHandle . '\' ', $scriptTag );
				}

				if ( strpos( $scriptTag, 'data-wpacu-script-handle' ) === false
				     && Menu::userCanManageAssets()
				     && self::instance()->isFrontendEditView ) {
					$scriptTag = str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $tagHandle . '\' ', $scriptTag );
				}

				if ( Plugin::preventAnyFrontendOptimization() || self::isTestModeActive() ) {
					return $scriptTag;
				}

				if ( strpos( $scriptTag, 'data-wpacu-script-handle' ) === false ) {
					$scriptTag = str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $tagHandle . '\' ', $scriptTag );
				}

				if ( $tagHandle === 'jquery-core' ) {
					$scriptTag = str_replace( '<script ', '<script data-wpacu-jquery-core-handle=1 ', $scriptTag );
				}

				if ( $tagHandle === 'jquery-migrate' ) {
					$scriptTag = str_replace( '<script ', '<script data-wpacu-jquery-migrate-handle=1 ', $scriptTag );
				}

				return $scriptTag;
			}, PHP_INT_MAX, 2 );

            Preloads::instance()->init();
		}
	    /*
	     * [END] Front-end page visited (e.g. by the admin or a guest visitor)
	     */

		// Only trigger it within the Dashboard when an Asset CleanUp (Pro) page is accessed and the transient is non-existent or expired
		if ( is_admin() ) {
			add_action( 'admin_footer', array( $this, 'ajaxFetchActivePluginsJsFooterCode' ) );
			add_action( 'wp_ajax_' . WPACU_PLUGIN_ID . '_fetch_active_plugins_icons', array( $this, 'ajaxFetchActivePluginsIconsFromWordPressOrg' ) );
		}

		add_filter( 'duplicate_post_meta_keys_filter', static function( $meta_keys ) {
			// Get the original post ID
			$postId = isset( $_GET['post'] ) ? (int)$_GET['post'] : false;

			if ( ! $postId ) {
				$postId = isset( $_POST['post'] ) ? (int)$_POST['post'] : false;
			}

			if ( $postId ) {
				global $wpdb;

				$metaKeyLike = '_' . WPACU_PLUGIN_ID . '_%';

				$assetCleanUpMetaKeysQuery = <<<SQL
SELECT `meta_key` FROM {$wpdb->postmeta} WHERE meta_key LIKE '{$metaKeyLike}' AND post_id='{$postId}'
SQL;
				$assetCleanUpMetaKeys      = $wpdb->get_col( $assetCleanUpMetaKeysQuery );

				if ( ! empty( $assetCleanUpMetaKeys ) ) {
					$meta_keys = array_merge( $meta_keys, $assetCleanUpMetaKeys );
				}
			}

			return $meta_keys;
		} );

		$this->wpacuHtmlNoticeForAdmin();

		add_action( 'wp_ajax_' . WPACU_PLUGIN_ID . '_check_external_urls_for_status_code', array( $this, 'ajaxCheckExternalUrlsForStatusCode' ) );
	}

	/**
	 *
	 */
	public function triggersAfterInit()
    {
		$wpacuSettingsClass = new Settings();
		$this->settings     = $wpacuSettingsClass->getAll();

		if ( $this->settings['dashboard_show'] && $this->settings['dom_get_type'] ) {
			self::$domGetType = $this->settings['dom_get_type'];
		}

		// Fetch the page in the background to see what scripts/styles are already loading
        // This applies only for front-end loading
		if ( ! is_admin() && ($this->isGetAssetsCall || $this->frontendShow()) ) {
			if ( $this->isGetAssetsCall ) {
				add_filter( 'show_admin_bar', '__return_false' );
			}

			// Save CSS handles list that is printed in the <HEAD>
			// No room for errors, some developers might enqueue (although not ideal) assets via "wp_head" or "wp_print_styles"/"wp_print_scripts"
			add_action( 'wp_enqueue_scripts', array( $this, 'saveHeadAssets' ), PHP_INT_MAX - 1 );

			// Save CSS/JS list that is printed in the <BODY>
			add_action( 'wp_print_footer_scripts', array( $this, 'saveFooterAssets' ), 100000000 );
			add_action( 'wp_footer', array( $this, 'printScriptsStyles' ), ( PHP_INT_MAX - 1 ) );
		}

	    // Send an AJAX request to get the list of the loaded hardcoded scripts and styles and print it
	    add_action( 'wp_ajax_' . WPACU_PLUGIN_ID . '_print_loaded_hardcoded_assets', array( $this, 'ajaxPrintLoadedHardcodedAssets' ) );

		if ( is_admin() ) {
			$metaboxes = new MetaBoxes;

			// Do not load the meta box nor do any AJAX calls
			// if the asset management is not enabled for the Dashboard
			if ( $this->settings['dashboard_show'] == 1 ) {
				// Send an AJAX request to get the list of loaded scripts and styles and print it nicely
				add_action( 'wp_ajax_' . WPACU_PLUGIN_ID . '_get_loaded_assets', array( $this, 'ajaxGetJsonListCallback' ) );

				// This is valid when the Gutenberg editor (not via "Classic Editor" plugin) is used and the user used the following option:
                // "Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin)"
				add_action( 'wp_ajax_' . WPACU_PLUGIN_ID . '_load_page_restricted_area', array( $this, 'ajaxLoadRestrictedPageAreaCallback' ) );
			}

			// If assets management within the Dashboard is not enabled, an explanation message will be shown within the box unless the meta box is hidden completely
			if ( $this->settings['show_assets_meta_box'] ) {
				$metaboxes->initMetaBox( 'manage_page_assets' );
			}

			}

		/*
		   DO NOT disable the features below if the following apply:
		   - The option is not enabled
		   - Test Mode Enabled & Admin Logged in
		   - The user is in the Dashboard (any changes are applied in the front-end view)
		*/
		if ( ! ( $this->preventAssetsSettings() || is_admin() ) ) {
			if ( $this->settings['disable_emojis'] == 1 ) {
				$wpacuCleanUp = new CleanUp();
				$wpacuCleanUp->doDisableEmojis();
			}

			if ( $this->settings['disable_oembed'] == 1 ) {
				$wpacuCleanUp = new CleanUp();
				$wpacuCleanUp->doDisableOembed();
			}
		}
	}

	/**
	 * Priority: 8 (earliest)
	 */
	public function setVarsBeforeUpdate()
    {
		// Conditions
		// 1) User has rights to manage the assets and the option is enabled in plugin's Settings
		// 2) Not an AJAX call from the Dashboard
		// 3) Not inside the Dashboard
		$this->isFrontendEditView = ( $this->frontendShow() && Menu::userCanManageAssets() // 1
		                              && ! $this->isGetAssetsCall // 2
		                              && ! is_admin() ); // 3

		if ( $this->isFrontendEditView ) {
			$wpacuCleanUp = new CleanUp();
			$wpacuCleanUp->cleanUpHtmlOutputForAssetsCall();
		}

		$this->getCurrentPostId();

		define( 'WPACU_CURRENT_PAGE_ID', $this->getCurrentPostId() );
	}

	/**
	 * Priority: 10 (latest)
	 */
	public function setVarsAfterAnyUpdate()
    {
		if ( ! is_admin() ) {
			$this->globalUnloaded = $this->getGlobalUnload();

			// [wpacu_lite]
            if (! $this->isUpdateable) {
                return;
            }
	        // [/wpacu_lite]

			$getCurrentPost = $this->getCurrentPost();

			$post = $type = false;

			if (Misc::isHomePage() && empty($getCurrentPost) && ! get_option('page_on_front')) {
				$type = 'front_page';
			} elseif ( ! empty($getCurrentPost) )  {
				$type = 'post';
				$post = $getCurrentPost;
				$this->postTypesUnloaded = (isset($post->post_type) && $post->post_type) ? $this->getBulkUnload('post_type', $post->post_type) : array();
				}

			// [wpacu_lite]
            if (! $type) {
            	// The request is done for a page such as is_archive(), is_author(), 404, search
	            // and the premium extension is not available, thus no load exceptions are available
            	return;
            }
            // [/wpacu_lite]

            self::$vars['for_type'] = $type;
			self::$vars['current_post_id'] = $this->currentPostId;

			if ($post && $type === 'post' && isset($post->post_type) && $post->post_type) {
				self::$vars['current_post_type'] = $post->post_type;
			}

			}
	}

	/**
	 * This is useful to change via hooks the "src", "ver" or other values of the loaded handle
     * Example: You have your theme's main style.css that is needed on every page
     * On some pages, you only need 20% of it to load, and you can manually trim the other 80% (if you're sure you know which CSS is not used)
     * You can use a filter hook such as 'wpacu_{main_theme_handle_name_here}_css_handle_obj' to filter the "src" of the object and load an alternative purified CSS file
	 */
	public function alterWpStylesScriptsObj()
    {
	    add_action('wp_print_styles', function() {
		    global $wp_styles;

		    if ( ! empty($wp_styles->registered) ) {
			    foreach (array_keys($wp_styles->registered) as $styleHandle) {
				    $wp_styles->registered[$styleHandle] = $this->maybeFilterAssetObject($wp_styles->registered[$styleHandle], 'css');
			    }
		    }
	    });

        foreach (array('wp_print_scripts', 'wp_print_footer_scripts') as $actionToAdd) {
	        add_action( $actionToAdd, function() {
		        global $wp_scripts;

		        if ( ! empty($wp_scripts->registered) ) {
			        foreach (array_keys($wp_scripts->registered) as $scriptHandle) {
				        $wp_scripts->registered[$scriptHandle] = $this->maybeFilterAssetObject($wp_scripts->registered[$scriptHandle], 'js');
			        }
		        }
	        });
        }
    }

	/**
	 * @param $object | as returned from $wp_styles or $wp_scripts
	 * @param $assetType | "css" or "js"
	 *
	 * @return mixed
	 */
	public function maybeFilterAssetObject($object, $assetType)
	{
		if ( isset($_GET['wpacu_clean_load']) || isset($_GET['wpacu_load_original']) ) {
			return $object; // this is for debugging purposes, load the original source
		}

		if ( ! isset($object->handle, $object->src) ) {
			return $object;
		}

		$filterTagName = 'wpacu_'.$object->handle.'_'.$assetType.'_handle_data';

		if ( has_filter($filterTagName) ) {
			$originData = (array)$object;
			$newData = apply_filters( $filterTagName, $originData );

			if ( isset($originData['src'], $newData['src']) && $newData['src'] !== $originData['src'] ) {
				$object->src = $newData['src'];
				$object->src_origin = $originData['src'];

				$object->ver = $newData['ver'] ?: null;
				$object->ver_origin = isset($originData['ver']) ? $originData['ver'] : null;
			}
		}

		return $object;
	}

	/**
	 * In case there were assets enqueued within "wp_footer" action hook, instead of the standard "wp_enqueue_scripts"
	 */
	public function onPrintFooterScriptsStyles()
    {
        $this->filterStyles();
        $this->filterScripts();
    }

	/* [START] Styles Dequeue */
	/**
	 * See if there is any list with styles to be removed in JSON format
	 * Only the handles (the ID of the styles) is stored
	 */
	public function filterStyles()
	{
		/* [wpacu_timing] */ Misc::scriptExecTimer( 'filter_dequeue_styles' );/* [/wpacu_timing] */

		if (is_admin()) {
			return;
		}

		global $wp_styles;

		if (current_action() === 'wp_print_styles') {
		    ObjectCache::wpacu_cache_set('wpacu_styles_object_after_wp_print_styles', $wp_styles);
		}

		$list = array();

		if (current_action() === 'wp_print_footer_scripts') {
			$cachedWpStyles = ObjectCache::wpacu_cache_get('wpacu_styles_object_after_wp_print_styles');
			if (isset($cachedWpStyles->registered) && count($cachedWpStyles->registered) === count($wp_styles->registered)) {
				// The list was already generated in "wp_print_styles" and the number of registered assets are the same
				// Save resources and do not re-generate it
				$list = ObjectCache::wpacu_cache_get('wpacu_styles_handles_marked_for_unload');
			}
		}

		if ( empty($list) || ! is_array($list) ) {
			// [wpacu_lite]
			$nonAssetConfigPage = ( ! $this->isUpdateable && ! Misc::getShowOnFront() );
			// [/wpacu_lite]

			// It looks like the page loaded is neither a post, page or the front-page
			// We'll see if there are assets unloaded globally and unload them

			/*
			* [START] Build unload list
			*/
			$globalUnload = $this->globalUnloaded;

			// [wpacu_lite]
			if ( $nonAssetConfigPage && ! empty( $globalUnload['styles'] ) ) {
				$list = $globalUnload['styles'];
			} else {
            // [/wpacu_lite]

				// Post, Page, Front-page
				// and more (if the Premium Extension is activated)
			$toRemove = $this->getAssetsUnloadedPageLevel();

			$jsonList = @json_decode( $toRemove );

				$list = array();

				if ( isset( $jsonList->styles ) ) {
					$list = (array) $jsonList->styles;
				}

                if (! is_array($list)) {
                    $list = array();
                }

				// Any global unloaded styles? Append them
				if ( ! empty( $globalUnload['styles'] ) ) {
					foreach ( $globalUnload['styles'] as $handleStyle ) {
						$list[] = $handleStyle;
					}
				}

			    if ( self::isSingularPage() ) {
					// Any bulk unloaded styles (e.g. for all pages belonging to a post type)? Append them
					if ( empty( $this->postTypesUnloaded ) ) {
						$post                    = $this->getCurrentPost();
						$this->postTypesUnloaded = ( isset( $post->post_type ) && $post->post_type ) ? $this->getBulkUnload( 'post_type', $post->post_type ) : array();
					}

					if ( isset( $this->postTypesUnloaded['styles'] ) && ! empty( $this->postTypesUnloaded['styles'] ) ) {
						foreach ( $this->postTypesUnloaded['styles'] as $handleStyle ) {
							$list[] = $handleStyle;
						}
					}
				}
				// [wpacu_lite]
			}
			// [/wpacu_lite]

			// Site-Wide Unload for "Dashicons" if user is not logged-in
			if ( $this->settings['disable_dashicons_for_guests'] && ! is_user_logged_in() ) {
				$list[] = 'dashicons';
			}

			// Any bulk unloaded styles for 'category', 'post_tag' and more?
			// If the Pro version is enabled, any of the unloaded CSS will be added to the list
			$list = apply_filters( 'wpacu_filter_styles_list_unload', array_unique( $list ) );
			/*
			* [END] Build unload list
			*/

			// Add handles such as the Oxygen Builder CSS ones that are missing and added differently to the queue
			$allStyles = $this->wpStylesFilter( $wp_styles, 'registered', $list );

			if ( $allStyles !== null && ! empty( $allStyles->registered ) ) {
				// Going through all the registered styles
				foreach ( $allStyles->registered as $handle => $value ) {
					// This could be triggered several times, check if the style already exists
					if ( ! isset( $this->wpAllStyles['registered'][ $handle ] ) ) {
						$this->wpAllStyles['registered'][ $handle ] = $value;
						if ( in_array( $handle, $allStyles->queue ) ) {
							$this->wpAllStyles['queue'][] = $handle;
						}
					}
				}

				if ( isset( $this->wpAllStyles['queue'] ) && ! empty( $this->wpAllStyles['queue'] ) ) {
					$this->wpAllStyles['queue'] = array_unique( $this->wpAllStyles['queue'] );
				}
			}

			if ( isset( $this->wpAllStyles['registered'] ) && ! empty( $this->wpAllStyles['registered'] ) ) {
				ObjectCache::wpacu_cache_set( 'wpacu_all_styles_handles', array_keys( $this->wpAllStyles['registered'] ) );
			}

			// e.g. for test/debug mode or AJAX calls (where all assets have to load)
            if ( isset($_REQUEST['wpacu_no_css_unload']) ) {
				/* [wpacu_timing] */Misc::scriptExecTimer( 'filter_dequeue_styles', 'end' ); /* [/wpacu_timing] */
				return;
			}

			if ( $this->preventAssetsSettings(array('assets_call')) ) {
				/* [wpacu_timing] */Misc::scriptExecTimer( 'filter_dequeue_styles', 'end' ); /* [/wpacu_timing] */
				return;
			}

			/*
			* [START] Load Exception Check
			* */
            // Load exception rules ALWAYS have priority over the unloading ones
            // Thus, if an exception is found, the handle will be removed from the unloading list
			// Let's see if there are load exceptions for this page or site-wide (e.g. for logged-in users)
			// Only check for any load exceptions if the unloading list has at least one item
			// Otherwise the action is irrelevant since the assets are loaded anyway by default

            // These are LITE rules
			$list = ( ! empty($list) ) ? $this->filterAssetsUnloadList($list, 'styles','load_exception') : $list;

			// These are PRO rules or rules added via custom coding
            $list = ( ! empty($list) ) ? apply_filters('wpacu_filter_styles_list_load_exception', $list) : $list;
			/*
			 * [END] Load Exception Check
			 * */

            // Is $list still empty? Nothing to unload? Stop here
			if (empty($list)) {
				/* [wpacu_timing] */ Misc::scriptExecTimer( 'filter_dequeue_styles', 'end' ); /* [/wpacu_timing] */
				return;
			}
		}

		$ignoreChildParentList = apply_filters('wpacu_ignore_child_parent_list', $this->getIgnoreChildren());

		foreach ($list as $handle) {
			if (isset($ignoreChildParentList['styles'], $this->wpAllStyles['registered'][$handle]->src) && is_array($ignoreChildParentList['styles']) && array_key_exists($handle, $ignoreChildParentList['styles'])) {
				// Do not dequeue it as it's "children" will also be dequeued (ignore rule is applied)
				// It will be stripped by cleaning its LINK tag from the HTML Source
				$this->ignoreChildren['styles'][$handle] = $this->wpAllStyles['registered'][$handle]->src;
				$this->ignoreChildren['styles'][$handle.'_has_unload_rule'] = 1;
				$this->allUnloadedAssets['styles'][] = $handle;
				continue;
			}

			$handle = trim($handle);

			// Ignore auto generated handles for the hardcoded CSS as they were added for reference purposes
			// They will get stripped later on via OptimizeCommon.php
			// This is in case there are references from using the Pro version
			if (strpos($handle, 'wpacu_hardcoded_link_') === 0) {
				continue;
			}

			if (strpos($handle, 'wpacu_hardcoded_style_') === 0) {
				continue;
			}

			// Do not unload "dashicons" if the top WordPress admin bar is showing up
			if ($handle === 'dashicons' && is_admin_bar_showing()) {
				continue;
			}

			$this->allUnloadedAssets['styles'][] = $handle;

			// Only trigger the unloading on regular page load, not when the assets list is collected
			if ( ! $this->isGetAssetsCall ) {
				wp_deregister_style( $handle );
				wp_dequeue_style( $handle );
			}
		}

		if (current_action() === 'wp_print_styles') {
			ObjectCache::wpacu_cache_set( 'wpacu_styles_handles_marked_for_unload', $list );
		}

		/* [wpacu_timing] */ Misc::scriptExecTimer( 'filter_dequeue_styles', 'end' ); /* [/wpacu_timing] */
	}

	/**
	 * @param $wpStylesFilter
	 * @param string $listType
	 * @param array $unloadedList
	 *
	 * @return mixed
	 */
	public function wpStylesFilter($wpStylesFilter, $listType, $unloadedList = array())
	{
		global $wp_styles, $oxygen_vsb_css_styles;

		if ( ( $listType === 'registered' ) && isset( $oxygen_vsb_css_styles->registered ) && is_object( $oxygen_vsb_css_styles ) && ! empty( $oxygen_vsb_css_styles->registered ) ) {
			$stylesSpecialCases = array();

			foreach ($oxygen_vsb_css_styles->registered as $oxygenHandle => $oxygenValue) {
				if (! array_key_exists($oxygenHandle, $wp_styles->registered)) {
					$wpStylesFilter->registered[$oxygenHandle] = $oxygenValue;
					$stylesSpecialCases[$oxygenHandle] = $oxygenValue->src;
				}
			}

			$unloadedSpecialCases = array();

			foreach ($unloadedList as $unloadedHandle) {
				if (array_key_exists($unloadedHandle, $stylesSpecialCases)) {
					$unloadedSpecialCases[$unloadedHandle] = $stylesSpecialCases[$unloadedHandle];
				}
			}

			if (! empty($unloadedSpecialCases)) {
				// This will be later used in 'wp_loaded' below to extract the special styles
				echo self::$wpStylesSpecialDelimiters['start'] . wp_json_encode($unloadedSpecialCases) . self::$wpStylesSpecialDelimiters['end'];
			}
		}

		if ( ( $listType === 'done' ) && isset( $oxygen_vsb_css_styles->done ) && is_object( $oxygen_vsb_css_styles ) ) {
			foreach ($oxygen_vsb_css_styles->done as $oxygenHandle) {
				if (! in_array($oxygenHandle, $wp_styles->done)) {
					$wpStylesFilter[] = $oxygenHandle;
				}
			}
		}

		if ( ( $listType === 'queue' ) && isset( $oxygen_vsb_css_styles->queue ) && is_object( $oxygen_vsb_css_styles ) ) {
			foreach ($oxygen_vsb_css_styles->queue as $oxygenHandle) {
				if (! in_array($oxygenHandle, $wp_styles->queue)) {
					$wpStylesFilter[] = $oxygenHandle;
				}
			}
		}

		return $wpStylesFilter;
	}

	/**
	 *
	 */
	public function filterStylesSpecialCases()
	{
		if ( isset($_REQUEST['wpacu_no_css_unload']) ) {
			return;
		}

		add_action('wp_loaded', static function() {
			ob_start(static function($htmlSource) {
				if (strpos($htmlSource, self::$wpStylesSpecialDelimiters['start']) === false && strpos($htmlSource, self::$wpStylesSpecialDelimiters['end']) === false) {
					return $htmlSource;
				}

				$jsonStylesSpecialCases = Misc::extractBetween($htmlSource, self::$wpStylesSpecialDelimiters['start'], self::$wpStylesSpecialDelimiters['end']);

				$stylesSpecialCases = json_decode($jsonStylesSpecialCases, ARRAY_A);

				if (Misc::jsonLastError() === JSON_ERROR_NONE && ! empty($stylesSpecialCases)) {
					foreach ($stylesSpecialCases as $styleSrc) {
						$styleLocalSrc = Misc::getLocalSrc($styleSrc);
						$styleRelSrc = isset($styleLocalSrc['rel_src']) ? $styleLocalSrc['rel_src'] : $styleSrc;
						$htmlSource = CleanUp::cleanLinkTagFromHtmlSource($styleRelSrc, $htmlSource);
					}

					// Strip the info HTML comment
					$htmlSource = str_replace(
						self::$wpStylesSpecialDelimiters['start'] . $jsonStylesSpecialCases . self::$wpStylesSpecialDelimiters['end'],
						'',
						$htmlSource
					);
				}

				return $htmlSource;
			});
		}, 1);
	}

	/**
	 *
	 */
	public function printAnySpecialCss()
    {
        if (isset($this->allUnloadedAssets['styles']) && ! empty($this->allUnloadedAssets['styles'])) {
	        if (in_array('photoswipe', $this->allUnloadedAssets['styles'])) {
	            ?>
                <!-- Asset CleanUp: "photoswipe" unloaded (avoid printing useless HTML) -->
                <style <?php echo Misc::getStyleTypeAttribute(); ?>>.pswp { display: none; }</style>
                <?php
            }
        }
    }
	/* [END] Styles Dequeue */

	/* [START] Scripts Dequeue */
    /**
     * See if there is any list with scripts to be removed in JSON format
     * Only the handles (the ID of the scripts) are saved
     */
    public function filterScripts()
    {
	    /* [wpacu_timing] */ Misc::scriptExecTimer( 'filter_dequeue_scripts' );/* [/wpacu_timing] */

        if (is_admin()) {
            return;
        }

        global $wp_scripts;

	    if (current_action() === 'wp_print_scripts') {
		    ObjectCache::wpacu_cache_set('wpacu_scripts_object_after_wp_print_scripts', $wp_scripts);
	    }

	    $list = array();

	    if (current_action() === 'wp_print_footer_scripts') {
		    $cachedWpScripts = ObjectCache::wpacu_cache_get('wpacu_scripts_object_after_wp_print_scripts');
		    if (isset($cachedWpScripts->registered) && count($cachedWpScripts->registered) === count($wp_scripts->registered)) {
			    // The list was already generated in "wp_print_scripts" and the number of registered assets are the same
			    // Save resources and do not re-generate it
			    $list = ObjectCache::wpacu_cache_get('wpacu_scripts_handles_marked_for_unload');
		    }
	    }

	    if ( empty($list) ) {
		    // [wpacu_lite]
		    $nonAssetConfigPage = ( ! $this->isUpdateable && ! Misc::getShowOnFront() );
		    // [/wpacu_lite]

		    // It looks like the page loaded is neither a post, page or the front-page
		    // We'll see if there are assets unloaded globally and unload them
		    /*
			* [START] Build unload list
		    */
		    $globalUnload = $this->globalUnloaded;

		    // [wpacu_lite]
		    if ( $nonAssetConfigPage && ! empty( $globalUnload['scripts'] ) ) {
			    $list = $globalUnload['scripts'];
		    } else { // [/wpacu_lite]
			    // Post, Page or Front-page?
		        $toRemove = $this->getAssetsUnloadedPageLevel();

			    $jsonList = @json_decode( $toRemove );

			    $list = array();

			    if ( isset( $jsonList->scripts ) ) {
				    $list = (array) $jsonList->scripts;
			    }

			    // Any global unloaded styles? Append them
			    if ( ! empty( $globalUnload['scripts'] ) ) {
				    foreach ( $globalUnload['scripts'] as $handleScript ) {
					    $list[] = $handleScript;
				    }
			    }

                if ( self::isSingularPage() ) {
                    // Any bulk unloaded styles (e.g. for all pages belonging to a post type)? Append them
                    if ( empty( $this->postTypesUnloaded ) ) {
                        $post = $this->getCurrentPost();
                        // Make sure the post_type is set; it's not in specific pages (e.g. BuddyPress ones)
                        $this->postTypesUnloaded = ( isset( $post->post_type ) && $post->post_type ) ? $this->getBulkUnload( 'post_type', $post->post_type ) : array();
                    }

                    if ( isset( $this->postTypesUnloaded['scripts'] ) && ! empty( $this->postTypesUnloaded['scripts'] ) ) {
                        foreach ( $this->postTypesUnloaded['scripts'] as $handleStyle ) {
                            $list[] = $handleStyle;
                        }
                    }
                }
			    // [wpacu_lite]
		    }
		    // [/wpacu_lite]

		    // Any bulk unloaded styles for 'category', 'post_tag' and more?
            // These are PRO rules or rules added via custom coding
		    $list = apply_filters( 'wpacu_filter_scripts_list_unload', array_unique( $list ) );

		    global $wp_scripts;

		    $allScripts = $wp_scripts;

		    if ( $allScripts !== null && ! empty( $allScripts->registered ) ) {
			    foreach ( $allScripts->registered as $handle => $value ) {
				    // This could be triggered several times, check if the script already exists
				    if ( ! isset( $this->wpAllScripts['registered'][ $handle ] ) ) {
					    $this->wpAllScripts['registered'][ $handle ] = $value;
					    if ( in_array( $handle, $allScripts->queue ) ) {
						    $this->wpAllScripts['queue'][] = $handle;
					    }
				    }
			    }

			    if ( isset( $this->wpAllScripts['queue'] ) && ! empty( $this->wpAllScripts['queue'] ) ) {
				    $this->wpAllScripts['queue'] = array_unique( $this->wpAllScripts['queue'] );
			    }
		    }
		    /*
			* [END] Build unload list
		    */

		    /*
			* [START] Load Exception Check
			* */
		    // Load exception rules ALWAYS have priority over the unloading ones
		    // Thus, if an exception is found, the handle will be removed from the unloading list
		    // Let's see if there are load exceptions for this page or site-wide (e.g. for logged-in users)

            // These are LITE rules
            $list = ( ! empty($list) ) ? $this->filterAssetsUnloadList($list, 'scripts', 'load_exception') : $list;

            // [wpacu_pro]
		    // These are PRO rules or rules added via custom coding
		    // Only check for any load exceptions if the unloading list has at least one item
		    // Otherwise the action is irrelevant since the assets are loaded anyway by default
		    $list = ( ! empty($list) ) ? apply_filters('wpacu_filter_scripts_list_load_exception', $list) : $list;
		    // [/wpacu_pro]
		    /*
			 * [END] Load Exception Check
			 * */

		    // Nothing to unload
		    if ( empty( $list ) ) {
			    /* [wpacu_timing] */Misc::scriptExecTimer( 'filter_dequeue_scripts', 'end' ); /* [/wpacu_timing] */
			    return;
		    }

		    // e.g. for test/debug mode or AJAX calls (where all assets have to load)
		    if ( isset($_REQUEST['wpacu_no_js_unload']) || $this->preventAssetsSettings(array('assets_call')) ) {
			    /* [wpacu_timing] */Misc::scriptExecTimer( 'filter_dequeue_scripts', 'end' ); /* [/wpacu_timing] */
			    return;
		    }
	    }

	    $ignoreChildParentList = apply_filters('wpacu_ignore_child_parent_list', $this->getIgnoreChildren());

	    foreach ($list as $handle) {
            $handle = trim($handle);

	        // Ignore auto generated handles for the hardcoded CSS as they were added for reference purposes
	        // They will get stripped later on via OptimizeCommon.php
	        // The handle is used just for reference for later stripping via altering the DOM
	        if (strpos($handle, 'wpacu_hardcoded_script_inline_') !== false) {
		        // [wpacu_pro]
		        $saveMarkedHandles = ObjectCache::wpacu_cache_get('wpacu_hardcoded_scripts_inline') ?: array();
		        $saveMarkedHandles[] = $handle;
		        $this->allUnloadedAssets['scripts'][] = $handle; // for "wpacu_no_load" on hardcoded list
		        ObjectCache::wpacu_cache_set( 'wpacu_hardcoded_scripts_inline', $saveMarkedHandles );
		        // [/wpacu_pro]
		        continue;
	        }

	        if (strpos($handle, 'wpacu_hardcoded_script_src_') !== false) {
		        // [wpacu_pro]
		        $saveMarkedHandles = ObjectCache::wpacu_cache_get('wpacu_hardcoded_scripts_src') ?: array();
		        $saveMarkedHandles[] = $handle;
		        $this->allUnloadedAssets['scripts'][] = $handle; // for "wpacu_no_load" on hardcoded list
		        ObjectCache::wpacu_cache_set( 'wpacu_hardcoded_scripts_src', $saveMarkedHandles );
		        // [/wpacu_pro]
		        continue;
	        }

            // Special Action for 'jquery-migrate' handler as it's tied to 'jquery'
            if ($handle === 'jquery-migrate' && isset($this->wpAllScripts['registered']['jquery'])) {
	            $jQueryRegScript = $this->wpAllScripts['registered']['jquery'];

	            if (isset($jQueryRegScript->deps)) {
		            $jQueryRegScript->deps = array_diff($jQueryRegScript->deps, array('jquery-migrate'));
	            }

	            if (Misc::isPluginActive('jquery-updater/jquery-updater.php')) {
		            wp_dequeue_script($handle);
	            }

				continue;
            }

	        if (isset($ignoreChildParentList['scripts'], $this->wpAllScripts['registered'][$handle]->src) && is_array($ignoreChildParentList['scripts']) && array_key_exists($handle, $ignoreChildParentList['scripts'])) {
		        // Do not dequeue it as it's "children" will also be dequeued (ignore rule is applied)
		        // It will be stripped by cleaning its SCRIPT tag from the HTML Source
                $this->ignoreChildren['scripts'][$handle] = $this->wpAllScripts['registered'][$handle]->src;
		        $this->ignoreChildren['scripts'][$handle.'_has_unload_rule'] = 1;
		        $this->allUnloadedAssets['scripts'][] = $handle;
		        continue;
	        }

	        $this->allUnloadedAssets['scripts'][] = $handle;

	        // Only trigger the unloading on regular page load, not when the assets list is collected
	        if ( ! $this->isGetAssetsCall ) {
		        wp_deregister_script( $handle );
		        wp_dequeue_script( $handle );
	        }
        }

	    if (current_action() === 'wp_print_scripts') {
		    ObjectCache::wpacu_cache_set( 'wpacu_scripts_handles_marked_for_unload', $list );
	    }

	    /* [wpacu_timing] */ Misc::scriptExecTimer( 'filter_dequeue_scripts', 'end' ); /* [/wpacu_timing] */
    }
	/* [END] Scripts Dequeue */


	/**
	 * @param $list (should never be empty when called)
	 * @param $assetType
	 * @param $ruleType
	 *
	 * @return mixed
	 */
	public function filterAssetsUnloadList($list, $assetType, $ruleType)
    {
	    // Remove any assets from the list in case there are any load exceptions detected
        if ($ruleType === 'load_exception') {
	        $this->loadExceptionsPageLevel      = $this->getLoadExceptionsPageLevel(self::$vars['for_type'], self::$vars['current_post_id']);
            $this->loadExceptionsLoggedInGlobal = $this->getHandleLoadLoggedIn();
            $this->loadExceptionsPostType       = $this->getLoadExceptionsPostType(self::$vars['current_post_type']);

	        $anyAssetsLoadExceptions = ( ! empty( $this->loadExceptionsPageLevel[ $assetType ] )
                || ( ! empty( $this->loadExceptionsLoggedInGlobal[ $assetType ] ) )
                || ( ! empty( $this->loadExceptionsPostType[$assetType] ) )
            );

	        if ( $anyAssetsLoadExceptions ) {
		        foreach ( $list as $handleKey => $handle ) {
			        $loadAssetAsException = in_array( $handle, $this->loadExceptionsPageLevel[ $assetType ] )  // 1) per page, per group pages OR
                        || in_array( $handle, $this->loadExceptionsPostType[ $assetType ] ) // 2) On all pages belonging to a specific post type
                        || ( in_array( $handle, $this->loadExceptionsLoggedInGlobal[ $assetType ] ) && is_user_logged_in() ); // 3) site-wide if the user is logged-in
			        if ( $loadAssetAsException ) {
				        unset( $list[ $handleKey ] );
			        }
		        }
	        }
        }

	    return $list;
    }

	/**
     * Alter CSS/JS list marked for dequeue
	 * @param $for
	 * @return array
	 */
	public function unloadAssetOnTheFly($for)
    {
	    $assetType = ($for === 'css') ? 'styles' : 'scripts';
	    $assetIndex = 'wpacu_unload_'.$for;

        if (! ($unloadAsset = Misc::getVar('get', $assetIndex))) {
            return array();
        }

	    $assetHandles = array();

        if (strpos($unloadAsset, ',') === false) { // No comma, just one asset targeted
            if (strpos($unloadAsset, '[ignore-deps]') !== false) {
                $unloadAsset = str_replace('[ignore-deps]', '', $unloadAsset);
                $this->ignoreChildrenHandlesOnTheFly[$assetType][] = $unloadAsset;
            }

            $assetHandles[] = $unloadAsset;
        } else { // There are commas, multiple assets targeted
            foreach (explode(',', $unloadAsset) as $unloadAsset) {
                $unloadAsset = trim($unloadAsset);

                if ($unloadAsset) {
                    if (strpos($unloadAsset, '[ignore-deps]') !== false) {
                        $unloadAsset = str_replace('[ignore-deps]', '', $unloadAsset);
                        $this->ignoreChildrenHandlesOnTheFly[$assetType][] = $unloadAsset;
                    }

                    $assetHandles[] = $unloadAsset;
                }
            }
        }

        return $assetHandles;
    }

	/**
	 * @param $exceptionsList
	 *
	 * @return array
	 */
	public function makeLoadExceptionOnTheFly($exceptionsList)
    {
	    $exceptionsListDebug = array('styles' => array(), 'scripts' => array());

        foreach (array('css', 'js') as $assetExt) {
            $assetKey = ($assetExt === 'css') ? 'styles' : 'scripts';
            $indexToCheck = 'wpacu_load_'.$assetExt;

            if ($loadAsset = Misc::getVar('get', $indexToCheck)) {
                if (strpos($loadAsset, ',') === false && (! in_array($loadAsset, $exceptionsList[$assetKey]))) {
                    $exceptionsList[$assetKey][] = $loadAsset;
	                $exceptionsListDebug[$assetKey][] = $loadAsset;
                } else {
                    foreach (explode(',', $loadAsset) as $loadAsset) {
                        if (($loadAsset = trim($loadAsset)) && (! in_array($loadAsset, $exceptionsList[$assetKey]))) {
                            $exceptionsList[$assetKey][] = $loadAsset;
	                        $exceptionsListDebug[$assetKey][] = $loadAsset;
                        }
                    }
                }
            }
	    }

        ObjectCache::wpacu_cache_add('wpacu_exceptions_list_page_request', $exceptionsListDebug);

        return $exceptionsList;
    }

    /**
     * This fetches the "Load it on this page" exceptions
     *
     * @param string $type ("post", "front_page")
     * @param string $postId
     *
     * @return array|array[]
     */
    public function getLoadExceptionsPageLevel($type = 'post', $postId = '')
    {
        if ( $this->loadExceptionsPageLevel['_set'] ) {
            return $this->loadExceptionsPageLevel; // it was set
        }

	    $exceptionsListDefault = $exceptionsList = array( 'styles' => array(), 'scripts' => array() );

	    if ( $type === 'post' && ! $postId ) {
            // $postId needs to have a value if $type is a 'post' type
            $this->loadExceptionsPageLevel = $exceptionsListDefault;
		    $this->loadExceptionsPageLevel['_set'] = true;
            return $this->loadExceptionsPageLevel;
        }

	    if ( ! $type ) {
            // Invalid request
            $this->loadExceptionsPageLevel = $exceptionsListDefault;
		    $this->loadExceptionsPageLevel['_set'] = true;
            return $this->loadExceptionsPageLevel;
        }

	    // Default
	    $exceptionsListJson = '';

	    // Post or Post of the Homepage (if chosen in the Dashboard)
	    if ( $type === 'post' || ( Misc::getShowOnFront() === 'page' && $postId ) ) {
            $exceptionsListJson = get_post_meta( $postId, '_' . WPACU_PLUGIN_ID . '_load_exceptions', true );
        }

	    // The home page could also be the list of the latest blog posts
	    if ( $type === 'front_page' ) {
            $exceptionsListJson = get_option( WPACU_PLUGIN_ID . '_front_page_load_exceptions' );
        }

	    if ( $exceptionsListJson ) {
            $exceptionsList = json_decode( $exceptionsListJson, true );

            if ( Misc::jsonLastError() !== JSON_ERROR_NONE ) {
                $exceptionsList = $exceptionsListDefault;
            }
        }

	    // Any exceptions on the fly added for debugging purposes? Make sure to grab them
	    $exceptionsList = $this->makeLoadExceptionOnTheFly( $exceptionsList );

	    // Avoid any notice errors
	    foreach ( array( 'styles', 'scripts' ) as $assetType ) {
            if ( ! isset( $exceptionsList[ $assetType ] ) ) {
                $exceptionsList[ $assetType ] = array();
            }
        }

	    $this->loadExceptionsPageLevel = $exceptionsList;
	    $this->loadExceptionsPageLevel['_set'] = true;

	    return $this->loadExceptionsPageLevel;
    }

	/**
     * Option: 'Make an exception from any unload rule & always load it' -> 'On all pages of "post" post type'
     *
	 * @param $postType
	 *
	 * @return \array[][]
	 */
	public function getLoadExceptionsPostType($postType)
    {
        if ($this->loadExceptionsPostType['_set']) {
            return $this->loadExceptionsPostType;
        }

        // Not on a singular page
        if ($postType === '') {
	        $this->loadExceptionsPostType['_set'] = true;
	        return $this->loadExceptionsPostType;
        }

	    $exceptionsListDefault = array('styles' => array(), 'scripts' => array());

	    $exceptionsListJson = get_option(WPACU_PLUGIN_ID . '_post_type_load_exceptions');

	    $exceptionsList = @json_decode($exceptionsListJson, true);

	    // Issues with decoding the JSON file? Return an empty list
	    if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
            $this->loadExceptionsPostType = $exceptionsListDefault;
		    $this->loadExceptionsPostType['_set'] = true;
		    return $this->loadExceptionsPostType;
	    }

	    // Return any handles added as load exceptions for the requested $postType
	    if (isset($exceptionsList[$postType])) {
            foreach ( $exceptionsList[$postType] as $assetType => $assetList ) {
                foreach ( $assetList as $assetHandle => $assetValue ) {
                    if ( $assetValue ) {
                        $this->loadExceptionsPostType[ $assetType ][] = $assetHandle;
                    }
                }
            }
        }

	    $this->loadExceptionsPostType['_set'] = true;
	    return $this->loadExceptionsPostType;
    }

    /**
     * Option: Unload site-wide * everywhere
     *
     * @return array
     */
    public function getGlobalUnload()
    {
        $existingListEmpty = array('styles' => array(), 'scripts' => array());
        $existingListJson  = get_option( WPACU_PLUGIN_ID . '_global_unload');

        $existingListData = $this->existingList($existingListJson, $existingListEmpty);

        // No 'styles' or 'scripts' - Set them as empty to avoid any PHP warning errors
	    foreach ( array('styles', 'scripts') as $assetType ) {
		    if ( ! isset( $existingListData['list'][$assetType] ) || ! is_array( $existingListData['list'][$assetType] ) ) {
			    $existingListData['list'][$assetType] = array();
		    }
	    }

        return $existingListData['list'];
    }

	/**
	 * @param string $for (could be 'post_type', 'taxonomy' for the Pro version etc.)
	 * @param string $type
	 *
	 * @return array
	 */
	public function getBulkUnload($for, $type = 'all')
    {
        $existingListEmpty = array('styles' => array(), 'scripts' => array());

        $existingListAllJson = get_option( WPACU_PLUGIN_ID . '_bulk_unload');

        if (! $existingListAllJson) {
            return $existingListEmpty;
        }

        $existingListAll = json_decode($existingListAllJson, true);

        if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
            return $existingListEmpty;
        }

        $existingList = $existingListEmpty;

        foreach (array('styles', 'scripts') as $assetType) {
	        if ( isset( $existingListAll[$assetType][ $for ][ $type ] ) && is_array( $existingListAll[$assetType][ $for ][ $type ] ) ) {
		        $existingList[$assetType] = $existingListAll[$assetType][ $for ][ $type ];
	        }
        }

        return $existingList;
    }

	/**
     * Option: Add Note
     *
	 * @return array
	 */
	public function getHandleNotes()
	{
		$handleNotes = array('styles' => array(), 'scripts' => array());

		$handleNotesListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($handleNotesListJson) {
			$handleNotesList = @json_decode($handleNotesListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return $handleNotes;
			}

			// Are new positions set for styles and scripts?
			foreach (array('styles', 'scripts') as $assetKey) {
				if ( isset( $handleNotesList[$assetKey]['notes'] ) && ! empty( $handleNotesList[$assetKey]['notes'] ) ) {
					$handleNotes[$assetKey] = $handleNotesList[$assetKey]['notes'];
				}
			}
		}

		return $handleNotes;
	}

	/**
     * Option: 'Make an exception from any unload rule & always load it' -> 'If the user is logged-in'
     *
	 * @return array
	 */
	public function getHandleLoadLoggedIn()
    {
    	if ($this->loadExceptionsLoggedInGlobal['_set']) {
			return $this->loadExceptionsLoggedInGlobal;
	    }

	    $targetGlobalKey = 'load_it_logged_in';

	    $handleData = array( 'styles' => array(), 'scripts' => array() );

	    $handleDataListJson = get_option( WPACU_PLUGIN_ID . '_global_data' );

	    if ( $handleDataListJson ) {
		    $handleDataList = @json_decode( $handleDataListJson, true );

		    // Issues with decoding the JSON file? Return an empty list
		    if ( Misc::jsonLastError() !== JSON_ERROR_NONE ) {
			    $this->loadExceptionsLoggedInGlobal = $handleData;
			    $this->loadExceptionsLoggedInGlobal['_set'] = true;
			    return $handleData;
		    }

		    // Are load exceptions set for styles and scripts?
		    foreach ( array( 'styles', 'scripts' ) as $assetKey ) {
			    if ( isset( $handleDataList[ $assetKey ][ $targetGlobalKey ] ) && ! empty( $handleDataList[ $assetKey ][ $targetGlobalKey ] ) ) {
				    $handleData[ $assetKey ] = array_keys($handleDataList[ $assetKey ][ $targetGlobalKey ]);
			    }
		    }
	    }

	    $this->loadExceptionsLoggedInGlobal = $handleData;

	    // Avoid any PHP notice errors
	    foreach (array('styles', 'scripts') as $assetType) {
	        if ( ! isset($this->loadExceptionsLoggedInGlobal[$assetType]) ) {
		        $this->loadExceptionsLoggedInGlobal[$assetType] = array();
            }
        }

	    $this->loadExceptionsLoggedInGlobal['_set'] = true;

	    return $this->loadExceptionsLoggedInGlobal;
    }

	/**
     * Option: 'Ignore dependency rule and keep the "children" loaded'
     *
	 * @return array
	 */
	public function getIgnoreChildren()
	{
	    if (empty($this->ignoreChildren)) {
		    $ignoreChildListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		    if ($ignoreChildListJson) {
			    $ignoreChildList = @json_decode($ignoreChildListJson, true);

			    // Issues with decoding the JSON file? Return an empty list
			    if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				    return $this->ignoreChildren;
			    }

			    // Are ignore "children" rules set for styles and scripts?
			    foreach (array('styles', 'scripts') as $assetKey) {
				    if (isset($ignoreChildList[$assetKey]['ignore_child']) && $ignoreChildList[$assetKey]['ignore_child']) {
					    $this->ignoreChildren[$assetKey] = $ignoreChildList[$assetKey]['ignore_child'];
				    }
			    }
		    }
	    }

		return $this->ignoreChildren;
	}

	/**
	 * @return array
	 */
	public static function getHandlesInfo()
    {
        $assetsInfo = array('styles' => array(), 'scripts' => array());

	    $wpacuGlobalDataJson = get_option(WPACU_PLUGIN_ID . '_global_data');
	    $wpacuGlobalData = json_decode($wpacuGlobalDataJson, ARRAY_A);
            if (Misc::jsonLastError() === JSON_ERROR_NONE) {
		    foreach (array('styles', 'scripts') as $assetKey) {
			    if ( isset( $wpacuGlobalData[$assetKey]['assets_info'] ) && ! empty( $wpacuGlobalData[$assetKey]['assets_info'] ) ) {
				    $assetsInfo[$assetKey] = Misc::filterList( $wpacuGlobalData[$assetKey]['assets_info'] );
			    }
		    }
		    }

	    // Fallback for those who still use the old transient way of fetching the assets info
	    if ($assetsInfoTransient = get_transient(WPACU_PLUGIN_ID . '_assets_info')) {
		    $assetsInfoTransientArray = @json_decode($assetsInfoTransient, ARRAY_A);

		    if (is_array($assetsInfoTransientArray) && ! empty($assetsInfoTransientArray)) {
			    foreach ($assetsInfoTransientArray as $assetKeyTransient => $handlesList) {
				    if (! in_array($assetKeyTransient, array('styles', 'scripts'))) {
					    continue;
				    }

				    foreach ($handlesList as $handleName => $handleData) {
					    if (! isset($assetsInfo[$assetKeyTransient][$handleName])) {
						    $assetsInfo[$assetKeyTransient][$handleName] = $handleData;
					    }
				    }
			    }
		    }
	    }

	    return $assetsInfo;
    }

	/**
	 * @param $ignoreChildParentList
	 *
	 * @return array
	 */
	public function filterIgnoreChildParentList($ignoreChildParentList)
	{
		if (isset($this->ignoreChildrenHandlesOnTheFly['styles']) && ! empty($this->ignoreChildrenHandlesOnTheFly['styles'])) {
			foreach ($this->ignoreChildrenHandlesOnTheFly['styles'] as $cssHandle) {
				$ignoreChildParentList['styles'][$cssHandle] = 1;
			}
		}

		if (isset($this->ignoreChildrenHandlesOnTheFly['scripts']) && ! empty($this->ignoreChildrenHandlesOnTheFly['scripts'])) {
			foreach ($this->ignoreChildrenHandlesOnTheFly['scripts'] as $jsHandle) {
				$ignoreChildParentList['scripts'][$jsHandle] = 1;
			}
		}

		return $ignoreChildParentList;
	}

	/**
	 *
	 */
	public function saveHeadAssets()
    {
        global $wp_styles, $wp_scripts;

	    if (isset($this->wpAllStyles['queue']) && ! empty($this->wpAllStyles['queue'])) {
		    $this->stylesInHead = $this->wpAllStyles['queue'];
	    }

	    if (isset($wp_styles->queue) && ! empty($wp_styles->queue)) {
            foreach ($wp_styles->queue as $styleHandle) {
	            $this->stylesInHead[] = $styleHandle;
            }
        }

	    $this->stylesInHead = array_unique($this->stylesInHead);

	    if (isset($this->wpAllScripts['queue']) && ! empty($this->wpAllScripts['queue'])) {
		    $this->scriptsInHead = $this->wpAllScripts['queue'];
	    }

	    if (isset($wp_scripts->queue) && ! empty($wp_scripts->queue)) {
		    foreach ($wp_scripts->queue as $scriptHandle) {
			    $this->scriptsInHead[] = $scriptHandle;
		    }
	    }

	    $this->scriptsInHead = array_unique($this->scriptsInHead);

	    }

    /**
     *
     */
    public function saveFooterAssets()
    {
        global $wp_scripts, $wp_styles;

        // [Styles Collection]
	    $footerStyles = array();

	    if (isset($this->wpAllStyles['queue']) && ! empty($this->wpAllStyles['queue'])) {
		    foreach ( $this->wpAllStyles['queue'] as $handle ) {
			    if ( ! in_array( $handle, $this->stylesInHead ) ) {
				    $footerStyles[] = $handle;
			    }
		    }
	    }

	    if (isset($wp_styles->queue) && ! empty($wp_styles->queue)) {
		    foreach ( $wp_styles->queue as $handle ) {
			    if ( ! in_array( $handle, $this->stylesInHead ) ) {
				    $footerStyles[] = $handle;
			    }
		    }
	    }

	    $this->assetsInFooter['styles'] = array_unique($footerStyles);
        // [/Styles Collection]

	    // [Scripts Collection]
	    $this->assetsInFooter['scripts'] = (isset($wp_scripts->in_footer) && ! empty($wp_scripts->in_footer)) ? $wp_scripts->in_footer : array();

	    if (isset($this->wpAllScripts['queue']) && ! empty($this->wpAllScripts['queue'])) {
		    foreach ( $this->wpAllScripts['queue'] as $handle ) {
			    if ( ! in_array( $handle, $this->scriptsInHead ) ) {
				    $this->assetsInFooter['scripts'][] = $handle;
			    }
		    }
	    }

	    if (isset($wp_scripts->queue) && ! empty($wp_scripts->queue)) {
		    foreach ( $wp_scripts->queue as $handle ) {
			    if ( ! in_array( $handle, $this->scriptsInHead ) ) {
				    $this->assetsInFooter['scripts'][] = $handle;
			    }
		    }
	    }

	    $this->assetsInFooter['scripts'] = array_unique($this->assetsInFooter['scripts']);
	    // [/Scripts Collection]

	    }

    /**
     * This output will be extracted and the JSON will be processed
     * in the WP Dashboard when editing a post
     *
     * It will also print the asset list in the front-end
     * if the option was enabled in the Settings
     */
    public function printScriptsStyles()
    {
    	// Not for WordPress AJAX calls
        if (self::$domGetType === 'direct' && defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        $isFrontEndEditView = $this->isFrontendEditView;
        $isDashboardEditView = (! $isFrontEndEditView && $this->isGetAssetsCall);

        if (! $isFrontEndEditView && ! $isDashboardEditView) {
            return;
        }

        if ($isFrontEndEditView && isset($_GET['elementor-preview']) && $_GET['elementor-preview']) {
            return;
        }

	    /* [wpacu_timing] */ $wpacuTimingName = 'output_css_js_manager'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */

        // Prevent plugins from altering the DOM
        add_filter('w3tc_minify_enable', '__return_false');

        Misc::w3TotalCacheFlushObjectCache();

        // This is the list of the scripts and styles that were eventually loaded
        // We have also the list of the ones that were unloaded
        // located in $this->wpScripts and $this->wpStyles
        // We will add it to the list as they will be marked

        $stylesBeforeUnload = $this->wpAllStyles;
        $scriptsBeforeUnload = $this->wpAllScripts;

        global $wp_scripts, $wp_styles;

        $list = array();

	    // e.g. for "Loaded" and "Unloaded" statuses
        $currentUnloadedAll = isset($this->allUnloadedAssets) ? $this->allUnloadedAssets : array('styles' => array(), 'scripts' => array());

        foreach (array('styles', 'scripts') as $assetType) {
	        if ( isset( $currentUnloadedAll[$assetType] ) ) {
		        $currentUnloadedAll[$assetType] = array_unique( $currentUnloadedAll[$assetType] );
	        }
        }

        $manageStylesCore = isset($wp_styles->done) && is_array($wp_styles->done) ? $wp_styles->done : array();
	    $manageStyles     = $this->wpStylesFilter($manageStylesCore, 'done');

	    $manageScripts    = isset($wp_scripts->done) && is_array($wp_scripts->done) ? $wp_scripts->done : array();

	    if ($isFrontEndEditView) {
	    	if (! empty($this->wpAllStyles) && isset($this->wpAllStyles['queue'])) {
			    $manageStyles = $this->wpStylesFilter($this->wpAllStyles['queue'], 'queue');
		    }

		    if (! empty($this->wpAllScripts) && isset($this->wpAllScripts['queue'])) {
			    $manageScripts = $this->wpAllScripts['queue'];
		    }

		    if (! empty($currentUnloadedAll['styles'])) {
			    foreach ( $currentUnloadedAll['styles'] as $currentUnloadedStyleHandle ) {
				    if ( ! in_array( $currentUnloadedStyleHandle, $manageStyles ) ) {
					    $manageStyles[] = $currentUnloadedStyleHandle;
				    }
			    }
		    }

		    if (! empty($manageStylesCore)) {
		    	foreach ($manageStylesCore as $wpDoneStyle) {
				    if ( ! in_array( $wpDoneStyle, $manageStyles ) ) {
					    $manageStyles[] = $wpDoneStyle;
				    }
			    }
		    }

		    $manageStyles = array_unique($manageStyles);

		    if (! empty($currentUnloadedAll['scripts'])) {
			    foreach ( $currentUnloadedAll['scripts'] as $currentUnloadedScriptHandle ) {
				    if ( ! in_array( $currentUnloadedScriptHandle, $manageScripts ) ) {
					    $manageScripts[] = $currentUnloadedScriptHandle;
				    }
			    }
		    }

		    if (isset($wp_scripts->done) && ! empty($wp_scripts->done)) {
			    foreach ($wp_scripts->done as $wpDoneScript) {
				    if ( ! in_array( $wpDoneScript, $manageScripts ) ) {
					    $manageScripts[] = $wpDoneScript;
				    }
			    }
		    }

		    $manageScripts = array_unique($manageScripts);
	    }

	    /*
		 * Style List
		 */
	    if ($isFrontEndEditView) { // "Manage in the Front-end"
		    $stylesList = $stylesBeforeUnload['registered'];
	    } else { // "Manage in the Dashboard"
		    $stylesListFilterAll = $this->wpStylesFilter($wp_styles, 'registered');
		    $stylesList = $stylesListFilterAll->registered;
        }

	    if (! empty($stylesList)) {
            foreach ($manageStyles as $handle) {
	            if (! isset($stylesList[$handle]) || in_array($handle, $this->skipAssets['styles'])) {
                    continue;
                }

	            $list['styles'][] = $stylesList[$handle];
            }

            // Append unloaded ones (if any)
	        if (! empty($stylesBeforeUnload) && ! empty($currentUnloadedAll['styles'])) {
                foreach ($currentUnloadedAll['styles'] as $sbuHandle) {
                    if (! in_array($sbuHandle, $manageStyles)) {
                        // Could be an old style that is not loaded anymore
                        // We have to check that
                        if (! isset($stylesBeforeUnload['registered'][$sbuHandle])) {
                            continue;
                        }

                        $sbuValue = $stylesBeforeUnload['registered'][$sbuHandle];
	                    $list['styles'][] = $sbuValue;
                    }
                }
            }

            ksort($list['styles']);
        }

	    /*
        * Scripts List
        */
	    $scriptsList = $wp_scripts->registered;

	    if ($isFrontEndEditView) {
		    $scriptsList = $scriptsBeforeUnload['registered'];
	    }

	    if (! empty($scriptsList)) {
            /* These scripts below are used by this plugin (except admin-bar) and they should not show in the list
               as they are loaded only when you (or other admin) manage the assets, never for your website visitors */
            foreach ($manageScripts as $handle) {
	            if (! isset($scriptsList[$handle]) || in_array($handle, $this->skipAssets['scripts'])) {
                    continue;
                }

	            $list['scripts'][] = $scriptsList[$handle];
            }

            // Append unloaded ones (if any)
            if (! empty($scriptsBeforeUnload) && ! empty($currentUnloadedAll['scripts'])) {
                foreach ($currentUnloadedAll['scripts'] as $sbuHandle) {
                    if (! in_array($sbuHandle, $manageScripts)) {
                        // Could be an old script that is not loaded anymore
                        // We have to check that
                        if (! isset($scriptsBeforeUnload['registered'][$sbuHandle])) {
                            continue;
                        }

                        $sbuValue = $scriptsBeforeUnload['registered'][$sbuHandle];

	                    $list['scripts'][] = $sbuValue;
                    }
                }
            }

            ksort($list['scripts']);

            }

	    if (! empty($list)) {
	        Update::updateHandlesInfo( $list );
        }

        // [wpacu_lite]
	    if ($isFrontEndEditView && ! $this->isUpdateable) {
		    $this->parseTemplate('settings-frontend-lite-locked', array(), true);
	    }
	    // [/wpacu_lite]

        // Front-end View while admin is logged in
        elseif ($isFrontEndEditView) {
	        $wpacuSettings = new Settings();

	        $data = array(
		        'is_frontend_view'            => true,
		        'post_type'                   => '',
		        'bulk_unloaded'               => array( 'post_type' => array() ),
                'plugin_settings'             => $wpacuSettings->getAll(),
		        'current_unloaded_all'        => $currentUnloadedAll,
		        'current_unloaded_page_level' => $this->getAssetsUnloadedPageLevel( $this->getCurrentPostId(), true )
	        );

	        $data['wpacu_page_just_updated'] = false;

	        if (isset($_GET['wpacu_time'], $_GET['nocache']) && get_transient('wpacu_page_just_updated')) {
		        $data['wpacu_page_just_updated'] = true;
		        delete_transient('wpacu_page_just_updated');
	        }

	        if ($currentDebug = ObjectCache::wpacu_cache_get('wpacu_assets_unloaded_list_page_request')) {
		        foreach ( array( 'styles', 'scripts' ) as $assetType ) {
			        if ( isset( $data['current_unloaded_all'][ $assetType ] ) && ! empty( $data['current_unloaded_all'][ $assetType ] ) ) {
				        foreach ( $data['current_unloaded_all'][ $assetType ] as $handleKey => $handle ) {
					        if ( isset( $currentDebug[ $assetType ] ) && in_array( $handle, $currentDebug[ $assetType ] ) ) {
						        unset( $data['current_unloaded_all'][ $assetType ][ $handleKey ] );
					        }
				        }
			        }
		        }
	        }

	        // e.g. /?wpacu_unload_(css|js)=
	        $data['current_debug'] = ObjectCache::wpacu_cache_get('wpacu_assets_unloaded_list_page_request');

            $data['all']['scripts'] = $list['scripts'];
            $data['all']['styles']  = $list['styles'];

            if ($data['plugin_settings']['assets_list_layout'] === 'by-location') {
	            $data['all'] = Sorting::appendLocation($data['all']);
            } else {
            	$data['all'] = Sorting::sortListByAlpha($data['all']);
            }

            $this->fetchUrl = Misc::getPageUrl($this->getCurrentPostId());

            $data['fetch_url']      = $this->fetchUrl;

            $data['nonce_action']   = Update::NONCE_ACTION_NAME;
            $data['nonce_name']     = Update::NONCE_FIELD_NAME;

            $data = $this->alterAssetObj($data);
	        $data['global_unload']   = $this->globalUnloaded;

	        $type = false;

	        if (Misc::isHomePage() && $this->getCurrentPostId() < 1 && ! get_option('page_on_front')) {
                $type = 'front_page';
            } elseif ($this->getCurrentPostId() > 0) {
                $type = 'post';
            }

            $data['wpacu_type'] = $type;

            $data['load_exceptions_per_page'] = $this->getLoadExceptionsPageLevel($type, $this->getCurrentPostId());

            // Avoid the /?wpacu_load_(css|js) to interfere with the form inputs
            if ($loadExceptionsDebug = ObjectCache::wpacu_cache_get( 'wpacu_exceptions_list_page_request' )) {
	            foreach ( array( 'styles', 'scripts' ) as $assetType ) {
		            if ( isset( $data['load_exceptions_per_page'][ $assetType ], $loadExceptionsDebug[ $assetType ] ) && ! empty( $data['load_exceptions_per_page'][ $assetType ] ) ) {
			            foreach ( $data['load_exceptions_per_page'][ $assetType ] as $handleKey => $handle ) {
				            if ( in_array( $handle, $loadExceptionsDebug[ $assetType ] ) ) {
					            unset( $data['load_exceptions_per_page'][ $assetType ][ $handleKey ] );
				            }
			            }
		            }
	            }

	            // e.g. /?wpacu_load_(css|js)=
	            $data['load_exceptions_debug'] = $loadExceptionsDebug;
            }

            // WooCommerce Shop Page?
            $data['is_woo_shop_page'] = self::$vars['is_woo_shop_page'];

            $data['is_bulk_unloadable'] = $data['bulk_unloaded_type'] = false;

	        $data['bulk_unloaded']['post_type'] = array('styles' => array(), 'scripts' => array());

	        $data['load_exceptions_post_type'] = array();

            if (self::isSingularPage()) {
                $post = $this->getCurrentPost();

                $data['post_id'] = $post->ID;

                // Current Post Type
                $data['post_type'] = $post->post_type;

                $data['load_exceptions_post_type'] = $this->getLoadExceptionsPostType($data['post_type']);

                // Are there any assets unloaded for this specific post type?
                // (e.g. page, post, product (from WooCommerce) or other custom post type)
                $data['bulk_unloaded']['post_type'] = $this->getBulkUnload('post_type', $data['post_type']);

	            $data['bulk_unloaded_type'] = 'post_type';

	            $data['is_bulk_unloadable'] = true;

	            $data['post_type_has_tax_assoc'] = self::getAllSetTaxonomies($data['post_type']);

	            $data = $this->setPageTemplate($data);
            }

            $data['total_styles']  = ! empty($data['all']['styles'])  ? count($data['all']['styles'])  : false;
            $data['total_scripts'] = ! empty($data['all']['scripts']) ? count($data['all']['scripts']) : false;

	        $data['all_deps'] = $this->getAllDeps($data['all']);

	        $data['preloads'] = Preloads::instance()->getPreloads();

	        // Load exception: If the user is logged in (applies globally)
	        $data['handle_load_logged_in'] = $this->getHandleLoadLoggedIn();

	        $data['handle_notes'] = $this->getHandleNotes();
	        $data['handle_rows_contracted'] = self::getHandleRowStatus();

	        $data['ignore_child'] = $this->getIgnoreChildren();

	        switch (assetCleanUpHasNoLoadMatches($data['fetch_url'])) {
                case 'is_set_in_settings':
	                // The rules from "Settings" -> "Plugin Usage Preferences" -> "Do not load the plugin on certain pages" will be checked
	                $data['status'] = 5;
                break;

                case 'is_set_in_page':
	                // The following option from "Page Options" (within the CSS/JS manager of the targeted page) is set: "Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin)"
	                $data['status'] = 6;
                break;

                default:
	                $data['status'] = 1;
	        }

	        $data['page_options'] = array();
	        $data['show_page_options'] = false;

	        if (in_array($type, array('post', 'front_page'))) {
		        $data['show_page_options'] = true;
		        $data['page_options'] = MetaBoxes::getPageOptions($this->getCurrentPostId(), $type);
	        }

	        $data['post_id'] = ($type === 'front_page') ? 0 : $this->getCurrentPostId();
	        ObjectCache::wpacu_cache_set('wpacu_settings_frontend_data', $data);
            $this->parseTemplate('settings-frontend', $data, true);
        } elseif ($isDashboardEditView && ! isset($_GET['wpacu_just_hardcoded'])) {
            // AJAX call (not the classic WP one) from the WP Dashboard
            // Send the altered value that has the initial position too

            // Taken front the front-end view
            $data = array();
	        $data['all']['scripts'] = $list['scripts'];
	        $data['all']['styles'] = $list['styles'];

	        $data = $this->alterAssetObj($data);

	        $list['styles']  = $data['all']['styles'];
	        $list['scripts'] = $data['all']['scripts'];

	        if ( isset($_GET['wpacu_print']) ) {
	            echo '<!-- '."\n".print_r(Misc::filterList($list), true)."\n".' -->';
            }

	        // e.g. for "Loaded" and "Unloaded" statuses
	        $list['current_unloaded_all'] = isset($this->allUnloadedAssets) ? $this->allUnloadedAssets : array('styles' => array(), 'scripts' => array());

	        echo self::START_DEL_ENQUEUED  . base64_encode(wp_json_encode($list)) . self::END_DEL_ENQUEUED; // Loaded via wp_enqueue_scripts()
            echo self::START_DEL_HARDCODED . '{wpacu_hardcoded_assets}' . self::END_DEL_HARDCODED; // Make the user aware of any hardcoded CSS/JS (if any)

            add_action('shutdown', static function() {
	            // Do not allow further processes as cache plugins such as W3 Total Cache could alter the source code
	            // and we need the non-minified version of the DOM (e.g. to determine the position of the elements)
	            exit();
            });
        } elseif ($isDashboardEditView && isset($_GET['wpacu_just_hardcoded'])) {
	        // AJAX call just for the hardcoded assets
            echo self::START_DEL_HARDCODED . '{wpacu_hardcoded_assets}' . self::END_DEL_HARDCODED; // Make the user aware of any hardcoded CSS/JS (if any)

	        add_action('shutdown', static function() {
		        // Do not allow further processes as cache plugins such as W3 Total Cache could alter the source code,
		        // and we need the non-minified version of the DOM (e.g. to determine the position of the elements)
		        exit();
	        });
        }

	    /* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */
    }

    /**
     * @param $name
     * @param array $data (if present $data values are used within the included template)
     * @param bool|false $echo
     * @param bool|false $returnData (relevant when $echo is set to true)
     * @return string|array
     */
    public function parseTemplate($name, $data = array(), $echo = false, $returnData = false)
    {
        $templateFile = apply_filters(
            'wpacu_template_file', // tag
            dirname(__DIR__) . '/templates/' . $name . '.php', // value
            $name // extra argument
        );

        if (! is_file($templateFile)) {
            return 'Template '.$templateFile.' not found.';
        }

        ob_start();
        include $templateFile;
        $result = ob_get_clean();

        // $echo is set to true ($returnData is not relevant), thus print the output
        if ($echo) {
            echo $result;
            return true;
        }

        // $echo is set to false and $returnData to true
        if ($returnData) {
            return array(
                'output' => $result,
                'data' => $data
            );
        }

        /// $echo is set to false (default) and $returnData to $false (default)
        return $result;
    }

    /**
     *
     */
    public function ajaxGetJsonListCallback()
    {
	    if ( ! isset($_POST['wpacu_nonce']) ) {
		    echo 'Error: The security nonce was not sent for verification. Location: '.__METHOD__;
		    return;
	    }

	    if ( ! wp_verify_nonce($_POST['wpacu_nonce'], 'wpacu_ajax_get_loaded_assets_nonce') ) {
		    echo 'Error: The nonce security check has failed. Location: '.__METHOD__;
		    return;
	    }

        $postId  = (int)Misc::getVar('post', 'post_id'); // if any (could be home page for instance)
        $pageUrl = Misc::getVar('post', 'page_url'); // post, page, custom post type, home page etc.

        $postStatus = $postId > 0 ? get_post_status($postId) : false;

	    // Not homepage, but a post/page? Check if it's published in case AJAX call
	    // wasn't stopped due to JS errors or other reasons
	    if ($postId > 0 && ! in_array($postStatus, array('publish', 'private'))) {
		    exit(__('The CSS/JS files will be available to manage once the post/page is published.', 'wp-asset-clean-up'));
	    }

	    if ($postId > 0) {
		    $type = 'post';
	    } elseif ($postId == 0) {
		    $type = 'front_page';
	    }

        $wpacuListE = $wpacuListH = '';

	    $settings = new Settings();

	    // If the post status is 'private' only direct method can be used to fetch the assets
        // as the remote post one will return a 404 error since the page is accessed as a guest visitor
        if (self::$domGetType === 'direct' || $postStatus === 'private') {
            $wpacuListE = Misc::getVar('post', 'wpacu_list_e');
            $wpacuListH = Misc::getVar('post', 'wpacu_list_h');
        } elseif (self::$domGetType === 'wp_remote_post') {
	        $wpRemotePost = wp_remote_post($pageUrl, array(
                'body' => array(
                    WPACU_LOAD_ASSETS_REQ_KEY => 1
                )
		        ));

	        $contents = (is_array($wpRemotePost) && isset($wpRemotePost['body']) && (! is_wp_error($wpRemotePost))) ? $wpRemotePost['body'] : '';

            // Enqueued List
            if ($contents
                && ( strpos($contents, self::START_DEL_ENQUEUED) !== false)
                && ( strpos($contents, self::END_DEL_ENQUEUED) !== false)) {
	            // Enqueued CSS/JS (most of them or all)
                $wpacuListE = Misc::extractBetween(
                    $contents,
                    self::START_DEL_ENQUEUED,
                    self::END_DEL_ENQUEUED
                );
            }

            // Hardcoded List
            if ($contents
                && ( strpos($contents, self::START_DEL_HARDCODED) !== false)
                && ( strpos($contents, self::END_DEL_HARDCODED) !== false)) {
                // Hardcoded (if any)
                $wpacuListH = Misc::extractBetween(
                    $contents,
                    self::START_DEL_HARDCODED,
                    self::END_DEL_HARDCODED
                );
            }

            // The list of assets COULD NOT be retrieved via "WP Remote Post" for this server
            // EITHER the enqueued or hardcoded list of assets HAS TO BE RETRIEVED
	        // Print out the 'error' response to make the user aware about it
            if ( ! ($wpacuListE || $wpacuListH) ) {
                // 'body' is set, and it's not an array
	            if ( is_wp_error($wpRemotePost) ) {
		            $wpRemotePost['response']['message'] = $wpRemotePost->get_error_message();
	            } elseif ( isset( $wpRemotePost['body']) ) {
		            if ( trim( $wpRemotePost['body'] ) === '' ) {
			            $wpRemotePost['body'] = '<strong>Error (blank page):</strong> It looks the targeted page is loading, but it has no content. The page seems to be blank. Please load it in incognito mode (when you are not logged-in) via your browser.';
		            } elseif ( ! is_array( $wpRemotePost['body'] ) ) {
			            $wpRemotePost['body'] = strip_tags( $wpRemotePost['body'], '<p><a><strong><b><em><i>' );
		            }
	            }

            	$data = array(
            		'is_dashboard_view' => true,
		            'plugin_settings'   => $settings->getAll(),
            		'wp_remote_post'    => $wpRemotePost
	            );

	            if (isset($type) && $type) {
		            $data['page_options'] = MetaBoxes::getPageOptions( $postId, $type );
	            }

	            $this->parseTemplate('meta-box-loaded', $data, true);
	            exit();
            }
        }

	    $data = array(
            'is_dashboard_view' => true,
		    'post_id'           => $postId,
		    'plugin_settings'   => $settings->getAll()
	    );

	    // [START] Enqueued CSS/JS (most of them or all)
        $jsonE = base64_decode($wpacuListE);
	    $data['all'] = (array)json_decode($jsonE);

	    // Make sure if there are no STYLES enqueued, the list will be empty to avoid any notice errors
	    if (! isset($data['all']['styles'])) {
		    $data['all']['styles'] = array();
	    }

	    // Make sure if there are no SCRIPTS enqueued, the list will be empty to avoid any notice errors
	    if (! isset($data['all']['scripts'])) {
		    $data['all']['scripts'] = array();
	    }
		// [END] Enqueued CSS/JS (most of them or all)

        // [START] Hardcoded (if any)
	    if ($wpacuListH) {
	    	// Only set the following variables if there is at least one hardcoded LINK/STYLE/SCRIPT
		    $jsonH                    = base64_decode( $wpacuListH );
		    $data['all']['hardcoded'] = (array) json_decode( $jsonH, ARRAY_A );

		    if (isset($data['all']['hardcoded']['within_conditional_comments']) && ! empty($data['all']['hardcoded']['within_conditional_comments'])) {
			    ObjectCache::wpacu_cache_set( 'wpacu_hardcoded_content_within_conditional_comments', $data['all']['hardcoded']['within_conditional_comments'] );
            }
	    }
	    // [END] Hardcoded (if any)

        $data['current_unloaded_page_level'] = $this->getAssetsUnloadedPageLevel( $postId, true );

	    // e.g. for "Loaded" and "Unloaded" statuses
	    $data['current_unloaded_all'] = isset($data['all']['current_unloaded_all']) ? (array)$data['all']['current_unloaded_all'] : array('styles' => array(), 'scripts' => array());

        if ($data['plugin_settings']['assets_list_layout'] === 'by-location') {
	        $data['all'] = Sorting::appendLocation($data['all']);
        } else {
	        $data['all'] = Sorting::sortListByAlpha($data['all']);
        }

        $data['fetch_url'] = $pageUrl;
        $data['global_unload'] = $this->getGlobalUnload();

	    // [wpacu_pro]
	    //ObjectCache::wpacu_cache_set('wpacu_data_global_unload', $data['global_unload']);
		// [/wpacu_pro]

        $data['is_bulk_unloadable'] = $data['bulk_unloaded_type'] = false;

	    $data['bulk_unloaded']['post_type'] = array('styles' => array(), 'scripts' => array());

        // Post Information
	    if ($postId > 0) {
		    $postData = get_post($postId);

		    if (isset($postData->post_type) && $postData->post_type) {
			    // Current Post Type
			    $data['post_type']                  = $postData->post_type;

			    // Are there any assets unloaded for this specific post type?
			    // (e.g. page, post, product (from WooCommerce) or other custom post type)
			    $data['bulk_unloaded']['post_type'] = $this->getBulkUnload('post_type', $data['post_type']);
			    $data['bulk_unloaded_type']         = 'post_type';
			    $data['is_bulk_unloadable']         = true;
			    $data['post_type_has_tax_assoc']    = self::getAllSetTaxonomies($data['post_type']);
		    }
	    }

	    // DO NOT alter any position as it's already verified and set
        // This AJAX call is for printing the assets that were already fetched
	    $data = $this->alterAssetObj($data, false);

	    $data['wpacu_type'] = $type;

        // e.g. LITE rules: Load it on this page & on all pages of a specific post type
        $data['load_exceptions_per_page']  = $this->getLoadExceptionsPageLevel($type, $postId);
        $data['load_exceptions_post_type'] = ($type === 'post' && $data['post_type']) ? $this->getLoadExceptionsPostType($data['post_type']) : array();


	    $data['handle_rows_contracted'] = self::getHandleRowStatus();

        $data['total_styles']  = ! empty($data['all']['styles'])  ? count($data['all']['styles'])  : 0;
        $data['total_scripts'] = ! empty($data['all']['scripts']) ? count($data['all']['scripts']) : 0;

	    $data['all_deps'] = $this->getAllDeps($data['all']);

	    $data['preloads'] = Preloads::instance()->getPreloads();

	    $data['handle_load_logged_in'] = $this->getHandleLoadLoggedIn();
	    $data['handle_notes'] = $this->getHandleNotes();

	    $data['ignore_child'] = $this->getIgnoreChildren();

	    $data['is_for_singular'] = (Misc::getVar('post', 'is_for_singular') === 'true');

	    $data['page_options'] = array();
	    $data['show_page_options'] = false;

	    if (in_array($type, array('post', 'front_page'))) {
	        $data['show_page_options'] = true;
		    $data['page_options'] = MetaBoxes::getPageOptions($postId, $type);
	    }

	    $this->parseTemplate('meta-box-loaded', $data, true);
        exit();
    }

	/**
	 *
	 */
	public function ajaxLoadRestrictedPageAreaCallback()
    {
	    if ( ! isset( $_POST['wpacu_nonce'] ) || ! wp_verify_nonce( $_POST['wpacu_nonce'], 'wpacu_ajax_load_page_restricted_area_nonce' ) ) {
		    echo 'Error: The security nonce is not valid.';
		    exit();
	    }

	    $postId = (int)Misc::getVar('post', 'post_id'); // if any (could be home page for instance)

        $data = array();

        $data['post_id']   = $this->currentPostId = $postId;
        $data['fetch_url'] = Misc::getPageUrl($postId);

        $data['show_page_options'] = true;
        $data['page_options']      = MetaBoxes::getPageOptions($postId);

	    $post = get_post($postId);

	    // Current Post Type
	    $data['post_type']          = $post->post_type;
	    $data['bulk_unloaded_type'] = 'post_type';
	    $data['is_bulk_unloadable'] = true;

	    $data = $this->setPageTemplate($data);

	    switch (assetCleanUpHasNoLoadMatches($data['fetch_url'])) {
		    case 'is_set_in_settings':
			    // The rules from "Settings" -> "Plugin Usage Preferences" -> "Do not load the plugin on certain pages" will be checked
			    $data['status']  = 5;
			    break;

		    case 'is_set_in_page':
			    // The following option from "Page Options" (within the CSS/JS manager of the targeted page) is set: "Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin)"
			    $data['status']  = 6;
			    break;

            default:
                $data['status'] = 1;
	    }

	    $this->parseTemplate('meta-box-restricted-page-load', $data, true);
	    exit();
    }

	/**
	 *
	 */
	public function ajaxPrintLoadedHardcodedAssets()
    {
        if ( ! isset( $_POST['wpacu_nonce'] ) || ! wp_verify_nonce( $_POST['wpacu_nonce'], 'wpacu_print_loaded_hardcoded_assets_nonce' ) ) {
	        echo 'Error: The security nonce is not valid.';
	        exit();
        }

	    $wpacuListH        = Misc::getVar('post', 'wpacu_list_h');
	    $wpacuSettingsJson = base64_decode(Misc::getVar('post', 'wpacu_settings'));
	    $wpacuSettings     = (array)json_decode($wpacuSettingsJson, ARRAY_A); // $data values are passed here

	    // Only set the following variables if there is at least one hardcoded LINK/STYLE/SCRIPT
	    $jsonH = base64_decode( $wpacuListH );

	    function wpacuPrintHardcodedManagementList($jsonH, $wpacuSettings) {
		    $data = $wpacuSettings ?: array();
		    $data['do_not_print_list'] = true;
		    $data['print_outer_html']  = false;
		    $data['all']['hardcoded']  = (array)json_decode($jsonH, ARRAY_A);
		    $totalHardcodedTags = 0; // default

		    ob_start();
		    // $totalHardcodedTags is set here
		    include_once WPACU_PLUGIN_DIR.'/templates/meta-box-loaded-assets/_assets-hardcoded-list.php'; // generate $hardcodedTagsOutput
		    $output = ob_get_clean();

		    return wp_json_encode( array(
			    'output' => $output,
			    'total_hardcoded_assets' => $totalHardcodedTags
		    ) );
	    }

	    echo wpacuPrintHardcodedManagementList( $jsonH, $wpacuSettings );

	    exit();
    }

	/**
	 *
	 */
	public function ajaxCheckExternalUrlsForStatusCode()
    {
	    if ( ! isset( $_POST['wpacu_nonce'] ) || ! wp_verify_nonce( $_POST['wpacu_nonce'], 'wpacu_ajax_check_external_urls_nonce' ) ) {
		    echo 'Error: The security nonce is not valid.';
		    exit();
	    }

	    if (! isset($_POST['action'], $_POST['wpacu_check_urls'])) {
		    echo 'Error: The post parameters are not the right ones.';
		    exit();
	    }

	    // Check privileges
	    if (! Menu::userCanManageAssets()) {
		    echo 'Error: Not enough privileges to perform this action.';
		    exit();
	    }

	    $checkUrls = explode('-at-wpacu-at-', $_POST['wpacu_check_urls']);
	    $checkUrls = array_filter(array_unique($checkUrls));

	    foreach ($checkUrls as $index => $checkUrl) {
	        if (strpos($checkUrl, '//') === 0) { // starts with // (append the right protocol)
	            if (strpos($checkUrl, 'fonts.googleapis.com') !== false)  {
		            $checkUrl = 'https:'.$checkUrl;
	            } else {
		            // either HTTP or HTTPS depending on the current page situation (that the admin has loaded)
		            $checkUrl = (Misc::isHttpsSecure() ? 'https:' : 'http:') . $checkUrl;
                }
            }

		    $response = wp_remote_get($checkUrl);

		    // Remove 200 OK ones as the other ones will remain for highlighting
		    if (wp_remote_retrieve_response_code($response) === 200) {
			    unset($checkUrls[$index]);
            }
        }

	    echo wp_json_encode($checkUrls);
	    exit();
    }

	/**
	 * @return void
	 */
	public function ajaxFetchActivePluginsIconsFromWordPressOrg()
	{
		if ( ! isset($_POST['wpacu_nonce']) ) {
			echo 'Error: The security nonce was not sent for verification. Location: '.__METHOD__;
			return;
		}

		if ( ! wp_verify_nonce($_POST['wpacu_nonce'], 'wpacu_fetch_active_plugins_icons') ) {
			echo 'Error: The security check has failed. Location: '.__METHOD__;
			return;
		}

		if (! isset($_POST['action'])) {
			return;
		}

		if (! Menu::userCanManageAssets()) {
			return;
		}

        echo 'POST DATA: '.print_r($_POST, true)."\n\n";

        echo '- Downloading from WordPress.org'."\n\n";

        $activePluginsIcons = Misc::fetchActiveFreePluginsIconsFromWordPressOrg();

        if ($activePluginsIcons && is_array($activePluginsIcons) && ! empty($activePluginsIcons)) {
            echo print_r($activePluginsIcons, true)."\n";
            exit;
        }
	}

	/**
	 *
	 */
	public function ajaxFetchActivePluginsJsFooterCode()
	{
	    if (! Menu::isPluginPage()) {
	        return;
        }

 		if (! Menu::userCanManageAssets()) {
			return;
		}

 		$forcePluginIconsDownload = isset($_GET['wpacu_force_plugin_icons_fetch']);

        $triggerPluginIconsDownload = $forcePluginIconsDownload || ! get_transient('wpacu_active_plugins_icons');

		if (! $triggerPluginIconsDownload) {
			return;
		}
		?>
		<script type="text/javascript" >
            jQuery(document).ready(function($) {
                var wpacuDataToSend = {
                    'action': '<?php echo WPACU_PLUGIN_ID.'_fetch_active_plugins_icons'; ?>',
                    'wpacu_nonce': '<?php echo wp_create_nonce('wpacu_fetch_active_plugins_icons'); ?>'
                };

                jQuery.post(ajaxurl, wpacuDataToSend, function(response) {
                    console.log(response);
                });
            });
		</script>
		<?php
	}

    /**
     * @param $data
     * @return mixed
     */
    public function alterAssetObj($data, $alterPosition = true)
    {
        $siteUrl = get_site_url();

        if (! empty($data['all']['styles'])) {
            $data['core_styles_loaded'] = false;

	        foreach ($data['all']['styles'] as $key => $obj) {
                if (! isset($obj->handle)) {
                    unset($data['all']['styles']['']);
                    continue;
                }

	            // From WordPress directories (false by default, unless it was set to true before: in Sorting.php for instance)
	            if (! isset($data['all']['styles'][$key]->wp)) {
		            $data['all']['styles'][$key]->wp = false;
	            }

	            if ($alterPosition) {
	                if ( in_array( $obj->handle, $this->assetsInFooter['styles'] ) ) {
		                $data['all']['styles'][ $key ]->position = 'body';
	                } else {
		                $data['all']['styles'][ $key ]->position = 'head';
	                }
                }

                if (isset($data['all']['styles'][$key], $obj->src) && $obj->src) {
	                $localSrc = Misc::getLocalSrc($obj->src);

	                if (! empty($localSrc)) {
		                $data['all']['styles'][$key]->baseUrl = $localSrc['base_url'];

		                if (Sorting::matchesWpCoreCriteria($obj, 'styles')) {
                            $data['all']['styles'][ $key ]->wp = true;
                            $data['core_styles_loaded']        = true;
                        }
	                }

                    // Determine source href (starting with '/' but not starting with '//')
                    if (strpos($obj->src, '/') === 0 && strpos($obj->src, '//') !== 0) {
                        $obj->srcHref = $siteUrl . $obj->src;
                    } else {
                        $obj->srcHref = $obj->src;
                    }

	                $data['all']['styles'][$key]->size    = apply_filters('wpacu_get_asset_file_size', $data['all']['styles'][$key], 'for_print');
	                $data['all']['styles'][$key]->sizeRaw = apply_filters('wpacu_get_asset_file_size', $data['all']['styles'][$key], 'raw');

                }
            }
        }

        if (! empty($data['all']['scripts'])) {
            $data['core_scripts_loaded'] = false;

            foreach ($data['all']['scripts'] as $key => $obj) {
                if (! isset($obj->handle)) {
                    unset($data['all']['scripts']['']);
                    continue;
                }

	            // From WordPress directories (false by default, unless it was set to true before: in Sorting.php for instance)
	            if (! isset($data['all']['scripts'][$key]->wp)) {
		            $data['all']['scripts'][$key]->wp = false;
	            }

	            if ($alterPosition) {
		            $initialScriptPos = ObjectCache::wpacu_cache_get( $obj->handle, 'wpacu_scripts_initial_positions' );

		            if ( $initialScriptPos === 'body' || in_array( $obj->handle, $this->assetsInFooter['scripts'] ) ) {
			            $data['all']['scripts'][ $key ]->position = 'body';
		            } else {
			            $data['all']['scripts'][ $key ]->position = 'head';
		            }
	            }

                if (isset($data['all']['scripts'][$key])) {
                    if (isset($obj->src) && $obj->src) {
	                    $localSrc = Misc::getLocalSrc($obj->src);

	                    if (! empty($localSrc)) {
		                    $data['all']['scripts'][$key]->baseUrl = $localSrc['base_url'];

                            if (Sorting::matchesWpCoreCriteria($obj, 'scripts')) {
	                            $data['all']['scripts'][ $key ]->wp = true;
	                            $data['core_scripts_loaded']        = true;
                            }
                        }

                        // Determine source href
                        if (substr($obj->src, 0, 1) === '/' && substr($obj->src, 0, 2) !== '//') {
                            $obj->srcHref = $siteUrl . $obj->src;
                        } else {
                            $obj->srcHref = $obj->src;
                        }
                    }

                    if (in_array($obj->handle,  array('jquery', 'jquery-core', 'jquery-migrate'))) {
                        $data['all']['scripts'][$key]->wp = true;
                        $data['core_scripts_loaded']      = true;
                    }

	                $data['all']['scripts'][$key]->size    = apply_filters('wpacu_get_asset_file_size', $data['all']['scripts'][$key], 'for_print');
	                $data['all']['scripts'][$key]->sizeRaw = apply_filters('wpacu_get_asset_file_size', $data['all']['scripts'][$key], 'raw');
                }
            }
        }

        return $data;
    }

    /**
     * This method retrieves only the assets that are unloaded per page
     * Including 404, date and search pages (they are considered as ONE page with the same rules for any URL variation)
     *
     * @param int $postId
     * @param bool $jsonDecoded
     *
     * @return string (The returned value must be a JSON one)
     */
    public function getAssetsUnloadedPageLevel($postId = 0, $jsonDecoded = false)
    {
        // Post Type (Overwrites 'front' - home page - if we are in a singular post)
        if ($postId == 0) {
            $postId = (int)$this->getCurrentPostId();
        }

        $isInAdminPageViaAjax = (is_admin() && defined('DOING_AJAX') && DOING_AJAX);

        if ( empty($this->assetsRemoved) ) {
            $this->assetsRemoved = wp_json_encode( array( 'styles' => array(), 'scripts' => array() ) );
	        // For Home Page (latest blog posts)
	        if ( $postId < 1 && ( $isInAdminPageViaAjax || Misc::isHomePage() ) ) {
		        $this->assetsRemoved = get_option( WPACU_PLUGIN_ID . '_front_page_no_load' );
	        } elseif ( $postId > 0 ) { // Singular Page
		        $this->assetsRemoved = get_post_meta( $postId, '_' . WPACU_PLUGIN_ID . '_no_load', true );
	        }

	        @json_decode( $this->assetsRemoved );

	        if ( ! ( Misc::jsonLastError() === JSON_ERROR_NONE ) || empty( $this->assetsRemoved ) || $this->assetsRemoved === '[]' ) {
		        // Reset value to a JSON formatted one
		        $this->assetsRemoved = wp_json_encode( array( 'styles' => array(), 'scripts' => array() ) );
	        }

	        $assetsRemovedDecoded = json_decode( $this->assetsRemoved, ARRAY_A );

	        if (! isset($assetsRemovedDecoded['styles'])) {
		        $assetsRemovedDecoded['styles'] = array();
	        }

	        if (! isset($assetsRemovedDecoded['scripts'])) {
		        $assetsRemovedDecoded['scripts'] = array();
	        }

            /* [START] Unload CSS/JS on page request for debugging purposes */
            $assetsUnloadedOnTheFly = array( 'styles' => array(), 'scripts' => array() );

            if ( Misc::getVar( 'get', 'wpacu_unload_css' ) ) {
                $cssOnTheFlyList = $this->unloadAssetOnTheFly( 'css' );

                if ( ! empty( $cssOnTheFlyList ) ) {
                    foreach ( $cssOnTheFlyList as $cssHandle ) {
                        if ( ! in_array( $cssHandle, $assetsRemovedDecoded['styles'] ) ) {
                            $assetsRemovedDecoded['styles'][] = $assetsUnloadedOnTheFly['styles'][] = $cssHandle;
                        }
                    }
                }
            }

            if ( Misc::getVar( 'get', 'wpacu_unload_js' ) ) {
                $jsOnTheFlyList = $this->unloadAssetOnTheFly( 'js' );

                if ( ! empty( $jsOnTheFlyList ) ) {
                    foreach ( $jsOnTheFlyList as $jsHandle ) {
                        if ( ! in_array( $jsHandle, $assetsRemovedDecoded['scripts'] ) ) {
                            $assetsRemovedDecoded['scripts'][] = $assetsUnloadedOnTheFly['scripts'][] = $jsHandle;
                        }
                    }
                }
            }

            ObjectCache::wpacu_cache_add( 'wpacu_assets_unloaded_list_page_request', $assetsUnloadedOnTheFly );
            /* [END] Unload CSS/JS on page request for debugging purposes */

	        $this->assetsRemoved = wp_json_encode( $assetsRemovedDecoded );
        }

        if ($jsonDecoded) {
	        $this->assetsRemoved = (array)@json_decode($this->assetsRemoved);

	        // Make sure there are no objects in the array to avoid any PHP errors later on in PHP 8+
	        foreach ( array( 'styles', 'scripts' ) as $assetType ) {
		        if ( isset( $this->assetsRemoved[ $assetType ] ) ) {
			        $this->assetsRemoved[ $assetType ] = (array) $this->assetsRemoved[ $assetType ];
		        }
	        }
        }

	    return $this->assetsRemoved;
    }

	/**
	 * @param $allAssets
	 *
	 * @return array
	 */
	public function getAllDeps($allAssets)
	{
		$allDepsParentToChild = $allDepsChildToParent = array('styles' => array(), 'scripts' => array());

		foreach (array('styles', 'scripts') as $assetType) {
			if ( ! (isset($allAssets[$assetType]) && ! empty($allAssets[$assetType])) ) {
				continue;
			}

			foreach ($allAssets[$assetType] as $assetObj) {
				if (isset($assetObj->deps) && ! empty($assetObj->deps)) {
					foreach ($assetObj->deps as $dep) {
						$allDepsParentToChild[$assetType][$dep][] = $assetObj->handle;
						$allDepsChildToParent[$assetType][$assetObj->handle][] = $dep;
					}
				}
			}
		}

		return array(
            'parent_to_child'      => $allDepsParentToChild,
            'child_to_parent'      => $allDepsChildToParent
        );
	}

	/**
	 * @param $obj
	 * @param $format | 'for_print': Calculates the format in KB / MB  - 'raw': The actual size in bytes
	 * @return string
	 */
	public function getAssetFileSize($obj, $format = 'for_print')
	{
		if (isset($obj->src) && $obj->src) {
			$src = $obj->src;
			$siteUrl = site_url();

			// Starts with / but not with //
			// Or starts with ../ (very rare cases)
			$isRelInternalPath = (strpos($src, '/') === 0 && strpos($src, '//') !== 0) || (strpos($src, '../') === 0);

			// Source starts with '//' - check if the file exists
			if (strpos($obj->src, '//') === 0) {
				list ($urlPrefix) = explode('//', $siteUrl);
				$srcToCheck = $urlPrefix . $obj->src;

				$hostSiteUrl = parse_url($siteUrl, PHP_URL_HOST);
				$hostSrc = parse_url($obj->src, PHP_URL_HOST);

				$siteUrlAltered = str_replace(array($hostSiteUrl, $hostSrc), '{site_host}', $siteUrl);
				$srcAltered = str_replace(array($hostSiteUrl, $hostSrc), '{site_host}', $srcToCheck);

				$srcMaybeRelPath = str_replace($siteUrlAltered, '', $srcAltered);

				$possibleStrips = array('?ver', '?cache=');

				foreach ($possibleStrips as $possibleStrip) {
					if ( strpos( $srcMaybeRelPath, $possibleStrip ) !== false ) {
						list ( $srcMaybeRelPath ) = explode( $possibleStrip, $srcMaybeRelPath );
					}
				}

				if (is_file(Misc::getWpRootDirPath() . $srcMaybeRelPath)) {
					$fileSize = filesize(Misc::getWpRootDirPath() . $srcMaybeRelPath);

					if ($format === 'raw') {
						return (int)$fileSize;
					}

					return Misc::formatBytes($fileSize);
				}
			}

			// e.g. /?scss=1 (Simple Custom CSS Plugin)
			if (str_replace($siteUrl, '', $src) === '/?sccss=1') {
				$customCss = DynamicLoadedAssets::getSimpleCustomCss();
				$sizeInBytes = mb_strlen($customCss);

				if ($format === 'raw') {
					return $sizeInBytes;
				}

				return Misc::formatBytes($sizeInBytes);
			}

			// External file? Use a different approach
			// Return an HTML code that will be parsed via AJAX through JavaScript
			$isExternalFile = (! $isRelInternalPath &&
			                   (! (isset($obj->wp) && $obj->wp === 1))
			                   && strpos($src, $siteUrl) !== 0);

			// e.g. /?scss=1 (Simple Custom CSS Plugin) From External Domain
			// /?custom-css (JetPack Custom CSS)
			$isLoadedOnTheFly = (strpos($src, '?sccss=1') !== false)
			                    || (strpos($src, '?custom-css') !== false);

			if ($isExternalFile || $isLoadedOnTheFly) {
				return '<a class="wpacu-external-file-size" data-src="' . $src . '" href="#">🔗 Get File Size</a>'.
				       '<span style="display: none;"><img style="width: 20px; height: 20px;" alt="" align="top" width="20" height="20" src="'.includes_url('images/spinner-2x.gif').'"></span>';
			}

			$forAssetType = $pathToFile = false;

            if ( stripos( $src, '.css' ) !== false ) {
                $forAssetType = 'css';
            } elseif ( stripos( $src, '.js' ) !== false ) {
                $forAssetType = 'js';
            }

            if ($forAssetType) {
	            $pathToFile = OptimizeCommon::getLocalAssetPath( $src, $forAssetType );
            }

			if ( ! is_file($pathToFile) ) { // Fallback, old code...
				// Local file? Core or from a plugin / theme?
				if ( strpos( $obj->src, $siteUrl ) !== false ) {
					// Local Plugin / Theme File
					// Could be a Staging site that is having the Live URL in the General Settings
					$src = ltrim( str_replace( $siteUrl, '', $obj->src ), '/' );
				} elseif ( ( isset( $obj->wp ) && $obj->wp === 1 ) || $isRelInternalPath ) {
					// Local WordPress Core File
					$src = ltrim( $obj->src, '/' );
				}

				$srcAlt = $src;

				if ( strpos( $src, '../' ) === 0 ) {
					$srcAlt = str_replace( '../', '', $srcAlt );
				}

				$pathToFile = Misc::getWpRootDirPath() . $srcAlt;

				if ( strpos( $pathToFile, '?ver' ) !== false ) {
					list( $pathToFile ) = explode( '?ver', $pathToFile );
				}

				// It can happen that the CSS/JS has extra parameters (rare cases)
				foreach ( array( '.css?', '.js?' ) as $needlePart ) {
					if ( strpos( $pathToFile, $needlePart ) !== false ) {
						list( $pathToFile ) = explode( '?', $pathToFile );
					}
				}
			}

			if (is_file($pathToFile)) {
				$sizeInBytes = filesize($pathToFile);

				if ($format === 'raw') {
					return (int)$sizeInBytes;
				}

				return Misc::formatBytes($sizeInBytes);
			}

			return '<em>Error: Could not read '.$pathToFile.'</em>';
		}

		if ($obj->handle === 'jquery' && isset($obj->src) && ! $obj->src) {
			return '"jquery-core" size';
		}

		// External or nothing to be shown (perhaps due to an error)
		return '';
	}

	/**
	 * Source: https://stackoverflow.com/questions/2602612/remote-file-size-without-downloading-file
	 */
	public function ajaxGetExternalFileSize()
	{
		// Check nonce
		if ( ! isset( $_POST['wpacu_nonce'] ) || ! wp_verify_nonce( $_POST['wpacu_nonce'], 'wpacu_ajax_check_remote_file_size_nonce' ) ) {
			echo 'Error: The security nonce is not valid.';
			exit();
		}

		// Check privileges
		if (! Menu::userCanManageAssets()) {
			echo 'Error: Not enough privileges to perform this action.';
			exit();
		}

		// Assume failure.
		$result = -1;

		$remoteFile = Misc::getVar('post', 'wpacu_remote_file', false);

		if (! $remoteFile) {
			echo 'N/A (external file)';
			exit;
		}

		// If it starts with //
		if (strpos($remoteFile, '//') === 0) {
			$remoteFile = 'http:'.$remoteFile;
		}

		// Check if the URL is valid
		if (! filter_var($remoteFile, FILTER_VALIDATE_URL)) {
			echo 'The asset\'s URL - '.$remoteFile.' - is not valid.';
			exit();
		}

		$curl = curl_init($remoteFile);

		// Issue a HEAD request and follow any redirects.
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		$data = curl_exec($curl);
		curl_close($curl);

		$content_length = $status = 'unknown';

		if ($data) {
			if (preg_match( '/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches ) ) {
				$status = (int)$matches[1];
			}

			if ( preg_match( '/Content-Length: (\d+)/', $data, $matches ) ) {
				$content_length = (int)$matches[1];
			}

			// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
			if ( $status === 200 || ($status > 300 && $status <= 308) ) {
				$result = $content_length;
			}
		}

		if ($content_length === 'unknown') {
			// One more try
			$response     = wp_remote_get($remoteFile);

			$responseCode = wp_remote_retrieve_response_code($response);

			if ($responseCode === 200) {
				$result = mb_strlen(wp_remote_retrieve_body($response));
			}
		}

		echo Misc::formatBytes($result);

		if (stripos($remoteFile, '//fonts.googleapis.com/') !== false) {
			// Google Font APIS CDN
			echo ' + the sizes of the loaded "Google Font" files (see "url" from @font-face within the Source file)';
		} elseif (stripos($remoteFile, '/font-awesome.css') || stripos($remoteFile, '/font-awesome.min.css')) {
			// FontAwesome CDN
			echo ' + the sizes of the loaded "FontAwesome" font files (see "url" from @font-face within the Source file)';
		}

		exit();
	}

    /**
     * @return bool
     */
    public static function isSingularPage()
    {
        return (self::$vars['is_woo_shop_page'] || is_singular() || Misc::isBlogPage());
    }

    /**
     * @return int|mixed|string
     */
    public function getCurrentPostId()
    {
        if ($this->currentPostId > 0) {
            return $this->currentPostId;
        }

        if (Misc::isElementorMaintenanceModeOnForCurrentAdmin() && defined('WPACU_IS_ELEMENTOR_MAINTENANCE_MODE_TEMPLATE_ID')) {
            $this->currentPostId = WPACU_IS_ELEMENTOR_MAINTENANCE_MODE_TEMPLATE_ID;
            return $this->currentPostId;
        }

        // Are we on the `Shop` page from WooCommerce?
        // Only check option if function `is_shop` exists
        $wooCommerceShopPageId = function_exists('is_shop') ? get_option('woocommerce_shop_page_id') : 0;

        // Check if we are on the WooCommerce Shop Page
        // Do not mix the WooCommerce Search Page with the Shop Page
        if (function_exists('is_shop') && is_shop()) {
            $this->currentPostId = $wooCommerceShopPageId;

            if ($this->currentPostId > 0) {
                self::$vars['is_woo_shop_page'] = true;
            }
        } else {
            if ($wooCommerceShopPageId > 0 && Misc::isHomePage() && strpos(get_site_url(), '://') !== false) {
                list($siteUrlAfterProtocol) = explode('://', get_site_url());
                $currentPageUrlAfterProtocol = parse_url(site_url(), PHP_URL_HOST) . $_SERVER['REQUEST_URI'];

                if ($siteUrlAfterProtocol != $currentPageUrlAfterProtocol && (strpos($siteUrlAfterProtocol, '/shop') !== false)) {
	                self::$vars['woo_url_not_match'] = true;
                }
            }
        }

	    // Blog Home Page (aka: Posts page) is not a singular page, it's checked separately
        if (Misc::isBlogPage()) {
        	$this->currentPostId = get_option('page_for_posts');
        }

        // It has to be a single page (no "Posts page")
        if (($this->currentPostId < 1) && is_singular()) {
            global $post;
            $this->currentPostId = isset($post->ID) ? $post->ID : 0;
        }

	    // [wpacu_lite]
        // Undetectable? The page is not a singular one nor the home page
        // It's likely an archive, category page (WooCommerce), 404 page manageable in the Pro version etc.
        if (! $this->currentPostId && ! Misc::isHomePage()) {
            $this->isUpdateable = false;
        }
	    // [/wpacu_lite]

        return $this->currentPostId;
    }

    /**
     * @return array|null|\WP_Post
     */
    public function getCurrentPost()
    {
        // Already set? Return it
        if (! empty($this->currentPost)) {
            return $this->currentPost;
        }

        // Not set? Create and return it
        if (! $this->currentPost && $this->getCurrentPostId() > 0) {
            $this->currentPost = get_post($this->getCurrentPostId());
            return $this->currentPost;
        }

        // Empty
        return $this->currentPost;
    }

	/**
	 * Get all contracted rows
	 *
	 * @return array
	 */
	public static function getHandleRowStatus()
	{
		$handleRowStatus = array('styles' => array(), 'scripts' => array());

		$handleRowStatusListJson = get_option(WPACU_PLUGIN_ID . '_global_data');
		$globalKey = 'handle_row_contracted';

		if ($handleRowStatusListJson) {
			$handleRowStatusList = @json_decode($handleRowStatusListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return $handleRowStatus;
			}

			// Are new positions set for styles and scripts?
			foreach (array('styles', 'scripts') as $assetKey) {
				if ( isset( $handleRowStatusList[$assetKey][$globalKey] ) && ! empty( $handleRowStatusList[$assetKey][$globalKey] ) ) {
					$handleRowStatus[$assetKey] = $handleRowStatusList[$assetKey][$globalKey];
				}
			}
		}

		return $handleRowStatus;
	}

	/**
	 * @param $postType
	 *
	 * @return array
	 */
	public static function getAllSetTaxonomies($postType)
	{
		if ( ! $postType ) {
			return array();
		}

		$postTaxonomies = get_object_taxonomies($postType);

		if ($postType === 'post') {
			$postFormatKey = array_search('post_format', $postTaxonomies);
			unset($postTaxonomies[$postFormatKey]);
		}

		if (empty($postTaxonomies)) {
			// There are no taxonomies associate with the $postType or $postType is not valid
			return array();
		}

		$allPostTypeTaxonomyTerms = get_terms( array(
			'taxonomy' => $postTaxonomies,
			'hide_empty' => true,
		) );

		$finalList = array();

		foreach ($allPostTypeTaxonomyTerms as $obj) {
			$taxonomyObj = get_taxonomy($obj->taxonomy);

			if ( ! $taxonomyObj->show_ui ) {
				continue;
			}
			$finalList[$taxonomyObj->label][] = (array)$obj;
		}

		foreach (array_keys($finalList) as $taxonomyLabel) {
			usort( $finalList[$taxonomyLabel], static function( $a, $b ) {
				return strcasecmp( $a['name'], $b['name'] );
			} );
		}

		ksort($finalList);

		return $finalList;
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function setPageTemplate($data)
    {
    	global $template;

	    $getPageTpl = get_post_meta($this->getCurrentPostId(), '_wp_page_template', true);

	    // Could be a custom post type with no template set
	    if (! $getPageTpl) {
		    $getPageTpl = get_page_template();

		    if (in_array(basename($getPageTpl), array('single.php', 'page.php'))) {
			    $getPageTpl = 'default';
		    }
	    }

	    if (! $getPageTpl) {
	    	return $data;
	    }

	    $data['page_template'] = $getPageTpl;

	    $data['all_page_templates'] = wp_get_theme()->get_page_templates();

	    // Is the default template shown? Most of the time it is!
	    if ($data['page_template'] === 'default') {
	    	$pageTpl = (isset($template) && $template) ? $template : get_page_template();
		    $data['page_template'] = basename( $pageTpl );
		    $data['all_page_templates'][ $data['page_template'] ] = 'Default Template';
	    }

	    if (isset($template) && $template && defined('ABSPATH')) {
	    	$data['page_template_path'] = str_replace(
			    Misc::getWpRootDirPath(),
			    '',
			    '/'.$template
		    );
	    }

	    return $data;
    }

	/**
	 * @return bool
	 */
	public static function isWpDefaultSearchPage()
	{
		// It will not interfere with the WooCommerce search page
		// which is considered to be the "Shop" page that has its own unload rules
		return (is_search() && (! (function_exists('is_shop') && is_shop())));
	}

	/**
	 * @param $existingListJson
	 * @param $existingListEmpty
	 *
	 * @return array
	 */
	public function existingList($existingListJson, $existingListEmpty)
	{
		$validJson = $notEmpty = true;

		if (! $existingListJson) {
			$existingList = $existingListEmpty;
			$notEmpty = false;
		} else {
			$existingList = json_decode($existingListJson, true);

			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				$validJson = false;
				$existingList = $existingListEmpty;
			}
		}

		return array(
			'list'       => $existingList,
			'valid_json' => $validJson,
			'not_empty'  => $notEmpty
		);
	}

	/**
	 * Situations when the assets will not be prevented from loading
	 * e.g. test mode and a visitor accessing the page, an AJAX request from the Dashboard to print all the assets
     *
	 * @param array $ignoreList
	 *
	 * @return bool
	 */
	public function preventAssetsSettings($ignoreList = array())
	{
		// This request specifically asks for all the assets to be loaded in order to print them in the assets management list
		// This is for the AJAX requests within the Dashboard, thus the admin needs to see all the assets,
		// including ones marked for unload, in case he/she decides to change their rules
		if ($this->isGetAssetsCall && ! in_array('assets_call', $ignoreList)) {
			return true;
		}

		// Is test mode enabled? Unload assets ONLY for the admin
		if (self::isTestModeActive()) {
			return true; // visitors (non-logged in) will view the pages with all the assets loaded
		}

		$isSingularPage = defined('WPACU_CURRENT_PAGE_ID') && WPACU_CURRENT_PAGE_ID > 0 && is_singular();

		if ($isSingularPage || Misc::isHomePage()) {
			if ($isSingularPage) {
				$pageOptions = MetaBoxes::getPageOptions( WPACU_CURRENT_PAGE_ID ); // Singular page
			} else {
				$pageOptions = MetaBoxes::getPageOptions(0, 'front_page'); // Home page
			}

			if (isset($pageOptions['no_assets_settings']) && $pageOptions['no_assets_settings']) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array $settings
	 *
	 * @return bool
	 */
	public static function isTestModeActive($settings = array())
    {
        if (defined('WPACU_IS_TEST_MODE_ACTIVE')) {
            return WPACU_IS_TEST_MODE_ACTIVE;
        }

        if (! $settings) {
            $settings = self::instance()->settings;
        }

        $wpacuIsTestModeActive = isset($settings['test_mode']) && $settings['test_mode'] && ! Menu::userCanManageAssets();

        define('WPACU_IS_TEST_MODE_ACTIVE', $wpacuIsTestModeActive);

        return $wpacuIsTestModeActive;
    }

	/**
	 * @return bool
	 */
	public static function currentUserCanViewAssetsList()
    {
	    if ( Main::instance()->settings['allow_manage_assets_to'] === 'chosen' && ! empty(Main::instance()->settings['allow_manage_assets_to_list']) ) {
		    $wpacuCurrentUserId = get_current_user_id();

		    if ( ! in_array( $wpacuCurrentUserId, Main::instance()->settings['allow_manage_assets_to_list'] ) ) {
			    return false; // the current logged-in admin is not in the list of "Allow managing assets to:"
		    }
	    }

	    return true;
    }

	/**
	 * @return bool
	 */
	public function frontendShow()
    {
        // The option is disabled
	    if (! $this->settings['frontend_show']) {
		    return false;
	    }

	    // The asset list is hidden via query string: /?wpacu_no_frontend_show
	    if (isset($_REQUEST['wpacu_no_frontend_show'])) {
	        return false;
        }

	    // Page loaded via Yellow Pencil Editor within an iframe? Do not show it as it's irrelevant there
        if (isset($_GET['yellow_pencil_frame'], $_GET['yp_page_type'])) {
            return false;
        }

	    // The option is enabled, but there are show exceptions, check if the list should be hidden
        if ($this->settings['frontend_show_exceptions']) {
	        $frontendShowExceptions = trim( $this->settings['frontend_show_exceptions'] );

	        // We want to make sure the RegEx rules will be working fine if certain characters (e.g. Thai ones) are used
	        $requestUriAsItIs = rawurldecode($_SERVER['REQUEST_URI']);

	        if ( strpos( $frontendShowExceptions, "\n" ) !== false ) {
		        foreach ( explode( "\n", $frontendShowExceptions ) as $frontendShowException ) {
			        $frontendShowException = trim($frontendShowException);

			        if ( strpos( $requestUriAsItIs, $frontendShowException ) !== false ) {
				        return false;
			        }
		        }
	        } elseif ( strpos( $requestUriAsItIs, $frontendShowExceptions ) !== false ) {
                return false;
	        }
        }

        // Allows managing assets to chosen admins and the user is not in the list
        if ( ! self::currentUserCanViewAssetsList() ) {
            return false;
        }

        return true;
    }

	/**
	 * Make administrator more aware if "TEST MODE" is enabled or not
	 */
	public function wpacuHtmlNoticeForAdmin()
	{
		add_action('wp_footer', static function() {
			if ((WPACU_GET_LOADED_ASSETS_ACTION === true) || (! apply_filters('wpacu_show_admin_console_notice', true)) || Plugin::preventAnyFrontendOptimization()) {
				return;
			}

			if ( ! (Menu::userCanManageAssets() && ! is_admin())) {
				return;
			}

			if (Main::instance()->settings['test_mode']) {
				$consoleMessage = sprintf(esc_html__('%s: "TEST MODE" ENABLED (any settings or unloads will be visible ONLY to you, the logged-in administrator)', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE);
				$testModeNotice = esc_html__('"Test Mode" is ENABLED. Any settings or unloads will be visible ONLY to you, the logged-in administrator.', 'wp-asset-clean-up');
			} else {
				$consoleMessage = sprintf(esc_html__('%s: "LIVE MODE" (test mode is not enabled, thus, all the plugin changes are visible for everyone: you, the logged-in administrator and the regular visitors)', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE);
				$testModeNotice = esc_html__('The website is in LIVE MODE as "Test Mode" is not enabled. All the plugin changes are visible for everyone: logged-in administrators and regular visitors.', 'wp-asset-clean-up');
			}
			?>
            <!--
            <?php echo sprintf(esc_html__('NOTE: These "%s: Page Speed Booster" messages are only shown to you, the HTML comment is not visible for the regular visitor.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?>

            <?php echo esc_html__($testModeNotice); ?>
            -->
            <script <?php echo Misc::getScriptTypeAttribute(); ?> data-wpacu-own-inline-script="true">
                console.log('<?php echo esc_js($consoleMessage); ?>');
            </script>
			<?php
		});
	}

	/**
	 *
	 */
	public function fallbacks()
	{
		// Fallback for the old filters (e.g. Pro version below 1.2.0.7)
		add_filter('wpacu_filter_styles_list_unload',  function ($list) { return apply_filters('wpacu_filter_styles',  $list); });
		add_filter('wpacu_filter_scripts_list_unload', function ($list) { return apply_filters('wpacu_filter_scripts', $list); });
	}
}
