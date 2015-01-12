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
    
    require_once ('CObjectList.inc.php');
    
    class CDodZadavatelList extends CObjectList
    {
        protected $table1 = 'xwt_zadavatel';
        protected $table2 = 'xwt_ukol';
        
        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT z.*
                      FROM {$this->table1} AS z,
                           {$this->table2} AS u";
        }
        
        protected function FilterQuery ()
        {
            $where = "WHERE z.id = u.id_zadavatel";
            
            $filter = array();
            foreach($this->filter as $k=>$v) {
                switch ($k) {
                    case 'id_dodavatel':
                        $filter[] = $k.' = '.$v;
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= ' AND '.$filter;
            
            return $where;
        }
        
        protected function GroupQuery ()
        {
            return "GROUP BY z.id";
        }
        
        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'nazev':
                    $sql = "ORDER BY nazev ASC";
                    break;
                case '_nazev':
                    $sql = "ORDER BY nazev DESC";
                    break;
                default:
                    $sql = "ORDER BY nazev DESC";
                    break;
            }
            return $sql;
        }
    }
?>
