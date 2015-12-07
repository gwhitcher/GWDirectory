<?php
//Queries
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$term1 = $_GET['term1'];
$term2 = $_GET['term2'];
$args = array(
    'post_type' => 'directory',
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'directory-categories',
            'terms' => $term1,
            'field' => 'slug',
            'include_children' => true,
            'operator' => 'AND'
        ),
        array(
            'taxonomy' => 'directory-tags',
            'terms' => $term2,
            'field' => 'slug',
            'include_children' => true,
            'operator' => 'AND'
        ),
    ),
    'posts_per_page' =>  10,
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
        echo '</span> <span class="tags-links">';
        echo get_the_term_list( get_the_ID(), 'directory-tags', '', ', ' );
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