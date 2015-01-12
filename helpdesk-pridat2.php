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
    require_once ('CHelpdesk.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    if ($_POST)
    {
        $vars = array ('id_zadavatel','id_dodavatel', 'typ', 'prava');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $error = new CError ();
        
        if (CRequest::Post ('id_zadavatel') == '') {
            $error->PushMsg ('id_zadavatel', 'Vyberte klienta');
        }

        if (CRequest::Post ('id_dodavatel') == '') {
            $error->PushMsg ('id_dodavatel', 'Vyberte dodavatele');
        }

        /*
        if (CRequest::Post ('typ') == '') {
            $error->PushMsg ('typ', 'Vyplňte typ');
        }*/

        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('pridat.php');

        $helpdesk =  new CHelpdesk;
        $helpdesk->Set('id_zadavatel', $id_zadavatel);
        $helpdesk->Set('id_dodavatel', $id_dodavatel);
        $helpdesk->Set('typ', $typ);
        $helpdesk->Set('prava', $prava);
        $helpdesk->Save();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
