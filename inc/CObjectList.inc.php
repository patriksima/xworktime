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


    abstract class AObjectList
    {
    }
    
    interface IObjectList
    {
    }
    
    class CObjectList extends AObjectList implements IObjectList
    {
        protected $data;
        protected static $count = NULL;

        protected $order;
        protected $limit;
        protected $filter;
        
        protected $table;
        
        public function __construct ()
        {
            $this->data  = array ();
            $this->order = '';
            $this->limit = array ('from'=>0, 'cnt'=>0);
            $this->filter = array ();
        }
        
        public function __destruct() {
            self::$count = NULL;
        }
        
        public function Load ()
        {
            $sql = $this->BaseQuery ();
            $sql.= ' '.$this->FilterQuery ();
            $sql.= ' '.$this->GroupQuery ();
            $sql.= ' '.$this->SortQuery ();
            $sql.= ' '.$this->LimitQuery ();

            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            while ($row = mysql_fetch_assoc ($res)) {
                $this->data[] = array_map('stripslashes', $row);
            }
        }
        
        protected function BaseQuery ()
        {
            return "SELECT * FROM {$this->table}";
        }

        protected function FilterQuery ()
        {
            return "";
        }
        
        protected function GroupQuery ()
        {
            return "";
        }
        
        protected function SortQuery ()
        {
            if ($this->order != '') {
                if ($this->order[0] == '_') {
                    $sql = "ORDER BY ".substr($this->order, 1)." DESC";
                } else {
                    $sql = "ORDER BY {$this->order} ASC";
                }
            } else {
                $sql = "";
            }
            return $sql;
        }

        protected function LimitQuery ()
        {
            if ($this->limit['cnt']) {
                $sql = "LIMIT {$this->limit['from']}, {$this->limit['cnt']}";
            } else {
                $sql = "";
            }
            return $sql;
        }
        
        public function SetLimit ($from, $cnt)
        {
            $this->limit['from'] = mysql_real_escape_string($from);
            $this->limit['cnt']  = mysql_real_escape_string($cnt);
        }
        
        public function SetOrder ($order)
        {
            if ($order != '')
                $this->order = mysql_real_escape_string($order);
        }
             
        public function SetFilter ($filter, $value, $sign = '')
        {
            $sign = mysql_real_escape_string($sign);
            if (is_array($value)) {
                $value = array_map(function($v){return mysql_real_escape_string($v);}, $value);
            } else {
                $value = mysql_real_escape_string($value);
            }
            if ($sign == '') {
                $this->filter[$filter] = $value;
            } else {
                $this->filter[$filter] = array ($value, $sign);
            }
        }
        
        public function ResetFilters ()
        {
            $this->filter = array ();
        }
        
        public function GetCount ()
        {
            if (is_null(self::$count)) {
                $sql = $this->BaseQuery ();
                $sql.= ' '.$this->FilterQuery ();
                $sql.= ' '.$this->GroupQuery ();
                
                $res = mysql_query ($sql);
                $cnt = mysql_num_rows ($res);
                
                self::$count = $cnt;
            }
            
            return self::$count;
        }
        
        public function GetData ()
        {
            return $this->data;
        }

        public function GetSql ()
        {
            $sql = $this->BaseQuery ();
            $sql.= ' '.$this->FilterQuery ();
            $sql.= ' '.$this->GroupQuery ();
            $sql.= ' '.$this->SortQuery ();
            $sql.= ' '.$this->LimitQuery ();            
            return $sql;
        }        
    }
?>