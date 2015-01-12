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
        $vars = array ('id_zadavatel', 'id_dodavatel', 'notifikace',
                       'nazev', 'popis', 'klic', 'klic2', 'termin');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        $zadavatel = new CZadavatel ();        
        $dodavatel = new CDodavatel ();
        
        if ($id_zadavatel == '') {
            $error->PushMsg ('id_zadavatel', 'Vyberte zadavatele');
        }
        
        if ($id_zadavatel != '' && !$zadavatel->Check($id_zadavatel)) {
            $error->PushMsg ('id_zadavatel', 'Zadavatel již neexistuje');
        }

        if ($id_dodavatel == '') {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele');
        }
        
        if ($id_dodavatel != '' && !$dodavatel->Check($id_dodavatel)) {
            $error->PushMsg ('id_dodavatel', 'Dodavatel již neexistuje');
        }
        
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Zadejte název úkolu');
        }

        if ($klic == '' && CRequest::Post ('klic2') == '') {
            $error->PushMsg ('klic', 'Zadejte klíčové slovo');
        }
        
        if ($termin != '' && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $termin)) {
            $error->PushMsg ('termin', 'Zadejte termín ve tvaru RRRR-MM-DD');
        }
        
        if ($klic2 != '') $klic = $klic2;
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('kopirovat.php?id='.$id);

        $ukol = new CUkol ();
        $ukol->Set ('id_zadavatel', $id_zadavatel);
        $ukol->Set ('id_dodavatel', $id_dodavatel);
        $ukol->Set ('nazev', $nazev);
        $ukol->Set ('popis', $popis);
        $ukol->Set ('klic', $klic);
        $ukol->Set ('zadano', date('Y-m-d'));
        $ukol->Set ('termin', $termin);
        $ukol->Set ('status', 'novy');
        $id_ukol = $ukol->Save ();
                
        $zadavatel->Load ($id_zadavatel);
        $dodavatel->Load ($id_dodavatel);
        
        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByDodavatel ($id_dodavatel);
        
        if ($notifikace)
        {
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
            $mail->Subject = 'Nový úkol | '.$subject;
            $mail->Body    = "Dobrý den,

byl Vám přidělen nový úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')."

Termín pro splnění úkolu: ".(($ukol->Get ('termin')=='') ? "nezadán" : strftime("%e. %B %Y", strtotime($ukol->Get ('termin'))))."

Úkol můžete přijmout zde {$BASEURL}dodavatel/ukol-prijmout.php?hash=".md5($id_ukol.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."
nebo zamítnout zde {$BASEURL}dodavatel/ukol-odmitnout.php?hash=".md5($id_ukol.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."

V případě, že není určen termín, prosím o jeho vyplnění.

S pozdravem

Váš XWorkTime
";
            $mail->Send ();
        }
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
