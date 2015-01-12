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
    require_once ('CZadavatel.inc.php');
    require_once ('CZadavatelSazba.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    if ($_POST)
    {
        $nazev = CRequest::Post ('nazev', '');
        $sazba = CRequest::Post ('sazba', array());
        $sazbanazev = CRequest::Post ('sazbanazev', array());
                
        foreach($sazba as $k=>$v) {
            $v = str_replace(" ","",$v);
            $sazba[$k] = str_replace(",",".",$v);
        }
        
        $error = new CError ();
        
        if (CRequest::Post ('nazev') == '') {
            $error->PushMsg ('nazev', 'Zadejte název zadavatele');
        }
        
        $error->PushData ('nazev', $nazev);
        $error->PushTo ('pridat.php');

        $zadavatel = new CZadavatel ();
        $zadavatel->Set ('nazev', $nazev);
        $zadavatel->Set ('sazba', $sazba[0]);
        $id_zadavatel = $zadavatel->Save ();

        $zadavatel->UpdateHelpDesk($id_zadavatel);
        
        foreach($sazbanazev as $k=>$v) {
            if ($v=='') continue;
            $s = new CZadavatelSazba ();
            $s->Set ('id_zadavatel', $id_zadavatel);
            $s->Set ('nazev', $sazbanazev[$k]);
            $s->Set ('sazba', $sazba[$k]);
            $s->Save ();
            unset($s);
        }
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>