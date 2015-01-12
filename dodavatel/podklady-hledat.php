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
    require_once ('CDodavatel.inc.php');
    require_once ('CPodkladList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CDodZadavatelList.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort', '_splneno');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatel ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->Load ();

    $zadavatele = new CDodZadavatelList ();
    $zadavatele->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
    $zadavatele->Load ();
    
    $podklady = new CPodkladList ();
    if ($filtr) {
        // zapnuty filtr
        $sort = ($sort=='') ? '_splneno' : $sort;
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilters ();
        $podklady->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
        $podklady->Load ();
    } else {
        // defaultni pohled
        $sort = ($sort=='') ? '_fakturad' : $sort;
        $podklady->SetOrder ($sort);
        $podklady->SetLimit (($page-1)*50, 50);
        $podklady->SetFilter ('zaplacend', '2');
        $podklady->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
        $podklady->Load ();
        if (!$podklady->GetCount()) {
            // kdyz nejsou v defaultnim pohledu data
            $podklady = new CPodkladList ();
            $podklady->SetOrder ($sort);
            $podklady->SetLimit (($page-1)*50, 50);
            $podklady->SetFilter ('id_dodavatel', $uzivatel['id_dodavatel']);
            $podklady->Load ();
        }
    }
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../../');
    $engine->assign ('error', $error);
    $engine->assign ('podklady', $podklady);
    $engine->assign ('dodavatel', $dodavatel);
    $engine->assign ('dodavatele', $dodavatele);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('podklady-hledat.tpl.htm');
?>
