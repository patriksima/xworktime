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
    
    require_once ('CError.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CUzivatel.inc.php');

    if ($_POST) {
        $email = CRequest::Post ('email', '');
        $prist_jmeno = CRequest::Post ('prist_jmeno', '');
        
        $error = new CError ();

        if ($prist_jmeno=='') {
            $error->PushMsg ('prist_jmeno', 'Zadejte přístupové jméno');
        }
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", $email)) {
            $error->PushMsg ('email', 'Zadejte platný e-mail');
        }
        
        $error->PushData ('email', $email);
        $error->PushTo ('zapomnel.php');
        
        $uzivatel = new CUzivatel ();
        if ($uzivatel->LoadByEmail ($prist_jmeno, $email)) {
            $prist_heslo = $uzivatel->GetNewPassword();
            $uzivatel->Set('prist_heslo', md5($prist_heslo));
            $uzivatel->Save ();
            
            require_once ('class.phpmailer.php');
            
            $mail = new PHPMailer ();
            $mail->From = $BASEMAIL;
            $mail->FromName = "XWorkTime";
            $mail->AddAddress ($email);
            $mail->Subject = 'Nové přístupy';
            $mail->Body    = "Dobrý den,

Vaše nové přístupové údaje jsou:

Přístupove jméno: $prist_jmeno
Přístupove heslo: $prist_heslo

S pozdravem

Váš XWorkTime
";
            $mail->Send ();
            
            CRedirect::To ('zapomnel.php?ok');
        }
        
        CRedirect::To ('zapomnel.php?fail');
    }
?>
