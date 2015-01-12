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
    
    require_once ('../inc/CSetting.inc.php');
    require_once ('Smarty/Smarty.class.php');
    
    require_once ('CAuth.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CDodavatel.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CDodZadavatelList.inc.php');

    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatel ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->Load ();

    $zadavatele = new CDodZadavatelList ();
    $zadavatele->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
    $zadavatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../../');
    $engine->assign ('dodavatel', $dodavatel);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-export.tpl.htm');
?>
