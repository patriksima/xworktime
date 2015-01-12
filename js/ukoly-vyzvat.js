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
            
            var re = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/;
            if (!re.test($("form:first #email").val())) {
                $("form:first #email").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #email").after('<span class="form-error-msg">! Zadejte platný e-mail dodavatele</span>');
                $("form:first #email").focus();
                return false;
            }
        })
    })

