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
    require_once ('CRedirect.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    require_once ('CZadavatelSazbyList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $list = CRequest::Get ('list');
    $id_zadavatel = CRequest::Get ('id_zadavatel');
    $id_dodavatel = CRequest::Get ('id_dodavatel');
    
    if ($id_zadavatel) {
        if ($list) {
            $json = array();
            $sazby = new CZadavatelSazbyList ();
            $sazby->SetFilter ('id_zadavatel', $id_zadavatel);
            $sazby->Load ();
            $sazbylist = $sazby->GetData();
            foreach($sazbylist as $k=>$v) {
                $json[] = '["'.$v['sazba'].'",'.json_encode($v['nazev']).']';
            }
            print '{"sazbaz":['.join(',', $json).']}';
        } else {
            $zadavatel = new CZadavatel();
            $zadavatel->Load($id_zadavatel);
            print '{"sazbaz":"'.$zadavatel->Get('sazba').'"}';
        }
    }
    
    if ($id_dodavatel) {
        $dodavatel = new CDodavatel();
        $dodavatel->Load($id_dodavatel);
        print '{"sazbad":"'.$dodavatel->Get('sazba').'"}';
    }
?>
