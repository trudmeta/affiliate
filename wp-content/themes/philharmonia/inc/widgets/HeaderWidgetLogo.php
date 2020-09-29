<?php

class headerWidgetLogo extends WP_Widget {
    /*
     * создание виджета
     */
    function __construct() {
        parent::__construct(
            'header_widget_logo',
            'Header Logo', // заголовок виджета
            array( 'description' => 'Header logo settings' ) // описание
        );
    }

    /*
     * фронтэнд виджета
     */
    public function widget( $args, $instance ) {
        $image1 = get_field('logo_image_1', 'widget_' . $args['widget_id']);
        $image2 = get_field('logo_image_2', 'widget_' . $args['widget_id']);
        $image3 = get_field('logo_image_3', 'widget_' . $args['widget_id']);
        ?>
        <ul class="logoLine">
            <?php if(!empty($image1)): ?>
            <li>
                <a href="/" class="logoPage">
                    <img src="<?php echo $image1['url'] ?>" alt="logo">
                </a>
            </li>
            <?php endif; ?>
            <?php if(!empty($image2)): ?>
            <li>
                <a href="#">
                    <img src="<?php echo $image2['url'] ?>" alt="season">
                </a>
            </li>
            <?php endif; ?>
            <?php if(!empty($image3)): ?>
            <li>
                <a href="#">
                    <img src="<?php echo $image3['url'] ?>" alt="season">
                </a>
            </li>
            <?php endif; ?>
        </ul>
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
function header_widget_logo_load() {
    register_widget( 'headerWidgetLogo' );
}
add_action( 'widgets_init', 'header_widget_logo_load' );