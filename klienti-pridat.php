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
    require_once ('CZadavatelList2.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $zadavatele = new CZadavatelList ();
    $zadavatele->Load ();
        
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('zadavatele', $zadavatele);
    
    $engine->display ('klienti-pridat.tpl.htm');
?>
