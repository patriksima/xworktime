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
        $vars = array ('popis', 'hodinyd', 'sazba');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $sazba = str_replace(" ","",$sazba);
        $sazba = str_replace(",",".",$sazba);
        $hodinyd = str_replace(" ","",$hodinyd);
        $hodinyd = str_replace(",",".",$hodinyd);
        
        $error = new CError ();
        $uzivatel = CAuth::GetUser ();
        
        if (!preg_match("/^[0-9]+([,.][0-9]+)*$/", CRequest::Post ('hodinyd'))) {
            $error->PushMsg ('hodinyd', 'Zadejte počet hodin');
        }
        
        if (!preg_match("/^[0-9]+$/", CRequest::Post ('sazba'))) {
            $error->PushMsg ('sazba', 'Zadejte hodinovou sazbu');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('ukol-splnen.php?id='.$id);
        
        $ukol = new CUkol ();
        $ukol->Load ($id);
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        foreach ($vars as $k=>$v) {
            if ($v == 'sazba' || $v == 'hodinyd') continue;
            $ukol->Set ($v, $$v);
        }
        $ukol->Set ('splneno', date('Y-m-d'));
        $ukol->Set ('status', 'dokonceny');
        $ukol->Save ();
        
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get('id_dodavatel'));
        
        $hodinyz = $dodavatel->Get('rychlost')*$hodinyd;
        $flr = floor($hodinyz);
        $des = $hodinyz-$flr;
        if ($des == 0.0) $hodinyz = $flr;
        else if ($des <= 0.25) $hodinyz = $flr + 0.25;
        else if ($des <= 0.50) $hodinyz = $flr + 0.50;
        else if ($des <= 0.75) $hodinyz = $flr + 0.75;
        else $hodinyz = $flr + 1.0;
        
        $podklad = new CPodklad ();
        $podklad->LoadByUkol($id);
        $podklad->Set ('id_ukol', $ukol->Get ('id'));
        $podklad->Set ('hodinyd', $hodinyd);
        $podklad->Set ('hodinyz', $hodinyz);
        $podklad->Set ('sazbaz', $zadavatel->Get ('sazba'));
        $podklad->Set ('sazbad', $sazba);
        if ($dodavatel->Get('nepocitat')) {
            $podklad->Set ('fakturad', $sazba);
            $podklad->Set ('zaplacend', $sazba);
        }        
        $podklad->Save ();
        
        
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
        $mail->Subject = 'Úkol splněn | '.$subject;
        $mail->Body    = "Dobrý den,

dodavatel ".$dodavatel->Get ('nazev')." splnil úkol.

Popis práce:
".$ukol->Get('popis')."

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
