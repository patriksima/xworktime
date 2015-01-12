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
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if (!re.test($("form:first #hodinyd").val())) {
                $("form:first #hodinyd").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #hodinyd").after('<span class="form-error-msg">! Zadejte počet hodin</span>');
                $("form:first #hodinyd").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if (!re.test($("form:first #sazbad").val())) {
                $("form:first #sazbad").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #sazbad").after('<span class="form-error-msg">! Zadejte hodinovou sazbu</span>');
                $("form:first #sazbad").focus();
                return false;
            }
        });
    });
