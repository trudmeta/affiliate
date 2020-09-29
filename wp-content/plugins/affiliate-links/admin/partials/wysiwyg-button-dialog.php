<?php
/**
 * @var $this Affiliate_Links
 */
?>
<div id="af-link-backdrop" class="af-link" style="display: none;"></div>
<div id="af-link-wrap" style="display: none; margin-top: -200px"
     class="af-link">
    <div id="af-link">
        <div id="link-modal-title"><?php _e( 'Insert Affiliate link', 'affiliate-links' ) ?>
            <button type="button" id="af-link-close" class="af-link">
                <span class="screen-reader-text"><?php _e( 'Close', 'affiliate-links' ) ?></span>
            </button>
        </div>
        <div id="link-selector">
            <div>
                <label>
                    <span><?php _e( 'Affiliate Links', 'affiliate-links' ) ?></span>
                    <select id="links" class="affiliate_links_control"
                            name="link_id" style="width: 100%">
						<?php foreach ( $this->get_links() as $link ): ?>
                            <option data-attr="id"
                                    data-value="<?php echo $link->ID ?>"><?php echo $link->post_title; ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div id="link-options">
                <div>
                    <label>
                        <span><?php _e( 'Add link title', 'affiliate-links' ) ?></span>
                        <input data-attr="title" class="affiliate_links_control"
                               type="text">
                    </label>
                </div>

                <div>
                    <label>
                        <span><?php _e( 'Add link class', 'affiliate-links' ) ?></span>
                        <input data-attr="class" class="affiliate_links_control"
                               type="text">
                    </label>
                </div>
                <div>
                    <label>
                        <span><?php _e( 'Add link anchor', 'affiliate-links' ) ?></span>
                        <input data-attr="anchor"
                               class="affiliate_links_control" type="text">
                    </label>
                </div>

                <div class="link-checkbox">
                    <label for="">
                        <span><?php _e( 'Add rel="nofollow"', 'affiliate-links' ) ?></span>
                        <input data-attr="rel" data-value="nofollow"
                               class="affiliate_links_control" type="checkbox">
                    </label>

                </div>
                <div class="link-checkbox">
                    <label for="">
                        <span><?php _e( 'Add target="_blank', 'affiliate-links' ) ?></span>
                        <input data-attr="target" data-value="_blank"
                               class="affiliate_links_control" type="checkbox">
                    </label>
                </div>
            </div>
            <p class="affiliate_links_proto_html"><a></a></p>
            <p>
                <strong><?php _e( 'Embed Shortcode', 'affiliate-links' ) ?></strong>
            </p>
            <textarea id="af-link-shortcode" readonly spellcheck="false"
                      class="affiliate_links_embed affiliate_links_embed_shortcode">[af_link][/af_link]</textarea>
            <button id="af-link-submit"
                    class="affiliate_links_copy button button-secondary hide-if-no-js"
                    data-source="affiliate_links_embed_shortcode"><?php _e( 'Insert link', 'affiliate-links' ) ?></button>
        </div>
    </div>
</div>