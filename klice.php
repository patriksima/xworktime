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
    require_once ('CKliceList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $id_zadavatel = CRequest::Get ('id_zadavatel','');

    if (is_numeric($id_zadavatel)) {
        $klice = new CKliceList ();
        $klice->SetFilter ('id_zadavatel', $id_zadavatel);
        $klice->Load ();

        $klice = $klice->GetData();
        
        $json = array ();
        foreach ($klice as $k=>$v) {
            $json[] = json_encode($v['klic']);
        }
        $json = '{"klice":['.join(',',$json).']}';
        
        print $json;
    }
?>