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

/**
 * Display current section info
 *
 * Wordpress action to display information about the current section
 * @param array $args an array of arguments
 * @param boolean $output return or output result
 */
add_action( 'jcr/show_section_info', 'jcr_show_section_info', 10);
function jcr_show_section_info($args, $output = true){
	
	global $post;

	if(!jcr_is_single_resource())
		return false;

	// set resource section
	$section = isset($args['section']) ? $args['section'] : false;
	$section = get_resource_section($section);

	ob_start();
	?>
	<div class="jcr_section_info">
		<img src="http://placehold.it/100x100" />
		<h3><?php echo $section->name; ?></h2>
	</div>
	<?php 
	$content = ob_get_clean();
	
	if($output)
		echo $content;
	
	return $content;
}

/**
 * Display list of links to other resources in same section
 *
 * Wordpress action to display a list of links to resources in the same section
 * @param array $args an array of arguments
 * @param boolean $output return or output result
 */
add_action( 'jcr/show_section_list', 'jcr_show_section_list', 10);
function jcr_show_section_list($args, $output = true){

	global $post;
	$resource_id = $post->ID;
	$before = isset($args['before']) ? $args['before'] : false;
	$after = isset($args['after']) ? $args['after'] : false;
	$columns = isset($args['columns']) ? $args['columns'] : 2;
	$title = isset($args['title']) ? $args['title'] : true;
	$description = isset($args['description']) ? $args['description'] : true;

	// styling options
	$wrapper = isset($args['wrapper']) ? $args['wrapper'] : 'div';
    $wrapper_id = isset($args['wrapper_id']) ? $args['wrapper_id'] : false;
    $wrapper_class = isset($args['wrapper_class']) ? $args['wrapper_class'] : false;
    $container = isset($args['container']) ? $args['container'] : 'ul';
    $container_class = isset($args['container_class']) ? $args['container_class'] : false;
    $container_id = isset($args['container_id']) ? $args['container_id'] : false;
    $item = isset($args['item']) ? $args['item'] : 'li';
    $item_class = isset($args['item_class']) ? $args['item_class'] : false;
	
	// set resource section
	$section = isset($args['section']) ? $args['section'] : false;
	$section = get_resource_section($section);

	if(!$section)
		return false;

	ob_start();
	
	$resources = new WP_Query(array(
		'post_type' => 'resource',
		'post_parent' => 0,
		'tax_query' => array(
			array(
				'taxonomy' => 'section',
				'field' => 'id',
				'terms' => $section->term_id
			)
		)
	));

	if($before)
		echo $before;

	if($resources->have_posts()){

		if($title !== false){

			if($title === true){
				echo '<h2 class="jcr_section_title">'.$section->name.'</h2>';
			}else{
				echo '<h2 class="jcr_section_title">'.$title.'</h2>';
			}
		}

    	if($description !== false){
    	
	    	echo '<p class="jcr_section_description">';
    		
    		if($description === true){
    			// output description from taxonomy
    			echo $section->description;
    		}else{
    			// output custom description
    			echo $description;
    		}

	    	echo '</p>';
	    }

		echo '<'.$container.' class="jcr_resource_sections '.$container_class.'">';

		while($resources->have_posts()){

			$resources->the_post();
			$classes = array('menu-item', 'jcr_resource_section');

			if(get_the_ID() == $resource_id){
				$classes[] = 'current-menu-item';
			}

    		// set column class
    		if($columns > 1 && $resources->current_post % intval($columns) === 0){
    			$classes[] = 'first';
    		}elseif($columns > 1 && $resources->current_post % intval($columns) === (intval($columns)-1)){
    			$classes[] = 'last';
    		}

			echo '<'.$item.' class="'.implode(' ', $classes).' '.$item_class.'"><a href="'.get_permalink().'">'.get_the_title().'</a></'.$item.'>';
		}

		echo '</'.$container.'>';

		wp_reset_postdata();
	}

	if($after)
		echo $after;

	if(!$before && !$after){
		$content = '<'.$wrapper.' id="'.sanitize_title($section->name).'" class="jcr_section jcr_section_'.sanitize_title($section->name).' '.$wrapper_class.'">' . ob_get_clean() . '</'.$wrapper.'>';
	}else{
		$content = ob_get_clean();
	}

	
	
	if($output)
		echo $content;
	
	return $content;
}

/**
 * Display list of resource anchors
 *
 * Wordpress action to display a list of resource anchors , generated from subheadings
 * @param array $args an array of arguments
 * @param boolean $output return or output result
 */
add_action( 'jcr/show_section_anchors', 'jcr_show_section_anchors', 10);
function jcr_show_section_anchors($args, $output = true){

	if(!jcr_is_single_resource())
		return false;

	$before = isset($args['before']) ? $args['before'] : false;
	$after = isset($args['after']) ? $args['after'] : false;
	
	global $post;
	$pattern = get_shortcode_regex();
	preg_match_all("/$pattern/",$post->post_content,$test_matches);

	if(empty($test_matches[2]))
		return;	

	if($before)
		echo $before;

	echo '<ul>';
	foreach($test_matches[2] as $key => $test){
		if($test == 'jcr_resource_heading'){

			preg_match("/name=\"(.*?)\"/s",$test_matches[3][$key], $result);
			$title = $result[1];
			echo '<li><a href="#'.sanitize_title($title).'">'.$title.'</a></li>';
		}
	}
	echo '</ul>';

	if($after)
		echo $after;

	$content = ob_get_clean();
	
	if($output)
		echo $content;
	
	return $content;
}