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
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('id_zadavatel', 'sazbaz', 'sazbaz2',
                       'id_dodavatel', 'sazbad',
                       'nazev', 'popis',
                       'hodinyd', 'hodinyz',
                       'klic', 'klic2', 'splneno',
                       'fakturaz', 'fakturad',
                       'zaplacenz', 'zaplacend');
        
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
        
        $podklad   = new CPodklad ();
        $zadavatel = new CZadavatel ();        
        $dodavatel = new CDodavatel ();
        
        if ($id != '' && !$podklad->Check($id)) {
            $error->PushMsg ('id_podklad', 'Tento podklad již neexistuje');
        }
        
        if ($id_zadavatel == '') {
            $error->PushMsg ('id_zadavatel', 'Vyberte zadavatele');
        }
        
        if ($id_zadavatel != '' && !$zadavatel->Check($id_zadavatel)) {
            $error->PushMsg ('id_zadavatel', 'Zadavatele již neexistuje');
        }

        if (CRequest::Post ('sazbaz') == '' && !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazbaz2)) {
            $error->PushMsg ('sazbaz', 'Zadejte sazbu pro zadavatele');
        }
        
        if ($id_dodavatel == '') {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele');
        }
        
        if ($id_dodavatel != '' && !$dodavatel->Check($id_dodavatel)) {
            $error->PushMsg ('id_dodavatel', 'Dodavatel již neexistuje');
        }
        
        if (!preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazbad)) {
            $error->PushMsg ('sazbad', 'Zadejte sazbu dodavatele');
        }
        
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Zadejte název úkolu');
        }

        if (!preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $hodinyd)) {
            $error->PushMsg ('hodinyd', 'Zadejte počet hodin');
        }
        
        if (!preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $hodinyz)) {
            $error->PushMsg ('hodinyz', 'Zadejte počet hodin');
        }
        
        if ($klic == '' && $klic2 == '') {
            $error->PushMsg ('klic', 'Zadejte klíčové slovo');
        }
        
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $splneno)) {
            $error->PushMsg ('splneno', 'Zadejte datum ve tvaru RRRR-MM-DD');
        }
        
        if ($klic2 != '') $klic = $klic2;
        if ($sazbaz2 != '') $sazbaz = $sazbaz2;
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('upravit.php?id='.$id);

        $podklad = new CPodklad ();
        $podklad->Load ($id);
        $hodinyd_old = $podklad->Get ('hodinyd');
        $hodinyz_old = $podklad->Get ('hodinyz');
        $sazbaz_old  = $podklad->Get ('sazbaz');
        $sazbad_old  = $podklad->Get ('sazbad');
        $podklad->Set ('hodinyd', $hodinyd);
        $podklad->Set ('hodinyz', $hodinyz);
        $podklad->Set ('sazbaz', $sazbaz);
        $podklad->Set ('sazbad', $sazbad);
        $podklad->Set ('fakturaz', $fakturaz);
        $podklad->Set ('fakturad', $fakturad);
        $podklad->Set ('zaplacenz', $zaplacenz);
        $podklad->Set ('zaplacend', $zaplacend);
        $podklad->Save ();
        
        $ukol = new CUkol ();
        $ukol->Load ($podklad->Get ('id_ukol'));
        $ukol->Set ('id_zadavatel', $id_zadavatel);
        $ukol->Set ('id_dodavatel', $id_dodavatel);
        $ukol->Set ('nazev', $nazev);
        $ukol->Set ('popis', $popis);
        $ukol->Set ('klic', $klic);
        $ukol->Set ('splneno', $splneno);
        $ukol->Save ();
        
        $zadavatel->Load ($id_zadavatel);
        $dodavatel->Load ($id_dodavatel);
        
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
        $mail->Subject = 'Podklad změněn | '.$subject;
        $mail->Body    = "Dobrý den,

podklad byl změněn.

Zadavatel: ".$zadavatel->Get('nazev')."
Dodavatel: ".$dodavatel->Get('nazev')."
Název: ".$nazev."
Popis: ".$popis."
Dod. hodiny: ".$hodinyd_old."->".number_format($hodinyd, 2, '.', '')."
Zad. hodiny: ".$hodinyz_old."->".number_format($hodinyz, 2, '.', '')."
Dod. sazba: ".sprintf("%d",$sazbad_old)."->".$sazbad."
Zad. sazba: ".sprintf("%d",$sazbaz_old)."->".$sazbaz."
Klíč. slovo: ".$klic."
Datum realizace: ".strftime("%e. %B %Y", strtotime($splneno))."

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
