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
// 
//
    
    require_once ('../inc/CSetting.inc.php');
    
    mysql_select_db('podklady');
    
    header('Content-type:text/plain');
    
    // zadavatele
    $sql = "SELECT * FROM zadavatel";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    while($row = mysql_fetch_assoc($res)) {
        print ("INSERT INTO xwt_zadavatel SET id = {$row['id']}, nazev = '{$row['nazev']}', sazba='{$row['sazba']}';\n");
        print ("INSERT INTO xwt_zadavatel_sazba SET id_zadavatel = {$row['id']}, nazev = 'údržba', sazba='{$row['sazba']}';\n");
    }
    print "\n";
    
    // dodavatele
    $sql = "SELECT * FROM realizator";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    while($row = mysql_fetch_assoc($res)) {
        print ("INSERT INTO xwt_dodavatel SET id = {$row['id']}, id_typ = 1, id_stabilita = 1, nazev = '{$row['nazev']}', fond='{$row['fond']}', sazba='{$row['sazba']}';\n");
    }
    print "\n";

    // uzivatele
    $sql = "SELECT * FROM uzivatele";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    while($row = mysql_fetch_assoc($res)) {
        if (is_null($row['id_realizator'])) {
            print ("INSERT INTO xwt_uzivatele SET id = {$row['id']}, prist_jmeno = '{$row['prist_jmeno']}', prist_heslo='{$row['prist_heslo']}', email='{$row['email']}', prava = 1;\n");
        } else {
            print ("INSERT INTO xwt_uzivatele SET id = {$row['id']}, id_dodavatel = {$row['id_realizator']}, prist_jmeno = '{$row['prist_jmeno']}', prist_heslo='{$row['prist_heslo']}', email='{$row['email']}', prava = 2;\n");
        }
    }
    print "\n";
    
    // podklady
    $sql = "SELECT * FROM podklady";
    $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
    while($row = mysql_fetch_assoc($res)) {
        $sql2 = "SELECT * FROM realizator_faktury WHERE id_podklad = {$row['id']}";
        $res2 = mysql_query ($sql2) or die ($sql2.':'.mysql_error ());
        $fakturad = $zaplacend = (mysql_num_rows($res2)) ? 1 : 0;
        
        $sql2 = "SELECT * FROM zadavatel_faktury WHERE id_podklad = {$row['id']}";
        $res2 = mysql_query ($sql2) or die ($sql2.':'.mysql_error ());
        $fakturaz = $zaplacenz = (mysql_num_rows($res2)) ? 1 : 0; 
        
        mb_internal_encoding("UTF-8");
        $nazev = mysql_escape_string($row['popis']);
        $nazev = (mb_strlen($nazev) > 23) ? mb_substr($nazev,0,20).'...' : $nazev;
        
        print ("INSERT INTO xwt_ukol SET id = {$row['id']}, id_zadavatel = {$row['id_zadavatel']}, id_dodavatel = {$row['id_realizator']}, nazev = '".$nazev."', popis='".mysql_escape_string($row['popis'])."', klic='{$row['klic']}', zadano='{$row['datum']}', termin='{$row['datum']}', splneno='{$row['datum']}', status='dokonceny';\n");
        print ("INSERT INTO xwt_podklady SET id_ukol = {$row['id']}, hodinyd = '{$row['hodiny']}', hodinyz = '{$row['hodiny']}', sazbaz = '{$row['sazbaz']}', sazbad = '{$row['sazbar']}', fakturad = $fakturad, fakturaz = $fakturaz, zaplacend = $zaplacend, zaplacenz = $zaplacenz;\n");
    }
    print "\n";
?>
