<?php
/**
 * Import Affiliate Links
 * Колонки в csv
 * Обязательные title, _affiliate_links_target
 *
0 => 'title',
1 => 'categories', // ids, delimiter ;
2 => '_affiliate_links_target',
3 => '_affiliate_links_description',
4 => '_affiliate_links_iframe',
5 => '_affiliate_links_nofollow',
6 => '_affiliate_links_redirect',
7 => '_embedded_add_rel',
8 => '_embedded_add_target',
9 => '_embedded_add_link_title',
10 => '_embedded_add_link_class',
11 => '_embedded_add_link_anchor',
 * optional
12 => '_additional_target_url_1',
13 => '_additional_target_url_1_name',
14 => '_additional_target_url_1_cond',
15 => '_additional_target_url_1_value',
16 => '_additional_target_url_2',
17 => '_additional_target_url_2_name',
18 => '_additional_target_url_2_cond',
19 => '_additional_target_url_2_value',
 * ...
 */

class ImportAffiliateLinks
{
    public $import_errors = array();
    public $filename;
    /**
     * $dataRows распарсенные строки из csv в массиве
     */
    public $dataRows = array();
    public $current_date;
    public $headRow = 'title,categories,_affiliate_links_target,_affiliate_links_description,_affiliate_links_iframe,_affiliate_links_nofollow,_affiliate_links_redirect,_embedded_add_rel,_embedded_add_target,_embedded_add_link_title,_embedded_add_link_class,_embedded_add_link_anchor';//_additional_target_url_0,_additional_target_url_0_name,_additional_target_url_0_cond,_additional_target_url_0_value';

    public function __construct() {
        $this->current_date = date('Y-m-d H:i:s');
    }

    /**
     * Проверка файла csv и парсинг файла в массив
     */
    public function init(){
        if(!$this->checkFile()){
            $this->updateErrors(__('File is empty', 'affiliate-links'));
            $this->showErrors();
            return false;
        }
        $this->csvToArray();
        if($this->hasErrors()){
            return false;
        }
        return true;
    }

    /**
     * Импорт
     */
    public function startImport(){
        if(!$this->init()) return false;

        if($this->importLinksFromDataRows()){
            $this->saveCSV();
            return true;
        }
        return false;
    }

