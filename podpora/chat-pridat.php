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
    require_once ('CUkol.inc.php');
    require_once ('CUkolChat.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CUzivatel.inc.php');
    
    $id_ukol  = CRequest::Get ('id_ukol');
    $id_helpdesk = CRequest::Get ('id_helpdesk');

    // kontrola helpdesk klice
    $zadavatel = new CZadavatel();

    if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
        CRedirect::To ('../prihlasit.php');
    }

    $zadavatel->LoadByHelpDesk($id_helpdesk);

    // kontrola ukolu
    $ukol = new CUkol();

    if (!$ukol->Check($id_ukol)) {
        CRedirect::To ('../podpora/?id_helpdesk='.$id_helpdesk);
    }

    $ukol->Load($id_ukol);

    if ($ukol->Get('id_zadavatel') != $zadavatel->Get('id')) {
        CRedirect::To ('../podpora/?id_helpdesk='.$id_helpdesk);
    }
    
    if ($_POST)
    {
        $vars = array ('zprava');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        if ($zprava == '') {
            $error->PushMsg ('zprava', 'Napište text zprávy');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('chat.php?id_helpdesk='.$id_helpdesk.'&id_ukol='.$id_ukol);
        
        $zadavatel = new CUzivatel ();
        $zadavatel->LoadByZadavatel ($ukol->Get('id_zadavatel'));

        $dodavatel = new CUzivatel();
        $dodavatel->LoadByDodavatel ($ukol->Get('id_dodavatel'));

        $ukolchat = new CUkolChat();
        $ukolchat->Set('id_ukol', $id_ukol);
        $ukolchat->Set('id_uzivatel', $zadavatel->Get('id'));
        $ukolchat->Set('zprava', $zprava);
        $ukolchat->Set('vytvoreno', date('Y-m-d H:i:s'));
        $id_ukolchat = $ukolchat->Save();

        // notifikace
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
        $mail->AddReplyTo ($zadavatel->Get('email'));
        $mail->AddAddress ($BASEMAIL);
        $mail->AddCC($dodavatel->Get('email'));
        $mail->Subject = 'Nový příspěvek | '.$subject;
        $mail->Body    = "Dobrý den,

v diskuzi k úkolu '".$ukol->Get('nazev')."' byl přidán nový příspěvek,
který si můžete po přihlášení prohlédnout na této adrese
{$BASEURL}dodavatel/ukol-chat.php?id_ukol=".$id_ukol."

S pozdravem

Váš XWorkTime
";
        $mail->Send ();
    }
    
    CRedirect::To ('chat.php?id_helpdesk='.$id_helpdesk.'&id_ukol='.$id_ukol);
?>