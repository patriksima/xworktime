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
    require_once ('CUkol.inc.php');
    require_once ('CPodklad.inc.php');
    require_once ('CDodavatel.inc.php');
    require_once ('CZadavatel.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = CAuth::GetUser ();
    
    $podklad = new CPodklad ();
    $podklad->Load ($id);
    
    $ukol = new CUkol ();
    $ukol->Load ($podklad->Get ('id_ukol'));
    if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
        CRedirect::To ('./');
    }
        
    $dodavatel = new CDodavatel ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
        
    $zadavatel = new CZadavatel ();
    $zadavatel->Load ($ukol->Get ('id_zadavatel'));
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';

    $engine->assign ('ROOTPATH', '../../');
    $engine->assign ('error', $error);
    $engine->assign ('ukol', $ukol);
    $engine->assign ('podklad', $podklad);
    $engine->assign ('dodavatel', $dodavatel);
    $engine->assign ('zadavatel', $zadavatel);
    
    $engine->display ('podklady-upravit.tpl.htm');
?>
