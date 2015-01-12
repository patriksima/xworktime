<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2011 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <programator@patriksima.cz>                      |
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
    require_once ('CUkolPrilohy.inc.php');
    require_once ('CHelpdesk.inc.php');
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');

    if ($_POST)
    {
        $vars = array ('jmeno', 'typ', 'nazev', 'popis');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }

        $id_helpdesk = CRequest::Get ('id_helpdesk');
        

        $zadavatel = new CZadavatel();

        if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
            CRedirect::To ('../prihlasit.php');
        }

        $zadavatel->LoadByHelpDesk($id_helpdesk);

        $error = new CError ();

        if ($jmeno == '') {
            $error->PushMsg ('jmeno', 'Vyplňte své jméno.');
        }
        if ($typ == '') {
            $error->PushMsg ('typ', 'Vyberte typ požadavku.');
        }
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Napište svůj požadavek.');
        }

        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('ticket.php?id_helpdesk='.$id_helpdesk);

        $helpdesk = new CHelpdesk();
        $helpdesk->LoadByParams($zadavatel->Get('id'), $typ);

        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByZadavatel ($helpdesk->Get('id_zadavatel'));     

        $ukol = new CUkol ();
        $ukol->Set ('id_zadavatel', $helpdesk->Get('id_zadavatel'));
        $ukol->Set ('id_dodavatel', $helpdesk->Get('id_dodavatel'));
        $ukol->Set ('vytvoril', $uzivatel->Get('email'));
        $ukol->Set ('nazev', $jmeno.': '.$nazev);
        $ukol->Set ('popis', $popis);
        $ukol->Set ('klic', $typ);
        $ukol->Set ('zadano', date('Y-m-d'));
        $ukol->Set ('status', 'novy');
        $id_ukol = $ukol->Save ();

        $prilohy = new CUkolPrilohy ();
        $prilohy->Upload($id_ukol);

        $dodavatel = new CDodavatel();
        $dodavatel->Load($helpdesk->Get('id_dodavatel'));

        $uzivatel = new CUzivatel ();
        $uzivatel->LoadByDodavatel ($helpdesk->Get('id_dodavatel'));        

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

        $mail = new PHPMailer ();
        $mail->From = $BASEMAIL;
        $mail->FromName = "XWorkTime";
        $mail->AddAddress ($zadavatel->Get ('email'));
        $mail->Subject = 'Helpdesk Ticket';
        $mail->Body    = "Dobrý den,

váš požadavek byl přijat.

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
