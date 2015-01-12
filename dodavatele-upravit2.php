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
    require_once ('CDodavatel.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id = CRequest::Get ('id', '');
    
    if ($_POST)
    {
        $vars = array ('nazev', 'id_typ', 'id_stabilita', 'nepocitat', 
                       'status', 'sazba', 'rychlost',
                       'ulice', 'mesto', 'stat', 'psc', 'ic', 'dic',
                       'tel', 'fax', 'email', 'icq', 'jabber', 'msn',
                       'www', 'poznamka');
        
        foreach ($vars as $k=>$v) {
            $$v = CRequest::Post ($v, '');
        }
        
        $sazba = str_replace(" ","",$sazba);
        $sazba = str_replace(",",".",$sazba);
        $rychlost = str_replace(" ","",$rychlost);
        $rychlost = str_replace(",",".",$rychlost);
        
        $error = new CError ();
        
        if ($nazev == '') {
            $error->PushMsg ('nazev', 'Zadejte název dodavatele');
        }

        if ($id_typ == '') {
            $error->PushMsg ('id_typ', 'Vyberte typ dodavatele');
        }
        
        if ($id_stabilita == '') {
            $error->PushMsg ('id_stabilita', 'Vyberte stabilitu dodavatele');
        }

        if (CRequest::Post ('sazba')=='' || !preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $sazba)) {
            $error->PushMsg ('sazba', 'Zadejte hodinovou sazbu dodavatele');
        }
        
        if (!preg_match("/^[0-9]+([,|.][0-9]+){0,1}$/", $rychlost)) {
            $error->PushMsg ('sazba', 'Zadejte koeficient rychlosti');
        }
        
        if (!preg_match("/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/", $email)) {
            $error->PushMsg ('email', 'Zadejte platný e-mail dodavatele');
        }
        
        foreach ($vars as $k=>$v) {
            $error->PushData ($v, $$v);
        }
        $error->PushTo ('upravit.php?id='.$id);

        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($id);
        foreach ($vars as $k=>$v) {
            $dodavatel->Set ($v, $$v);
        }
        $dodavatel->Save ();
    }
    
    $query = CRequest::Post ('query');
    $url = './';
    $url.= ($query!='') ? '?'.$query : '';
    
    CRedirect::To ($url);
?>
