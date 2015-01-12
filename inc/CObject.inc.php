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

    abstract class AObject
    {
        protected $data;
        protected $table;
        
        public function Get ($attribute)
        {
            if (is_array($this->data) && array_key_exists( $attribute, $this->data )) {
                return stripslashes($this->data[$attribute]);
            } else {
                return false;
            }
        }

        public function Set ($attribute, $value)
        {
            if (is_string($value))
                $this->data[$attribute] = mysql_real_escape_string($value);
            else
                $this->data[$attribute] = $value;
        }
        
        final function __set($name, $value)
        {
            if (isset($this->_props[$name]))
                if (method_exists($this, 'set'.$name))
                    $this->{'set'.$name}(mysql_real_escape_string($value));
                else
                    throw new Exception("Property '$name' is read-only.");
            else
                throw new Exception("Undefined property '$name'.");
        }

        final function &__get($name)
        {
            if (isset($this->_props[$name]))
                if (method_exists($this, 'get'.$name))
                    return $this->{'get'.$name}();
                else
                    return $this->{'_'.$name};
            else
                throw new Exception("Undefined property '$name'.");
        }        
    }
    
    interface IObject
    {
        public function Load ($id);
        public function Save ();
        public function Delete ($id);
        public function Get ($attribute);
        public function Set ($attribute, $value);
    }
    
    class CObject extends AObject implements IObject
    {
        public function __construct ()
        {
        }
        
        public function Load ($id)
        {
            $sql = "SELECT * FROM {$this->table} WHERE id = ".mysql_real_escape_string($id);
            $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
            $row = mysql_fetch_assoc ($res);
            $this->data = $row;
        }
        
        public function Save ()
        {
            $tmp = array();
            foreach($this->data as $k=>$v) {
                if ($k == 'id') continue;
                if (is_null($v)) {
                    $tmp[] = "$k = NULL";
                } else {
                    $tmp[] = "$k = '".mysql_real_escape_string($v)."'";
                }
            }
            $part = join(",", $tmp);
            
            if (is_array($this->data) && array_key_exists( 'id', $this->data )) {
                $sql = "UPDATE {$this->table}
                           SET {$part}
                         WHERE id = {$this->data['id']}";
            } else {
                $sql = "INSERT INTO {$this->table}
                           SET {$part}";
            }

            $res = mysql_query ($sql) or die($sql.':'.mysql_error());
            
            return mysql_insert_id ();            
        }
        
        public function Delete ($id)
        {
            $sql = "DELETE FROM {$this->table} WHERE id = ".mysql_real_escape_string($id);
            $res = mysql_query ($sql) or die($sql.':'.mysql_error());
        }
        
        public function Check ($id)
        {
            if (is_numeric($id)) {
                $sql = "SELECT * FROM {$this->table} WHERE id = ".mysql_real_escape_string($id);
                $res = mysql_query ($sql) or die ($sql.':'.mysql_error ());
                return mysql_fetch_assoc ($res);
            }
            return false;
        }
    }
?>