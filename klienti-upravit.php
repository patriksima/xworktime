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
    require_once ('CUzivatel.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CZadavatelList2.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = new CUzivatel ();
    $uzivatel->Load ($id);
        
    $id_zadavatel = $uzivatel->Get('id_zadavatel');
    
    if (is_null($id_zadavatel)) {
        $zadavatele = new CZadavatelList ();
        $zadavatele->Load ();
    } else {
        $zadavatel = new CZadavatel ();
        $zadavatel->Load ($id_zadavatel);
    }
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('uzivatel', $uzivatel);
    if (is_null($id_zadavatel)) {
        $engine->assign ('zadavatele', $zadavatele);
    } else {
        $engine->assign ('zadavatel', $zadavatel);
    }
    
    $engine->display ('klienti-upravit.tpl.htm');
?>
