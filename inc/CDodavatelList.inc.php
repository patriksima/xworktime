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

    class CDodavatelList extends CObjectList
    {
        protected $table1 = 'xwt_dodavatel';
        protected $table2 = 'xwt_dodavatel_typ';
        protected $table3 = 'xwt_dodavatel_dluh';
        protected $table4 = 'xwt_dodavatel_stabilita';
        protected $table5 = 'xwt_ukol';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = '_dluh';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT d.*, dt.nazev as typ, dd.dluh, ds.nazev as stabilita, count(u.id) as ukoly
                      FROM {$this->table1} as d
                      LEFT JOIN {$this->table2} as dt ON dt.id = d.id_typ
                      LEFT JOIN {$this->table3} as dd ON d.id = dd.id
                      LEFT JOIN {$this->table4} as ds ON ds.id = d.id_stabilita
                      LEFT JOIN {$this->table5} as u ON d.id = u.id_dodavatel
                            AND (u.status='novy' OR u.status='prijaty')";
        }

        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'nazev':
                    $sql = "ORDER BY d.nazev ASC";
                    break;
                case '_nazev':
                    $sql = "ORDER BY d.nazev DESC";
                    break;
                case 'typ':
                    $sql = "ORDER BY typ ASC";
                    break;
                case '_typ':
                    $sql = "ORDER BY typ DESC";
                    break;
                case 'stabilita':
                    $sql = "ORDER BY stabilita ASC";
                    break;
                case '_stabilita':
                    $sql = "ORDER BY stabilita DESC";
                    break;
                case 'tel':
                    $sql = "ORDER BY tel ASC";
                    break;
                case '_tel':
                    $sql = "ORDER BY tel DESC";
                    break;
                case 'email':
                    $sql = "ORDER BY email ASC";
                    break;
                case '_email':
                    $sql = "ORDER BY email DESC";
                    break;
                case 'sazba':
                    $sql = "ORDER BY d.sazba ASC";
                    break;
                case '_sazba':
                    $sql = "ORDER BY d.sazba DESC";
                    break;
                case 'rychlost':
                    $sql = "ORDER BY d.rychlost ASC";
                    break;
                case '_rychlost':
                    $sql = "ORDER BY d.rychlost DESC";
                    break;
                case 'dluh':
                    $sql = "ORDER BY dluh ASC";
                    break;
                case '_dluh':
                    $sql = "ORDER BY dluh DESC";
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
                    case 'status':
                        $filter[] = "d.status = ".$v;
                        break;
                    case 'nazev':
                        $filter[] = "d.nazev LIKE '%".$v."%'";
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= 'WHERE '.$filter;
            
            return $where;
        }   
        
        protected function GroupQuery ()
        {
            return "GROUP BY d.id";
        }
    }
?>
