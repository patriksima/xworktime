<?php
/**
 * Test prihlasovani
 */

class LoginTest extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://demo.xworktime.com/');
    }
 
    public function testLoginSpravce()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=demo');
        $this->clickAndWait('id=submit');

        $this->assertText('css=h3', 'Finanční přehled');
        $this->assertElementPresent('id=ui-id-1');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }

    public function testLoginDodavatel()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=dodavatel');
        $this->clickAndWait('id=submit');

        $this->assertText('css=h3', 'Úkoly');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }

    public function testLoginKlient()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->select('id=prihlasitjako', 'value=klient');
        $this->clickAndWait('id=submit');

        $this->assertText('css=h3', 'Podklady');
        $this->assertEval('window.jsErrors', '');

        $this->clickAndWait('//div[@id="logout"]/p/a');    
    }

    public function testLoginFails()
    {
        $this->open('/');
        $this->assertEval('window.jsErrors', '');
        $this->assertStringStartsWith('XWorkTime', $this->getTitle());
        $this->assertElementPresent('id=login');
        $this->assertElementPresent('id=prihlasitjako');
        $this->assertSelectedValue('id=prihlasitjako', 'demo');
        $this->type('id=prist_jmeno', 'test');
        $this->type('id=prist_heslo', 'fail');
        $this->clickAndWait('id=submit');

        $this->assertTextPresent('Přihlášení bylo neúspěšné');
    }    
}