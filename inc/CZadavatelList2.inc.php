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
        protected $table2 = 'xwt_uzivatele';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT d.*
                      FROM {$this->table1} as d
                      LEFT JOIN {$this->table2} as u ON d.id = u.id_zadavatel";
        }

        protected function FilterQuery ()
        {
            return "WHERE u.id IS NULL";
        }
    }
?>
