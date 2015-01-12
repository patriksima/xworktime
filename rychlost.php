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
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CDodavatel.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $id_dodavatel = CRequest::Get ('id_dodavatel');
    
    if ($id_dodavatel) {
        $dodavatel = new CDodavatel();
        $dodavatel->Load($id_dodavatel);
        print '{"rychlost":"'.$dodavatel->Get('rychlost').'"}';
    }
?>
