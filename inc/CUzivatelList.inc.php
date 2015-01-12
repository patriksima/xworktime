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

    class CUzivatelList extends CObjectList
    {
        protected $table1 = 'xwt_uzivatele';
        protected $table2 = 'xwt_dodavatel';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT u.*, d.nazev as nazev
                      FROM {$this->table1} AS u
                      LEFT JOIN {$this->table2} AS d
                        ON u.id_dodavatel = d.id";
        }

        protected function FilterQuery ()
        {
            $where = "WHERE u.id_zadavatel IS NULL";
                        
            $filter = array();
            foreach($this->filter as $k=>$v) {
                switch ($k) {
                    case 'prava':
                        $filter[] = "u.prava = ".$v."";
                        break;
                    case 'nazev':
                        $filter[] = "d.nazev LIKE '%".$v."%'";
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= ' AND '.$filter;
                        
            return $where;
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
                case 'prist_jmeno':
                    $sql = "ORDER BY prist_jmeno ASC";
                    break;
                case '_prist_jmeno':
                    $sql = "ORDER BY prist_jmeno DESC";
                    break;
                case 'email':
                    $sql = "ORDER BY email ASC";
                    break;
                case '_email':
                    $sql = "ORDER BY email DESC";
                    break;
                case 'admin':
                    $sql = "ORDER BY prava ASC";
                    break;
                case '_admin':
                    $sql = "ORDER BY prava DESC";
                    break;
                default:
                    $sql = "ORDER BY nazev ASC";
                    break;
            }
            return $sql;
        }        
    }
?>
