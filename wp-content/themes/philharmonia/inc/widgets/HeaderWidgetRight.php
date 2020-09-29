<?php

class headerWidgetRight extends WP_Widget {
    /*
     * создание виджета
     */
    function __construct() {
        parent::__construct(
            'header_widget_right',
            'Header right sidebar', // заголовок виджета
            array( 'description' => 'Header right sidebar settings' ) // описание
        );
    }

    /*
     * фронтэнд виджета
     */
    public function widget( $args, $instance ) { ?>
        <div class="topHeaderBlock">
            <ul class="headerNav">
                <li>
                    <a href="/shop" class="shop"><?php echo get_field('header_shop', 'widget_' . $args['widget_id']) ?? 'Shop / Cart'; ?></a>
                </li>
                <li>
                    <ul class="headerListSoc">
                        <li><div class="sendSearch"></div></li>
                        <?php
                        $socials = carbon_get_theme_option( 'crb_socials' );
                        if(!empty($socials)): ?>
                        <?php foreach($socials as $social): ?>
                            <li>
                                <a href="<?php echo $social['link'] ?? '#' ?>">
                                    <span class="icon-social icon-<?php echo $social['crb_select_socials']?>">
                                        <i></i>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php
                        else: ?>
                        <li>
                            <a href="#">
                                <img src="<?php echo get_field('header_socials_twitter', 'widget_' . $args['widget_id'])['url'] ?? ''; ?>" alt="twitter">
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <img src="<?php echo get_field('header_socials_instagram', 'widget_' . $args['widget_id'])['url'] ?? ''; ?>" alt="instagram">
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <img src="<?php echo get_field('header_socials_youtube', 'widget_' . $args['widget_id'])['url'] ?? ''; ?>" alt="youtube">
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>
                    <a href="#"><?php echo get_field('header_donate', 'widget_' . $args['widget_id']) ?? 'Donate'; ?></a>
                </li>
            </ul>
            <p class="headerText">
                <?php echo get_field('header_right_text', 'widget_' . $args['widget_id']) ?? ''; ?>
            </p>
        </div>
        <?php
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

    }

}

/*
 * регистрация виджета
 */
function header_widget_right_load() {
    register_widget( 'headerWidgetRight' );
}
add_action( 'widgets_init', 'header_widget_right_load' );