<?php
/**
 * Export Affiliate Links
 *
 */

class ExportAffiliateLinks
{
    protected $stats_instance;

    public function __construct() {
        $this->stats_instance = Affiliate_Links_Pro_Stats::get_instance();
    }

    public function export_affiliate_links(){
        $exportLinks = array();
        $allAffiliatePostLinks = $this->stats_instance->get_links();
        foreach ( $allAffiliatePostLinks as $link ) {
            $link_id = $link->ID;
            $linkMeta = get_post_meta($link->ID);
            $catsName = isset($_POST['affiliate_category_name'])? 'name' : 'term_id';
            $meta = array();
            $meta['title'] = $link->post_title;
            unset($linkMeta['_edit_last']);
            unset($linkMeta['_edit_lock']);
            $term_list = get_the_terms( $link_id, 'affiliate-links-cat' );
            $termsArray = array();
            $categories = '';
            if(!empty($term_list)){
                foreach($term_list as $term){
                    $term = (array)$term;
                    $termsArray[] = $term[$catsName];
                }
                $categories = implode(';', $termsArray);
            }
            $meta['categories'] = $categories;

            foreach( (array) $linkMeta as $k => $v ){
                //если Custom Target URL
                if($k == '_affiliate_links_additional_target_url'){
                    $urls = maybe_unserialize(unserialize($v[0]));
                    foreach($urls as $key => $rules){
                        $meta_key = '_additional_target_url_'.$key;
                        $meta[$meta_key] = $rules['url'];
                        foreach($rules['rules'][0] as $rule_key => $rule){
                            $meta[$meta_key.'_'.$rule_key] = $rule;
                        }
                    }
                }else{
                    $meta[$k] = $v[0]?: '';
                }
            }
            $exportLinks[] = $meta;
        }
        usort($exportLinks, function($a, $b){
            return ($a['title'] > $b['title']);
        });
        $exportLinks = $this->checkColumns($exportLinks);
        $this->outputCsv( 'affiliate_links.csv', array_values($exportLinks) );
    }

    public function export_links_activity(){
        $exportLinks = array();
        $allAffiliatePostLinks = $this->stats_instance->get_links();
        $linksActivity = $this->stats_instance->load_activity([], ARRAY_A);
        foreach($linksActivity as $activity){
            $link_id = $activity['link_id'];
            foreach($activity as $k => $v){
                $meta_key = 'links_activity_'.$k;
                if($k != 'id' && $k != 'link_id'){
                    $meta[$meta_key] = $v;
                }
                $res = array_filter($allAffiliatePostLinks, function($link) use($link_id){
                    return $link->ID == $link_id;
                });
                if(!empty($res)){
                    $meta['parent_link'] = array_values($res)[0]->post_name;
                }else{
                    $meta['parent_link'] = $link_id;
                }
            }
            $term_list = get_the_terms( $link_id, 'affiliate-links-cat' );
            $termsArray = array();
            $categories = '';
            if(!empty($term_list)){
                foreach($term_list as $term){
                    $termsArray[] = $term->name;
                }
                $categories = implode(',', $termsArray);
            }
            $meta['links_activity_categories'] = $categories;
            $exportLinks[] = $meta;
        }
        usort($exportLinks, function($a, $b){
            return ($a['parent_link'] > $b['parent_link']);
        });
        $this->outputCsv( 'af_links_activity.csv', $exportLinks );
    }

    public function outputCsv( $fileName, $exportData, $delimiter=',' ) {
        ob_clean();
        header( 'Expires: 0' );
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename={$fileName}");
        header("Content-Transfer-Encoding: binary");
        if ( !empty( $exportData ) ) {
            $fp = fopen( 'php://output', 'w' );
            fputcsv( $fp, array_keys( reset($exportData) ), $delimiter );
            foreach ( $exportData AS $values ) {
                fputcsv( $fp, $values, $delimiter );
            }
            fclose( $fp );
        }
        ob_flush();
        exit;
    }

    /**
     * Одинаковые ключи у всех записей
     */
    public function checkColumns($exportLinks){
        $keys = array();
        $exports = array();
        foreach($exportLinks as $links){
            foreach($links as $key => $link){
                if(!in_array($key, $keys)){
                    $keys[] = $key;
                }
            }
        }
        foreach($exportLinks as $link){
            foreach($keys as $key){
                if(!isset($link[$key])){
                    $link[$key] = '';
                }
            }
            $exports[] = $link;
        }
        return $exports;
    }
}
