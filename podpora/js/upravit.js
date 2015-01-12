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
    $('a.ajax').on('click', function (event) {
        event.preventDefault();
        // confirm dialog
        if ($(this).attr('data-confirm')) {
            if (!confirm($(this).attr('data-confirm'))) {
                return false;
            }
        }
        $.get(this.href, function(data){
            $(this).parents('tr').remove();
        });
    });
});
