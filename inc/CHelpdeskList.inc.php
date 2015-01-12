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

    class CHelpdeskList extends CObjectList
    {
        protected $table1 = 'xwt_helpdesk';
        protected $table2 = 'xwt_zadavatel';
        protected $table3 = 'xwt_dodavatel';

        public function __construct ()
        {
            parent::__construct ();
        }
        
        protected function BaseQuery ()
        {
            return "SELECT h.*,
                           z.nazev as zadavatel,
                           d.nazev as dodavatel
                      FROM {$this->table1} as h
                 LEFT JOIN {$this->table2} as z ON z.id = h.id_zadavatel
                 LEFT JOIN {$this->table3} as d ON d.id = h.id_dodavatel";
        }

        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'zadavatel':
                    $sql = "ORDER BY zadavatel ASC, dodavatel ASC, typ ASC";
                    break;
                case '_zadavatel':
                    $sql = "ORDER BY zadavatel DESC, dodavatel DESC, typ DESC";
                    break;
                case 'dodavatel':
                    $sql = "ORDER BY dodavatel ASC, zadavatel ASC, typ ASC";
                    break;
                case '_dodavatel':
                    $sql = "ORDER BY dodavatel DESC, zadavatel DESC, typ DESC";
                    break;
                case 'typ':
                    $sql = "ORDER BY typ ASC, dodavatel ASC, zadavatel ASC";
                    break;
                case '_typ':
                    $sql = "ORDER BY typ DESC, dodavatel DESC, zadavatel DESC";
                    break;
                default:
                    $sql = "ORDER BY zadavatel ASC, dodavatel ASC, typ ASC";
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
                    case 'id_zadavatel':
                        $filter[] = $k.' = '.$v;
                        break;
                    case 'id_dodavatel':
                        $filter[] = $k.' = '.$v;
                        break;
                    case 'typ':
                        $filter[] = $k." LIKE '".mysql_escape_string($v)."%'";
                        break;
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= 'WHERE '.$filter;
            
            return $where;
        }

        public function SetFilters ()
        {
            $filter = CRequest::Get ('filtr', 0);
            
            if ($filter)
            {
                $params = array ('id_zadavatel', 'id_dodavatel', 'typ');
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
