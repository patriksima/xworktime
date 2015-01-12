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
    require_once ('CDodavatelExt.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('prihlasit.php');
    }    

    $id_ukol  = CRequest::Get ('id_ukol');
    $err = CRequest::Get ('err');
    
    $error = new CError ();
    $error->Load ($err);
    
    $uzivatel = CAuth::GetUser ();
    
    $dodavatel = new CDodavatelExt ();
    $dodavatel->Load ($uzivatel['id_dodavatel']);
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $ukol = new CUkol ();
    $ukol->Load ($id_ukol);
    if (!$ukol->CheckUser($uzivatel['id_dodavatel'])) {
        CRedirect::To ('./');
    }
    
    if ($ukol->Check($id_ukol)) { 
        $ukolchat = new CUkolChatList ();
        $ukolchat->SetFilter ('id_ukol', $id_ukol);
        $ukolchat->Load ();
        
        $engine->assign ('ROOTPATH', '../');
        $engine->assign ('error', $error);
        $engine->assign ('ukol', $ukol);
        $engine->assign ('ukolchat', $ukolchat);
        $engine->assign ('dodavatel', $dodavatel);
        
        $engine->display ('ukol-chat.tpl.htm');
    } else {
        $engine->assign ('ROOTPATH', '../');
        $engine->assign ('dodavatel', $dodavatel);
        
        $engine->display ('ukol-chyba.tpl.htm');
    }
?>