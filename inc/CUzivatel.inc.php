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
    
    class CUzivatel extends CObject
    {
        protected $table = 'xwt_uzivatele';
        
        public function LoadByEmail ($prist_jmeno, $email)
        {
            $sql = "SELECT * FROM {$this->table}
                     WHERE prist_jmeno = '{$prist_jmeno}'
                       AND email = '{$email}'
                     LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
            
            return (mysql_num_rows($res)!=0);
        }        
        
        public function LoadByDodavatel ($id_dodavatel)
        {
            $sql = "SELECT * FROM {$this->table}
                     WHERE id_dodavatel = {$id_dodavatel}
                     LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
            
            return (mysql_num_rows($res)!=0);
        }        

        public function LoadByZadavatel ($id_zadavatel)
        {
            $sql = "SELECT * FROM {$this->table}
                     WHERE id_zadavatel = {$id_zadavatel}
                     LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
            
            return (mysql_num_rows($res)!=0);
        }        
        
        function GetNewPassword ($length = 10)
        {
            $password = "";
            
            $vals = "#ABCDEFGHJKLMNPQRSTUVWXabchefghjkmnpqrstuvwx23456789";
            while (strlen($password) < $length) {
                mt_getrandmax();
                $num = rand() % strlen($vals);
                $tmp = substr($vals, $num+4, 1);
                $password = $password . $tmp;
                $tmp = "";
            }
            
            return $password;
        }
    }
?>
