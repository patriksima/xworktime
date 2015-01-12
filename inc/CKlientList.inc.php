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

    class CKlientList extends CObjectList
    {
        protected $table1 = 'xwt_uzivatele';
        protected $table2 = 'xwt_zadavatel';

        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
        }
        
        protected function BaseQuery ()
        {
            return "SELECT u.*, d.nazev as nazev
                      FROM {$this->table1} AS u
                      LEFT JOIN {$this->table2} AS d
                        ON u.id_zadavatel = d.id";
        }

        protected function FilterQuery ()
        {
            return "WHERE id_zadavatel IS NOT NULL";
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
                case 'prist_jmeno':
                    $sql = "ORDER BY prist_jmeno ASC";
                    break;
                case '_prist_jmeno':
                    $sql = "ORDER BY prist_jmeno DESC";
                    break;
                case 'email':
                    $sql = "ORDER BY email ASC";
                    break;
                case '_email':
                    $sql = "ORDER BY email DESC";
                    break;
                case 'admin':
                    $sql = "ORDER BY admin ASC";
                    break;
                case '_admin':
                    $sql = "ORDER BY admin DESC";
                    break;
                default:
                    $sql = "ORDER BY nazev ASC";
                    break;
            }
            return $sql;
        }        
    }
?>
