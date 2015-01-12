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
    
    require_once ('CAuth.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUkol.inc.php');
    require_once ('CUkolPrilohy.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id = CRequest::Get('id');
    
    $ukol = new CUkol ();
    $ukol->Load ($id);

    $priloha = new CUkolPrilohy();
    $priloha->LoadByUkol ($id);
    
    if ($ukol->Get ('splneno') == '0000-00-00') {
        
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get ('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get ('id_dodavatel'));
        
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
        $mail->Subject = 'Úkol zrušen | '.$subject;
        $mail->Body    = "Dobrý den,

úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')." byl zrušen.

S pozdravem

Váš XWorkTime
";
        $mail->Send ();

        @unlink('podpora/upload/'.$priloha->Get('soubor'));
        
        $ukol->Delete ($id);
    }
    
    CRedirect::To ($_SERVER['HTTP_REFERER']);
?>