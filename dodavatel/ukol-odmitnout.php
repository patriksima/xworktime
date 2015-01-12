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
    require_once ('CUkol.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatelExt.inc.php');
    
    $hash = CRequest::Get ('hash');
    
    if ($hash != '' && !CAuth::CheckHash ($hash)) {
        CRedirect::To ('prihlasit.php');
    }
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    

    $id  = ($hash=='') ? CRequest::Get ('id') : CAuth::CheckHash ($hash);
    
    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatelExt ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $ukol = new CUkol ();
    $ukol->Load ($id);
    if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
        CRedirect::To ('./');
    }
    
    if ($ukol->Check($id) && $ukol->Get('status')=='novy') {
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($ukol->Get ('id_zadavatel'));
        
        $engine->assign ('ROOTPATH', '../');
        $engine->assign ('ukol', $ukol);
        $engine->assign ('zadavatel', $zadavatel);
        $engine->assign ('dodavatel', $dodavatel);
        
        $engine->display ('ukol-odmitnout.tpl.htm');
    } else {
        $engine->assign ('ROOTPATH', '../');
        $engine->assign ('dodavatel', $dodavatel);
        
        $engine->display ('ukol-chyba.tpl.htm');
    }
?>
