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
    require_once ('CUkolChatList.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CDodavatelExt.inc.php');
    
    $id_ukol  = CRequest::Get ('id_ukol');
    $id_helpdesk = CRequest::Get ('id_helpdesk');

    // kontrola helpdesk klice
    $zadavatel = new CZadavatel();

    if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
        CRedirect::To ('../prihlasit.php');
    }

    $zadavatel->LoadByHelpDesk($id_helpdesk);

    // kontrola ukolu
    $ukol = new CUkol();

    if (!$ukol->Check($id_ukol)) {
        CRedirect::To ('../podpora/?id_helpdesk='.$id_helpdesk);
    }

    $ukol->Load($id_ukol);

    if ($ukol->Get('id_zadavatel') != $zadavatel->Get('id')) {
        CRedirect::To ('../podpora/?id_helpdesk='.$id_helpdesk);
    }

    $err = CRequest::Get ('err');
    
    $error = new CError ();
    $error->Load ($err);
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $ukol = new CUkol ();
    $ukol->Load ($id_ukol);
    
    if ($ukol->Check($id_ukol)) { 
        $ukolchat = new CUkolChatList ();
        $ukolchat->SetFilter ('id_ukol', $id_ukol);
        $ukolchat->Load ();
        
        $engine->assign ('ROOTPATH', '../');
        $engine->assign ('error', $error);
        $engine->assign ('ukol', $ukol);
        $engine->assign ('ukolchat', $ukolchat);
        $engine->assign ('zadavatel', $zadavatel);
        
        $engine->display ('chat.tpl.htm');
    }
?>