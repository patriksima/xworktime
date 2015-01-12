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
    require_once ('CUzivatelList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort', '');
    $page = CRequest::Get ('page', 1);
    $nazev = CRequest::Get ('nazev');
    
    $uzivatele = new CUzivatelList ();
    $uzivatele->SetOrder ($sort);
    if ($nazev) $uzivatele->SetFilter ('nazev', $nazev);
    $uzivatele->SetLimit (($page-1)*20, 20);
    $uzivatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('uzivatele', $uzivatele);

    if (@$_COOKIE['help_uzivatele']!='off') {
        $engine->assign ('help', true);
    }

    
    $engine->display ('uzivatele.tpl.htm');
?>
