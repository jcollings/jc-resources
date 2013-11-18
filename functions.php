<?php
/**
 * Check to see if single resource
 *
 * @return  boolean
 */
function jcr_is_single_resource(){
	if(is_singular( 'resource' )){
		return true;
	}
	return false;
}

/**
 * Get resource section
 * 
 * @return term
 */
function get_resource_section($section_id = false){

	global $post;

	if(!$section_id){
		$current_section = wp_get_post_terms( $post->ID, 'section');
		$section = $current_section[0];
	}else{
		if($section_id > 0){
			$section = get_term($section_id, 'section');
		}else{
			$section = get_term_by( 'slug', $section_id, 'section');
		}
	}

	return $section;
}