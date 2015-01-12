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

    class CDodUkolList extends CObjectList
    {
        protected $table1 = 'xwt_ukol';
        protected $table2 = 'xwt_zadavatel';
        protected $table3 = 'xwt_dodavatel';
        protected $table4 = 'xwt_ukol_prilohy';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'termin';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT u.*,
                           z.nazev as zadavatel, d.nazev as dodavatel,
                           p.nazev as priloha, p.soubor as soubor
                      FROM {$this->table1} as u
                 LEFT JOIN {$this->table2} as z ON z.id = u.id_zadavatel
                 LEFT JOIN {$this->table3} as d ON d.id = u.id_dodavatel
                 LEFT JOIN {$this->table4} as p ON u.id = p.id_ukol";
        }

        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'zadano':
                    $sql = "ORDER BY zadano ASC, id ASC";
                    break;
                case '_zadano':
                    $sql = "ORDER BY zadano DESC, id DESC";
                    break;
                case 'termin':
                    $sql = "ORDER BY termin ASC, id ASC";
                    break;
                case '_termin':
                    $sql = "ORDER BY termin DESC, id DESC";
                    break;
                case 'splneno':
                    $sql = "ORDER BY splneno ASC, id ASC";
                    break;
                case '_splneno':
                    $sql = "ORDER BY splneno DESC, id DESC";
                    break;
                case 'zadavatel':
                    $sql = "ORDER BY zadavatel ASC";
                    break;
                case '_zadavatel':
                    $sql = "ORDER BY zadavatel DESC";
                    break;
                case 'dodavatel':
                    $sql = "ORDER BY dodavatel ASC";
                    break;
                case '_dodavatel':
                    $sql = "ORDER BY dodavatel DESC";
                    break;
                case 'nazev':
                    $sql = "ORDER BY nazev ASC";
                    break;
                case '_nazev':
                    $sql = "ORDER BY nazev DESC";
                    break;
                case 'klic':
                    $sql = "ORDER BY klic ASC";
                    break;
                case '_klic':
                    $sql = "ORDER BY klic DESC";
                    break;
                case 'status':
                    $sql = "ORDER BY status ASC, splneno ASC";
                    break;
                case '_status':
                    $sql = "ORDER BY status DESC, splneno DESC";
                    break;
                default:
                    $sql = "ORDER BY termin ASC, status DESC";
                    break;
            }
            return $sql;
        }
        
        protected function FilterQuery ()
        {
            $where = "WHERE u.id_dodavatel = d.id
                        AND u.id_zadavatel = z.id";
                        
            $filter = array();
            foreach($this->filter as $k=>$v) {
                switch ($k) {
                    case 'id_zadavatel':
                        $filter[] = $k." = ".$v."";
                        break;
                    case 'id_dodavatel':
                        $filter[] = $k." = ".$v."";
                        break;
                    case 'klic':
                        $filter[] = "u.klic LIKE '%".$v."%'";
                        break;
                    case 'status':
                        if (is_array($v) && count($v)) {
                            $filter2 = array();
                            foreach($v as $k2=>$v2) {
                                $filter2[] = "u.status = '".$v2."'";
                            }
                            $filter[] = "(".join(' OR ', $filter2).")";
                        }
                        if (is_string($v) && $v != "") {
                            $filter[] = "u.status = '".$v."'";
                        }
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= ' AND '.$filter;
                        
            return $where;
        }
        
        public function SetFilters ()
        {
            $filter = CRequest::Get ('filtr', 0);
            
            if ($filter)
            {
                $params = array ('id_zadavatel','id_dodavatel', 'status', 'klic');
                foreach ($params as $k=>$v)
                {
                    $$v = CRequest::Get ($v);
                    if ($$v !== '')
                        $this->SetFilter ($v, $$v);
                }
            }
        }
    }
?>
