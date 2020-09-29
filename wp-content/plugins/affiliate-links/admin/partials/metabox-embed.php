<p class="affiliate_links_proto_html"><a
            href="<?php the_permalink() ?>"><?php the_title() ?></a></p>

<p>
    <strong><?php _e( 'Embed HTML link', 'affiliate-links' ) ?></strong>
</p>
<textarea readonly spellcheck="false"
          class="affiliate_links_embed affiliate_links_embed_html"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></textarea>
<button class="affiliate_links_copy button button-secondary hide-if-no-js"
        data-source="affiliate_links_embed_html"><?php _e( 'Copy', 'affiliate-links' ) ?></button>

<p>
    <strong><?php _e( 'Embed Shortcode', 'affiliate-links' ) ?></strong>
</p>
<textarea readonly spellcheck="false"
          class="affiliate_links_embed affiliate_links_embed_shortcode">[af_link id="<?php echo $post->ID ?>"]<?php the_title() ?>[/af_link]</textarea>
<button class="affiliate_links_copy button button-secondary hide-if-no-js"
        data-source="affiliate_links_embed_shortcode"><?php _e( 'Copy', 'affiliate-links' ) ?></button>