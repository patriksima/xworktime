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
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CDodZadavatelList.inc.php');

    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RCLIENT)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $uzivatel = CAuth::GetUser ();
    
    $zadavatel = new CZadavatel ();
    $zadavatel->Load ($uzivatel['id_zadavatel']);
        
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('zadavatel', $zadavatel);
    
    $engine->display ('podklady-export.tpl.htm');
?>
