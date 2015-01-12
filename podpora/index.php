<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2011 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <programator@patriksima.cz>                      |
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
    require_once ('CHelpdeskUkolList.inc.php');
    
    require_once ('Query.inc.php');

    $id_helpdesk = CRequest::Get ('id_helpdesk');

    $zadavatel = new CZadavatel();

    if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
        CRedirect::To ('../prihlasit.php');
    }

    $zadavatel->LoadByHelpDesk($id_helpdesk);

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    
    // defaultni razeni
    if ($filtr) {
        $sort = ($sort=='') ? '_splneno' : $sort;
    } else {
        $sort = ($sort=='') ? '_zadano' : $sort;
    }
    
    $ukoly = new CHelpdeskUkolList ();
    $ukoly->SetOrder ($sort);
    $ukoly->SetLimit (($page-1)*50, 50);
    $ukoly->SetFilters ();
    $ukoly->SetFilter ('id_zadavatel', $zadavatel->Get('id'));
    $ukoly->SetFilter ('status', 'dokonceny', '!=');
    $ukoly->Load ();
        
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('sort', $sort);
    $engine->assign ('ukoly', $ukoly);    
    $engine->assign ('zadavatel', $zadavatel);
    
    $engine->assign ('Q', new Query);
    
    $engine->display ('pozadavky.tpl.htm');
?>
