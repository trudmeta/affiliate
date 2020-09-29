<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PHILHARMONIA
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="container">
    <div class="row">
        <div class="col-md-7 col-sm-7">
        <?php dynamic_sidebar('header-logo'); ?>
        </div>
        <div class="col-md-5 col-sm-5">
            <?php dynamic_sidebar('header-right-sidebar'); ?>
            <div class="searchRow">
                <form role="search" method="get"  action="<?php esc_url(home_url( '/' )) ?>">
                    <div class="searchHeaderBlock">
                        <div class="small-8 columns">
                            <input type="text" value="" name="s"  placeholder="Search" class="inputSearch" autofocus/>
                        </div>
                        <div class="small-4 columns">
                            <input type="submit" value="Search" class="buttonSearch">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed icon-menu menuActive" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse menu">
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'main_menu',
                    'menu_id'         => 'primary-menu',
                    'menu_class' => 'nav navbar-nav',
                )
            );
            ?>
        </div>
    </nav>
</header>
