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

    public static function directory() {
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        // the query
        $the_query = new WP_Query( 'post_type=directory&posts_per_page=1&paged=' . $paged );
        if ( $the_query->have_posts() ) :
            // the loop
            while ( $the_query->have_posts() ) : $the_query->the_post();
                the_title();
            endwhile;
            //Pagination
            $total_pages = $the_query->max_num_pages;
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
            echo '<p> '._e( 'Sorry, no posts matched your criteria.' ).'</p>';
        endif;
    }

    public static function directory_category() {
        $category = '';
        if(!empty($_GET['cat4'])) {
            $category = ''.$_GET['cat1'].','.$_GET['cat2'].','.$_GET['cat3'].','.$_GET['cat4'].'';
        } elseif(!empty($_GET['cat3'])) {
            $category = ''.$_GET['cat1'].','.$_GET['cat2'].','.$_GET['cat3'].'';
        } elseif(!empty($_GET['cat2'])) {
            $category = ''.$_GET['cat1'].','.$_GET['cat2'].'';
        } elseif(!empty($_GET['cat1'])) {
            $category = $_GET['cat1'];
        }
        $category = explode(',', $category);
        $args = array(
            'post_type' => 'directory',
            'tax_query' => array(
                array(
                    'taxonomy' => 'directory_categories',
                    'terms' => $category,
                    'field' => 'slug',
                    'include_children' => true,
                    'operator' => 'AND'
                )
            ),
            'post_status' => 'publish'
        );
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post();
                the_title();
                echo '<div class="entry-content">';
                the_content();
                echo '</div>';
            endwhile;
            // clean up after our query
            wp_reset_postdata();
        else:
            echo '<p> '._e( 'Sorry, no posts matched your criteria.' ).'</p>';
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
            'taxonomy'           => 'directory_categories',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args );

        $args2 = array(
            'show_option_all'    => '',
            'show_option_none'   => 'Empty',
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
            'taxonomy'           => 'directory_categories',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args2 );

        $args3 = array(
            'show_option_all'    => '',
            'show_option_none'   => 'Empty',
            'option_none_value'  => '',
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_count'         => 1,
            'hide_empty'         => 1,
            'child_of'           => 0,
            'exclude'            => '',
            'echo'               => 1,
            'selected'           => $_GET['cat3'],
            'hierarchical'       => 0,
            'name'               => 'cat3',
            'id'                 => '',
            'class'              => 'postform',
            'depth'              => 0,
            'tab_index'          => 0,
            'taxonomy'           => 'directory_categories',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args3 );

        $args4 = array(
            'show_option_all'    => '',
            'show_option_none'   => 'Empty',
            'option_none_value'  => '',
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_count'         => 1,
            'hide_empty'         => 1,
            'child_of'           => 0,
            'exclude'            => '',
            'echo'               => 1,
            'selected'           => $_GET['cat4'],
            'hierarchical'       => 0,
            'name'               => 'cat4',
            'id'                 => '',
            'class'              => 'postform',
            'depth'              => 0,
            'tab_index'          => 0,
            'taxonomy'           => 'directory_categories',
            'hide_if_empty'      => false,
            'value_field'	     => 'slug',
        );
        wp_dropdown_categories( $args4 );

        echo '<input type="submit" name="submit" value="view" />';
        echo '</form>';
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
                'taxonomies' => array( 'directory_categories' ),
                //'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
                'has_archive' => true,
                'rewrite'       => array(
                    'slug' => 'directory',
                ),
            )
        );
    }

    function create_my_taxonomies() {
        register_taxonomy(
            'directory_categories',
            'directory',
            array(
                'labels' => array(
                    'name' => 'Directory Categories',
                    'add_new_item' => 'Add New Category',
                    'new_item_name' => "New Category"
                ),
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true
            )
        );
    }
}

//Admin pages
add_action( 'init', array('GWDirectory', 'create_listing') );
add_action( 'init', array('GWDirectory', 'create_my_taxonomies'), 0 );

//Shortcode
add_shortcode('GWDirectory', array('GWDirectory', 'directory'));
add_shortcode('GWDirectory_Category', array('GWDirectory', 'directory_category'));
add_shortcode('GWDirectory_Form', array('GWDirectory', 'directory_form'));