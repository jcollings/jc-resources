<?php
/**
 * Display Resource Sections
 *
 * Fetch all resource section headings, outputting a internal link list
 *
 * @author James Collings <james@jclabs.co.uk>
 * @version 0.0.1
 */
class JCR_CurrentResourceSections extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( 
			'jcr_current_resource_sections', 
			'Current Resource Sections' ,
			array( 'description' => __( 'JC Resources')) // Args
		);
	}

	function widget( $args, $instance ) {

		if(!jcr_is_single_resource())
			return false;

		extract( $args );

		global $post;
		$pattern = get_shortcode_regex();
		preg_match_all("/$pattern/",$post->post_content,$test_matches);

		if(empty($test_matches[2]))
			return;

		// Widget output
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		echo $before_widget;

		if ( ! empty( $title ) )
		    echo $before_title . $title . $after_title;

		

		echo '<ul>';
		foreach($test_matches[2] as $key => $test){
			if($test == 'jcr_resource_heading'){

				preg_match("/name=\"(.*?)\"/s",$test_matches[3][$key], $result);
				$title = $result[1];
				echo '<li><a href="#'.sanitize_title($title).'">'.$title.'</a></li>';
			}
		}
		echo '</ul>';		

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? $instance['title'] : '';
		?>
		<p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "JCR_CurrentResourceSections" );' ) );

/**
 * Display Resource Sections
 *
 * Fetch all resource section headings, outputting a internal link list
 *
 * @author James Collings <james@jclabs.co.uk>
 * @version 0.0.1
 */
class JCR_RelatedResources extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( 
			'jcr_current_related_resource', 
			'Related Resources' ,
			array( 'description' => __( 'JC Resources')) // Args
		);
	}

	function widget( $args, $instance ) {

		if(!jcr_is_single_resource())
			return false;

		extract( $args );

		// Widget output
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		echo $before_widget;

		if ( ! empty( $title ) )
		    echo $before_title . $title . $after_title;	

		global $post;
		$resource_id = $post->ID;
		$current_section = wp_get_post_terms( $post->ID, 'section');

		$resources = new WP_Query(array(
			'post_type' => 'resource',
			'post_parent' => 0,
			'tax_query' => array(
				array(
					'taxonomy' => 'section',
					'field' => 'id',
					'terms' => $current_section[0]->term_id
				)
			)
		));

		if($resources->have_posts()){
			echo '<ul>';
			while($resources->have_posts()){
				$resources->the_post();
				$classes = array('menu-item');
				if(get_the_ID() == $resource_id){
					$classes[] = 'current-menu-item';
				}
				echo '<li class="'.implode(' ', $classes).'"><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? $instance['title'] : '';
		?>
		<p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "JCR_RelatedResources" );' ) );