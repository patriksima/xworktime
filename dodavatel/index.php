<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2008 Patrik Šíma                                       |
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
    require_once ('CDodUkolList.inc.php');
    require_once ('CDodavatelExt.inc.php');
    require_once ('Query.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    $status = CRequest::Get ('status');
    $filtr = CRequest::Get ('filtr', 0);

    // defaultni razeni
    if ($filtr) {
        $status = ($status=='') ? array() : $status;
    } else {
        $status = ($status=='') ? array('novy', 'prijaty') : $status;
    }
    
    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatelExt ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $ukoly = new CDodUkolList ();
    $ukoly->SetOrder ($sort);
    $ukoly->SetLimit (($page-1)*20, 20);
    $ukoly->SetFilters ();
    $ukoly->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
    $ukoly->SetFilter ('status', $status);
    $ukoly->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('ukoly', $ukoly);
    $engine->assign ('dodavatel', $dodavatel);
    $engine->assign ('status', $status);
    $engine->assign ('Q', new Query);
    
    $engine->display ('ukoly.tpl.htm');