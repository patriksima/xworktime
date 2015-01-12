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
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('id_dodavatel',
                       'prist_jmeno', 'prist_heslo', 'email', 'prava');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        if (CRequest::Post ('prist_jmeno') == '') {
            $error->PushMsg ('prist_jmeno', 'Zadejte přístupové jméno uživatele');
        }

        if (CRequest::Post ('prist_heslo') == '') {
            $error->PushMsg ('prist_heslo', 'Zadejte přístupové heslo uživatele');
        }
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", CRequest::Post ('email'))) {
            $error->PushMsg ('email', 'Zadejte platný e-mail uživatele');
        }
        
        if (CRequest::Post ('id_dodavatel') == '' && !CRequest::Post ('prava')) {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele nebo zaškrtněte checkbox pro administrátora');
        }

        if (CRequest::Post ('id_dodavatel') && CRequest::Post ('prava')) {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele nebo zaškrtněte checkbox pro administrátora');
        }        
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('upravit.php?id='.$id);

        if ($prava) {
            $prava = CAuth::RADMIN;
        } else {
            $prava = CAuth::RUSER;
        }
        
        $_prist_jmeno = $prist_jmeno;
        $_prist_heslo = $prist_heslo;
        
        $uzivatel = new CUzivatel ();
        $uzivatel->Load ($id);
        foreach ($vars as $k=>$v) {
            if ($v == 'prist_heslo') {
                if ($$v == '**********') {
                    continue;
                } else {
                    $$v = md5($$v);
                }
            }
            if ($v == 'id_dodavatel' && $$v == '') {
                $$v = NULL;
            }
            $uzivatel->Set ($v, $$v);
        }
        $uzivatel->Save ();
        
        if ($prist_jmeno != $uzivatel->Get('prist_jmeno') ||
            $prist_heslo != '**********')
        {
            require_once ('class.phpmailer.php');
            
            $mail = new PHPMailer ();
            $mail->From = $BASEMAIL;
            $mail->FromName = "XWorkTime";
            $mail->AddAddress ($email);
            $mail->Subject = 'Přístupové údaje';
            $mail->Body    = "Dobrý den,

toto jsou přístupové údaje k aplikaci, která eviduje podklady pro fakturaci.

URL: {$BASEURL}dodavatel/
Přístupove jméno: $_prist_jmeno
Přístupove heslo: $_prist_heslo

S pozdravem

Váš XWorkTime
";
            $mail->Send ();
        }
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
