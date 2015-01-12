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
    require_once ('CError.inc.php');
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CPodkladList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CDodZadavatelList.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RCLIENT)) {
        CRedirect::To ('prihlasit.php');
    }    

    $sort = CRequest::Get ('sort', '');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = CAuth::GetUser ();
    
    $zadavatel = new CZadavatel ();
    $zadavatel->Load ($uzivatel['id_zadavatel']);
    
    $podklady = new CPodkladList ();
    
    if ($filtr) {
        // zapnuty filtr
        $sort = ($sort=='') ? '_splneno' : $sort;
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilters ();
        $podklady->SetFilter ('id_zadavatel', $uzivatel['id_zadavatel']);
        $podklady->Load ();
    } else {
        // defaultni pohled
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilter ('zaplacenz', '2');
        $podklady->SetFilter ('id_zadavatel', $uzivatel['id_zadavatel']);
        $podklady->Load ();
        if (!$podklady->GetCount()) {
            // kdyz nejsou v defaultnim pohledu data
            $podklady = new CPodkladList ();
            $podklady->SetOrder ($sort);
            $podklady->SetLimit (($page-1)*50, 50);
            $podklady->SetFilter ('id_zadavatel', $uzivatel['id_zadavatel']);
            $podklady->Load ();
        }
    }

    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('podklady', $podklady);
    $engine->assign ('zadavatel', $zadavatel);
    
    $engine->display ('podklady.tpl.htm');
?>
