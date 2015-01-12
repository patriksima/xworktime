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
    require_once ('CHelpdeskTypyList.inc.php');
    
    if (isset($_GET['term']) && $_GET['term']!='' && isset($_GET['type']) && $_GET['type']=='typy')
    {
        $typy = new CHelpdeskTypyList();
        $typy->SetFilter('typ', $_GET['term']);
        $typy->Load();

        $json = array();
        foreach($typy->GetData() as $typ) {
            $json[] = '{"label":"'.$typ['typ'].'","value":"'.$typ['typ'].'"}';
        }
        if (count($json)) {
            die('['.join(', ',$json).']');
        }
    }