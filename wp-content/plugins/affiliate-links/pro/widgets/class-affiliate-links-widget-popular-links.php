<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Affiliate_Links_Pro_Popular_Links extends WP_Widget {

	protected $stat_instance;

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_affiliate_links_populart',
			'description' => __( 'The most popular affiliate links on your site', 'affiliate-links' ),
		);

		$this->stat_instance = Affiliate_Links_Pro_Stats::get_instance();

		parent::__construct( 'affiliate-links-popular', __( 'Popular Affiliate Links', 'affiliate-links' ), $widget_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	}

	public function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_affiliate_links_popular', 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];

			return;
		}

		ob_start();
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Popular Links', 'affiliate-links' ) : $instance['title'], $instance, $this->id_base );

		$links_ids = $this->stat_instance->get_popular_links( $instance );

		if ( ! empty( $links_ids ) ) :
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
            <ul>
				<?php foreach ( $links_ids as $id ): ?>
                    <li>
                        <a href="<?php the_permalink( $id ) ?>"
                           title="<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>">
							<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
			<?php
			echo $args['after_widget'];
		endif;

		$cache[ $args['widget_id'] ] = ob_get_flush();
		wp_cache_set( 'widget_affiliate_links_recent', $cache, 'widget' );

	}

	public function update( $new_instance, $old_instance ) {

		$instance           = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = $new_instance['number'];
		$instance['cat']    = $new_instance['cat'];

		$this->flush_widget_cache();

		delete_option( 'widget_affiliate_links_popular' );

		return $instance;

	}

	public function flush_widget_cache() {

		wp_cache_delete( 'widget_affiliate_links_popular', 'widget' );

	}

	public function form( $instance ) {

		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? esc_attr( $instance['number'] ) : 5;
		$cat    = isset( $instance['cat'] ) ? $instance['cat'] : 0;
		$terms  = get_terms( Affiliate_Links::$taxonomy );
		?>
        <p>
            <label
                    for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title', 'affiliate-links' ) ?>
                :</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ) ?>"
                   name="<?php echo $this->get_field_name( 'title' ) ?>"
                   type="text"
                   value="<?php echo $title ?>">
        </p>

        <p>
            <label>
				<?php _e( 'Category', 'affiliate-links' ) ?>:
                <select name="<?php echo $this->get_field_name( 'cat' ) ?>"
                        id="<?php echo $this->get_field_id( 'cat' ) ?>"
                        class="postform">
                    <option class="level-0"
                            value="0" <?php selected( $cat, 1 ) ?>><?php _e( 'All', 'affiliate-links' ) ?></option>
					<?php
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							?>
                            <option class="level-0"
                                    value="<?php echo $term->term_id ?>" <?php selected( $cat, $term->term_id ) ?>><?php echo $term->name ?></option>
							<?php
						}
					}
					?>
                </select>
            </label>
        </p>

        <p>
            <label
                    for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of posts to show', 'affiliate-links' ) ?>
                :</label>
            <input class="tiny-text"
                   id="<?php echo $this->get_field_id( 'number' ) ?>"
                   name="<?php echo $this->get_field_name( 'number' ) ?>"
                   type="number"
                   min="-1"
                   value="<?php echo $number ?>"
                   size="3">
            <span
                    class="description"><?php _e( 'Enter "-1" to show all links', 'affiliate-links' ) ?></span>
        </p>
		<?php

	}

}