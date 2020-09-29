<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * The Affiliate Links Shortcode Class.
 */
class Affiliate_Links_Shortcode {

	public function __construct() {

		add_shortcode( 'af_link', array( $this, 'shortcode' ) );

	}

	public function shortcode( $atts, $content = NULL ) {

		$a = shortcode_atts(
			array(
				'href'   => '#',
				'rel'    => FALSE,
				'target' => FALSE,
				'title'  => FALSE,
				'class'  => FALSE,
				'id'     => FALSE,
			),
			$atts, 'af_link'
		);

		$href = $a['href'];

		if ( ! empty( $a['id'] ) AND get_post( $a['id'] ) ) {
			$href = esc_url( get_post_permalink( $a['id'] ) );
		}

		$link_attrs = sprintf( ' %s="%s"', 'href', $href )
		              . ( $a['rel'] ? ' rel="nofollow"' : '' )
		              . ( $a['target'] ? ' target="_blank"' : '' )
		              . $this->format_attr( 'title', $a )
		              . $this->format_attr( 'class', $a );

		ob_start();
		?>
        <a<?php echo $link_attrs ?>><?php echo $content ?></a>
		<?php

		return ob_get_clean();

	}

	protected function format_attr( $key, $atts ) {
		if ( $atts[ $key ] ) {
			return sprintf( ' %s="%s"', $key, esc_attr( $atts[ $key ] ) );
		}
	}

}

$Affiliate_Links_Shortcode = new Affiliate_Links_Shortcode();