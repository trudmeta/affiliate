<?php

use Codeception\Util\Locator;

class ImportExportCest {
    public function checkExport( AcceptanceTester $I ) { //тестирование экспорта
        $I->loginAsAdmin(); // вход в админку
        $I->amOnPage( '/wp-admin/edit.php?post_type=affiliate-links&page=impexp&tab=export' );
        $I->see( 'Export data from post and postmeta table' ); //страница экспорта
        $I->click('Export Affiliate links'); // клик по кнопке экспорта
        $I->wait(3);
        $I->assertFileExists(codecept_data_dir() . 'affiliate_links.csv'); // файл закачивается в папку /tests/_data/
    }
    public function checkImport( AcceptanceTester $I ) { //тестирование импорта
        $I->loginAsAdmin(); // вход в админку
        $I->amOnPage( '/wp-admin/edit.php?post_type=affiliate-links&page=impexp&tab=import' );
        $I->see( 'Download csv' ); // страница импорта
        $I->seeElement(Locator::find('input', ['name' => 'affiliate_file']));
        $I->assertFileExists(codecept_data_dir() . 'affiliate_links.csv');
        $I->attachFile(Locator::find('input', ['type' => 'file']), 'affiliate_links.csv'); // выбор файла affiliate_links.csv из папки /tests/_data/
        $I->wait(3);
        $I->click('Import Affiliate links'); //клик по кнопке импорта
        $I->wait(3);
        $I->see('The file was successfully uploaded to the uploads folder'); // успешная загрузка
        $I->dontSee('File is empty'); // нет надписи File is empty
        $I->wait(1);
    }
}