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

    class CPodkladList extends CObjectList
    {
        protected $table1 = 'xwt_podklady';
        protected $table2 = 'xwt_ukol';
        protected $table3 = 'xwt_zadavatel';
        protected $table4 = 'xwt_dodavatel';

        public function __construct ()
        {
            parent::__construct ();
        }
        
        protected function BaseQuery ()
        {
            return "SELECT p.*, p.hodinyd*p.sazbad as vydaje, p.hodinyz*p.sazbaz as prijmy,
                           u.id_zadavatel, u.id_dodavatel,
                           u.nazev, u.popis, u.klic,
                           u.termin, u.splneno, u.status,
                           z.nazev as zadavatel, d.nazev as dodavatel
                      FROM {$this->table1} as p,
                           {$this->table2} as u,
                           {$this->table3} as z,
                           {$this->table4} as d";
        }

        protected function SortQuery ()
        {
            switch ($this->order) {
                case 'splneno':
                    $sql = "ORDER BY splneno ASC";
                    break;
                case '_splneno':
                    $sql = "ORDER BY splneno DESC";
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
                case 'hodinyd':
                    $sql = "ORDER BY hodinyd ASC";
                    break;
                case '_hodinyd':
                    $sql = "ORDER BY hodinyd DESC";
                    break;
                case 'hodinyz':
                    $sql = "ORDER BY hodinyz ASC";
                    break;
                case '_hodinyz':
                    $sql = "ORDER BY hodinyz DESC";
                    break;
                case 'prijmy':
                    $sql = "ORDER BY prijmy ASC";
                    break;
                case '_prijmy':
                    $sql = "ORDER BY prijmy DESC";
                    break;
                case 'vydaje':
                    $sql = "ORDER BY vydaje ASC";
                    break;
                case '_vydaje':
                    $sql = "ORDER BY vydaje DESC";
                    break;
                case 'klic':
                    $sql = "ORDER BY klic ASC, splneno DESC";
                    break;
                case '_klic':
                    $sql = "ORDER BY klic DESC, splneno DESC";
                    break;
                case 'fakturaz':
                    $sql = "ORDER BY fakturaz ASC";
                    break;
                case '_fakturaz':
                    $sql = "ORDER BY fakturaz DESC";
                    break;
                case 'fakturad':
                    $sql = "ORDER BY fakturad ASC";
                    break;
                case '_fakturad':
                    $sql = "ORDER BY fakturad DESC";
                    break;
                case 'zaplacenz':
                    $sql = "ORDER BY zaplacenz ASC";
                    break;
                case '_zaplacenz':
                    $sql = "ORDER BY zaplacenz DESC";
                    break;
                case 'zaplacend':
                    $sql = "ORDER BY zaplacend ASC";
                    break;
                case '_zaplacend':
                    $sql = "ORDER BY zaplacend DESC";
                    break;
                default:
                    $sql = "ORDER BY fakturaz DESC, fakturad DESC, splneno DESC";
                    break;
            }
            return $sql;
        }
        
        protected function FilterQuery ()
        {
            $where = "WHERE p.id_ukol = u.id
                        AND u.id_dodavatel = d.id
                        AND u.id_zadavatel = z.id
                        AND splneno != '0000-00-00'";

            $filter = array();
            foreach($this->filter as $k=>$v) {
                switch ($k) {
                    case 'id_zadavatel':
                        $filter[] = $k.' = '.$v;
                        break;
                    case 'id_dodavatel':
                        $filter[] = $k.' = '.$v;
                        break;
                    case 'nazev':
                        $filter[] = "u.nazev LIKE '%".$v."%'";
                        break;
                    case 'popis':
                        $filter[] = "u.popis LIKE '%".$v."%'";
                        break;
                    case 'klic':
                        $filter[] = $k." LIKE '%".$v."%'";
                        break;
                    case 'fakturaz':
                        switch($v) {
                            case 1:
                                $filter[] = 'fakturaz = 1';
                                break;
                            case 2:
                                $filter[] = 'fakturaz = 0';
                                break;
                        }
                        break;
                    case 'fakturad':
                        switch($v) {
                            case 1:
                                $filter[] = 'fakturad = 1';
                                break;
                            case 2:
                                $filter[] = 'fakturad = 0';
                                break;
                        }
                        break;
                    case 'zaplacenz':
                        switch($v) {
                            case 1:
                                $filter[] = 'zaplacenz = 1';
                                break;
                            case 2:
                                $filter[] = 'zaplacenz = 0';
                                break;
                        }
                        break;
                    case 'zaplacend':
                        switch($v) {
                            case 1:
                                $filter[] = 'zaplacend = 1';
                                break;
                            case 2:
                                $filter[] = 'zaplacend = 0';
                                break;
                        }
                        break;
                    case 'splnenood':
                        $filter[] = "u.splneno >= '".$v."'";
                        break;
                    case 'splnenodo':
                        $filter[] = "u.splneno < '".$v."'";
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
                $params = array ('splnenood', 'splnenodo',
                                 'id_zadavatel', 'id_dodavatel',
                                 'nazev', 'popis', 'klic', 'klic2',
                                 'fakturaz', 'zaplacenz', 'fakturad', 'zaplacend');
                foreach ($params as $k=>$v)
                {
                    $$v = CRequest::Get ($v);
                    if ($v=='klic' || $v=='klic2')
                        continue;
                    if ($$v !== '')
                        $this->SetFilter ($v, $$v);
                }
                
                if ($klic2 != '')
                    $this->SetFilter ('klic', $klic2);
                elseif ($klic != '')
                    $this->SetFilter ('klic', $klic);
            }
        }
    }
?>
