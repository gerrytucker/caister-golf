<?php
/**
 * PHP Version 7
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
 * Caister Golf Course Plugin Functions
 * 
 * @category WordPress
 * @package  caister-golf
 * @author   Gerry Tucker <gerrytucker@gerrytucker.co.uk>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://gerrytucker.co.uk/plugins/caister-golf
 * @since    1.0.0
 */
class Caister_Golf
{

    /**
     * Version 
     */
    const VERSION = "1.0";

    /**
     * Set up the client
     * 
     * @return null
     */
    public function __construct() 
    {
    }

    /**
     * Get holes
     * 
     * @return array
     */
    public function getHoles() 
    {

        $posts = get_posts(
            array(
                'numberposts'  => -1
            )
        );

        $response = array();

        foreach ( $posts as $post ) {
            // Get post thumbnail
            $thumbnail = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'thumbnail', false
            );
            $medium = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'medium', false
            );
            $large = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'large', false
            );
  
            $response[] = array(
                'id'              => $post->ID,
                'title'            => $post->post_title,
                'link'            => '/hole/' . $post->ID, 
                'thumbnail_url'   => $thumbnail[0],
                'medium_url'      => $medium[0],
                'large_url'       => $large[0],
            );
        }

        return $response;
    }

    /**
     * Get posts
     * 
     * @return array
     */
    public function getHole($postid) 
    {

        $posts = get_posts(
            array(
                'post__in' => array($postid)
            )
        );

        $response = array();

        foreach ( $posts as $post ) {
            // Get post thumbnail
            $thumbnail = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'thumbnail', false
            );
            $medium = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'medium', false
            );
            $large = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID), 'large', false
            );
  
            $response[] = array(
                'id'              => $post->ID,
                'title'            => $post->post_title,
                'content'         => apply_filters('the_content', $post->post_content),
                'link'            => '/hole/' . $post->ID,
                'thumbnail_url'   => $thumbnail[0],
                'medium_url'      => $medium[0],
                'large_url'       => $large[0],
            );
        }

        return $response;
    }

}