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

    for ($i=0; $i<100; $i++) {    
        mysql_query ("INSERT INTO xwt_ukol SET zadano = NOW(),id_zadavatel = '5',id_dodavatel = '1',nazev = 'nazev',popis = 'popis',klic = 'klic',termin = '2008-03-21',status = 'novy'");
    }
?>
