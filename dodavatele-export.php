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
    require_once ('CRequest.inc.php');
    require_once ('CRedirect.inc.php');
    require_once ('CDodavatelList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    mysql_query ('SET NAMES cp1250');
    
    // odradkuje zaznam v csv
    function postprocess_csv($tpl_output, &$smarty) {
        $tpl_output = preg_replace("/\r\n/", "", $tpl_output);
        $tpl_output = preg_replace("/,;/", "\r\n", $tpl_output);
        $tpl_output = preg_replace("/,;/", "", $tpl_output);
        return $tpl_output;
    }
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->SetOrder ('nazev');
    $dodavatele->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('dodavatele', $dodavatele);
    
    $engine->register_outputfilter('postprocess_csv');
    
    header('Content-type: application/vnd.ms-excel; charset=windows-1250');
    header('Content-Disposition: attachment; filename="'.date("Y-m-d").'-xworktime.csv"');
    $engine->display ('dodavatele-export-csv.tpl.htm');
?>
