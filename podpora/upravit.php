<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2011 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <programator@patriksima.cz>                      |
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
    require_once ('CUkolPrilohy.inc.php');
    require_once ('CZadavatel.inc.php');
    require_once ('CHelpdeskTypyList.inc.php');

    $id_helpdesk = CRequest::Get ('id_helpdesk');

    $zadavatel = new CZadavatel();

    if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
        CRedirect::To ('../prihlasit.php');
    }

    $zadavatel->LoadByHelpDesk($id_helpdesk);

    $err = CRequest::Get ('err', '');
    $id_ukol = CRequest::Get ('id_ukol', '');
    
    $error = new CError ();
    $error->Load ($err);

    $ukol = new CUkol ();
    $ukol->Load ($id_ukol);
    // overeni, zda pozadavek patri klientovi
    if (!$ukol->CheckZadavatel($zadavatel->Get('id'))) {
        CRedirect::To ('../prihlasit.php');        
    }

    $priloha = new CUkolPrilohy();
    $priloha->LoadByUkol($id_ukol);

    $typy = new CHelpdeskTypyList();
    $typy->SetFilter('id_zadavatel', $zadavatel->Get('id'));
    $typy->Load();
        
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';

    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('error', $error);
    $engine->assign ('ukol', $ukol);
    $engine->assign ('priloha', $priloha);
    $engine->assign ('zadavatel', $zadavatel);
    $engine->assign ('typy', $typy);
    
    $engine->display ('upravit.tpl.htm');
?>
