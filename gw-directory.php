<?php
/*
Plugin Name: GWDirectory
Plugin URI: http://georgewhitcher.com/
Description: Makes a directory with categories.
Version: 0.1
Author: George Whitcher
Author URI: https://georgewhitcher.com/
*/
class GWDirectory {


    //Plugin init
    public function __construct() {
    }

    public static function directory() {
        //Stylesheet
        echo '<link rel="stylesheet" id="gwdirectory-styles"  href="'.plugins_url().'/gw-directory/styles.css" type="text/css" media="all" />';
        //Queries
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $category1 = $_GET['cat1'];
        $category2 = $_GET['cat2'];
        $args = array(
            'post_type' => 'directory',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'directory-categories',
                    'terms' => $category1,
                    'field' => 'slug',
                    'include_children' => true,
                    'operator' => 'AND'
                ),
                array(
                    'taxonomy' => 'directory-states',
                    'terms' => $category2,
                    'field' => 'slug',
                    'include_children' => true,
                    'operator' => 'AND'
                ),
            ),
            'posts_per_page' =>  1,
            'paged' =>  $paged,
            'post_status' => 'publish'
        );

        //Display entry
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post();
                //Main div
                echo '<article id="post-';
                echo the_ID();
                echo '" ';
                echo post_class();
                echo '>';

                //Header
                echo '<header class="entry-header">';

                //Title
                echo '<h1 class="entry-title">';
                echo '<a href="';
                echo the_permalink();
                echo '" rel="bookmark">';
                echo the_title();
                echo '</a>';
                echo '</h1>';

                echo '<div class="entry-meta">';

                //Date
                $format_prefix = '%2$s';
                $date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
                    esc_url( get_permalink() ),
                    esc_attr( sprintf( __( 'Permalink to %s', 'directory' ), the_title_attribute( 'echo=0' ) ) ),
                    esc_attr( get_the_date( 'c' ) ),
                    esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
                );
                echo '<span class="date"><time class="entry-date">';
                echo $date;
                echo '</time></span> ';

                //Categories
                echo '<span class="categories-links">';
                echo get_the_term_list( get_the_ID(), 'directory-categories', '', ', ' );
                echo '</span> <span class="categories-links">';
                echo get_the_term_list( get_the_ID(), 'directory-states', '', ', ' );
                echo '</span>';

                //Closing meta div tag
                echo '</div>';

                //Closing header tag
                echo '</header>';

                echo '<div class="entry-content">';
                echo the_excerpt();
                echo '</div>';

                //Closing article tag
                echo '</article>';
            endwhile;

            //Pagination
            $total_pages = $loop->max_num_pages;
            if ($total_pages > 1) {
                $current_page = max(1, get_query_var('paged'));

                echo '<div class="page_nav">';

                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => 'page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => 'Prev',
                    'next_text' => 'Next'
                ));

                echo '</div>';
            }

            // clean up after our query
            wp_reset_postdata();
        else:
            echo '<p> '._e( 'Sorry, no directory listings matched your criteria.' ).'</p>';
        endif;
    }

    public static function directory_form() {
        echo '<form action="'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'" method="get" name="directory">';
        $args = array(
            'show_option_all'    => '',
            'show_option_none'   => '',
            'option_none_value'  => '-1',
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_count'         => 1,
            'hide_empty'         => 1,
            'child_of'           => 0,
            'exclude'            => '',
            'echo'               => 1,
            'selected'           => $_GET['cat1'],
            'hierarchical'       => 0,
            'name'               => 'cat1',
            'id'                 => '',
            'class'              => 'postform',
            'depth'              => 0,
            'tab_index'          => 0,
            'taxonomy'           => 'directory-categories',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args );

        $args2 = array(
            'show_option_all'    => '',
            'show_option_none'   => '',
            'option_none_value'  => '',
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_count'         => 1,
            'hide_empty'         => 1,
            'child_of'           => 0,
            'exclude'            => '',
            'echo'               => 1,
            'selected'           => $_GET['cat2'],
            'hierarchical'       => 0,
            'name'               => 'cat2',
            'id'                 => '',
            'class'              => 'postform',
            'depth'              => 0,
            'tab_index'          => 0,
            'taxonomy'           => 'directory-states',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args2 );

        echo '<input type="submit" name="submit" value="view" />';
        echo '</form>';

        //Breadcrumbs
        self::directory_breadcrumbs();
    }

    public static function directory_breadcrumbs() {
        echo '<div id="directory-breadcrumbs">';
        if(!empty($_GET['cat2'])) {
            echo '&raquo; '.self::breadcrumb_clean($_GET['cat1']).' &raquo; '.self::breadcrumb_clean($_GET['cat2']);
        } elseif (!empty($_GET['cat1'])) {
            echo '&raquo; '.self::breadcrumb_clean($_GET['cat1']);
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
                'taxonomies' => array( 'directory-categories',  'directory-states'),
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
        //States
        register_taxonomy(
            'directory-states',
            'directory',
            array(
                'labels' => array(
                    'name' => 'Directory States',
                    'add_new_item' => 'Add New States',
                    'new_item_name' => "New State"
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

//Content Filters
add_filter('single_template', array('GWDirectory', 'directory_view'));
add_filter('taxonomy_template', array('GWDirectory', 'directory_taxonomy'));