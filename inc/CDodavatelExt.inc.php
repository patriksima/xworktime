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
    
    require_once ('CObject.inc.php');
    
    class CDodavatelExt extends CObject
    {
        protected $table1 = 'xwt_dodavatel';
        protected $table2 = 'xwt_dodavatel_dluh';
        
        public function Load ($id)
        {
            $sql = "SELECT d. * , dd.dluh
                      FROM {$this->table1} AS d
                      LEFT JOIN {$this->table2} AS dd ON d.id = dd.id
                     WHERE d.id = {$id}";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
        }
    }
?>
