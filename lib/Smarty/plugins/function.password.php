<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {password} function plugin
 *
 * Type:     function<br>
 * Name:     password<br>
 * Date:     January 9, 2008
 * Purpose:  randow password generator<br>
 * Input:<br>
 *         - length = length of password
 * @version  1.0
 * @author   Patrik Šíma <ja at patriksima dot cz>
 * @param    array
 * @param    Smarty
 * @return   string
 */
function smarty_function_password($params, &$smarty)
{
    $password = "";
    
    if (empty($params['length'])) {
        $smarty->trigger_error("password: missing 'length' parameter");
        return;
    } elseif (!is_numeric($params['length'])) {
        $smarty->trigger_error("password: 'length' must be a number > 3");
        return;
    } elseif ($params['length'] < 3) {
        $smarty->trigger_error("password: 'length' must be a number > 3");
        return;
    } else {
        $length = $params['length'];
    }
    
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

/* vim: set expandtab: */

?>
