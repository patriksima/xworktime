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
    require_once ('CZadavatelSazbyList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
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
        $error->PushTo ('upravit.php?id='.$id);

        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($id);
        $zadavatel->Set ('nazev', $nazev);
        $zadavatel->Set ('sazba', $sazba[0]);
        $zadavatel->Save ();

        $zadavatel->UpdateHelpDesk($id);
        
        $sazby = new CZadavatelSazbyList ();
        $sazby->SetFilter ('id_zadavatel', $id);
        $sazby->Load ();
        
        $sazbylist = $sazby->GetData();
        foreach($sazbylist as $k=>$v) {
            $s = new CZadavatelSazba ();
            $s->Delete ($v['id']);
        }
        
        foreach($sazbanazev as $k=>$v) {
            if ($v=='') continue;
            $s = new CZadavatelSazba ();
            $s->Set ('id_zadavatel', $id);
            $s->Set ('nazev', $sazbanazev[$k]);
            $s->Set ('sazba', $sazba[$k]);
            $s->Save ();
        }
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
