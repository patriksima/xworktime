<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2011 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <programator@patriksima.cz>                      |
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

    require_once ('CUkol.inc.php');
    require_once ('CUkolPrilohy.inc.php');
    require_once ('CHelpdesk.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');

    if ($_POST)
    {
        $vars = array ('jmeno', 'typ', 'nazev', 'popis');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }

        $id_ukol = CRequest::Get ('id_ukol');
        $id_helpdesk = CRequest::Get ('id_helpdesk');

        $zadavatel = new CZadavatel();

        if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
            CRedirect::To ('../prihlasit.php');
        }

        $zadavatel->LoadByHelpDesk($id_helpdesk);

        $ukol = new CUkol ();
        $ukol->Load ($id_ukol);
        // overeni, zda pozadavek patri klientovi
        if (!$ukol->CheckZadavatel($zadavatel->Get('id'))) {
            CRedirect::To ('../prihlasit.php');
        }        

        $error = new CError ();

        if ($jmeno == '') {
            $error->PushMsg ('jmeno', 'Vyplňte své jméno.');
        }
        if ($typ == '') {
            $error->PushMsg ('typ', 'Vyberte typ požadavku.');
        }
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Napište svůj požadavek.');
        }

        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('upravit.php?id_helpdesk='.$id_helpdesk.'&id_ukol='.$id_ukol);

        $helpdesk = new CHelpdesk();
        $helpdesk->LoadByParams($zadavatel->Get('id'), $typ);

        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByZadavatel ($helpdesk->Get('id_zadavatel'));     

        $ukol->Set ('nazev', $jmeno.': '.$nazev);
        $ukol->Set ('popis', $popis);
        $ukol->Set ('klic', $typ);
        $ukol->Save ();

        $prilohy = new CUkolPrilohy ();
        $prilohy->Upload($id_ukol);

        $dodavatel = new CDodavatel();
        $dodavatel->Load($helpdesk->Get('id_dodavatel'));

        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByDodavatel ($helpdesk->Get('id_dodavatel'));        

        require_once ('class.phpmailer.php');
        
        mb_internal_encoding('UTF-8');
        if (mb_strlen($ukol->Get('nazev')) > 43) {
            $subject = mb_substr($ukol->Get('nazev'),0,40).'...';
        } else {
            $subject = $ukol->Get('nazev');
        }

        $mail = new PHPMailer ();
        $mail->From = $BASEMAIL;
        $mail->FromName = "XWorkTime";
        $mail->AddAddress ($dodavatel->Get ('email'));
        $mail->Subject = 'Úkol změněn | '.$subject;
        $mail->Body    = "Dobrý den,

úkol '".$ukol->Get('nazev')."' pro klienta ".$zadavatel->Get ('nazev')." byl změněn.

S pozdravem

Váš XWorkTime
";
        $mail->Send ();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);