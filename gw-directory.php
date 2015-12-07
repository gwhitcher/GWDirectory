<?php
/*
Plugin Name: GWDirectory
Plugin URI: http://georgewhitcher.com/
Description: Makes a directory with categories and tags.
Version: 0.1
Author: George Whitcher
Author URI: https://georgewhitcher.com/
*/
class GWDirectory {


    //Plugin init
    public function __construct() {
    }

    public static function directory() {
        include(dirname( __FILE__ ) . '/directory-home.php');
    }

    public static function directory_form() {
        include(dirname( __FILE__ ) . '/directory-form.php');
        //Breadcrumbs
        self::directory_breadcrumbs();
    }

    public static function directory_search() {
        include(dirname( __FILE__ ) . '/directory-search.php');
    }

    public static function directory_breadcrumbs() {
        echo '<div id="directory-breadcrumbs">';
        if(!empty($_GET['cat2'])) {
            echo '&raquo; '.self::breadcrumb_clean($_GET['term1']).' &raquo; '.self::breadcrumb_clean($_GET['term2']);
        } elseif (!empty($_GET['term1'])) {
            echo '&raquo; '.self::breadcrumb_clean($_GET['term2']);
        }
        echo '</div>';
    }

    public static function breadcrumb_clean($breadcrumb) {
        $breadcrumb_clean = str_replace('-', ' ', $breadcrumb);
        $breadcrumb_uppercase = ucfirst($breadcrumb_clean);
        return $breadcrumb_uppercase;
    }

    public static function directory_view($single_template) {
        global $post;

        if ($post->post_type == 'directory') {
            $single_template = dirname( __FILE__ ) . '/directory-single.php';
        }
        return $single_template;
    }

    public static function directory_taxonomy($taxonomy) {
        global $post;

        if ($post->post_type == 'directory') {
            $taxonomy = dirname( __FILE__ ) . '/directory-taxonomy.php';
        }

        return $taxonomy;
    }

    public static function create_listing() {
        register_post_type('directory',
            array(
                'labels' => array(
                    'name' => 'Directory Listings',
                    'singular_name' => 'Directory',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Directory Listing',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Directory Listing',
                    'new_item' => 'New Directory Listing',
                    'view' => 'View',
                    'view_item' => 'View Directory Listing',
                    'search_items' => 'Search Directory Listing',
                    'not_found' => 'No Directory Listing found',
                    'not_found_in_trash' => 'No Directory Listing found in Trash',
                    'parent' => 'Directory Listing Review'
                ),

                'public' => true,
                'menu_position' => 15,
                'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
                'taxonomies' => array( 'directory-categories',  'directory-tags'),
                'has_archive' => true,
                'rewrite'       => array(
                    'slug' => 'directory-listing',
                ),
            )
        );
        flush_rewrite_rules();
    }

    function create_my_taxonomies() {
        //Categories
        register_taxonomy(
            'directory-categories',
            'directory',
            array(
                'labels' => array(
                    'name' => 'Directory Categories',
                    'add_new_item' => 'Add New Category',
                    'new_item_name' => "New Category"
                ),
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
            )
        );
        //Tags
        register_taxonomy(
            'directory-tags',
            'directory',
            array(
                'labels' => array(
                    'name' => 'Directory Tags',
                    'add_new_item' => 'Add New Tags',
                    'new_item_name' => "New Tag"
                ),
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true
            )
        );
    }
}

//Init pages
add_action('init', array('GWDirectory', 'create_my_taxonomies'), 0);
add_action('init', array('GWDirectory', 'create_listing'));

//Shortcode
add_shortcode('GWDirectory', array('GWDirectory', 'directory'));
add_shortcode('GWDirectory_Form', array('GWDirectory', 'directory_form'));
add_shortcode('GWDirectory_Search', array('GWDirectory', 'directory_search'));

//Content Filters
add_filter('single_template', array('GWDirectory', 'directory_view'));
add_filter('taxonomy_template', array('GWDirectory', 'directory_taxonomy'));