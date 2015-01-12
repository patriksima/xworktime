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
        $('#navi ul>li ul').each(function(){
            var x = $(this).parent().offset().left;
            var y = $(this).parent().offset().top;
            $(this).css({top:y+25,left:x});
        })
        $('#navi ul>li').hover(
            function(){
                $(this).css({backgroundColor:'#e8e8e8'});
                $(this).find('ul').show();
            },
            function(){
                $(this).css({backgroundColor:''});
                $(this).find('ul').hide();
            }
        )
    });
