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

    require_once ('Config.php');

    interface IConfig
    {
        public static function GetDSN ($file);
    }

    class CConfig implements IConfig
    {
        private static $development = false;
        
        /**
         * Constructor
         */
        private function __construct ()
        {
            // empty constructor
        }

        public static function IsDevelopment ($status)
        {
            CConfig::$development = $status;
        }

        private static function GetConfig ($cfgfile)
        {
            $cfg = new Config ();
            $cfg->parseConfig ( $cfgfile, 'xml' );

            $root =& $cfg->getRoot ();

            $config  =& $root->getItem ('section','config');
            if (CConfig::$development) {
                $config  =& $config->getItem ('section','development');
            } else {
                $config  =& $config->getItem ('section','production');
            }

            return $config;
        }
        
        /**
         * GetVersion
         */
        public static function GetVersion ($cfgfile)
        {
            $config = CConfig::GetConfig ($cfgfile);
            $version =& $config->getItem ('directive','version');
            
            return $version->getContent ();
        }
        
        /**
         * GetEmail
         */
        public static function GetEmail ($cfgfile)
        {
            $config = CConfig::GetConfig ($cfgfile);
            $email  =& $config->getItem ('directive','e-mail');
            
            return $email->getContent ();
        }
            
        /**
         * GetBaseURL
         */
        public static function GetBaseURL ($cfgfile)
        {
            $config = CConfig::GetConfig ($cfgfile);
            $baseurl =& $config->getItem ('directive','baseurl');
            
            return $baseurl->getContent ();
        }
            
        /**
         * DSN
         */
        public static function GetDSN ($cfgfile)
        {
            $config = CConfig::GetConfig ($cfgfile);
            $database =& $config->getItem ('section','database');

            $type =  $database->getAttribute ('type');
            $host =& $database->getItem ('directive','host');
            $user =& $database->getItem ('directive','user');
            $pass =& $database->getItem ('directive','pass');
            $db   =& $database->getItem ('directive','db');

            $dsn = "";
            $dsn.= ($type) ? $type : '';
            $dsn.= "://";
            $dsn.= ($user) ? $user->getContent () : '';
            $dsn.= ":";
            $dsn.= ($pass) ? $pass->getContent () : '';
            $dsn.= "@";
            $dsn.= ($host) ? $host->getContent () : '';
            $dsn.= "/";
            $dsn.= ($db) ? $db->getContent () : '';

            return $dsn;
        }
    }
?>