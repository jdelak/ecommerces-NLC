<?php
/**
 * Plugin Name: Latest Posts Widget
 */

add_action( 'widgets_init', 'solopine_latest_news_load_widget' );

function solopine_latest_news_load_widget() {
	register_widget( 'solopine_latest_news_widget' );
}

class solopine_latest_news_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'solopine_latest_news_widget', 'description' => __('A widget that displays your latest posts from all categories or a certain', 'solopine') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'solopine_latest_news_widget' );

		/* Create the widget. */
		parent::__construct( 'solopine_latest_news_widget', __('Rosemary: Latest Posts', 'solopine'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$layout = $instance['layout'];
		$categories = $instance['categories'];
		$number = $instance['number'];
		$counter = $instance['counter'];
		$date = $instance['date'];
		$postids = $instance['postids'];
		
		
		if($postids) {
			$postids = explode(',', $postids);
			$args = array( 'showposts' => $number, 'post_type' => array('post', 'page'), 'post__in' => $postids, 'orderby' => 'post__in', 'ignore_sticky_posts' => 1 );
		} else {
			$args = array( 'showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'cat' => $categories );
		}
		
		$loop = new WP_Query($args);
		if ($loop->have_posts()) :
		
		$number_count = 0;
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>
			
			<?php  while ($loop->have_posts()) : $loop->the_post(); ?>
				<?php $number_count++; ?>
				
				<?php if($layout == 'big_thumb_overlay') : ?>
				
				<div class="side-pop overlay">
				
					<div class="side-pop-img">
						
						<?php if(has_post_thumbnail()) : ?>
						<a href="<?php echo get_permalink() ?>"><?php the_post_thumbnail('misc-thumb'); ?></a>
						<?php else : ?>
						<a href="<?php echo get_permalink() ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/default-misc.png" alt="<?php esc_attr_e('Default', 'solopine'); ?>" /></a>
						<?php endif; ?>
						
						<?php if($counter) : ?><span class="side-count"><?php echo $number_count; ?></span><?php endif; ?>
						
						<div class="side-pop-content">
							<h4><a href="<?php echo get_permalink() ?>"><?php the_title(); ?></a></h4>
							<?php if(!$date) : ?><span class="sp-date"><?php the_time( get_option('date_format') ); ?></span><?php endif; ?>
						</div>
					</div>
				
				</div>
				
				<?php else : ?>
				<div class="side-pop <?php if($layout == 'small_thumb') : ?>list<?php endif; ?>">
					
					<div class="side-pop-img">
						<?php if(has_post_thumbnail()) : ?>
						<a href="<?php echo get_permalink() ?>"><?php the_post_thumbnail('misc-thumb'); ?></a>
						<?php else : ?>
						<a href="<?php echo get_permalink() ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/default-misc.png" alt="<?php esc_attr_e('Default', 'solopine'); ?>" /></a>
						<?php endif; ?>
						<?php if($counter) : ?><span class="side-count"><?php echo $number_count; ?></span><?php endif; ?>
					</div>
					
					<div class="side-pop-content">
						<h4><a href="<?php echo get_permalink() ?>"><?php the_title(); ?></a></h4>
						<?php if(!$date) : ?><span class="sp-date"><?php the_time( get_option('date_format') ); ?></span><?php endif; ?>
					</div>
				
				</div>
				<?php endif; ?>
			
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			<?php /* After widget (defined by themes). */ echo $after_widget; ?>
			<?php endif; ?>
			
		<?php

		
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['layout'] = $new_instance['layout'];
		$instance['categories'] = $new_instance['categories'];
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['date'] = strip_tags( $new_instance['date'] );
		$instance['counter'] = strip_tags( $new_instance['counter'] );
		$instance['postids'] = strip_tags( $new_instance['postids'] );		
		
		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Latest Posts', 'solopine'), 'number' => 3, 'categories' => '', 'layout' => '', 'date' => false, 'counter' => false, 'postids' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'solopine'); ?></label>
			<input  type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  />
		</p>
		
		<!-- Layout -->
		<p>
		<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e( 'Choose Layout', 'solopine' ); ?>:</label> 
		<select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>" class="widefat categories" style="width:100%;">
			<option value='small_thumb' <?php if ('small_thumb' == $instance['layout']) echo 'selected="selected"'; ?>><?php _e( 'Small Thumbnail', 'solopine' ); ?></option>
			<option value='big_thumb' <?php if ('big_thumb' == $instance['layout']) echo 'selected="selected"'; ?>><?php _e( 'Big Thumbnail', 'solopine' ); ?></option>
			<option value='big_thumb_overlay' <?php if ('big_thumb_overlay' == $instance['layout']) echo 'selected="selected"'; ?>><?php _e( 'Big Thumbnail w/ Overlay', 'solopine' ); ?></option>
		</select>
		</p>
		
		<!-- Category -->
		<p>
		<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e( 'Filter by Category', 'solopine' ); ?>:</label> 
		<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
			<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>All categories</option>
			<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
			<?php foreach($categories as $category) { ?>
			<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
			<?php } ?>
		</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'postids' ); ?>"><?php _e('Post IDs (separate with comma):', 'solopine'); ?></label>
			<input  type="text" class="widefat" id="<?php echo $this->get_field_id( 'postids' ); ?>" name="<?php echo $this->get_field_name( 'postids' ); ?>" value="<?php echo $instance['postids']; ?>" size="3" />
			<small><?php esc_html_e( 'Display specific posts using post IDs. This option will override the category option above.', 'solopine' ); ?></small>
		</p>
		
		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of posts to show:', 'solopine'); ?></label>
			<input  type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'counter' ); ?>"><?php _e( 'Add Numbering', 'solopine' ); ?>:</label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'counter' ); ?>" name="<?php echo $this->get_field_name( 'counter' ); ?>" <?php checked( (bool) $instance['counter'], true ); ?> />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Hide Date', 'solopine' ); ?>:</label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" <?php checked( (bool) $instance['date'], true ); ?> />
		</p>


	<?php
	}
}

?>