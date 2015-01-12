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
    
    interface IError
    {
    }
    
    class CError implements IError
    {
        private $data;
        private $hash;
        private $loaded;
        
        /**
         * Constructor
         */
        public function __construct ()
        {
            @session_start ();
            $this->data = array (
                                 'msg'=>array(),
                                 'data'=>array()
                                );
            $this->hash = substr (md5 (uniqid (time ()) . md5(time())), 0, 10);
            $this->loaded = false;
        }
        
        /**
         * Load
         */
        public function Load ($hash)
        {
            if ($hash != '') {
                $this->hash = $hash;
                $this->data = $_SESSION[$hash];
                $this->loaded = true;
            }
        }
        
        /**
         * Clear
         */
        public function Clear ()
        {
            $_SESSION[$this->hash] = '';
            $this->hash = '';
        }

        /**
         * IsError
         */
        public function IsError ()
        {
            return $this->loaded;
        }
        
        /**
         * CheckMsg
         */
        public function CheckMsg ($id)
        {
            if (is_array ($this->data['msg']) 
             && array_key_exists ($id, $this->data['msg']))
            {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * GetMsg
         */
        public function GetMsg ($id)
        {
            if (is_array ($this->data['msg']) 
             && array_key_exists ($id, $this->data['msg']))
            {
                return $this->data['msg'][$id];
            } else {
                return null;
            }
        }

        /**
         * GetVal
         */
        public function GetVal ($id)
        {
            if (is_array ($this->data['data']) 
             && array_key_exists ($id, $this->data['data']))
            {
                return $this->data['data'][$id];
            } else {
                return null;
            }
        }
        
        /**
         * PushMsg
         */
        public function PushMsg ($id, $message)
        {
            $this->data['msg'][$id] = $message;
        }
        
        /**
         * PushData
         */
        public function PushData ($id, $value)
        {
            $this->data['data'][$id] = $value;
        }
        
        /**
         * PushTo
         */
        public function PushTo ($url)
        {
            $_SESSION[$this->hash] = $this->data;
            
            if (count ($this->data['msg'])) {
                $parsed = parse_url ($url);
                $hash = 'err='.$this->hash;
                $url = (empty($parsed['query'])) ? $url.'?'.$hash : $url.'&'.$hash;
                CRedirect::To ($url);
            } else {
                $this->Clear ();
            }
        }
    }
?>
