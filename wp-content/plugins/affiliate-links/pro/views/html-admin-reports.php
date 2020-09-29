<?php
/**
 * @var $this Affiliate_Links_Pro_Stats
 */
?>
<div class="wrap">
    <h2 class="nav-tab-wrapper">
		<?php foreach ( $this->get_setting_tabs() as $name => $label ): ?>
            <a href="<?php echo admin_url( 'edit.php?post_type=affiliate-links&page=reports&tab=' . $name ) ?>"
               class="nav-tab <?php echo $this->get_current_tab() == $name ? 'nav-tab-active' : '' ?>"><?php echo $label ?></a>
		<?php endforeach; ?>
    </h2>
	<?php do_action( 'af_link_report_tab_' . $this->get_current_tab(), $this->get_current_tab() ); ?>
	<div>
		<p>
            <a href="<?php print wp_nonce_url( admin_url( 'edit.php?post_type=affiliate-links&page=reports' ), 'delete_stats', 'af_delete_nonce' ) ?>"
               onclick="return confirm('<?php _e( 'Are you sure you want to delete all stats? This cannot be undone.', 'affiliate-links' ) ?>');">
                <?php _e( 'Delete', 'affiliate-links' ) ?>
            </a>
            <?php _e( 'all stats data', 'affiliate-links' ) ?>
        </p>
	</div>
</div>