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
    require_once ('CUkol.inc.php');
    require_once ('CPodklad.inc.php');
    require_once ('CKliceList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');
    require_once ('CZadavatelSazbyList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $podklad = new CPodklad ();
    $podklad->Load ($id);
    
    $ukol = new CUkol ();
    if ($podklad->Check($id)) {
        $ukol->Load ($podklad->Get ('id_ukol'));
    }
    
    $klice = new CKliceList ();
    if ($error->IsError() && $error->GetVal ('id_zadavatel')) {
        $klice->SetFilter ('id_zadavatel', $error->GetVal ('id_zadavatel'));
    } else {
        $klice->SetFilter ('id_zadavatel', $ukol->Get ('id_zadavatel'));
    }
    $klice->Load ();    
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->SetOrder ('nazev');
    $dodavatele->Load ();

    $zadavatele = new CZadavatelList ();
    $zadavatele->SetOrder ('nazev');
    $zadavatele->Load ();
    
    $sazby = new CZadavatelSazbyList ();
    if ($podklad->Check($id)) {
        $sazby->SetFilter ('id_zadavatel', $ukol->Get('id_zadavatel'));
    } else {
        $sazby->SetFilter ('id_zadavatel', 0);
    }
    $sazby->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('klice', $klice);
    $engine->assign ('ukol', $ukol);
    $engine->assign ('sazby', $sazby);
    $engine->assign ('podklad', $podklad);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-duplikovat.tpl.htm');
?>
