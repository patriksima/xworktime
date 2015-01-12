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

    class CRedirect
    {
        public static function To ($url)
        {
            if (headers_sent ()) {
                exit ("<script type=\"text/javascript\">document.location='{$url}'</script>");
            } else {
                header ("Location: {$url}");
                exit ("<a href=\"{$url}\">{$url}</a>");
            }
        }
    }
?>