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
            
            if ($("form:first #prist_jmeno").val() == '') {
                $("form:first #prist_jmeno").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_jmeno").after('<span class="form-error-msg">! Zadejte přístupové jméno uživatele</span>');
                $("form:first #prist_jmeno").focus();
                return false;
            }
                        
            if ($("form:first #prist_heslo").val() == '') {
                $("form:first #prist_heslo").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_heslo").after('<span class="form-error-msg">! Zadejte přístupové heslo uživatele</span>');
                $("form:first #prist_heslo").focus();
                return false;
            }
            
            var re = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,5}$/;
            if (!re.test($("form:first #email").val())) {
                $("form:first #email").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #email").after('<span class="form-error-msg">! Zadejte platný e-mail uživatele</span>');
                $("form:first #email").focus();
                return false;
            }
            
            if ($("form:first #id_dodavatel").val() == '' && !$("form:first #prava").is(':checked')) {
                $("form:first #id_dodavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_dodavatel").after('<span class="form-error-msg">! Vyberte dodavatele nebo zaškrtněte checkbox pro administrátora</span>');
                $("form:first #id_dodavatel").focus();
                return false;
            }
            
            if ($("form:first #id_dodavatel").val() != '' && $("form:first #prava").is(':checked')) {
                $("form:first #id_dodavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_dodavatel").after('<span class="form-error-msg">! Vyberte dodavatele nebo zaškrtněte checkbox pro administrátora</span>');
                $("form:first #id_dodavatel").focus();
                return false;
            }
        });
        
        $('#id_dodavatel').change(function(){
            var id_dodavatel = $('#id_dodavatel option:selected').val();
            $.ajax({
                type: "GET",
                url: "../dodavatel.php",
                data: "id_dodavatel="+id_dodavatel,
                dataType: "json",
                success: function(json) {
                    $('#email').val(json.email);
                }
            })
        });
    });
