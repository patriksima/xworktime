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
            
            if ($("form:first #nazev").val() == '') {
                $("form:first #nazev").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #nazev").after('<span class="form-error-msg">! Zadejte název dodavatele</span>');
                $("form:first #nazev").focus();
                return false;
            }
            
            if ($("form:first #id_typ").val() == '') {
                $("form:first #id_typ").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_typ").after('<span class="form-error-msg">! Vyberte typ dodavatele</span>');
                $("form:first #id_typ").focus();
                return false;
            }
            
            if ($("form:first #id_stabilita").val() == '') {
                $("form:first #id_stabilita").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_stabilita").after('<span class="form-error-msg">! Vyberte stabilitu dodavatele</span>');
                $("form:first #id_stabilita").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if (!re.test($("form:first #sazba").val())) {
                $("form:first #sazba").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #sazba").after('<span class="form-error-msg">! Zadejte hodinovou sazbu dodavatele</span>');
                $("form:first #sazba").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if (!re.test($("form:first #rychlost").val())) {
                $("form:first #rychlost").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #rychlost").after('<span class="form-error-msg">! Zadejte koeficient rychlosti</span>');
                $("form:first #rychlost").focus();
                return false;
            }
            
            var re = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/;
            if (!re.test($("form:first #email").val())) {
                $("form:first #email").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #email").after('<span class="form-error-msg">! Zadejte platný e-mail dodavatele</span>');
                $("form:first #email").focus();
                return false;
            }
        });        
    });
