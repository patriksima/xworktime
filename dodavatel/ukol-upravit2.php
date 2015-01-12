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
    require_once ('CPodklad.inc.php');
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
        $vars = array ('termin', 'popis', 'hodinyd', 'sazba');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $uzivatel = CAuth::GetUser ();
        
        $ukol = new CUkol ();
        $ukol->Load ($id);
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        
        $termin_old = $ukol->Get('termin');
        
        $sazba = str_replace(" ","",$sazba);
        $sazba = str_replace(",",".",$sazba);
        $hodinyd = str_replace(" ","",$hodinyd);
        $hodinyd = str_replace(",",".",$hodinyd);
        
        $error = new CError ();
        
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", CRequest::Post ('termin'))) {
            $error->PushMsg ('termin', 'Zadejte termín ve tvaru RRRR-MM-DD');
        }
        
        if ($ukol->Get('status')=='dokonceny' &&
            !preg_match("/^[0-9]+([,.][0-9]+)*$/", CRequest::Post ('hodinyd'))) {
            $error->PushMsg ('hodinyd', 'Zadejte počet hodin');
        }
        
        if ($ukol->Get('status')=='dokonceny' &&
            !preg_match("/^[0-9]+$/", CRequest::Post ('sazba'))) {
            $error->PushMsg ('sazba', 'Zadejte hodinovou sazbu');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('ukol-upravit.php?id='.$id);
        
        foreach ($vars as $k=>$v) {
            if ($v == 'sazba' || $v == 'hodinyd') continue;
            $ukol->Set ($v, $$v);
        }
        $ukol->Save ();
        
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get('id_dodavatel'));

        if ($ukol->Get('status')=='dokonceny') {
            $podklad = new CPodklad ();
            $podklad->LoadByUkol ($id);
            $podklad->Set ('hodinyd', $hodinyd);
            $podklad->Set ('sazbad', $sazba);
            $podklad->Save ();
        }        
        
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
        $mail->Subject = 'Úkol změněn | '.$subject;
        if ($ukol->Get('status')=='dokonceny') {
            $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." upravil dokončený úkol.

S pozdravem

Váš XWorkTime
";
        } else {
            $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." upravil probíhající úkol.";

        if ($termin_old!=$termin && $termin_old!='0000-00-00') {
            $mail->Body .= "\nPůvodní termín dokončení: ".strftime("%e. %B %Y", strtotime($termin_old))."
Nový termín dokončení: ".strftime("%e. %B %Y", strtotime($ukol->Get ('termin')));
        }
        
        $mail->Body .= "
        
S pozdravem

Váš XWorkTime
";
        }
        $mail->Send ();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>