<?php
/*
Template Name: Main page
*/

get_header();
?>

    <section class="sliderBlock">
        <?php
        $slides = carbon_get_theme_option( 'crb_header_slider' );
        if(!empty($slides)): ?>
            <div class="owl-carousel owl-theme actSlider">
                <?php
                $n = 1;
                foreach($slides as $slide): ?>
                    <div class="item mainSlider<?php echo $n; ?>">
                        <div class="sliderStyle slider<?php echo $n; ?>">
                            <div class="containerSlider">
                                <div class="blockNow">
                                    <h3><?php echo $slide['title'] ?></h3>
                                    <p class="textSlider"><?php echo $slide['text'] ?></p>
                                    <a href="<?php echo $slide['link'] ?>" class="nowLink"><?php echo $slide['link_text'] ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $n++; endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

<?php
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 7
);
$loop = new WP_Query( $args );

if ( $loop->have_posts() ) { ?>
    <section class="articleSpaceBlock container">
        <h4 class="tittleBlock padleft"><?php echo __( 'WHAT’S ON', 'philharmonia' ); ?></h4>

        <?php
        $n=0;
        while ( $loop->have_posts() ) : $loop->the_post(); ?>

            <div class="articleSpace">
                <div class="imgBlock">
                    <?php the_post_thumbnail(); ?>
                </div>

                <div class="contentText">
                    <h5 class="titleArticle"><?php the_title(); ?></h5>
                    <?php
                    the_content();
                    $price = get_post_meta( get_the_ID(), '_regular_price', true);
                    $price_sale = get_post_meta( get_the_ID(), '_sale_price', true);
                    if ($price_sale !== "") {
                        $price = $price_sale;
                    }
                    if(!empty($price)){
                        $price = '$'.$price;
                    }
                    ?>
                    <p>Tickets: <?php echo $price; ?> plus booking fees</p>
                    <a href="#" class="moreInfo">MORE INFO</a>
                    <a href="#" class="bookNow">BOOK NOW</a>
                </div>
            </div>

            <?php
            $n++;
            if($n == ($loop->found_posts-1) ){
                $loop->the_post();
                break;
            }
        endwhile; ?>
        <h4 class="tittleBlock padleft"><?php echo __( 'SING WITH US', 'philharmonia' ); ?></h4>

        <div class="articleSpace">
            <div class="imgBlock">
                <?php the_post_thumbnail(); ?>
            </div>

            <div class="contentText">
                <h5 class="titleArticle"><?php the_title(); ?></h5>
                <?php
                the_content();
                $price = get_post_meta( get_the_ID(), '_regular_price', true);
                $price_sale = get_post_meta( get_the_ID(), '_sale_price', true);
                if ($price_sale !== "") {
                    $price = $price_sale;
                }
                if(!empty($price)){
                    $price = '$'.$price;
                }
                ?>
                <p>Tickets: <?php echo $price; ?> plus booking fees</p>
                <a href="#" class="moreInfo">MORE INFO</a>
                <a href="#" class="bookNow">BOOK NOW</a>
            </div>
        </div>
    </section>
    <?php
} else {
    echo __( 'No products found' );
}
wp_reset_postdata();
?>

    <section class="youtube">
        <h4 class="tittleBlock padleft"><?php echo __( 'OUR LATEST YOUTUBE VIDEO', 'philharmonia' ); ?></h4>
        <?php
            $youtube = get_field('main_youtube_link', get_the_ID()) ?? "https://www.youtube.com/embed/73h_s4SAAHs";
        ?>
        <div class="video_container" data-youtube-src="<?php echo $youtube; ?>">
            <buttom class="play_button">
                <img src="<?php echo get_template_directory_uri() ?>/images/page/buttonStart.png" alt="start Play">
            </buttom>
            <div class="poster" >
                <?php
                $videoYoutube = get_field('main_youtube', get_the_ID())['url'] ?? get_template_directory_uri() ."images/page/youTube.png";
                ?>
                <img src="<?php echo $videoYoutube; ?>" alt="videoYoutube">
            </div>
        </div>
    </section>

    <section class="ourEvent container">
        <div class="row">
            <div class="col-md-4">
                <h4 class="tittleBlock"><?php echo __( 'OUR SHOP', 'philharmonia' ); ?></h4>
                <a href="/shop">
                    <img src="<?php echo get_template_directory_uri() ?>/images/fest.jpg" alt="fest">
                </a>
            </div>
            <div class="col-md-4">
                <h4 class="tittleBlock"><?php echo __( 'SUPPORT US', 'philharmonia' ); ?></h4>
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() ?>/images/concert.png" alt="fest">
                </a>
            </div>
            <div class="col-md-4">
                <a href="#">
                    <div class="concertBlockText">
                        <?php
                        $concertblocktext = get_field('concertblocktext', get_the_ID()) ?? "";
                        echo $concertblocktext;
                        ?>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="subscribe">
        <h4 class="tittleBlock text-center"><?php echo __( 'SUBSCRIBE.', 'philharmonia' ); ?></h4>
        <div class="formEmail">
            <form action="index.php" post="GET">
                <input type="email" name="mailUser" placeholder="EMAIL" id="email" class="postSendStyle h5-email"required="required">
                <button type="button">SIGN UP</button>
            </form>
        </div>
    </section>

    <?php
    $our_instagram = get_field('our_instagram', get_the_ID())['url'];
    if(!empty($our_instagram)): ?>
    <section class="instagramBlock">
        <h4 class="tittleBlock text-center"><?php echo __( 'OUR INSTAGRAM.', 'philharmonia' ); ?></h4>
        <div class="wiget">
            <img src="<?php echo $our_instagram ?>">
        </div>
    </section>
    <?php endif; ?>

    <?php
    $socials = carbon_get_theme_option( 'crb_socials' );

    if(!empty($socials)): ?>
    <section class="socBlock">
        <ul class="socList">
            <?php foreach($socials as $social): ?>
            <li>
                <a href="<?php echo $social['link'] ?? '#' ?>">
                    <span class="icon-social icon-<?php echo $social['crb_select_socials']?> big">
                        <i></i>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

<?php
get_footer();
