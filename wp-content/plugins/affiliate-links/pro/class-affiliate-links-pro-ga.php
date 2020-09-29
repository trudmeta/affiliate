<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
require_once AFFILIATE_LINKS_PLUGIN_DIR . 'admin/class-affiliate-links-settings.php';

class Affiliate_Links_Pro_Google_Analytics {

	public function __construct() {
		if ( Affiliate_Links_Settings::get_option( 'enable_ga' ) ) {
			add_action( 'wp_footer', array( $this, 'tracking' ) );
		}
	}

	public function tracking() {
		?>
        <script type="text/javascript">
            (function ($) {
                $('a').on('click', function () {
                    var hrefLink = $(this).attr('href') || '';
                    var searchSlug = "/<?php echo Affiliate_Links::$slug ?>/";
                    var ga = window["<?php echo Affiliate_Links_Settings::get_option( 'ga_global_object' ) ?>"];
                    if (hrefLink.toLowerCase().indexOf(searchSlug) >= 0) {
                        if (typeof(ga) == 'function') {
                            ga('send', {
                                hitType: 'event',
                                eventCategory: "<?php echo Affiliate_Links_Settings::get_option( 'ga_ev_category' ) ?>",
                                eventAction: "<?php echo Affiliate_Links_Settings::get_option( 'ga_ev_label' ) ?>",
                                eventLabel: "<?php echo Affiliate_Links_Settings::get_option( 'ga_ev_label' ) ?>",
                                transport: 'beacon'
                            });
                        }
                    }
                });
            })(jQuery);
        </script>
		<?php
	}

}