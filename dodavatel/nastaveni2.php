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

    require_once ('../inc/CSetting.inc.php');
    require_once ('Smarty/Smarty.class.php');

    require_once ('CAuth.inc.php');
    require_once ('CError.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $user = CAuth::GetUser();
    
    if ($_POST)
    {
        $vars = array ('prist_jmeno', 'prist_heslo', 'prist_heslo2',
                       'nazev', 'sazba',
                       'ulice', 'mesto', 'stat', 'psc', 'ic', 'dic',
                       'tel', 'fax', 'email', 'icq', 'jabber', 'msn',
                       'www');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $sazba = str_replace(" ","",$sazba);
        $sazba = str_replace(",",".",$sazba);
        
        $error = new CError ();
        
        if ($prist_jmeno == '') {
            $error->PushMsg ('prist_jmeno', 'Zadejte přístupové jméno');
        }

        if ($prist_heslo == '') {
            $error->PushMsg ('prist_heslo', 'Zadejte přístupové heslo');
        }

        if ($prist_heslo != $prist_heslo2) {
            $error->PushMsg ('prist_heslo', 'Heslo nesouhlasí s ověřením');
        }
        
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Zadejte název');
        }

        if (CRequest::Post('sazba') == '' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazba)) {
            $error->PushMsg ('sazba', 'Zadejte hodinovou sazbu');
        }
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", $email)) {
            $error->PushMsg ('email', 'Zadejte platný e-mail');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('nastaveni.php');

        $_prist_jmeno = $user['prist_jmeno'];
        $_prist_heslo = $prist_heslo;
        
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($user['id_dodavatel']);
        $sazba_old = $dodavatel->Get('sazba');
        foreach ($vars as $k=>$v) {
            if ($v == 'prist_jmeno' ||
                $v == 'prist_heslo' ||
                $v == 'prist_heslo2') continue;
            $dodavatel->Set ($v, $$v);
        }
        $dodavatel->Save ();

        if ($prist_heslo != '**********')
        {
            $uzivatel = new CUzivatel ();
            $uzivatel->Load ($user['id']);
            $uzivatel->Set('prist_heslo', md5($prist_heslo));
            $uzivatel->Save ();
            
            $_SESSION['xwt_auth_hash'] = md5 ($user['prist_jmeno'].'x'.md5 ($prist_heslo));
        
            require_once ('class.phpmailer.php');
            
            $mail = new PHPMailer ();
            $mail->From = $BASEMAIL;
            $mail->FromName = "XWorkTime";
            $mail->AddAddress ($email);
            $mail->Subject = 'Nové přístupy';
            $mail->Body    = "Dobrý den,

toto jsou přístupové údaje k on-line webové aplikaci pro správu zakázek, dodavatelů a podkladů k fakturaci

URL: {$BASEURL}dodavatel/
Přístupove jméno: $_prist_jmeno
Přístupove heslo: $_prist_heslo

S pozdravem

Patrik Šíma
internetový specialista
777 287 451
";
            $mail->Send ();
        }
        
        if ($sazba_old != $sazba) {
            require_once ('class.phpmailer.php');
            
            $mail = new PHPMailer ();
            $mail->From = $BASEMAIL;
            $mail->FromName = "XWorkTime";
            $mail->AddAddress ($BASEMAIL);
            $mail->Subject = 'Dodavatel | Změna údajů';
            $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get('nazev')." změnil své údaje.

Původní sazba: ".sprintf("%d",$sazba_old)." Kč/hod.
Nová sazba: ".sprintf("%d",$sazba)." Kč/hod.

S pozdravem

Patrik Šíma
internetový specialista
777 287 451
";
            $mail->Send ();
        }
    }
    
    CRedirect::To ('nastaveni.php?ok');
?>
