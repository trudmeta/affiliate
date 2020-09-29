<div class="wrap">
    <?php if ( ! empty( $this->messages['message'] ) ): ?>
        <div id="message" class="updated">
            <p><?php echo $this->messages['message'] ?></p></div>
	<?php endif; ?>

	<?php if ( ! empty( $this->messages['notice'] ) ): ?>
        <div id="notice" class="error">
            <p><?php echo $this->messages['notice'] ?></p></div>
	<?php endif; ?>

    <h1><?php _e( 'Links Import/Export', 'affiliate-links' ) ?></h1>
    <p><?php _e( 'Using this tool you can import/export all affiliate links', 'affiliate-links' ) ?></p>

    <h2 class="nav-tab-wrapper">
        <?php foreach ( static::$tabs as $name => $label ): ?>
            <a href="<?php echo admin_url( 'edit.php?post_type=affiliate-links&page=impexp&tab=' . $name ) ?>"
               class="nav-tab <?php echo $this->get_current_tab() == $name ? 'nav-tab-active' : '' ?>"><?php echo $label['title'] ?></a>
        <?php endforeach; ?>
    </h2>
    <?php do_action( 'af_link_ie_tab_' . $this->get_current_tab(), $this->get_current_tab() ); ?>

    <div class="form-wrap form-wrap-impexp">
        <?php
        $current_tab = $this->get_current_tab();

        echo '<nav class="nav-tab-wrapper">';
        foreach ( static::$tabs[$current_tab]['navs'] as $key => $nav ) {
            $active = $key == 'affiliate_links'? ' nav-tab-active' : '';
            echo '<a href="#'.$current_tab.'_'.$key.'" class="impexp-nav-tab nav-tab '. $key .$active.'">'.$nav.'</a>';
        }
        echo '</nav>';

        echo '<div class="form-table">';
        foreach ( static::$tabs[$current_tab]['navs'] as $key => $nav ) {
            $active = $key == 'affiliate_links'? ' nav-tab-item-active' : '';
            if($current_tab == 'import'){ // import
                echo '<div id="'.$current_tab.'_'.$key.'" class="nav-tab-item'.$active.'">';
                if($key == 'affiliate_links'){
                    echo '<form action="" method="post" enctype="multipart/form-data">';
                    wp_nonce_field('impexp_links', 'impexp_links_nonce');

                    foreach ( static::$tabs[$current_tab]['fields'] as $field ) {
                        if( isset( $field['nav']) && $key == $field['nav'] ){
                            $this->render_fields( $field );
                        }
                    }
                    echo '<p><input type="submit" class="button button-primary" name="import_'.$key.'_submit" value="'.__( static::$tabs[$current_tab]['title'], 'affiliate-links').' '.__( $nav, 'affiliate-links').'"></p>';
                    echo '</form>';
                }elseif($key == 'affiliate_history'){ //загруженные csv
                    echo '<div class="history wrap">';
                    date_default_timezone_set('Europe/Kiev');
                    if(!empty($_GET['file'])){
                        $this->import->csvView($_GET['file']);
                    }else{
                        $files = list_files( ABSPATH . 'wp-content/uploads/affiliate');
                        usort($files, function($a, $b) {
                            $ad = date ("Y-m-d H:i:s", filemtime($a));
                            $bd = date ("Y-m-d H:i:s", filemtime($b));
                            if ($ad == $bd) {
                                return 0;
                            }
                            return $ad < $bd ? 1 : -1;
                        });
                        $n=1;
                        foreach ($files as $file) {
                            echo '<p>'.$n .' - <a href="?post_type=affiliate-links&page=impexp&tab=import&nav=history&file='.$file.'">' . basename($file) . '</a>  '.date ("Y-m-d H:i:s", filemtime($file)).' <input type="button" class="js-history-delete" data-file="'.$file.'" value="' . __('Delete', 'wordpress') . '"></p>';
                            $n++;
                        }
                    }

                    echo '</div>';
                }elseif($key == 'affiliate_description'){

                    foreach ( static::$tabs[$current_tab]['fields'] as $field ) {
                        if( isset( $field['nav']) && $key == $field['nav'] ){
                            $this->render_fields( $field );
                        }
                    }
                }
                echo '</div>';//nav-tab-item

            }elseif($current_tab == 'export'){ //export
                echo '<div id="'.$current_tab.'_'.$key.'" class="nav-tab-item'.$active.'">';
                if($key == 'affiliate_links'){
                    echo '<form action="" method="post">';
                    wp_nonce_field('impexp_links', 'impexp_links_nonce');

                    foreach ( static::$tabs[$current_tab]['fields'] as $field ) {
                        if( isset( $field['nav']) && $key == $field['nav'] ){
                            $this->render_fields( $field );
                        }
                    }
                    echo '<p><input type="submit" class="button button-primary" name="export_'.$key.'_submit" value="'.__( static::$tabs[$current_tab]['title'], 'affiliate-links').' '.__( $nav, 'affiliate-links').'"></p>';
                    echo '</form>';
                }elseif($key == 'links_activity'){
                    echo '<form action="" method="post">';
                    wp_nonce_field('impexp_links', 'impexp_links_nonce');
                    foreach ( static::$tabs[$current_tab]['fields'] as $field ) {
                        if( isset( $field['nav']) && $key == $field['nav'] ){
                            $this->render_fields( $field );
                        }
                    }
                    echo '<p><input type="submit" class="button button-primary" name="export_'.$key.'_submit" value="'.__( static::$tabs[$current_tab]['title'], 'affiliate-links').' '.__( $nav, 'affiliate-links').'"></p>';
                    echo '</form>';
                }
                echo '</div>';//nav-tab-item
            }
        }

        echo '</div>';//form-table
        ?>
    </div>
</div>