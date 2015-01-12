<?php
//
// +----------------------------------------------------------------------+
// | XWorkTime                                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007-2013 Patrik Šíma                                  |
// +----------------------------------------------------------------------+
// | Author: Patrik Šíma <ja@patriksima.cz>                               |
// |         http://www.patriksima.cz/                                    |
// +----------------------------------------------------------------------+
// $Id$
//

    require_once ('CObjectList.inc.php');

    class CUkolChatList extends CObjectList
    {
        protected $table1 = 'xwt_ukol_chat';
        protected $table2 = 'xwt_uzivatele';
        protected $table3 = 'xwt_dodavatel';
        protected $table4 = 'xwt_zadavatel';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = '_vytvoreno';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT uc.id, uc.id_ukol, uc.zprava,
                           IF(u.id_dodavatel IS NULL, IF(u.id_zadavatel IS NULL, 'Admin', z.nazev), d.nazev) as poslal,
                           IF(u.id_dodavatel IS NULL, IF(u.id_zadavatel IS NULL, 'admin', 'klient'), 'dodavatel') as typ,
                           uc.vytvoreno
                      FROM {$this->table1} as uc
                 LEFT JOIN {$this->table2} u ON u.id = uc.id_uzivatel
                 LEFT JOIN {$this->table3} d ON d.id = u.id_dodavatel
                 LEFT JOIN {$this->table4} z ON z.id = u.id_zadavatel";
        }

        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'vytvoreno':
                    $sql = "ORDER BY vytvoreno ASC";
                    break;
                case '_vytvoreno':
                    $sql = "ORDER BY vytvoreno DESC";
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
                    case 'id_ukol':
                        $filter[] = $k." = ".$v."";
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
                $params = array ('id_ukol');
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
