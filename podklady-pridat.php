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
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    // klice nacitej jen pri chybe pro vybraneho zadavatele
    $klice = new CKliceList ();
    if ($error->IsError() && $error->GetVal ('id_zadavatel')) {
        $klice->SetFilter ('id_zadavatel', $error->GetVal ('id_zadavatel'));
        $klice->Load ();
    }    
    
    // seznam dodavatelu
    $dodavatele = new CDodavatelList ();
    $dodavatele->SetOrder ('nazev');
    $dodavatele->SetFilter('status', 1);
    $dodavatele->Load ();

    // seznam zadavatelu
    $zadavatele = new CZadavatelList ();
    $zadavatele->SetOrder ('nazev');
    $zadavatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('klice', $klice);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-pridat.tpl.htm');
?>
