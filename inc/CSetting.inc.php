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

    class CSetting
    {
        /**
         * Init
         */
        public static function Init ()
        {
            error_reporting (E_ALL^E_NOTICE^E_DEPRECATED);
            
            ini_set('default_charset','utf-8');
            @setlocale(LC_TIME, "cs_CZ.utf8");
            
            if (substr(PHP_OS, 0, 3) == 'WIN') {
                $idx = substr( dirname(__FILE__), 0,  strrpos(dirname(__FILE__),'\\'));
                set_include_path( $idx . '\\lib' . ';' . get_include_path());
                set_include_path( $idx . '\\inc' . ';' . get_include_path());
            } else {
                $idx = substr( dirname(__FILE__), 0,  strrpos(dirname(__FILE__),'/'));
                set_include_path( $idx . '/lib' . ':' . get_include_path());
                set_include_path( $idx . '/inc' . ':' . get_include_path());
            }
        }
        
        /**
         * Connect
         */
        public static function Connect ()
        {
            require_once('CConfig.inc.php');

            if (substr(PHP_OS, 0, 3) == 'WIN') {
                $idx = substr( dirname(__FILE__), 0,  strrpos(dirname(__FILE__),'\\'));
                $cfg = $idx.'\\config\\config.xml';
            } else {
                $idx = substr( dirname(__FILE__), 0,  strrpos(dirname(__FILE__),'/'));
                $cfg = $idx.'/config/config.xml';
            }

            CConfig::IsDevelopment (($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='patriksima.dyndns.org' || $_SERVER['SERVER_ADDR']=='192.168.1.10'));
            
            $GLOBALS['BASEURL']  = CConfig::GetBaseURL($cfg);
            $GLOBALS['BASEMAIL'] = CConfig::GetEmail($cfg);
            $GLOBALS['VERSION']  = CConfig::GetVersion($cfg);
            
            $dsn = CConfig::GetDSN($cfg);
            $dsn = parse_url ($dsn);
            
            $host = (empty($dsn['host'])) ? 'localhost' : $dsn['host'];
            $user = (empty($dsn['user'])) ? '' : $dsn['user'];
            $pass = (empty($dsn['pass'])) ? '' : $dsn['pass'];
            $db = (empty($dsn['path'])) ? '' : str_replace ('/', '', $dsn['path']);

            mysql_connect ($host, $user, $pass);
            mysql_select_db ($db);
            mysql_query ('SET NAMES utf8');
        }
    }
 
    CSetting::Init ();
    CSetting::Connect ();
?>
