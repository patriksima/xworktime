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
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatel.inc.php');
    require_once ('CZadavatelList.inc.php');
    require_once ('CDodavatelList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $ukol = new CUkol ();
    $ukol->Load ($id);
    $ukol->Set('status', 'novy');
    $ukol->Set('termin', date('Y-m-d'));
        
    if ($error->IsError() && $error->GetVal ('id_zadavatel')) {
        $id_zadavatel = $error->GetVal ('id_zadavatel');
    } else {
        $id_zadavatel = $ukol->Get ('id_zadavatel');
    }

    if ($error->IsError() && $error->GetVal ('id_dodavatel')) {
        $id_dodavatel = $error->GetVal ('id_dodavatel');
    } else {
        $id_dodavatel = $ukol->Get ('id_dodavatel');
    }
    
    $klice = new CKliceList ();
    $klice->SetFilter ('id_zadavatel', $id_zadavatel);
    $klice->Load ();
    
    $podklad = new CPodklad ();
    if ($ukol->Check($id) && $ukol->Get('status')=='dokonceny') {
        $podklad->LoadByUkol($id);
    }
    
    $zadavatel = new CZadavatel ();
    $zadavatel->Load ($id_zadavatel);
    
    $dodavatel = new CDodavatel ();
    $dodavatel->Load ($id_dodavatel);
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->SetOrder ('nazev');
    $dodavatele->Load ();

    $zadavatele = new CZadavatelList ();
    $zadavatele->SetOrder ('nazev');
    $zadavatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('ukol', $ukol);
    $engine->assign ('klice', $klice);
    $engine->assign ('podklad', $podklad);
    $engine->assign ('zadavatel2', $zadavatel);
    $engine->assign ('dodavatel2', $dodavatel);
    $engine->assign ('zadavatele', $zadavatele);
    $engine->assign ('dodavatele', $dodavatele);
    
    $engine->display ('ukoly-kopirovat.tpl.htm');
?>
