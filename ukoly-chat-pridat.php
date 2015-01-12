<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2013 Patrik Šíma                                  |
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
    require_once ('CUkol.inc.php');
    require_once ('CUkolChat.inc.php');
    require_once ('CUzivatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }  

    $id_ukol = CRequest::Get ('id_ukol', '');  

    if ($_POST)
    {
        $vars = array ('zprava');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        $ukol = new CUkol();
        
        if ($id_ukol != '' && !$ukol->Check($id_ukol)) {
            $error->PushMsg ('id_ukol', 'Úkol neexistuje');
        }
        
        if ($zprava == '') {
            $error->PushMsg ('zprava', 'Napište text zprávy');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }

        $error->PushTo ('chat.php?id_ukol='.$id_ukol);

        
        $ukol->Load($id_ukol);

        $user = CAuth::GetUser();
        
        $uzivatel = new CUzivatel();
        $uzivatel->Load ($user['id']);

        $dodavatel = new CUzivatel ();
        $dodavatel->LoadByDodavatel ($ukol->Get('id_dodavatel'));

        $ukolchat = new CUkolChat();
        $ukolchat->Set('id_ukol', $id_ukol);
        $ukolchat->Set('id_uzivatel', $uzivatel->Get('id'));
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
        $mail->AddReplyTo ($uzivatel->Get('email'));
        $mail->AddAddress ($dodavatel->Get ('email'));
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
    
    CRedirect::To ('chat.php?id_ukol='.$id_ukol);
?>