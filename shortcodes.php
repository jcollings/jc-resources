<?php
/**
 * Output the current section, and all its resources
 */
add_shortcode( 'jcr_section', 'jcr_section_shortcode' );
function jcr_section_shortcode($atts){
	extract(shortcode_atts( array(
	    'id' => null,
	    'slug' => null,
	    'columns' => 2,
	    'description' => true
    ), $atts ));

	// no section has been set
    if(!$id && !$slug)
    	return false;

	if($id > 0){
		$section = get_term($id, 'section');
	}else{
		$section = get_term_by( 'slug', $id, 'section');
	}

	if(!$section)
		return false;

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

    ob_start();

    if($resources->have_posts()): ?>

    	<h2 class="jcr_section_title"><?php echo $section->name; ?></h2>
    	
    	<?php // output description
    	if($description !== false): ?>
    	<p class="jcr_section_description">
    		<?php 
    		if($description === true){
    			// output description from taxonomy
    			echo $section->description;
    		}else{
    			// output custom description
    			echo $description;
    		}
    		?>
    	</p>
	    <?php endif; ?>

    	<ul class="jcr_resource_sections">
    	<?php while($resources->have_posts()): $resources->the_post(); ?>
    		<?php 
    		$classes = array();

    		// set column class
    		if($columns > 1 && $resources->current_post % intval($columns) === 0)
    			$classes[] = 'first';
    		elseif($columns > 1 && $resources->current_post % intval($columns) === (intval($columns)-1))
    			$classes[] = 'last';
    		?>
    		<li class="jcr_resource_section <?php echo implode(' ', $classes); ?>">
    			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    		</li>
    	<?php endwhile; ?>
    	</ul>
    	<?php wp_reset_postdata(); ?>
	    <?php
	    return '<div id="jcr_resource_section-'.$section->term_id.'" class="jcr_resources jcr_resource_section-'.$section->name.'">' . ob_get_clean() . '</div>';
    else:
    	ob_clean();
    	return '';
    endif;
}

add_shortcode( 'jcr_resource_heading', 'jcr_section_heading_shortcode' );
function jcr_section_heading_shortcode($atts){
	extract(shortcode_atts( array(
	    'name' => false,
    ), $atts ));

    if(!$name)
    	return false;

    return '<h3 id="'.sanitize_title($name).'">'.$name.'</h3>';
}