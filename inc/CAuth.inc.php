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

    // 1 for testing module
    define('CAUTH_MODULE_TEST', 0);
    
    interface IAuth
    {
        public static function Login ();
        public static function Logout ();
        public static function Check ($rights = 0);
        public static function GetUser ();
    }
    
    class CAuth implements IAuth
    {
        const RADMIN  = 1;
        const RUSER   = 2;
        const RCLIENT = 4;
        
        private static $user = array();
        
        public static function Login ()
        {
            @session_start();
            
            $_SESSION['xwt_auth_hash'] = '';
            
            $prist_jmeno = mysql_escape_string ($_POST['prist_jmeno']);
            $prist_heslo = mysql_escape_string ($_POST['prist_heslo']);
            
            $sql = "SELECT * FROM xwt_uzivatele
                     WHERE prist_jmeno = '$prist_jmeno'
                       AND prist_heslo = MD5('$prist_heslo')";
            $res = mysql_query ($sql) or die (mysql_error());
            
            if (mysql_num_rows ($res)) {
                $_SESSION['xwt_auth_hash'] = md5 ($prist_jmeno.'x'.md5 ($prist_heslo));
            }
        }
        
        public static function Logout ()
        {
            @session_start ();
            
            $_SESSION['xwt_auth_hash'] = '';
        }
        
        public static function Check ($rights = 0)
        {
            @session_start ();
            
            if (!isset($_SESSION['xwt_auth_hash']) || $_SESSION['xwt_auth_hash']=='') {
                return false;
            }
            
            $xwt_auth_hash = mysql_escape_string ($_SESSION['xwt_auth_hash']);
            $sql = "SELECT * FROM xwt_uzivatele
                     WHERE MD5(CONCAT(prist_jmeno,'x',prist_heslo)) = '$xwt_auth_hash'";
            $res = mysql_query ($sql) or die (mysql_error());
            
            $auth = (mysql_num_rows ($res)) ? true : false;
            
            if ($auth) {
                self::$user = mysql_fetch_assoc ($res);
                
                if ($rights && $rights != self::$user['prava']) {
                    self::$user = array();
                    $auth = false;
                }
            }
            
            return $auth;
        }
        
        public static function CheckHash ($hash)
        {
            @session_start ();
            
            $sql = "SELECT uz.*, u.id as id_ukol
                      FROM xwt_uzivatele as uz,
                           xwt_dodavatel as d,
                           xwt_ukol as u
                     WHERE MD5(CONCAT(u.id,'x',uz.prist_jmeno,'x',uz.prist_heslo)) = '$hash'
                       AND u.id_dodavatel = d.id
                       AND d.id = u.id_dodavatel";
            $res = mysql_query ($sql) or die (mysql_error());
            $auth = (mysql_num_rows ($res)) ? true : false;
            
            if ($auth) {
                self::$user = $row = mysql_fetch_assoc ($res);
                $_SESSION['xwt_auth_hash'] = md5 ($row['prist_jmeno'].'x'.$row['prist_heslo']);
                $auth = $row['id_ukol'];
            }
            
            return $auth;
        }
        
        public static function GetUser ()
        {
            return self::$user;
        }
    }
    
    if (CAUTH_MODULE_TEST) {
        // testing
    }
?>
