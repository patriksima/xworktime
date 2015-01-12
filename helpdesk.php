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
    require_once ('Query.inc.php');

    require_once ('CHelpdeskList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    
    $helpdesk = new CHelpdeskList ();
    $helpdesk->SetOrder ($sort);
    $helpdesk->SetLimit (($page-1)*50, 50);
    $helpdesk->SetFilters();
    $helpdesk->Load();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('helpdesk', $helpdesk);
    $engine->assign ('Q', new Query);

    if (@$_COOKIE['help_helpdesk']!='off') {
        $engine->assign ('help', true);
    }

    $engine->display ('helpdesk.tpl.htm');
?>