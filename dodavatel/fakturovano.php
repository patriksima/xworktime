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
    
    require_once ('CAuth.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUkol.inc.php');
    require_once ('CPodklad.inc.php');
    require_once ('CDodavatel.inc.php');

    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $id_podklady = CRequest::Get ('id_podklad');
    $user = CAuth::GetUser();
    $mail = false;
    
    foreach ($id_podklady as $k=>$id_podklad) {
        $podklad = new CPodklad();
        $podklad->Load ($id_podklad);
        
        $ukol = new CUkol();
        $ukol->Load ($podklad->Get('id_ukol'));
        
        if (!$podklad->Get('fakturad') && 
            $user['id_dodavatel']==$ukol->Get('id_dodavatel'))
        {
            $podklad->Set ('fakturad', 1);
            $podklad->Save ();
            $mail = true;
        }
    }

    if ($mail) {
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($user['id_dodavatel']);
        
        require_once ('class.phpmailer.php');
        
        $mail = new PHPMailer ();
        $mail->From = $BASEMAIL;
        $mail->FromName = "XWorkTime";
        $mail->AddAddress ($BASEMAIL);
        $mail->Subject = 'Fakturace | '.$dodavatel->Get ('nazev');
        $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." vyfakturoval některé podklady.

S pozdravem

Patrik Šíma
internetový specialista
777 287 451
";
        $mail->Send ();
    }
?>
