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
    
    class CUkolPrilohy extends CObject
    {
        protected $table = 'xwt_ukol_prilohy';
        
        public function Upload ($id_ukol)
        {
            $tmp = @$_FILES['priloha']['tmp_name'];
            if (!is_uploaded_file($tmp)) {
                return false;
            }

            $name = @$_FILES['priloha']['name'];
            $mime = @$_FILES['priloha']['type'];
            $size = @$_FILES['priloha']['size'];

            $this->data['id_ukol'] = $id_ukol;
            $this->data['nazev'] = $name;
            $this->data['soubor'] = '';
            $this->data['mime'] = $mime;
            $this->data['velikost'] = $size;
            $id_priloha = $this->Save();
            $this->data['id'] = $id_priloha;
            
            $inf = pathinfo(@$_FILES['priloha']['name']);
            $att = date('Y-m-d').'-priloha'.substr(md5($id_priloha),1,6);
            $att.= ($inf['extension']!='') ? '.'.$inf['extension'] : '';
            $dst = 'upload/'.$att;
                
            if (!move_uploaded_file($tmp, $dst)) {
                $this->Delete($id_priloha);
                return false;
            }

            $this->data['soubor'] = $att;
            $this->Save();

            return true;
        }

        public function LoadByUkol ($id_ukol)
        {
            $sql = "SELECT * FROM {$this->table} WHERE id_ukol = {$id_ukol} LIMIT 1";
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
        }

        public function Remove ()
        {
            @unlink(__DIR__.'/../podpora/upload/'.$this->Get('soubor'));
        }
    }
?>
