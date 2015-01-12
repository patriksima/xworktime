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
    require_once ('CPodklad.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id = CRequest::Get('id');
        
    $podklad = new CPodklad ();
    $podklad->Load ($id);
    
    $ukol = new CUkol ();
    $ukol->Load ($podklad->Get ('id_ukol'));
    
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
    $mail->AddAddress ($BASEMAIL);
    $mail->Subject = 'Podklad smazán | '.$subject;
    $mail->Body    = "Dobrý den,

podklad s těmito údaji byl smazán.

Zadavatel: ".$zadavatel->Get('nazev')."
Dodavatel: ".$dodavatel->Get('nazev')."
Název: ".$ukol->Get('nazev')."
Popis: ".$ukol->Get('popis')."
Dod. hodiny: ".$podklad->Get('hodinyd')."
Zad. hodiny: ".$podklad->Get('hodinyz')."
Klíč. slovo: ".$ukol->Get('klic')."
Datum realizace: ".strftime("%e. %B %Y", strtotime($ukol->Get('splneno')))."

S pozdravem

Váš XWorkTime
";
    $mail->Send ();
    
    $podklad->Delete ($id);
    $ukol->Delete ($podklad->Get ('id_ukol'));
    
    CRedirect::To ($_SERVER['HTTP_REFERER']);
?>
