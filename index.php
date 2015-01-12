<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2008 Patrik Šíma                                       |
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
    require_once ('CPodkladList.inc.php');

    if (!CAuth::Check (CAuth::RADMIN)) {
        CRedirect::To ('prihlasit.php');
    }    
    
    $sql = "SELECT SUM(nefakturovano) as nefakturovano FROM xwt_zadavatel_nefakturovano";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $zadavatele_nefakturovano = $row['nefakturovano'];
    
    $sql = "SELECT SUM(dluh) as dluh FROM xwt_zadavatel_dluh";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $zadavatele_dluh = $row['dluh'];
    
    $sql = "SELECT SUM(nefakturovano) as nefakturovano FROM xwt_dodavatel_nefakturovano";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $dodavatele_nefakturovano = $row['nefakturovano'];
    
    $sql = "SELECT SUM(dluh) as dluh FROM xwt_dodavatel_dluh";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $dodavatele_dluh = $row['dluh'];

    $sql = "SELECT SUM(hodinyz*sazbaz-hodinyd*sazbad) as zisk
              FROM xwt_podklady as p, xwt_ukol as u
             WHERE p.id_ukol = u.id AND u.status='dokonceny'";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $zisk = $row['zisk'];

    $sql = "SELECT SUM(hodinyz*sazbaz-hodinyd*sazbad) as zisk
              FROM xwt_podklady as p, xwt_ukol as u
             WHERE p.id_ukol = u.id AND u.status='dokonceny'
               AND p.zaplacenz = 1 AND p.zaplacend = 1";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    $row = mysql_fetch_assoc ($res);
    $zisk_skutecny = $row['zisk'];
    
    $engine = new Smarty ();
    $engine->template_dir = 'templates/';
    $engine->compile_dir  = 'compiled/';
    $engine->config_dir   = 'config/';
    
    $engine->assign ('ROOTPATH', './');
    $engine->assign ('zadavatele_nefakturovano', $zadavatele_nefakturovano);
    $engine->assign ('zadavatele_dluh', $zadavatele_dluh);
    $engine->assign ('dodavatele_nefakturovano', $dodavatele_nefakturovano);
    $engine->assign ('dodavatele_dluh', $dodavatele_dluh);
    $engine->assign ('zisk', $zisk);
    $engine->assign ('zisk_skutecny', $zisk_skutecny);

    if (@$_COOKIE['help_index']!='off') {
        $engine->assign ('help', true);
    }
    
    $engine->display ('index.tpl.htm');
?>
