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
        $("form:first #prist_jmeno").focus();               
        $('form:first').submit(function(){
            $('form:first input,select,textarea').css({backgroundColor:''});
            $('form:first span.form-error-msg').empty();
            
            if ($("form:first #prist_jmeno").val() == '') {
                $("form:first #prist_jmeno").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_jmeno").focus();
                return false;
            }
            
            if ($("form:first #prist_heslo").val() == '') {
                $("form:first #prist_heslo").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_heslo").focus();
                return false;
            }
            
            if ($("form:first #prist_heslo").val() != $("form:first #prist_heslo2").val()) {
                $("form:first #prist_heslo").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_heslo").focus();
                return false;
            }
            
            if ($("form:first #nazev").val() == '') {
                $("form:first #nazev").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #nazev").after('<span class="form-error-msg">! Zadejte název</span>');
                $("form:first #nazev").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if (!re.test($("form:first #sazba").val())) {
                $("form:first #sazba").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #sazba").after('<span class="form-error-msg">! Zadejte sazbu</span>');
                $("form:first #sazba").focus();
                return false;
            }
            
            var re = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/
            if (!re.test($("form:first #email").val())) {
                $("form:first #email").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #email").after('<span class="form-error-msg">! Zadejte platný e-mail</span>');
                $("form:first #email").focus();
                return false;
            }
        });
    });
