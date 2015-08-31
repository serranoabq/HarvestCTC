<?php 
/* Locations Widget */
/* Uses ctc_location to display alllocations */

class harvest_Locations extends WP_Widget {
		
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'harvest-locations', 
			'description' => __( 'Harvest Locations', 'harvest' ) 
		);
		parent::__construct( 'harvest-locations', __( 'Harvest Locations', 'harvest' ), $widget_ops);
	}
		
	function widget( $args, $instance ) {
		global $post;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$show_map = $instance['show_map'];
		
		$query = array(
			'post_type' 			=> 'ctc_location', 
			'order' 					=> 'ASC',
			'orderby' 				=> 'menu_order',
		); 
		
		$posts = new WP_Query( $query ); 
		if ( $posts -> have_posts() ){
			echo $before_widget;
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			echo '<div id="ctc-locations" class="ctc-loc-grid grid-parent">';
			
			while ($posts->have_posts()) :
				$posts		 	-> the_post();
				$post_id = get_the_ID();
				$permalink = get_permalink();
				
				// Location data
				$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
				$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
				$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
				
?>
			<div class="ctc-location grid-33 mobile-grid-50 tiny-grid-100">
				<h3><a href="<?php echo $permalink; ?>"><?php echo the_title(); ?></a></h3>
<?php if( $loc_address ): ?>
				<div class="loc-address"><?php echo nl2br($loc_address); ?></div>
<?php endif; ?>

			</div> <!-- .ctc-loc -->
<?php				
			endwhile;

		echo '</div>';
		echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_map'] = strip_tags( $new_instance['show_map'] );
		return $instance;
	}
	
	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$show_map = esc_attr( $instance['show_map'] );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'harvest' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
	<?php 
	} 
} 

