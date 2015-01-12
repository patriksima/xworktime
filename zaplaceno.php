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
    
    require_once ('CAuth.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CPodklad.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $typ = CRequest::Get ('typ');
    $id_podklady = CRequest::Get ('id_podklad');
    
    foreach ($id_podklady as $k=>$id_podklad) {
        $podklad = new CPodklad();
        $podklad->Load ($id_podklad);
        switch ($typ) {
            case 'zf':
                $podklad->Set ('fakturaz', 1);
                break;
            case 'df':
                $podklad->Set ('fakturad', 1);
                break;
            case 'zz':
                $podklad->Set ('zaplacenz', 1);
                break;
            case 'dz':
                $podklad->Set ('zaplacend', 1);
                break;
        }
        $podklad->Save ();
    }

    //print_r($id_podklady);    
?>
