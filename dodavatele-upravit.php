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
    require_once ('CDodavatel.inc.php');
    require_once ('CDodavatelTypList.inc.php');
    require_once ('CDodavatelStabilitaList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $dodavatel = new CDodavatel ();
    $dodavatel->Load ($id);
    
    $dodavateltyplist = new CDodavatelTypList ();
    $dodavateltyplist->Load ();
    
    $dodavatelstabilitalist = new CDodavatelStabilitaList ();
    $dodavatelstabilitalist->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('dodavatel', $dodavatel);
    $engine->assign ('dodavateltyplist', $dodavateltyplist);
    $engine->assign ('dodavatelstabilitalist', $dodavatelstabilitalist);
    
    $engine->display ('dodavatele-upravit.tpl.htm');
?>
