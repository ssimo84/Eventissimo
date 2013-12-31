<?php
//Widget
class eventissimo_calendar extends WP_Widget {
    function eventissimo_calendar() {
        parent::__construct( false, 'Eventissimo: ' . __('Calendar') );
    }
    function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;
        $title = apply_filters( __('Calendar'), $instance['title'] );
        echo $before_widget;
        if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
    	$instance['title'] = strip_tags( $new_instance['title'] );
        return $new_instance;
    }
    function form( $instance ) {
       _e("Title");
        $title = apply_filters( __('Calendar'), $instance['title'] );
        ?>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
          
        <?php
    }
}
class eventissimo_listevents extends WP_Widget {
    function eventissimo_listevents() {
        parent::__construct( false, 'eventissimo_list' );
		$widget_ops = array( 'classname' => 'eventissimo_list','description' => __( 'List of the next event', 'eventissimo' ) );
        $this->WP_Widget( 'eventissimo_list', __( 'Next Events', 'eventissimo' ), $widget_ops );
    }
    function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;
        $title = apply_filters( 'eventissimo_list' , $instance['title'] );
		$num = apply_filters( 'eventissimo_list', $instance['num'], $instance );
		$dateview = apply_filters( 'eventissimo_list', $instance['dateview'], $instance );
        echo $before_widget;
        if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo  eventissimo_frontend_list($num,$dateview);
        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num'] = strip_tags( $new_instance['num'] );
		$instance['dateview'] = strip_tags( $new_instance['dateview'] );
        return $instance;
    }
    function form( $instance ) {
        $defaults = array( 'num' => 5, 'dateview' => 'next' );
        $instance = wp_parse_args( (array) $instance, $defaults ); 
	   if (isset($instance['title'] ))
        	$title = apply_filters( 'eventissimo_list' , $instance['title'] );
		else
			$title = __('List Events','eventissimo');
		if ((isset($instance['num']) && $instance['num']!=""))
        	$num = apply_filters( 'eventissimo_list' , $instance['num'] );
		else
			$num = 5;
		
		if (isset($instance['dateview']))
        	$dateview = apply_filters( 'eventissimo_list' , $instance['dateview'] );
		else
			$dateview = "false";
		
        ?>
        
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title");?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
         
		 <label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e("Number","eventissimo");?></label>
         <input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo esc_attr( $num ); ?>" /> 
         
          <label for="<?php echo $this->get_field_id( 'dateview' ); ?>"><?php _e("View Date","eventissimo");?></label>
          <select class="widefat" id="<?php echo $this->get_field_id( 'dateview' ); ?>" name="<?php echo $this->get_field_name( 'dateview' ); ?>">
            <option value="true" <?php if ($dateview=="true") echo "selected='selected'"; ?>><?php _e("Yes","eventissimo");?></option>
            <option value="false" <?php if ($dateview=="false") echo "selected='selected'"; ?>><?php _e("No (only title)","eventissimo");?></option>
          </select>
         
          
        <?php
    }
}

function eventissimo_calendar_widget() {
    register_widget( "eventissimo_calendar" );
}
function eventissimo_list_widget() {
    register_widget( "eventissimo_listevents" );
}
add_action( 'widgets_init', 'eventissimo_calendar_widget' );
add_action( 'widgets_init', 'eventissimo_list_widget' );

?>