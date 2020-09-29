<?php
/**
 * @var $this  Affiliate_Links_Pro_Stats
 */
?>

<!-- browser statistic -->
<?php if ( count( $this->get_link_browsers_data() ) ): ?>
    <table cellpadding="3" cellspacing="2"
           style="float: left; margin: 20px 10px 10px 0;">
        <tbody>
        <tr class="alternate">
            <th><?php _e( 'Browser' ) ?></th>
            <th><?php _e( 'Sessions' ) ?></th>
        </tr>
		<?php foreach ( $this->get_link_browsers_data() as $browser ): ?>
            <tr class="alternate">
                <td><?php echo AFL_PRO()->get_browser_title( $browser['name'] ) ?></td>
                <td class="stats-number"><?php echo $browser['hits'] ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


<!-- os statistic -->
<?php if ( count( $this->get_link_data( 'os' ) ) ): ?>
    <table cellpadding="3" cellspacing="2"
           style="float: left; margin: 20px 10px 10px 0;">
        <tbody>
        <tr class="alternate">
            <th><?php _e( 'OS' ) ?></th>
            <th><?php _e( 'Hits' ) ?></th>
        </tr>
		<?php foreach ( $this->get_link_data( 'os' ) as $link ): ?>
            <tr class="alternate">
                <td><?php echo esc_html( $link['os'] ) ?></td>
                <td class="stats-number"><?php echo $link['hits'] ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- platform statistic -->
<?php if ( count( $this->get_link_data( 'platform' ) ) ): ?>
    <table cellpadding="3" cellspacing="2"
           style="float: left; margin: 20px 10px 10px 0;">
        <tbody>
        <tr class="alternate">
            <th><?php _e( 'Platform' ) ?></th>
            <th><?php _e( 'Hits' ) ?></th>
        </tr>
		<?php foreach ( $this->get_link_data( 'platform' ) as $link ): ?>
            <tr class="alternate">
                <td><?php echo esc_html( $link['platform'] ) ?></td>
                <td class="stats-number"><?php echo $link['hits'] ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- language statistic -->
<?php if ( count( $this->get_link_data( 'lang' ) ) ): ?>
    <table cellpadding="3" cellspacing="2"
           style="float: left; margin: 20px 10px 10px 0;">
        <tbody>
        <tr class="alternate">
            <th><?php _e( 'Language' ) ?></th>
            <th><?php _e( 'Hits' ) ?></th>
        </tr>
		<?php foreach ( $this->get_link_data( 'lang' ) as $link ): ?>
            <tr class="alternate">
                <td><?php echo esc_html( $link['lang'] ) ?></td>
                <td class="stats-number"><?php echo $link['hits'] ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- referrer statistic -->
<?php if ( count( $this->get_link_data( 'referer' ) ) ): ?>
    <table cellpadding="3" cellspacing="2"
           style="float: left; margin: 20px 10px 10px 0;">
        <tbody>
        <tr class="alternate">
            <th><?php _e( 'Referrer link' ) ?></th>
            <th><?php _e( 'Hits' ) ?></th>
        </tr>
		<?php foreach ( $this->get_link_data( 'referer' ) as $link ): ?>
            <tr class="alternate">
                <td>
					<?php if ( 'Direct Entry' == $link['referer'] ): ?>
						<?php echo $link['referer'] ?>
					<?php else: ?>
                        <a href="<?php echo esc_url( $link['referer'] ) ?>"><?php echo esc_url( $link['referer'] ) ?></a>
					<?php endif; ?>
                </td>
                <td class="stats-number"><?php echo $link['hits'] ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
