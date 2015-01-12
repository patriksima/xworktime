<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2011 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <ja@patriksima.cz>                               |
// |         http://www.patriksima.cz/                                    |
// +----------------------------------------------------------------------+
// $Id$
//

    require_once ('inc/CSetting.inc.php');
    require_once ('Smarty/Smarty.class.php');

    require_once ('CAuth.inc.php');
    require_once ('CError.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    if ($_POST)
    {
        $vars = array ('id_zadavatel',
                       'prist_jmeno', 'prist_heslo', 'email');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        if (CRequest::Post ('prist_jmeno') == '') {
            $error->PushMsg ('prist_jmeno', 'Zadejte přístupové jméno klienta');
        }

        if (CRequest::Post ('prist_heslo') == '') {
            $error->PushMsg ('prist_heslo', 'Zadejte přístupové heslo klienta');
        }
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", CRequest::Post ('email'))) {
            $error->PushMsg ('email', 'Zadejte platný e-mail klienta');
        }
        
        if (CRequest::Post ('id_zadavatel') == '') {
            $error->PushMsg ('id_zadavatel', 'Vyberte zadavatele');
        }

        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('pridat.php');
        
        $_prist_jmeno = $prist_jmeno;
        $_prist_heslo = $prist_heslo;

        $uzivatel = new CUzivatel ();
        foreach ($vars as $k=>$v) {
            if ($v == 'prist_heslo') {
                $$v = md5($$v);
            }
            if ($v == 'id_zadavatel' && $$v == '') {
                $$v = NULL;
            }
            $uzivatel->Set ($v, $$v);
        }
        $uzivatel->Set('prava', CAuth::RCLIENT);
        $uzivatel->Save ();

        $zadavatel = new CZadavatel();
        $zadavatel->Load($id_zadavatel);
        
        require_once ('class.phpmailer.php');
        
        $mail = new PHPMailer ();
        $mail->From = $BASEMAIL;
        $mail->FromName = "XWorkTime";
        $mail->AddAddress ($email);
        $mail->Subject = 'Přístupové údaje';
        $mail->Body    = "Dobrý den,

toto jsou přístupové údaje k aplikaci, která eviduje podklady pro fakturaci.

URL: {$BASEURL}klient/
Přístupove jméno: $_prist_jmeno
Přístupove heslo: $_prist_heslo

Na této adrese jsme pro vás spustili helpdesk:
{$BASEURL}podpora/?id_helpdesk={$zadavatel->get('id_helpdesk')}

S pozdravem

Váš XWorkTime
";
        $mail->Send ();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
