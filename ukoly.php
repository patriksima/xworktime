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
    require_once ('CUkolList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    
    // defaultni razeni
    if ($filtr) {
        $sort = ($sort=='') ? '_splneno' : $sort;
    } else {
        $sort = ($sort=='') ? 'status' : $sort;
    }
    
    $ukoly = new CUkolList ();
    $ukoly->SetOrder ($sort);
    $ukoly->SetLimit (($page-1)*50, 50);
    $ukoly->SetFilters ();
    $ukoly->Load ();

    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('sort', $sort);
    $engine->assign ('ukoly', $ukoly);

    if (@$_COOKIE['help_ukoly']!='off') {
        $engine->assign ('help', true);
    }
   
    
    $engine->display ('ukoly.tpl.htm');
?>
