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
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CKliceList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }

    $id_zadavatel = CRequest::Get ('id_zadavatel');    
    
    // klice nacitej jen pro vybraneho zadavatele
    $klice = new CKliceList ();
    if (is_numeric($id_zadavatel)) {
        $klice->SetFilter ('id_zadavatel', $id_zadavatel);
        $klice->Load ();
    }    
    
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
    $engine->assign ('klice', $klice);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-export.tpl.htm');
?>
