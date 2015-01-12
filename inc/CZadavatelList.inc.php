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
    
    class CZadavatelList extends CObjectList
    {
        protected $table1 = 'xwt_zadavatel';
        protected $table2 = 'xwt_zadavatel_dluh';
        protected $table3 = 'xwt_zadavatel_zaplaceno';
        protected $table4 = 'xwt_zadavatel_naklady';
        protected $table5 = 'xwt_zadavatel_zisk';
        protected $table6 = 'xwt_zadavatel_nefakturovano';
        
        public function __construct ()
        {
            parent::__construct ();
            $this->order = '_dluh';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT z.*, zd.dluh, zz.zaplaceno,
                           zn.naklady, zk.zisk, zf.nefakturovano
                      FROM {$this->table1} AS z
                      LEFT JOIN {$this->table2} AS zd ON z.id=zd.id
                      LEFT JOIN {$this->table3} AS zz ON z.id=zz.id
                      LEFT JOIN {$this->table4} AS zn ON z.id=zn.id
                      LEFT JOIN {$this->table5} AS zk ON z.id=zk.id
                      LEFT JOIN {$this->table6} AS zf ON z.id=zf.id";
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
                case 'sazba':
                    $sql = "ORDER BY sazba ASC";
                    break;
                case '_sazba':
                    $sql = "ORDER BY sazba DESC";
                    break;
                case 'dluh':
                    $sql = "ORDER BY dluh ASC";
                    break;
                case '_dluh':
                    $sql = "ORDER BY dluh DESC";
                    break;
                case 'zaplaceno':
                    $sql = "ORDER BY zaplaceno ASC";
                    break;
                case '_zaplaceno':
                    $sql = "ORDER BY zaplaceno DESC";
                    break;
                case 'naklady':
                    $sql = "ORDER BY naklady ASC";
                    break;
                case '_naklady':
                    $sql = "ORDER BY naklady DESC";
                    break;
                case 'zisk':
                    $sql = "ORDER BY zisk ASC";
                    break;
                case '_zisk':
                    $sql = "ORDER BY zisk DESC";
                    break;
                case 'nefakturovano':
                    $sql = "ORDER BY nefakturovano ASC";
                    break;
                case '_nefakturovano':
                    $sql = "ORDER BY nefakturovano DESC";
                    break;
                default:
                    $sql = "ORDER BY dluh DESC";
                    break;
            }
            return $sql;
        }
        
        protected function FilterQuery ()
        {
            $where = "";

            $filter = array();
            foreach($this->filter as $k=>$v) {
                switch ($k) {
                    case 'nazev':
                        $filter[] = "z.nazev LIKE '%".$v."%'";
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= 'WHERE '.$filter;
            
            return $where;
        }   
    }
?>
