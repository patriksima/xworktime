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
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');    
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $id_ukol = CRequest::Get ('id_ukol', '');
    
    if ($_POST)
    {
        $vars = array ('zprava');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $uzivatel = CAuth::GetUser ();
        
        $ukol = new CUkol ();
        $ukol->Load ($id_ukol);
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        
        $error = new CError ();
        
        if ($zprava == '') {
            $error->PushMsg ('zprava', 'Napište text zprávy');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('ukol-chat.php?id_ukol='.$id_ukol);
        
        $ukolchat = new CUkolChat();
        $ukolchat->Set('id_ukol', $id_ukol);
        $ukolchat->Set('id_uzivatel', $uzivatel['id']);
        $ukolchat->Set('zprava', $zprava);
        $ukolchat->Set('vytvoreno', date('Y-m-d H:i:s'));
        $id_ukolchat = $ukolchat->Save();

        $zadavatel = new CZadavatel();
        $zadavatel->Load($ukol->Get('id_zadavatel'));

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
        $mail->AddReplyTo ($uzivatel['email']);
        $mail->AddAddress ($BASEMAIL);
        // maily ve sloupci vytvoril lze oddelit carkou a zadat jich vic
        if ($ukol->Get('vytvoril') != ''){
            foreach (explode(',', $ukol->Get('vytvoril')) as $cc) {
                $mail->AddCC($cc);
            }
        }
        $mail->Subject = 'Nový příspěvek | '.$subject;
        $mail->Body    = "Dobrý den,

v diskuzi k úkolu '".$ukol->Get('nazev')."' byl přidán nový příspěvek,
který si můžete po přihlášení prohlédnout na této adrese
{$BASEURL}podpora/chat.php?id_helpdesk=".$zadavatel->Get('id_helpdesk')."&id_ukol=".$id_ukol."

S pozdravem

Váš XWorkTime
";
        $mail->Send ();
    }
    
    CRedirect::To ('ukol-chat.php?id_ukol='.$id_ukol);
?>