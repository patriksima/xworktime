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
    require_once ('CUkol.inc.php');
    require_once ('CUkolChatList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    

    $sort = CRequest::Get ('sort');
    $page = CRequest::Get ('page', 1);
    $filtr = CRequest::Get ('filtr', 0);
    $id_ukol = CRequest::Get ('id_ukol', '');

    if (!is_numeric($id_ukol)) {
        CRedirect::To ('../ukoly.php');
    }

    $err = CRequest::Get ('err', '');
    
    $error = new CError ();
    $error->Load ($err);

    $ukol = new CUkol ();
    $ukol->Load ($id_ukol);

    $ukolchat = new CUkolChatList ();
    $ukolchat->SetFilter ('id_ukol', $id_ukol);
    $ukolchat->Load ();

    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('ukol', $ukol);
    $engine->assign ('ukolchat', $ukolchat);
    
    $engine->display ('ukoly-chat.tpl.htm');
?>
