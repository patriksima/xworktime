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
            
            if ($("form:first #jmeno").val() == '') {
                $("form:first #jmeno").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #jmeno").after('<span class="form-error-msg">! Vyplňte své jméno</span>');
                $("form:first #jmeno").focus();
                return false;
            }
            
            if ($("form:first #typ").val() == '') {
                $("form:first #typ").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #typ").after('<span class="form-error-msg">! Vyberte typ požadavku.</span>');
                $("form:first #typ").focus();
                return false;
            }
                        
            if ($("form:first #nazev").val() == '') {
                $("form:first #nazev").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #nazev").after('<span class="form-error-msg">! Napište svůj požadavek.</span>');
                $("form:first #nazev").focus();
                return false;
            }
        });
    });
