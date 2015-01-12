<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2010 Patrik Šíma                                       |
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
    require_once ('CDodavatelExt.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    

    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatelExt ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('dodavatel', $dodavatel);
    
    $engine->display ('novinky.tpl.htm');?>