    /**
     *  Разбор csv в массив $dataRows с проверкой ошибок
     */
    public function csvToArray(){
        if (($handle = fopen($this->filename, "r")) !== FALSE) {
            $delimiter = $this->detectDelimiter($this->filename);
            $row = 0;
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $num = count($data);
                if($row === 0){
                    if($this->searchBOM($data[0])){
                        $data[0] = substr($data[0], 3);
                    }
                    //заголовок
                    if($data[0] == 'title'){
                        continue;
                    }
                }

                for ($c = 0; $c < $num; $c++) {
                    $item = trim($data[$c]);
                    if ($c == 0 && empty($item)) {
                        $this->updateErrors(__('Title columns is empty', 'affiliate-links'), ($row+1), ($c+1));
                    }elseif($c == 2 && empty($item)){ // _affiliate_links_target
                        $this->updateErrors(__('Title columns is empty', 'affiliate-links'), ($row+1), ($c+1));
                    }elseif($c == 6 && empty($item)){ // _affiliate_links_redirect
                        $data[$c] = '301'; //default redirect type 301
                    }
                }

                $this->dataRows[$row] = $data;
                $row++;
            }
            if(!empty($this->import_errors)){
                $this->updateErrors(__('File was not uploaded', 'affiliate-links'));
            }

            fclose($handle);
        }
    }

    /**
     * Сохранение в базу данных
     */
    public function importLinksFromDataRows(){
        if(empty($this->dataRows))return false;

        $row = 0;
        foreach ($this->dataRows as $data) {
            $num = count($data);
            $rowData = array();
            $affiliate_data = array(
                'post_title'    => $data[0],
                'post_status'   => 'publish',
                'post_type'     => 'affiliate-links',
            );
            if(!$post_id = wp_insert_post( wp_slash($affiliate_data) ) ){
                $this->updateErrors(__('Insert error', 'affiliate-links'), ($row+1));
            }else {
                //affiliate link создан
                if(!empty($data[1])) {
                    $affiliate_categories = explode(';', $data[1]);
                    wp_set_post_terms($post_id, $affiliate_categories, 'affiliate-links-cat', true);
                }
                for($c = 0; $c < $num; $c++) {
                    $item = $data[$c];
                    if($c == 2) {
                        $rowData['_affiliate_links_target'] = $item;
                    } elseif($c == 3) {
                        $rowData['_affiliate_links_description'] = $item;
                    } elseif($c == 4) {
                        $rowData['_affiliate_links_iframe'] = $item;
                    } elseif($c == 5) {
                        $rowData['_affiliate_links_nofollow'] = $item;
                    } elseif($c == 6) {
                        $rowData['_affiliate_links_redirect'] = $item;
                    } elseif($c == 7) {
                        $rowData['_embedded_add_rel'] = $item;
                    } elseif($c == 8) {
                        $rowData['_embedded_add_target'] = $item;
                    } elseif($c == 9) {
                        $rowData['_embedded_add_link_title'] = $item;
                    } elseif($c == 10) {
                        $rowData['_embedded_add_link_class'] = $item;
                    } elseif($c == 11) {
                        $rowData['_embedded_add_link_anchor'] = $item;
                        $c++;
                        break;
                    }
                }
                //additional_target_url
                $additional = array();
                $rules = array('name', 'cond', 'value');
                for($n = 0, $i = -1; $c < $num; $n++, $c++) {
                    $item = $data[$c];
                    if(empty($item))break;
                    if($n%4 == 0){
                        $i++;
                        $n = 0;
                        $additional[$i]['url'] = $item;
                    }else{
                        $additional[$i]['rules'][0][$rules[$n-1]] = $item;
                    }
                }
                if(!empty($additional)){
                    $rowData['_affiliate_links_additional_target_url'] = $additional;
                }
                if(empty($rowData)){
                    $this->updateErrors( __('Empty meta data', 'affiliate-links') );
                }else{
                    foreach($rowData as $key => $meta){
                        update_post_meta($post_id, $key, $meta);
                    }
                }
            }
        }
        return true;
    }

    /**
     *  Сохранение csv в папку uploads/affiliate
     */
    public function saveCSV(){
        if (!empty($_FILES['affiliate_file'])) {
            $dirname = 'affiliate';
            $uploadDir = wp_upload_dir()['basedir'];
            $upload_dir = $uploadDir . '/' . $dirname;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0700);
                if (!is_dir($upload_dir)) {
                    return;
                }
            }

            if ( ! function_exists( 'wp_handle_upload' ) )
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $file = & $_FILES['affiliate_file'];
            $overrides = [ 'test_form' => false ];
            function my_upload_dir($upload) {
                $upload['path'] = ABSPATH . 'wp-content/uploads/affiliate';
                $upload['url'] = '/wp-content/uploads/affiliate';
                $upload['subdir'] = '/affiliate';
                return $upload;
            }
            add_filter('upload_dir', 'my_upload_dir');
            $movefile = wp_handle_upload( $file, $overrides );
            remove_filter('upload_dir', 'my_upload_dir');
            if ( $movefile && empty($movefile['error']) ) {
                echo __('The file was successfully uploaded to the uploads folder', 'affiliate-links');
                echo ' - <a href="?post_type=affiliate-links&page=impexp&tab=import&nav=history&file='.$movefile['file'].'">'.basename($movefile['file']).'</a>';
                return true;
            }
        }
        return false;
    }

    /**
     * валидация файла перед разбором csv
     */
    public function checkFile(){
        if(!$this->filename){
            $mimes = array('application/vnd.ms-excel');
            if (!empty($_FILES['affiliate_file']) && in_array($_FILES['affiliate_file']['type'], $mimes)) {
                if(!empty($_FILES['affiliate_file']['tmp_name'])){
                    $this->filename = $_FILES['affiliate_file']['tmp_name'];
                    return true;
                }
            }
        }else{
            if(strpos($this->filename, '.csv') !== false && file_exists($this->filename)){
                return true;
            }
        }
        return false;
    }

    /**
     * Вывод содержимого выбранного csv в таблице
     */
    public function csvView($filename=null){
        if($filename){
            $this->filename = $filename;
        }
        if(!$this->init()) return false;

        return $this->showDownloadedCsv();
    }

    /**
     * Показ загруженных csv в таблице
     */
    public function showDownloadedCsv(){
        if(empty($this->dataRows))return false;
        echo '<h2>'. basename($this->filename) . '</h2>';
        echo '<p title="'.__('Show all history', 'affiliate-links' ) . '"><a href="?post_type=affiliate-links&page=impexp&tab=import&nav=history">'.__('History', 'affiliate-links' ) . '</a></p>';
        echo '<div class="table-affiliate-wrap">';
        echo '<table class="table table-affiliate">';
        $row = 0;
        $headrow = explode( ',', $this->headRow );

        foreach ($this->dataRows as $data) {
            $num = count($data);
            if ($row == 0) {
                echo '<tr>';
                for ($_c = 0; $_c < count($this->dataRows[count($this->dataRows)-1]); $_c++) {
                    echo '<th>' . $headrow[$_c] ?? '' . '</th>';
                }
                echo '</tr>';
            }
            echo '<tr class="">';
            $item = null;
            for ($c = 0; $c < $num; $c++) {
                $item = $data[$c];
                echo '<td>' . $item . '</td>';
            }//for
            echo '</tr>';
            $row++;
        }
        echo '</table>';
        echo '</div>';
        return true;
    }

    /**
     * разделитель колонок в csv
     */
    public function detectDelimiter($csvFile){
        $delimiters = array(
            ';' => 0,
            ',' => 0,
            "\t" => 0,
            "|" => 0
        );

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }

    /**
     * выводит ошибки из csv
     */
    public function hasErrors(){
        if(!empty($this->import_errors)){
            $this->showErrors();
            return true;
        }
        return false;
    }

    public function updateErrors($err, $row=null, $column=null){
        $error = $err;
        if($row){
            $error .= ', ' . __('row', 'affiliate-links') . ' - ' . $row;
        }
        if($column){
            $error .= ', ' . __('column', 'affiliate-links') . ' - ' . $column;
        }
        $this->import_errors[] = $error;
    }

    public function showErrors(){
        if(!empty($this->import_errors)){
            echo '<h3 style="color:gray;">'.__('Notification', 'affiliate-links').':</h3>';
            foreach($this->import_errors as $error){
                echo '<p>'.$error.'</p>';
            }
        }
    }

    public function searchBOM($string){
        if(substr($string,0,3) == pack("CCC",0xef,0xbb,0xbf)) return true;
        return false;
    }
}
