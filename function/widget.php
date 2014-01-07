<?php
//Widget
class eventissimo_listevents extends WP_Widget {
    function eventissimo_listevents() {
        parent::__construct( false, 'eventissimo_list' );
		$widget_ops = array( 'classname' => 'eventissimo_list','description' => __( 'List of the next event', 'eventissimo' ) );
        $this->WP_Widget( 'eventissimo_list', "Eventissimo:" . __( 'Next Events', 'eventissimo' ), $widget_ops );
    }
    function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;
        $title = apply_filters( 'eventissimo_list' , $instance['title'] );
		$num = apply_filters( 'eventissimo_list', $instance['num'], $instance );
		$dateview = apply_filters( 'eventissimo_list', $instance['dateview'], $instance );
        $filter = apply_filters( 'eventissimo_list', $instance['filter'], $instance );
        $dateview = (strtoupper($dateview)=="FALSE")?FALSE:TRUE;
        if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo  eventissimo_frontend_list($num,$dateview,'NEXT',FALSE,'LIST',$filter);
        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['num'] = strip_tags( $new_instance['num'] );
		$instance['dateview'] = strip_tags( $new_instance['dateview'] );
        $instance['filter'] = strip_tags( $new_instance['filter'] );
        
		return $instance;
    }
    function form( $instance ) {
        $defaults = array( 'num' => 5, 'dateview' => '' , 'filter'=>'');
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
		
		if (isset($instance['filter']))
        	$filter = apply_filters( 'eventissimo_list' , $instance['filter'] );
		else
			$filter = "";
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
         
        <label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e("Filter","eventissimo");?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>">
				<option value="" <?php if ($filter=="") echo "selected='selected'"; ?>>--</option>
				<option value="TODAY" <?php if ($filter=="TODAY") echo "selected='selected'"; ?>><?php _e("Today","eventissimo");?></option>
				<option value="MONTH" <?php if ($filter=="MONTH") echo "selected='selected'"; ?>><?php _e("Month","eventissimo");?></option>
			</select>
           
        <?php
    }
}

function eventissimo_list_widget() {
    register_widget( "eventissimo_listevents" );
}
add_action( 'widgets_init', 'eventissimo_list_widget' );


class eventissimo_taxonomy extends WP_Widget {
    function eventissimo_taxonomy() {
        parent::__construct( false, 'eventissimo_taxonomy' );
		$widget_ops = array( 'classname' => 'eventissimo_taxonomy','description' => __( 'List of type or category', 'eventissimo' ) );
        $this->WP_Widget( 'eventissimo_taxonomy', "Eventissimo:" . __( 'List of event Categories or Types', 'eventissimo' ), $widget_ops );
    }
    function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;
        $title = apply_filters( 'eventissimo_taxonomy' , $instance['title'] );
		$numview = apply_filters( 'eventissimo_taxonomy', $instance['numview'], $instance );
        $type = apply_filters( 'eventissimo_taxonomy', $instance['type'], $instance );
        if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			echo eventissimo_frontend_taxonomy($numview,$type);
        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['numview'] = strip_tags( $new_instance['numview'] );
        $instance['type'] = strip_tags( $new_instance['type'] );
        
		return $instance;
    }
    function form( $instance ) {
        $defaults = array( 'num' => 5, 'dateview' => '' , 'filter'=>'');
        $instance = wp_parse_args( (array) $instance, $defaults ); 
	   if (isset($instance['title'] ))
        	$title = apply_filters( 'eventissimo_list' , $instance['title'] );
		else
			$title = __('Category or type','eventissimo');
		
		if (isset($instance['numview']))
        	$numview = apply_filters( 'eventissimo_taxonomy' , $instance['numview'] );
		else
			$numview = "true";
		
		if (isset($instance['type']))
        	$type = apply_filters( 'eventissimo_taxonomy' , $instance['type'] );
		else
			$type = "eventscategories";
		
		
        ?>
        
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title");?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

		
		<label for="<?php echo $this->get_field_id( 'numview' ); ?>"><?php _e("View Number","eventissimo");?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'numview' ); ?>" name="<?php echo $this->get_field_name( 'numview' ); ?>">
				<option value="true" <?php if ($numview=="true") echo "selected='selected'"; ?>><?php _e("Yes","eventissimo");?></option>
				<option value="false" <?php if ($numview=="false") echo "selected='selected'"; ?>><?php _e("No","eventissimo");?></option>
			</select>
         
        <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e("View:","eventissimo");?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<option value="eventscategories" <?php if ($type=="eventscategories") echo "selected='selected'"; ?>><?php _e("Event categories","eventissimo");?></option>
				<option value="typeEvents" <?php if ($type=="typeEvents") echo "selected='selected'"; ?>><?php _e("Event types","eventissimo");?></option>
			</select>
           
        <?php
    }
}
function eventissimo_taxonomy_widget() {
    register_widget( "eventissimo_taxonomy" );
}
add_action( 'widgets_init', 'eventissimo_taxonomy_widget' );
?>