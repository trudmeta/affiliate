<?php
/**
 * @var $this Affiliate_Links_Pro_Stats
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="poststuff" class="af-links-reports-wide">
    <div class="postbox">

		<?php $this->render_view( 'admin-reports-range' ) ?>

		<?php $this->table
			->set_columns( array(
				'legend'  => 'Legend',
				'browser' => 'Browser',
				'hits'    => 'Hits',
			) )
			->set_sortable_columns( array(
				'browser' => array(
					'browser',
					FALSE,
				),
				'hits'    => array( 'hits', FALSE ),
			) )
			->set_table_data( $this->get_browser_data() )
			->prepare_items();
		?>
		<?php if ( count( $this->table->items ) ): ?>
            <div id="col-container">
                <div id="col-right" class="stat-plot">
                    <div class="col-wrap">
						<?php $this->table->display(); ?>
                    </div>
                </div>
                <div id="col-left" class="stat-plot">
                    <div class="col-wrap">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
		<?php else: ?>
            <div class="wrap">
                <p class="chart-prompt"><?php echo __( 'There is no activity for given period.' ); ?></p>
            </div>
		<?php endif; ?>
    </div>
</div>
