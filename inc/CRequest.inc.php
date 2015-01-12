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

    interface IRequest
    {
        public static function Get ($var, $default='');
        public static function Post ($var, $default='');
        public static function Cookie ($var, $default='');
        public static function Request ($var, $default='');
    }
    
    class CRequest implements IRequest
    {
        public static function Get ($var, $default='')
        {
            if (array_key_exists ($var, $_GET)) {
                if (function_exists ('get_magic_quotes_gpc') && !get_magic_quotes_gpc ()) {
                    $var = ($_GET[$var]);
                } else {
                    $var = (is_array($_GET[$var])) ? array_map ('stripslashes', $_GET[$var])
                                                   : stripslashes ($_GET[$var]);
                }
            } else {
                $var = $default;
            }
            return $var;
        }
        
        public static function Post ($var, $default='')
        {
            if (array_key_exists ($var, $_POST)) {
                if (function_exists ('get_magic_quotes_gpc') && !get_magic_quotes_gpc ()) {
                    $var = ($_POST[$var]);
                } else {
                    $var = (is_array($_POST[$var])) ? array_map ('stripslashes', $_POST[$var])
                                                    : stripslashes ($_POST[$var]);
                }
            } else {
                $var = $default;
            }
            return $var;
        }
        
        public static function Cookie ($var, $default='')
        {
            if (array_key_exists ($var, $_COOKIE)) {
                if (function_exists ('get_magic_quotes_gpc') && !get_magic_quotes_gpc ()) {
                    $var = ($_COOKIE[$var]);
                } else {
                    $var = (is_array($_COOKIE[$var])) ? array_map ('stripslashes', $_COOKIE[$var])
                                                      : stripslashes ($_COOKIE[$var]);
                }
            } else {
                $var = $default;
            }
            return $var;
        }
        
        public static function Request ($var, $default='')
        {
            if (array_key_exists ($var, $_REQUEST)) {
                if (function_exists ('get_magic_quotes_gpc') && !get_magic_quotes_gpc ()) {
                    $var = ($_REQUEST[$var]);
                } else {
                    $var = (is_array($_REQUEST[$var])) ? array_map ('stripslashes', $_REQUEST[$var])
                                                       : stripslashes ($_REQUEST[$var]);
                }
            } else {
                $var = $default;
            }
            return $var;
        }
    }
