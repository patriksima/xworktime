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
    
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'programátor'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'grafik'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'kodér'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'testér'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'projektový manažer'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'analytik'");
    mysql_query ("INSERT INTO xwt_dodavatel_typ SET nazev = 'údržbář'");
    
    mysql_query ("INSERT INTO xwt_dodavatel_stabilita SET nazev = 'stálý'");
    mysql_query ("INSERT INTO xwt_dodavatel_stabilita SET nazev = 'občasný'");
    mysql_query ("INSERT INTO xwt_dodavatel_stabilita SET nazev = 'nouzový'");
    mysql_query ("INSERT INTO xwt_dodavatel_stabilita SET nazev = 'potencionální'");
    
/*
INSERT INTO `ev-hodin`.`zadavatel` (
`id` ,
`nazev` ,
`sazba`
)
VALUES (
NULL , 'OVX.cz 2003 s.r.o.', '0'
), (
NULL , 'APImedia s.r.o.', '750'
);

INSERT INTO `dodavatel` VALUES (1, 5, 1, 'Patrik Šíma', 'Petra Křičky 19', 'Ostrava', 'Česká republika', '702 00', '1234567890', 'CZ1234567890', '+420 777287451', '+420 777287451', 'patrik@ovx.cz', '304372426', 'patrik.sima@jabbim.cz', 'patrik.sima@msn.com', 'http://www.ovx.cz', 'řízení projektů v ovx', 0, 0.00);
INSERT INTO `dodavatel` VALUES (2, 2, 1, 'Lukáš Žitník', '', '', 'Česká republika', '', '', '', '+420 ', '', 'patrik@ovx.cz', '', '', '', 'http://', '', 0, 450.00);

*/
?>
