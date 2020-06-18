<?php
/**
 * PHP version 7
 * 
 * Caister Golf Course Plugin Functions
 * 
 * @category WordPress
 * @package  caister-golf
 * @author   Gerry Tucker <gerrytucker@gerrytucker.co.uk>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://gerrytucker.co.uk/plugins/caister-golf
 */

/**
 * Plugin Name:       Caister Golf Course
 * Plugin URI:        https://gerrytucker.co.uk/plugins/caister-golf
 * GitHub Plugin URI: https://github.com/gerrytucker/caister-golf
 * Description:       Caister Golf Course plugin
 * Version:           1.0.0
 * Author:            Gerry Tucker
 * Author URI:        https://gerrytucker@gerrytucker.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       caister-golf
 * Domain Path:       /languages
 */

require_once 'classes/class_holes.php';

/**
 * Caister Golf Course Functions
 * 
 * @category WordPress
 * @package  caister-golf
 * @author   Gerry Tucker <gerrytucker@gerrytucker.co.uk>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://gerrytucker.co.uk/plugins/caister-golf
 * @since    1.0
 */
class WP_Caister_Golf
{

    // API Version
    const API_VERSION = 'golf/v2';

    /**
     * Set up the client
     * 
     * @return null
     */
    function __construct() 
    {
    }

    /**
     * Activate the plugin
     * 
     * @return null
     */
    public function activate() {}

    /**
     * Register API routes
     * 
     * @return null
     */
    public function registerApiHooks() 
    {

        self::registerPostRoutes();

    }        

    /**
     * Register customer function routes
     * 
     * @return null
     */
    public function registerPostRoutes() 
    {

        // Get posts
        register_rest_route(
            self::API_VERSION,
            'holes/',
            array(
                'methods'   => 'GET',
                'callback'  => array( 'WP_Caister_Golf', 'getHoles' )
            )
        );
    
        // Get post
        register_rest_route(
            self::API_VERSION,
            'hole/(?<holeid>\d+)',
            array(
                'methods'   => 'GET',
                'callback'  => array( 'WP_Caister_Golf', 'getHole' )
            )
        );
    
    }

    /**
     * Get posts
     *
     * @param WP_REST_Request $request Rest request
     * 
     * @return WP_REST_Response
     */
    static function getHoles( WP_REST_Request $request ) 
    {

        $wp = new Golf_Holes();

        if ($posts = $wp->getPosts() ) {
            return new WP_REST_Response($posts, 200);
        } else {
            // return an 404 empty result set
            return new WP_REST_Response(array(), 404);
        }
            
    }

    /**
     * Get hole
     *
     * @param WP_REST_Request $request Rest request
     * 
     * @return WP_REST_Response
     */
    static function getHole( WP_REST_Request $request ) 
    {
        $holeid = $request['holeid'];

        $wp = new Golf_Holes();

        if ($post = $wp->getPost($holeid) ) {
            return new WP_REST_Response($post, 200);
        } else {
            // return an 404 empty result set
            return new WP_REST_Response(array(), 404);
        }
            
    }

    /**
     * Initialize plugin
     * 
     * @return null
     */
    static function init() 
    {
        register_activation_hook(__FILE__, array( 'WP_Caister_Golf', 'activate' ));
        add_action('rest_api_init', array( 'WP_Caister_Golf', 'registerApiHooks' ));
    }

}

$WP_Caister_Golf = new WP_Caister_Golf();
$WP_Caister_Golf->init();
