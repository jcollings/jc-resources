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
		$before = $after = '';

		// Widget output
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		$before .= $before_widget;

		if ( ! empty( $title ) )
		    $before .= $before_title . $title . $after_title;

		$after .= $after_widget;

		do_action( 'jcr/show_section_anchors', array(
			'before' => $before,
			'after' => $after
		));
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
		$before = $after = '';

		// Widget output
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		$before .= $before_widget;

		if ( ! empty( $title ) )
		    $before .= $before_title . $title . $after_title;	

		$after .= $after_widget;

		do_action( 'jcr/show_section_links', array(
			'before' => $before,
			'after' => $after
		));

		
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