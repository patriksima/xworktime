<?php
/**
 * Test na javascript chyby
 */

class JsErrorsTest extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://demo.xworktime.com/');
    }
 
    public function testSpravce()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=demo');
        $this->clickAndWait('id=submit');

        $this->assertEval('window.jsErrors', '');

        $this->open('/podklady/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/podklady/pridat.htm');
        $this->assertEval('window.jsErrors', '');
        $this->open('/podklady/export.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/ukoly/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/ukoly/pridat.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/zadavatele/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/zadavatele/pridat.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/dodavatele/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/dodavatele/pridat.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/uzivatele/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/uzivatele/pridat.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/klienti/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/klienti/pridat.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/helpdesk/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/helpdesk/pridat.php');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }

    public function testDodavatel()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=dodavatel');
        $this->clickAndWait('id=submit');

        $this->assertEval('window.jsErrors', '');

        $this->open('/dodavatel/');
        $this->assertEval('window.jsErrors', '');

        $this->open('/dodavatel/podklady/');
        $this->assertEval('window.jsErrors', '');
        $this->open('/dodavatel/podklady/hledat.htm');
        $this->assertEval('window.jsErrors', '');
        $this->open('/dodavatel/podklady/export.htm');
        $this->assertEval('window.jsErrors', '');

        $this->open('/dodavatel/nastaveni.php');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }

    public function testKlient()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=klient');
        $this->clickAndWait('id=submit');

        $this->assertEval('window.jsErrors', '');

        $this->open('/klient/');
        $this->assertEval('window.jsErrors', '');

        $this->open('/klient/export.php');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }
}