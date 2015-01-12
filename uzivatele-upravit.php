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
    require_once ('CUzivatel.inc.php');
    require_once ('CDodavatel.inc.php');
    require_once ('CDodavatelList2.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $id  = CRequest::Get ('id', '');
    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = new CUzivatel ();
    $uzivatel->Load ($id);
        
    $id_dodavatel = $uzivatel->Get('id_dodavatel');
    
    if (is_null($id_dodavatel)) {
        $dodavatele = new CDodavatelList ();
        $dodavatele->Load ();
    } else {
        $dodavatel = new CDodavatel ();
        $dodavatel->Load ($id_dodavatel);
    }
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('uzivatel', $uzivatel);
    if (is_null($id_dodavatel)) {
        $engine->assign ('dodavatele', $dodavatele);
    } else {
        $engine->assign ('dodavatel', $dodavatel);
    }
    
    $engine->display ('uzivatele-upravit.tpl.htm');
?>
