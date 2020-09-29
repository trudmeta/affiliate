<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

$theme_options_container = Container::make( 'theme_options', __( 'Theme options', 'philharmonia' ) )
    ->add_tab( __( 'Header slider', 'philharmonia' ), array(
        Field::make( 'complex', 'crb_header_slider', 'Список' )
            ->add_fields( array(
                Field::make( 'image', 'url', __( 'Background', 'philharmonia' ) )->set_value_type( 'url' )->set_width( 33 ),
                Field::make( 'text', 'title', __( 'Slide title', 'philharmonia' ) ),
                Field::make( 'text', 'text', __( 'Slide text', 'philharmonia' ) ),
                Field::make( 'text', 'link', __( 'Slide link', 'philharmonia' ) )->set_width( 50 ),
                Field::make( 'text', 'link_text', __( 'Slide link text', 'philharmonia' ) )->set_width( 50 ),
            ) )
    ) );

Container::make( 'theme_options', __( 'Footer', 'philharmonia' ) )
    ->set_page_parent( $theme_options_container )  // Название родительской страницы настроек
    ->add_tab( __( 'Footer slider', 'philharmonia' ), array(
        Field::make( 'complex', 'crb_footer_slider', 'Список' )
            ->add_fields( array(
                Field::make( 'image', 'photo', __( 'Photo', 'philharmonia' ) )->set_value_type( 'url' )->set_width( 33 ),
                Field::make( 'text', 'link', __( 'Slide link', 'philharmonia' ) )->set_width( 50 ),
            ) )
    ) )
    ->add_tab( __( 'Footer sidebar 1', 'philharmonia' ), array(
        Field::make( 'textarea', 'crb_footer_sidebar1' )
            ->set_rows( 4 )
    ) )
    ->add_tab( __( 'Footer sidebar 2', 'philharmonia' ), array(
        Field::make( 'textarea', 'crb_footer_sidebar2' )
            ->set_rows( 4 )
    ) );

Container::make( 'theme_options', __( 'Social Links', 'philharmonia' ) )
    ->set_page_parent( $theme_options_container )  // Название родительской страницы настроек
    ->add_tab( __( 'Social Links', 'philharmonia' ), array(
        Field::make( 'complex', 'crb_socials', 'Список' )
            ->set_layout( 'tabbed-horizontal' )
            ->add_fields( array(
                Field::make( 'select', 'crb_select_socials', 'Text alignment' )
                    ->set_width( 50 )
                    ->add_options( array(
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter',
                        'instagram' => 'Instagram',
                        'youtube' => 'Youtube',
                    ) ),
                Field::make( 'text', 'link', __( 'Social link', 'philharmonia' ) )->set_width( 50 ),
            ) )
    ) );


