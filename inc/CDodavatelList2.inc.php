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
        protected $table3 = 'xwt_uzivatele';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT d.*, dt.nazev as typ
                      FROM {$this->table1} as d
                      LEFT JOIN {$this->table2} as dt ON dt.id = d.id_typ
                      LEFT JOIN {$this->table3} as u ON d.id = u.id_dodavatel";
        }

        protected function FilterQuery ()
        {
            return "WHERE u.id IS NULL";
        }
    }
?>
