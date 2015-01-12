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

    class CKliceList extends CObjectList
    {
        protected $table = 'xwt_ukol';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'klic';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT klic
                      FROM {$this->table}";
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
                }
            }
            $filter = join(" AND ", $filter);
            
            if ($filter != '')
                $where .= 'WHERE '.$filter;
            
            return $where;
        }
        
        protected function GroupQuery ()
        {
            return "GROUP BY klic";
        }
    }
?>
