<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2010 Patrik Šíma                                       |
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
        CRedirect::To ('../../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('popis', 'hodinyd', 'sazbad');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $sazbad = str_replace(" ","",$sazbad);
        $sazbad = str_replace(",",".",$sazbad);
        $hodinyd = str_replace(" ","",$hodinyd);
        $hodinyd = str_replace(",",".",$hodinyd);
        
        $error = new CError ();
        $uzivatel = CAuth::GetUser ();
                
        if (!preg_match("/^[0-9]+([,.][0-9]+)*$/", CRequest::Post ('hodinyd'))) {
            $error->PushMsg ('hodinyd', 'Zadejte počet hodin');
        }
        
        if (!preg_match("/^[0-9]+$/", CRequest::Post ('sazbad'))) {
            $error->PushMsg ('sazbad', 'Zadejte hodinovou sazbu');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('kopirovat.php?id='.$id);

        $podklad = new CPodklad ();
        $podklad->Load ($id);
        
        $ukol = new CUkol ();
        $ukol->Load ($podklad->Get ('id_ukol'));
        if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
            CRedirect::To ('./');
        }
        
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get('id_zadavatel'));
        
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($ukol->Get('id_dodavatel'));
        
        $ukolnew = new CUkol ();
        $ukolnew->Set ('id_zadavatel', $ukol->Get('id_zadavatel'));
        $ukolnew->Set ('id_dodavatel', $ukol->Get('id_dodavatel'));
        $ukolnew->Set ('nazev', $ukol->Get('nazev'));
        $ukolnew->Set ('popis', $popis);
        $ukolnew->Set ('klic', $ukol->Get('klic'));
        $ukolnew->Set ('termin', $ukol->Get('termin'));
        $ukolnew->Set ('splneno', date('Y-m-d'));
        $ukolnew->Set ('status', 'dokonceny');
        $id_ukol = $ukolnew->Save ();
        
        $hodinyz = $dodavatel->Get('rychlost')*$hodinyd;
        $flr = floor($hodinyz);
        $des = $hodinyz-$flr;
        if ($des == 0.0) $hodinyz = $flr;
        else if ($des <= 0.25) $hodinyz = $flr + 0.25;
        else if ($des <= 0.50) $hodinyz = $flr + 0.50;
        else if ($des <= 0.75) $hodinyz = $flr + 0.75;
        else $hodinyz = $flr + 1.0;
        
        $podkladnew = new CPodklad ();
        $podkladnew->Set ('id_ukol', $id_ukol);
        $podkladnew->Set ('hodinyd', $hodinyd);
        $podkladnew->Set ('hodinyz', $hodinyz);
        $podkladnew->Set ('sazbad', $sazbad);
        $podkladnew->Set ('sazbaz', $podklad->Get ('sazbaz'));
        $podkladnew->Save ();
                
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
        $mail->Subject = 'Nový podklad | '.$subject;
        $mail->Body    = "Dobrý den,

do evidence byl přidán nový podklad.

Zadavatel: ".$zadavatel->Get('nazev')."
Dodavatel: ".$dodavatel->Get('nazev')."
Název: ".$ukol->Get('nazev')."
Popis: ".$popis."
Dod. hodiny: ".$hodinyd."
Zad. hodiny: ".$hodinyz."
Dod. sazba: ".$sazbad."
Zad. sazba: ".$podklad->Get ('sazbaz')."
Klíč. slovo: ".$ukol->Get('klic')."
Datum realizace: ".strftime("%e. %B %Y", strtotime(date('Y-m-d')))."

S pozdravem

Patrik Šíma
internetový specialista
777 287 451
";
        $mail->Send ();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
