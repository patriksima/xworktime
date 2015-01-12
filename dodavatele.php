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
    require_once ('CDodavatelList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $sort = CRequest::Get ('sort', '');
    $page = CRequest::Get ('page', 1);
    $nazev = CRequest::Get ('nazev');
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->SetOrder ($sort);
    if ($nazev) $dodavatele->SetFilter ('nazev', $nazev);
    $dodavatele->SetLimit (($page-1)*20, 20);
    $dodavatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('dodavatele', $dodavatele);

    if (@$_COOKIE['help_dodavatele']!='off') {
        $engine->assign ('help', true);
    }

    
    $engine->display ('dodavatele.tpl.htm');
?>
