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
        $('input#nazev').focus();

        // ovladani +
        $("table tr td a[href^='#']").each(function(){
            var re = /^#[0-9]+$/
            if (re.test($(this).attr('href'))) {
                //$(this).css({'padding':'0 2px 0 2px','text-decoration':'none'});
                $(this).click(function(){
                    var tr = $(this).parent().parent().next();
                    if (tr.css('display')=='none') {
                        //$(this).text('-');
                        tr.css({display:'table-row'});
                    } else {
                        //$(this).text('+');
                        tr.css({display:'none'});
                    }
                    
                    return false;
                });
            }
        })
        
        $('table tr').hover(
            function(){
                $(this).toggleClass("orange");
            },
            function(){
                $(this).toggleClass("orange");
            }
        )
    });
