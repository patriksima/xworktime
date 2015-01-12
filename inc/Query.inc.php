<?php
class Query
{
    // eg:Q::S('id',$id,'lol',$lol)
    public function S()
    {
        $n = func_num_args();
        
        if ($n % 2) {
            throw new Exception('Number of arguments must be divided by two');
        }
        
        parse_str(html_entity_decode($_SERVER['QUERY_STRING']), $q);
        
        for ($i=0; $i<$n; $i+=2)
        {
            $k = func_get_arg($i);
            if (!is_string($k)) {
                throw new Exception('Argument '.$i.' do not string');
            }
            
            $v = func_get_arg($i+1);
            if (!is_scalar($v) && !is_null($v)) {
                throw new Exception('Argument '.($i+1).' do not scalar');
            }
            
            if (is_array($q[$k])) {
                $q[$k][] = $v;
            } else {
                $q[$k] = $v;
            }
        }
        
        return '?'.http_build_query($q, '', '&amp;');
    }
}
?>