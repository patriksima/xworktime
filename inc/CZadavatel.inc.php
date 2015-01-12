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
    
    class CZadavatel extends CObject
    {
        protected $table = 'xwt_zadavatel';

        public function CheckHelpDesk ($id_helpdesk)
        {
            $sql = "SELECT * FROM {$this->table} WHERE '{$id_helpdesk}'=LEFT(MD5(CONCAT(id,'x',nazev)),8) LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            return mysql_num_rows($res) ? true : false;
        }
        
        public function LoadByHelpDesk ($id_helpdesk)
        {
            $sql = "SELECT * FROM {$this->table} WHERE '{$id_helpdesk}'=LEFT(MD5(CONCAT(id,'x',nazev)),8) LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
        }

        public function UpdateHelpDesk ($id_zadavatel)
        {
            $sql = "UPDATE {$this->table} SET id_helpdesk = LEFT(MD5(CONCAT(id,'x',nazev)),8) WHERE id = ".mysql_escape_string($id_zadavatel);
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
        }
    }
?>
