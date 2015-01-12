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
    require_once ('CKliceList.inc.php');
    require_once ('CPodkladList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    $id_zadavatel = CRequest::Get ('id_zadavatel');
    
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
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
    
    $podklady = new CPodkladList ();
    if ($filtr) {
        // zapnuty filtr
        $sort = ($sort=='') ? '_splneno' : $sort;
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilters ();
        $podklady->Load ();
    } else {
        // defaultni pohled
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilter ('zaplacenz', '2');
        $podklady->SetFilter ('zaplacend', '2');
        $podklady->Load ();
        if (!$podklady->GetCount()) {
            // kdyz nejsou v defaultnim pohledu data
            $podklady = new CPodkladList ();
            $podklady->SetOrder ($sort);
            $podklady->SetLimit (($page-1)*50, 50);
            $podklady->Load ();
        }
    }
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('klice', $klice);
    $engine->assign ('podklady', $podklady);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-hledat.tpl.htm');
?>
