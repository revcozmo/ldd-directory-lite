<?php
/**
 * Filters related to our custom post type.
 *
 * Post types are registered in setup.php, all actions and filters in this file are related
 * to customizing the way WordPress handles our custom post types and taxonomies.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


function ldl_filter__enter_title_here($title) {
    if (get_post_type() == LDDLITE_POST_TYPE)
        $title = __('Listing Name', 'lddlite');

    return $title;
}


function ldl_filter__admin_post_thumbnail_html($content) {

    if (LDDLITE_POST_TYPE == get_post_type()) {
        $content = str_replace(__('Set featured image'), __('Upload A Logo', 'lddlite'), $content);
        $content = str_replace(__('Remove featured image'), __('Remove Logo', 'lddlite'), $content);
    }

    return $content;
}


function ldl_action__admin_menu_icon() {
    echo "\n\t<style>";
    echo '#adminmenu .menu-icon-' . LDDLITE_POST_TYPE . ' div.wp-menu-image:before { content: \'\\f307\'; }';
    echo '</style>';
}


function ldl_action__submenu_title() {
    global $submenu;
    $submenu['edit.php?post_type=' . LDDLITE_POST_TYPE][5][0] = 'All Listings';
}




function ldl_customize_appearance() {

    $css = '';

    $primary_defaults = array(
        'normal'     => '#3bafda',
        'hover'      => '#3071a9',
        'foreground' => '#fff',
    );

    $primary_custom = array(
        'normal'     => ldl_get_setting('appearance_primary_normal'),
        'hover'      => ldl_get_setting('appearance_primary_hover'),
        'foreground' => ldl_get_setting('appearance_primary_foreground'),
    );

    if (array_diff($primary_defaults, $primary_custom)) {
        $css .= <<<CSS
    .btn-primary {
        background: {$primary_custom['normal']};
        background-color: {$primary_custom['normal']};
        border-color: {$primary_custom['normal']};
        color: {$primary_custom['foreground']};
    }
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.btn-primary.dropdown-toggle {
        background: {$primary_custom['hover']};
        background-color: {$primary_custom['hover']};
        border-color: {$primary_custom['hover']};
        color: {$primary_custom['foreground']};
    }
    .label-primary {
        background-color: {$primary_custom['normal']};
    }
CSS;
    }

    if ($css) {
        echo '<style media="all">' . $css . '</style>';
    }

}
add_action('wp_head', 'ldl_customize_appearance', 20);


function ldl_template_include($template) {

    if (LDDLITE_POST_TYPE == get_post_type()) {

        $templates = array();

        if (is_single()) {
            $templates[] = 'single.php';
        } else if (is_archive()) {
            $templates[] = 'category.php';
        } else if (is_search()) {
            $templates[] = 'search.php';
        }

        $located = ldl_locate_template($templates, false, false);

        if ($located)
            return $located;

    }

    return $template;
}

add_filter('template_include', 'ldl_template_include');

add_filter('enter_title_here', 'ldl_filter__enter_title_here');
add_filter('admin_post_thumbnail_html', 'ldl_filter__admin_post_thumbnail_html');

add_action('admin_head', 'ldl_action__admin_menu_icon');
add_action('_admin_menu', 'ldl_action__submenu_title');


function ldl_filter_post_class($classes) {

    if ( is_single() && LDDLITE_POST_TYPE == get_post_type() && in_array( 'directory_listings', $classes ) ) {
        $classes[] = 'listing-single';
    }

    return $classes;
}
add_filter('post_class', 'ldl_filter_post_class');

