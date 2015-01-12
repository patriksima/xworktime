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
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUkol.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $duvod = CRequest::Post ('duvod');
        
        $uzivatel = CAuth::GetUser ();
        
        $ukol = new CUkol ();
        $ukol->Load ($id);
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        
        $ukol->Set ('status', 'zamitnuty');
        $ukol->Save ();
        
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get('id_dodavatel'));
        
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
        if ($ukol->Get('vytvoril') != ''){
            foreach (explode(',', $ukol->Get('vytvoril')) as $cc) {
                $mail->AddCC($cc);
            }
        }
        $mail->Subject = 'Úkol odmítnut | '.$subject;
        $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." úkol odmítnul";
        
        if ($duvod != "") {
            $mail->Body .= " s uvedením důvodu:\n$duvod";
        } else {
            $mail->Body .= " bez uvedení důvodu.";
        }

        $mail->Body .= "
        
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
