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
    
    class CHelpdesk extends CObject
    {
        const REDIT = 1; // pravo upravovat

        protected $table = 'xwt_helpdesk';
        
        public function LoadByParams ($id_zadavatel, $typ)
        {
            $sql = "SELECT * FROM {$this->table}
                     WHERE id_zadavatel = {$id_zadavatel} 
                       AND typ = '".mysql_real_escape_string($typ)."'
                     LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
        }
    }
?>
