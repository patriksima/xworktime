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

    $(document).ready(function(){
        $('table tr:not(:first,:last)').hover(
            function(){
                $(this).toggleClass("orange");
            },
            function(){
                $(this).toggleClass("orange");
            }
        )
    });
