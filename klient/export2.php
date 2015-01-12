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
    require_once ('CPodkladList.inc.php');
    require_once ('CDodavatelList.inc.php');
    require_once ('CZadavatelList.inc.php');
    
    if (CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('../');
    }
    
    if (!CAuth::Check (CAuth::RCLIENT)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $user = CAuth::GetUser();
    
    mysql_query ('SET NAMES cp1250');
    
    // odradkuje zaznam v csv
    function postprocess_csv($tpl_output, &$smarty) {
        //$tpl_output = preg_replace("/\r\n/", "", $tpl_output);
        //$tpl_output = preg_replace("/,;/", "\r\n", $tpl_output);
        $tpl_output = preg_replace("/,;/", "", $tpl_output);
        return $tpl_output;
    }
    
    $podklady = new CPodkladList ();
    $podklady->SetFilters ();
    $podklady->SetFilter ('id_zadavatel', $user['id_zadavatel']);
    $podklady->Load ();
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    
    $engine->assign ('ROOTPATH', '../');
    $engine->assign ('podklady', $podklady);
    
    $engine->register_outputfilter('postprocess_csv');
    
    if (CRequest::Get('format')==1) {
        header('Content-type: application/vnd.ms-excel; charset=windows-1250');
        header('Content-Disposition: attachment; filename="'.date("Y-m-d").'-xworktime.csv"');
        $engine->display ('podklady-export-csv.tpl.htm');
    }
?>
