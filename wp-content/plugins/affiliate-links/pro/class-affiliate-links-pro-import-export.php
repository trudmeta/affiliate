<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
include_once AFFILIATE_LINKS_PRO_PLUGIN_DIR . '/' . 'class-affiliate-links-pro-base.php';
include_once AFFILIATE_LINKS_PRO_PLUGIN_DIR . '/vendors/' . 'ExportAffiliateLinks.php';
include_once AFFILIATE_LINKS_PRO_PLUGIN_DIR . '/vendors/' . 'ImportAffiliateLinks.php';

class Affiliate_Links_Pro_Import_Export extends Affiliate_Links_Pro_Base {

	public $template = 'link-import-export';
	public $messages = array();
    public static $tabs;
    protected $import;
    protected $export;

	public function __construct() {
		parent::__construct();
        $this->import = new ImportAffiliateLinks;
        $this->export = new ExportAffiliateLinks;
        self::$tabs   = self::get_tabs();

        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'add_menu' ) );
            add_action( 'af_link_ie_tab_' . $this->get_current_tab(), array(
                $this,
                'render_view',
            ) );

            add_action( 'wp_ajax_af_delete_link', array(
                $this,
                'af_delete_link',
            ) );
        }
	}

	public function add_menu() {
		add_submenu_page(
			'edit.php?post_type=affiliate-links',
			'Affiliate Links Import/Export',
			'Link Import/Export',
			'manage_options',
			'impexp',
			array( $this, 'controller' )
		);
	}

	public function render_import_export() {
		$this->render_view( $this->template );
	}

	public function controller(){
        global $wpdb;
        if ( isset( $_POST['impexp_links_nonce'] ) && wp_verify_nonce( $_POST['impexp_links_nonce'], 'impexp_links' ) ) {
            /**
             * Импорт affiliate links
             * @file extension csv
             */
            if(isset( $_POST['import_affiliate_links_submit'] )){
                $this->import->startImport();
            }
            /**
             * Экспорт данных из таблиц post, postmeta
             * @post_type affiliate-links
             */
            elseif( isset( $_POST['export_affiliate_links_submit'])){
                $this->export->export_affiliate_links();
            }
            /**
             * Экспорт данных из таблицы af_links_activity
             */
            elseif( isset( $_POST['export_links_activity_submit'])){
                $this->export->export_links_activity();
            }
        }
        $this->render_import_export();
    }

    /**
     * Удаление файла csv из папки wp-content/uploads/affiliate по ajax
     */
    public function af_delete_link() {
        $file = $_POST['file']? sanitize_text_field($_POST['file']):'';
        if(empty($file)){
            echo 'error';
            exit;
        }
        wp_delete_file( $file );
        echo 'ok';
        exit;
    }

    public function get_tabs() {
        return array(
            'import' => array(
                'title' => __( 'Import', 'affiliate-links' ),
                'navs' => array(
                    'affiliate_links' => __('Affiliate links', 'affiliate-links'),
                    'affiliate_history' => __('Affiliate history', 'affiliate-links'),
                    'affiliate_description' => __('Description', 'affiliate-links'),
                ),
                'fields' => array(
                    array(
                        'nav'         => 'affiliate_links',
                        'name'        => 'affiliate_file',
                        'title'       => __('Download csv', 'affiliate-links'),
                        'type'        => 'file',
                        'default'     => '',
                        'description' => __('Download csv file', 'affiliate-links'),
                    ),
                    array(
                        'nav'         => 'affiliate_description',
                        'title' => __('Required columns for import', 'affiliate-links'),
                        'description' => [
                            $this->import->headRow,
                            __('Sample file for import. You can import this file', 'affiliate-links') .' - <a href="'.  plugin_dir_url( dirname( __FILE__ ) ) . 'pro/vendors/affiliate_links.csv' .'" download="affiliate_links.csv" title="download">affiliate_links</a>'
                        ]
                    ),
                ),
            ),
            'export' => array(
                'title' => __( 'Export', 'affiliate-links' ),
                'navs' => array(
                    'affiliate_links' => __('Affiliate links', 'affiliate-links'),
                    'links_activity'  => __('Links activity', 'affiliate-links'),
                ),
                'fields' => array(
                    array(
                        'nav'         => 'affiliate_links',
                        'title'       => __('Export data from post and postmeta table', 'affiliate-links'),
                    ),
                    array(
                        'nav'         => 'affiliate_links',
                        'title'       => __('Category ids or name', 'affiliate-links'),
                        'name'        => 'affiliate_category_name',
                        'type'        => 'checkbox',
                        'default'     => 'term_id',
                        'description' => __('Export affiliate categories, if checked = name, otherwise will be saved ids', 'affiliate-links'),
                    ),
                    array(
                        'nav'         => 'links_activity',
                        'title' => __('Export all data from af_links_activity table', 'affiliate-links'),
                        'description' => __('exports only records from the af_links_activity table (existing clicks)', 'affiliate-links'),
                    ),
                ),
            ),
        );
    }

    public function get_current_tab() {
        return $this->get_request_var( 'tab', 'import' );
    }

    public function render_fields($field){
        $html = '';
        if(!empty($field['title'])){
            $html .= '<h2>'.$field['title'].'</h2>';
        }
        $html .= '<div class="form-group">';
        if(isset($field['type'])){
            $html .= '<input type="'.$field['type'].'" name="'.$field['name'].'"';
            if(!empty($field['default'])){
                $html .= ' value="'.$field['default'].'"';
            }
            if(isset($field['checked'])){
                $html .= ' checked="checked"';
            }
            $html .= ">";
        }

        if(!empty($field['description'])){
            if(is_array($field['description'])){
                foreach($field['description'] as $desc){
                    $html .= '<p>'.$desc.'</p>';
                }
            }else{
                $html .= '<p>'.$field['description'].'</p>';
            }
        }
        $html .= '</div>';
        echo $html;
    }

}