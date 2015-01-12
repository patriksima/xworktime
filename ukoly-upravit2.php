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
    require_once ('CPodklad.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('id_zadavatel', 'sazbaz', 'sazbaz2',
                       'id_dodavatel', 'notifikace', 'sazbad',
                       'status', 'nazev', 'popis',
                       'hodinyd', 'hodinyz',
                       'klic', 'klic2', 'termin');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $sazbaz  = str_replace(" ","",$sazbaz);
        $sazbaz  = str_replace(",",".",$sazbaz);
        $sazbaz2 = str_replace(" ","",$sazbaz2);
        $sazbaz2 = str_replace(",",".",$sazbaz2);
        $sazbad  = str_replace(" ","",$sazbad);
        $sazbad  = str_replace(",",".",$sazbad);
        $hodinyd = str_replace(" ","",$hodinyd);
        $hodinyd = str_replace(",",".",$hodinyd);
        $hodinyz = str_replace(" ","",$hodinyz);
        $hodinyz = str_replace(",",".",$hodinyz);
        
        $error = new CError ();
        
        $ukol      = new CUkol ();
        $zadavatel = new CZadavatel ();        
        $dodavatel = new CDodavatel ();
        
        if ($id != '' && !$ukol->Check($id)) {
            $error->PushMsg ('id_ukol', 'Tento úkol již neexistuje');
        }
        
        if ($id_zadavatel == '') {
            $error->PushMsg ('id_zadavatel', 'Vyberte zadavatele');
        }
        
        if ($id_zadavatel != '' && !$zadavatel->Check($id_zadavatel)) {
            $error->PushMsg ('id_zadavatel', 'Zadavatel již neexistuje');
        }
        
        if ($status == 'dokonceny' && CRequest::Post ('sazbaz') == '' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazbaz2)) {
            $error->PushMsg ('sazbaz', 'Zadejte sazbu pro zadavatele');
        }
        
        if ($id_dodavatel == '') {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele');
        }
        
        if ($id_dodavatel != '' && !$dodavatel->Check($id_dodavatel)) {
            $error->PushMsg ('id_dodavatel', 'Dodavatel již neexistuje');
        }
        
        if ($status == 'dokonceny' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazbad)) {
            $error->PushMsg ('sazbad', 'Zadejte sazbu pro dodavatele');
        }
        
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Zadejte název úkolu');
        }

        if ($status == 'dokonceny' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $hodinyd)) {
            $error->PushMsg ('hodinyd', 'Zadejte počet hodin');
        }
        
        if ($status == 'dokonceny' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $hodinyz)) {
            $error->PushMsg ('hodinyz', 'Zadejte počet hodin');
        }
        
        if ($klic == '' && CRequest::Post ('klic2') == '') {
            $error->PushMsg ('klic', 'Zadejte klíčové slovo');
        }
        
        if ($termin != '' && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $termin)) {
            $error->PushMsg ('termin', 'Zadejte termín ve tvaru RRRR-MM-DD');
        }
        
        if ($klic2 != '') $klic = $klic2;
        if ($sazbaz2 != '') $sazbaz = $sazbaz2;
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('upravit.php?id='.$id);

        $ukol = new CUkol ();
        $ukol->Load ($id);
        $termin_old = $ukol->Get('termin');
        $id_dodavatel_old = $ukol->Get('id_dodavatel');
        $ukol->Set ('id_zadavatel', $id_zadavatel);
        $ukol->Set ('id_dodavatel', $id_dodavatel);
        $ukol->Set ('status', $status);
        $ukol->Set ('nazev', $nazev);
        $ukol->Set ('popis', $popis);
        $ukol->Set ('klic', $klic);
        $ukol->Set ('termin', $termin);
        if ($status == 'dokonceny') {
            $ukol->Set ('splneno', date('Y-m-d'));
        } else {
            $ukol->Set ('splneno', '0000-00-00');
        }
        $ukol->Save ();
        
        if ($status == 'dokonceny') {
            $podklad = new CPodklad ();
            $podklad->LoadByUkol($id);
            $podklad->Set ('id_ukol', $id);
            $podklad->Set ('hodinyd', $hodinyd);
            $podklad->Set ('hodinyz', $hodinyz);
            $podklad->Set ('sazbaz', $sazbaz);
            $podklad->Set ('sazbad', $sazbad);
            $podklad->Save ();
        } else {
            $podklad = new CPodklad ();
            $podklad->LoadByUkol($id);
            if ($podklad->Get('id')) {
                $podklad->Delete($podklad->Get('id'));
            }
        }
        
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
            
            // úkol byl přiřazen jinému dodavateli, upozorníme původního,
            // že mu byl úkol zrušen 
            if ($id_dodavatel_old != $id_dodavatel) {
                $dodavatel_old = new CDodavatel ();
                $dodavatel_old->Load ($id_dodavatel_old);
                
                $mail = new PHPMailer ();
                $mail->From = $BASEMAIL;
                $mail->FromName = "XWorkTime";
                $mail->AddAddress ($dodavatel_old->Get ('email'));
                $mail->Subject = 'Úkol zrušen | '.$subject;
                $mail->Body    = "Dobrý den,

úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')." byl zrušen.

S pozdravem

Váš XWorkTime
";
                $mail->Send ();
            }
        
            $mail = new PHPMailer ();
            $mail->From = $BASEMAIL;
            $mail->FromName = "XWorkTime";
            $mail->AddAddress ($dodavatel->Get ('email'));
            
            if ($ukol->Get('status') == 'novy') {
                $mail->Subject = 'Nový úkol | '.$subject;
                $mail->Body    = "Dobrý den,

byl Vám přidělen nový úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')."

Termín pro splnění úkolu: ".(($ukol->Get ('termin')=='') ? "nezadán" : strftime("%e. %B %Y", strtotime($ukol->Get ('termin'))))."

Úkol můžete přijmout zde {$BASEURL}dodavatel/ukol-prijmout.php?hash=".md5($id.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."
nebo zamítnout zde {$BASEURL}dodavatel/ukol-odmitnout.php?hash=".md5($id.'x'.$uzivatel->Get('prist_jmeno').'x'.$uzivatel->Get('prist_heslo'))."

V případě, že není určen termín, prosím o jeho vyplnění.

S pozdravem

Váš XWorkTime
";
            } else {
                if ($termin_old == $termin) {
                    $mail->Subject = 'Úkol změněn | '.$subject;
                    $mail->Body    = "Dobrý den,

úkol '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')." byl změněn.

S pozdravem

Váš XWorkTime
";
                } else {                
                    $mail->Subject = 'Úkol změněn | '.$subject;
                    $mail->Body    = "Dobrý den,

úkolu '".$ukol->Get('nazev')."' pro zadavatele ".$zadavatel->Get ('nazev')."
byl změněn termín z ".strftime("%e. %B %Y", strtotime($termin_old))." na ".strftime("%e. %B %Y", strtotime($termin)).".

S pozdravem

Váš XWorkTime
";
                }
            }
            $mail->Send ();
        }
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
