<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PHILHARMONIA
 */

?>

<footer>
    <?php
    $slides = carbon_get_theme_option( 'crb_footer_slider' );
    if(!empty($slides)): ?>
        <h4 class="tittleBlock text-center">our partners.</h4>

        <div class="owl-carousel owl-theme logoList actSliderFooter">
        <?php
        $n = 1;
        foreach($slides as $slide): ?>
        <div class="item">
            <a href="<?php echo $slide['link'] ?? '#' ?>">
                <img src="<?php echo $slide['photo'] ?>" alt="logo<?php echo $n; ?>" class="logo<?php echo $n++; ?>">
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="back-to-top">
        <img src="<?php echo get_template_directory_uri() ?>/images/upArroy.png" alt="up">
    </div>
    <?php
    $footerSidebar1 = carbon_get_theme_option( 'crb_footer_sidebar1' );
    echo $footerSidebar1;

    $socials = carbon_get_theme_option( 'crb_socials' );

    if(!empty($socials)): ?>
    <ul class="footerListSoc">
        <?php foreach($socials as $social): ?>
        <li>
            <a href="<?php echo $social['link'] ?? '#' ?>">
                <span class="icon-social icon-<?php echo $social['crb_select_socials']?>">
                    <i></i>
                </span>
            </a>
        </li>
        <?php endforeach; ?>
        <li><a href="#"><img src="<?php echo get_template_directory_uri() ?>/images/search.png" alt="search"></a></li>
    </ul>
    <?php endif; ?>

    <?php
    $footerSidebar2 = carbon_get_theme_option( 'crb_footer_sidebar2' );
    echo $footerSidebar2;
    ?>

</footer>


<?php wp_footer(); ?>

</body>
</html>
