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
    require_once ('CAuth.inc.php');
    require_once ('CRedirect.inc.php');

    CAuth::Login ();
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('./');
    }
    
    if (CAuth::Check (CAuth::RUSER)) {
        CRedirect::To ('dodavatel/');
    }
    
    if (CAuth::Check (CAuth::RCLIENT)) {
        CRedirect::To ('klient/');
    }
    
    CRedirect::To ('prihlasit.php?fail');
?>
