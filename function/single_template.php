<?php
function eventissimo_single_events($single_template) {
     global $post;

     if ($post->post_type == 'events') {
          $single_template = BASE_URL . '/pages/events-template-single.php';
     }
     return $single_template;
}
add_filter( "single_template", "eventissimo_single_events" );

?>