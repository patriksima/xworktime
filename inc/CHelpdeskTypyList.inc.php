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

    class CHelpdeskTypyList extends CObjectList
    {
        protected $table = 'xwt_helpdesk';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'typ';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT typ
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
                    case 'typ':
                        $filter[] = $k." LIKE '".mysql_real_escape_string($v)."%'";
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
            return "GROUP BY typ";
        }
    }
?>
