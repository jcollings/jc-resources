<?php
/*
Plugin Name: JC Resources
Plugin URI: http://jamescollings.co.uk/wordpress-plugins/jc-resources
Description: A simple resources plugin, allowing you to create sections, resources, and internal links. Output them width widgets, shortcodes
Author: James Collings
Version: 0.0.1
Author URI: http://jamescollings.co.uk
*/


require_once plugin_dir_path(__FILE__) . '/widgets.php';
require_once plugin_dir_path(__FILE__) . '/functions.php';
require_once plugin_dir_path(__FILE__) . '/shortcodes.php';


/**
 * Register Resource Post Type
 */
add_action('init', 'jcr_register_resources');
function jcr_register_resources(){

	
	$labels = array(
		'name'                => __( 'Resoures', 'text-domain-plural' ),
		'singular_name'       => __( 'Resource', 'text-domain' ),
		'add_new'             => _x( 'Add New Resource', 'text-domain-plural', 'text-domain-plural' ),
		'add_new_item'        => __( 'Add New Resource', 'text-domain' ),
		'edit_item'           => __( 'Edit Resource', 'text-domain' ),
		'new_item'            => __( 'New Resource', 'text-domain' ),
		'view_item'           => __( 'View Resource', 'text-domain' ),
		'search_items'        => __( 'Search Resource', 'text-domain-plural' ),
		'not_found'           => __( 'No Resource found', 'text-domain-plural' ),
		'not_found_in_trash'  => __( 'No Resource found in Trash', 'text-domain-plural' ),
		'parent_item_colon'   => __( 'Parent Singular Name:', 'text-domain' ),
		'menu_name'           => __( 'Resource', 'text-domain-plural' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => true,
		'description'         => 'description',
		'taxonomies'          => array( 'section' ),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title', 'editor', 'author', 'thumbnail',
			'excerpt','custom-fields', 'trackbacks', 'comments',
			'revisions', 'page-attributes', 'post-formats'
		)
	);

	register_post_type( 'resource', $args );
}

/**
 * Register Sections Taxonomy
 */
add_action('init', 'jcr_register_sections');
function jcr_register_sections(){

	$labels = array(
		'name'					=> _x( 'Sections', 'Taxonomy plural name', 'text-domain' ),
		'singular_name'			=> _x( 'Section', 'Taxonomy singular name', 'text-domain' ),
		'search_items'			=> __( 'Search Sections', 'text-domain' ),
		'popular_items'			=> __( 'Popular Sections', 'text-domain' ),
		'all_items'				=> __( 'All Sections', 'text-domain' ),
		'parent_item'			=> __( 'Parent Section', 'text-domain' ),
		'parent_item_colon'		=> __( 'Parent Section', 'text-domain' ),
		'edit_item'				=> __( 'Edit Section', 'text-domain' ),
		'update_item'			=> __( 'Update Section', 'text-domain' ),
		'add_new_item'			=> __( 'Add New Section', 'text-domain' ),
		'new_item_name'			=> __( 'New Section Name', 'text-domain' ),
		'add_or_remove_items'	=> __( 'Add or remove Sections', 'text-domain' ),
		'choose_from_most_used'	=> __( 'Choose from most used text-domain', 'text-domain' ),
		'menu_name'				=> __( 'Section', 'text-domain' ),
	);

	register_taxonomy(
		'section',
		'resource',
		array(
			'label' => __( 'Sections' ),
			'rewrite' => array( 'slug' => 'sections' ),
			'hierarchical' => true,
		)
	);
}