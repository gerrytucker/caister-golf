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
 * Version:           1.3.2
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

        if ($posts = $wp->getHoles() ) {
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

        if ($post = $wp->getHole($holeid) ) {
            return new WP_REST_Response($post, 200);
        } else {
            // return an 404 empty result set
            return new WP_REST_Response(array(), 404);
        }
            
    }

        // Register Custom Post Type
    static function register_post_types() {

        $labels = array(
            'name'                  => _x( 'Holes', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Hole', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Holes', 'text_domain' ),
            'name_admin_bar'        => __( 'Hole', 'text_domain' ),
            'archives'              => __( 'Item Archives', 'text_domain' ),
            'attributes'            => __( 'Item Attributes', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Hole:', 'text_domain' ),
            'all_items'             => __( 'All Holes', 'text_domain' ),
            'add_new_item'          => __( 'Add New Hole', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Hole', 'text_domain' ),
            'edit_item'             => __( 'Edit Hole', 'text_domain' ),
            'update_item'           => __( 'Update Hole', 'text_domain' ),
            'view_item'             => __( 'View Hole', 'text_domain' ),
            'view_items'            => __( 'View Holes', 'text_domain' ),
            'search_items'          => __( 'Search Hole', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
            'items_list'            => __( 'Items list', 'text_domain' ),
            'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );
        $args = array(
            'label'                 => __( 'Hole', 'text_domain' ),
            'description'           => __( 'Holes', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-location-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'holes', $args );

    }

    /**
     * Initialize plugin
     * 
     * @return null
     */
    static function init() 
    {
        add_action( 'init', array('WP_Caister_Golf', 'register_post_types'), 0 );
        register_activation_hook(__FILE__, array( 'WP_Caister_Golf', 'activate' ));
        add_action('rest_api_init', array( 'WP_Caister_Golf', 'registerApiHooks' ));
    }

}

$WP_Caister_Golf = new WP_Caister_Golf();
$WP_Caister_Golf->init();
