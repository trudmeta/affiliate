<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Affiliate_Links_Pro_Shortcodes {

	private $stat_instance;

	public function __construct() {
		$this->stat_instance = Affiliate_Links_Pro_Stats::get_instance();
		add_shortcode( 'af_links_popular', array( $this, 'popular_links' ) );
		add_shortcode( 'af_links_recent', array( $this, 'recent_links' ) );
	}

	function popular_links( $atts ) {
		$options = shortcode_atts( array(
			'title'    => __( 'Popular Links' ),
			'number'   => 5,
			'category' => 0,
		), $atts );
		?>
		<?php $links = $this->stat_instance->get_popular_links( $options ) ?>
		<?php if ( ! empty( $links ) ): ?>
            <section>
                <h2 class="title"><?php echo $options['title'] ?></h2>
                <ul>
					<?php foreach ( $links as $id ): ?>
                        <li>
                            <a href="<?php the_permalink( $id ) ?>"
                               title="<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>">
								<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>
                            </a>
                        </li>
					<?php endforeach; ?>
                </ul>
            </section>
		<?php endif;
	}

	function recent_links( $atts ) {
		$options = shortcode_atts( array(
			'title'    => __( 'Recent Links' ),
			'number'   => 5,
			'category' => 0,
		), $atts );
		?>
		<?php $links = $this->stat_instance->get_recent_links( $options ) ?>
		<?php if ( ! empty( $links ) ): ?>
            <section>
                <h2 class="title"><?php echo $options['title'] ?></h2>
                <ul>
					<?php foreach ( $links as $id ): ?>
                        <li>
                            <a href="<?php the_permalink( $id ) ?>"
                               title="<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>">
								<?php echo esc_attr( get_the_title( $id ) ? get_the_title( $id ) : $id ); ?>
                            </a>
                        </li>
					<?php endforeach; ?>
                </ul>
            </section>
		<?php endif;
	}

}