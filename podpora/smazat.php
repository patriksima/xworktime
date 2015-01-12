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
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');

    require_once ('CZadavatel.inc.php');
    require_once ('CUkol.inc.php');
    require_once ('CUkolPrilohy.inc.php');
    
    require_once ('Query.inc.php');

    $id_helpdesk = CRequest::Get ('id_helpdesk');

    $zadavatel = new CZadavatel();

    if (!$zadavatel->CheckHelpDesk ($id_helpdesk)) {
        CRedirect::To ('../prihlasit.php');
    }

    $zadavatel->LoadByHelpDesk($id_helpdesk);

    $id_ukol = CRequest::Get ('id_ukol', '');
    $id_priloha = CRequest::Get ('id_priloha', '');

    $ukol = new CUkol ();
    $ukol->Load ($id_ukol);
    // overeni, zda pozadavek patri klientovi
    if (!$ukol->CheckZadavatel($zadavatel->Get('id'))) {
        CRedirect::To ('../prihlasit.php');        
    }

    $priloha = new CUkolPrilohy();
    $priloha->Load($id_priloha);
    $priloha->Remove();
    $priloha->Delete($id_priloha);

    die('{"status":"ok"}');