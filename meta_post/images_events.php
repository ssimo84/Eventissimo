<?php
/**
 * Define the custom box for images gallery events.
 */

$prefix = 'eventissimo_';
 
$meta_box_eventissimo_images = array(
  'id' => 'eventissimo-meta-box-images',
	'title' =>  __( 'Gallery Images Event', 'eventissimo' ),
	'page' => 'eventissimo',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(  "name" => '',
				"desc" => '',
				"type" => "attachments",
				'std' => ''
			)		
	)	
);
 
function eventissimo_add_imagesbox() {
    add_meta_box(
        'eventimages',
        __( 'Gallery', 'eventissimo' ),
        'eventissimo_images_field',
        'events','side', 'core'
    );

}
add_action( 'add_meta_boxes', 'eventissimo_add_imagesbox' );
/**
 * Callback function to show fields in meta box.
 */
function eventissimo_images_field() {
	global $meta_box_eventissimo_images, $post;
 	
echo '<p style="padding:10px 0 0 0;">'.__( 'These settings enable you to manage the gallery of this event. Upload your images and use "Manage Images" to edit, reorder and delete images.', 'eventissimo' ).'</p>';
	// Use nonce for verification
	echo '<input type="hidden" name="eventissimo_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	?>
	<table class="form-table eventissimo-custom-table">
		<tr style="border-top:1px solid #eeeeee;">
			<td>				
				<div id="eventissimo_images_container">
					<ul class="eventissimo_images">
						<?php
							if ( metadata_exists( 'post', $post->ID, '_eventissimo_image_gallery' ) ) {
								$eventissimo_image_gallery = get_post_meta( $post->ID, '_eventissimo_image_gallery', true );
							} else {
								// Backwards compat
								$attachment_ids = array_filter( array_diff( get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' ), array( get_post_thumbnail_id() ) ) );
								$eventissimo_image_gallery = implode( ',', $attachment_ids );
							}
							

							$attachments = array_filter( explode( ',', $eventissimo_image_gallery ) );
							$thumbs = array();
							if ( $attachments ) {
								foreach ( $attachments as $attachment_id ) {
									echo '<li class="image" style="float:left" data-attachment_id="' . $attachment_id . '">
										' . wp_get_attachment_image( $attachment_id, array(80,80) ) . '
										<ul class="actions">
											<li><a href="#" class="delete" title="' . __( 'Delete image', 'eventissimo' ) . '">' . __( 'Delete', 'eventissimo' ) . '</a></li>
										</ul>
									</li>';
									$thumbs[$attachment_id] = wp_get_attachment_image( $attachment_id, 'thumbnail' );
								}
							}								
						?>
					</ul>
					<input type="hidden" id="eventissimo_image_gallery" name="eventissimo_image_gallery" value="<?php echo esc_attr( $eventissimo_image_gallery ); ?>" />
				</div>
				<p class="add_eventissimo_images hide-if-no-js">
					<a href="#"><?php _e( 'Add images', 'eventissimo' ); ?></a>
				</p>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						// Uploading files
						var eventissimo_gallery_frame;
						var $image_gallery_ids = $('#eventissimo_image_gallery');
						var $eventissimo_images = $('#eventissimo_images_container ul.eventissimo_images');

						jQuery('.add_eventissimo_images').on( 'click', 'a', function( event ) {

							var $el = $(this);
							var attachment_ids = $image_gallery_ids.val();

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( eventissimo_gallery_frame ) {
								eventissimo_gallery_frame.open();
								return;
							}

							// Create the media frame.
							eventissimo_gallery_frame = wp.media.frames.downloadable_file = wp.media({
								// Set the title of the modal.
								title: '<?php _e( 'Add Images to Gallery', 'eventissimo' ); ?>',
								button: {
									text: '<?php _e( 'Add to gallery', 'eventissimo' ); ?>',
								},
								multiple: true
							});

							// When an image is selected, run a callback.
							eventissimo_gallery_frame.on( 'select', function() {

								var selection = eventissimo_gallery_frame.state().get('selection');

								selection.map( function( attachment ) {

									attachment = attachment.toJSON();

									if ( attachment.id ) {
										attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

										$eventissimo_images.append('\
											<li class="image" data-attachment_id="' + attachment.id + '">\
												<img src="' + attachment.sizes.thumbnail.url + '" />\
												<ul class="actions">\
													<li><a href="#" class="delete" title="<?php _e( 'Delete image', 'eventissimo' ); ?>"><?php _e( 'Delete', 'eventissimo' ); ?></a></li>\
												</ul>\
											</li>');
									}

								} );

								$image_gallery_ids.val( attachment_ids );
							});

							// Finally, open the modal.
							eventissimo_gallery_frame.open();
						});

						// Image ordering
						$eventissimo_images.sortable({
							items: 'li.image',
							cursor: 'move',
							scrollSensitivity:40,
							forcePlaceholderSize: true,
							forceHelperSize: false,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'wc-metabox-sortable-placeholder',
							start:function(event,ui){
								ui.item.css('background-color','#f6f6f6');
							},
							stop:function(event,ui){
								ui.item.removeAttr('style');
							},
							update: function(event, ui) {
								var attachment_ids = '';

								$('#eventissimo_images_container ul li.image').css('cursor','move').each(function() {
									var attachment_id = jQuery(this).attr( 'data-attachment_id' );
									attachment_ids = attachment_ids + attachment_id + ',';
								});

								$image_gallery_ids.val( attachment_ids );
							}
						});

						// Remove images
						$('#eventissimo_images_container').on( 'click', 'a.delete', function() {

							$(this).closest('li.image').remove();

							var attachment_ids = '';

							$('#eventissimo_images_container ul li.image').css('cursor','move').each(function() {
								var attachment_id = jQuery(this).attr( 'data-attachment_id' );
								attachment_ids = attachment_ids + attachment_id + ',';
							});

							$image_gallery_ids.val( attachment_ids );

							return false;
						} );

					});
				</script>
			</table>
<?php		
}


function eventissimo_clean( $var ) {
	return sanitize_text_field( $var );
}

/**
 * Save data from meta box.
 */
add_action( 'save_post', 'eventissimo_save_gallery' );
function eventissimo_save_gallery($post_id) {
	global $meta_box_eventissimo_images,$post;
	if (isset($post->ID)) {
		// Saving images from metabox
		$attachment_ids = array_filter( explode( ',', eventissimo_clean( $_POST['eventissimo_image_gallery'] ) ) );
		update_post_meta( $post_id, '_eventissimo_image_gallery', implode( ',', $attachment_ids ) );
	 
		if ( isset($_POST['eventissimo_meta_box_nonce'])) {
			// verify nonce
			if (!wp_verify_nonce($_POST['eventissimo_meta_box_nonce'], basename(__FILE__))) {
				return $post_id;
			}
		 
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
		 
			// check permissions
			if ('events' == $_POST['post_type']) {
				if (!current_user_can('edit_post', $post_id)) {
					return $post_id;
				}
			}
			
		}
	}
}