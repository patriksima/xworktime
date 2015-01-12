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
            
            if ($("form:first #id_zadavatel").val() == '') {
                $("form:first #id_zadavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_zadavatel").after('<span class="form-error-msg">! Vyberte klienta</span>');
                $("form:first #id_zadavatel").focus();
                return false;
            }
            
            if ($("form:first #prist_jmeno").val() == '') {
                $("form:first #prist_jmeno").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_jmeno").after('<span class="form-error-msg">! Zadejte přístupové jméno klienta</span>');
                $("form:first #prist_jmeno").focus();
                return false;
            }
                        
            if ($("form:first #prist_heslo").val() == '') {
                $("form:first #prist_heslo").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_heslo").after('<span class="form-error-msg">! Zadejte přístupové heslo klienta</span>');
                $("form:first #prist_heslo").focus();
                return false;
            }
            
            var re = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/;
            if (!re.test($("form:first #email").val())) {
                $("form:first #email").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #email").after('<span class="form-error-msg">! Zadejte platný e-mail klienta</span>');
                $("form:first #email").focus();
                return false;
            }
        });
    });
