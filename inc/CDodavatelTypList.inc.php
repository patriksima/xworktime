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
    
    class CDodavatelTypList extends CObjectList
    {
        public function __construct ()
        {
            parent::__construct ();
            $this->order = 'nazev';
            $this->table = 'xwt_dodavatel_typ';
        }
    }
?>
