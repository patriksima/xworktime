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
// 
//

    require_once ('inc/CSetting.inc.php');
    require_once ('Smarty/Smarty.class.php');

    require_once ('CAuth.inc.php');
    require_once ('CError.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUkol.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('email');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", CRequest::Post ('email'))) {
            $error->PushMsg ('email', 'Zadejte platný e-mail dodavatele');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('vyzvat.php?id='.$id);
     
        $ukol = new CUkol ();
        $ukol->Load ($id);

        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get('id_dodavatel'));
        
        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByDodavatel ($ukol->Get('id_dodavatel'));
        
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
        $mail->AddAddress ($email);
        $mail->Subject = 'Nový úkol | '.$subject;
        $mail->Body    = "Dobrý den,

byl Vám přidělen nový úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')."

Termín pro splnění úkolu: ".(($ukol->Get ('termin')=='0000-00-00') ? "nezadán" : strftime("%e. %B %Y", strtotime($ukol->Get ('termin'))))."

Úkol můžete přijmout zde {$BASEURL}dodavatel/ukol-prijmout.php?hash=".md5($id.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."
nebo zamítnout zde {$BASEURL}dodavatel/ukol-odmitnout.php?hash=".md5($id.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."

V případě, že není určen termín, prosím o jeho vyplnění.

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
