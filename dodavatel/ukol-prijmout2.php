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
        $vars = array ('termin');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        $uzivatel = CAuth::GetUser ();
        
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", CRequest::Post ('termin'))) {
            $error->PushMsg ('termin', 'Zadejte termín ve tvaru RRRR-MM-DD');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('ukol-prijmout.php?id='.$id);

        $ukol = new CUkol ();
        $ukol->Load ($id);
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        $termin_old = $ukol->Get('termin');
        $ukol->Set ('termin', $termin);
        $ukol->Set ('status', 'prijaty');
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
        $mail->Subject = 'Úkol přijat | '.$subject;
        $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." přijal úkol";

        if ($termin_old!=$termin && $termin_old!='0000-00-00') {
            $mail->Body .= ", ale změnil termín dokončení na ".strftime("%e. %B %Y", strtotime($ukol->Get ('termin')));
        } else {
            $mail->Body .= " s termínem dokončení ".strftime("%e. %B %Y", strtotime($ukol->Get ('termin')));
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
