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
    
    class CUkol extends CObject
    {
        protected $table = 'xwt_ukol';
        
        public function CheckUser( $id_user ) {
            return ($this->Get('id_dodavatel') == $id_user);
        }

        public function CheckZadavatel( $id_zadavatel ) {
            return ($this->Get('id_zadavatel') == $id_zadavatel);
        }
    }
?>
