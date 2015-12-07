<?php
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
    'selected'           => $_GET['term1'],
    'hierarchical'       => 0,
    'name'               => 'term1',
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
    'selected'           => $_GET['term2'],
    'hierarchical'       => 0,
    'name'               => 'term2',
    'id'                 => '',
    'class'              => 'postform',
    'depth'              => 0,
    'tab_index'          => 0,
    'taxonomy'           => 'directory-tags',
    'hide_if_empty'      => false,
    'value_field'	     => 'slug',
);
wp_dropdown_categories( $args2 );

echo '<input type="submit" name="submit" value="view" />';
echo '</form>';