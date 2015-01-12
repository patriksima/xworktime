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
    require_once ('CPodkladList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');
    
    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../prihlasit.php');
    }    
    
    $sort   = CRequest::Get ('sort');
    $format = CRequest::Get('format');
    
    // vstupní proměnné musíme překódovat na stejný charset
    // jako má mysql collation
    if ($format==1)
    {
        $_GET['nazev'] = iconv ("utf-8", "windows-1250", $_GET['nazev']);
        $_GET['klic']  = iconv ("utf-8", "windows-1250", $_GET['klic']);
        $_GET['klic2'] = iconv ("utf-8", "windows-1250", $_GET['klic2']);
        mysql_query ('SET NAMES cp1250');
    }
    
    // odradkuje zaznam v csv
    function postprocess_csv($tpl_output, &$smarty) {
        //$tpl_output = preg_replace("/\r\n/", "", $tpl_output);
        //$tpl_output = preg_replace("/,;/", "\r\n", $tpl_output);
        $tpl_output = preg_replace("/,;/", "", $tpl_output);
        return $tpl_output;
    }
    
    $dodavatele = new CDodavatelList ();
    $dodavatele->Load ();

    $zadavatele = new CZadavatelList ();
    $zadavatele->Load ();
    
    $podklady = new CPodkladList ();
    $podklady->SetOrder ($sort);
    $podklady->SetFilters ();
    $podklady->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('podklady', $podklady);
    
    $engine->register_outputfilter('postprocess_csv');
    
    if ($format==1) {
        header('Content-type: application/vnd.ms-excel; charset=windows-1250');
        header('Content-Disposition: attachment; filename="'.date("Y-m-d").'-xworktime.csv"');
        $engine->display ('podklady-export-csv.tpl.htm');
    }
    if ($format==2) {
        header('Content-type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.date("Y-m-d").'-xworktime.csv"');
        $engine->display ('podklady-export-csv2.tpl.htm');
    }
?>
