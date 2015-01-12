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

    var ERR_BGCOLOR = '#ffc8c8';

    $(document).ready(function(){
                      
        $('form:first').submit(function(){
            $('form:first input,select,textarea').css({backgroundColor:''});
            $('form:first span.form-error-msg').empty();
            
            var re = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/
            if (!re.test($("form:first #termin").val())) {
                $("form:first #termin").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #termin").after('<span class="form-error-msg">! Zadejte termín ve tvaru RRRR-MM-DD</span>');
                $("form:first #termin").focus();
                return false;
            }
        });
    });
