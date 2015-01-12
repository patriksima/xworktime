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
                $("form:first #id_zadavatel").after('<span class="form-error-msg">! Vyberte zadavatele</span>');
                $("form:first #id_zadavatel").focus();
                return false;
            }
            
            if ($("form:first #id_dodavatel").val() == '') {
                $("form:first #id_dodavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_dodavatel").after('<span class="form-error-msg">! Vyberte dodavatele</span>');
                $("form:first #id_dodavatel").focus();
                return false;
            }
            
            if ($("form:first #nazev").val() == '') {
                $("form:first #nazev").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #nazev").after('<span class="form-error-msg">! Zadejte název úkolu</span>');
                $("form:first #nazev").focus();
                return false;
            }
            
            if ($("form:first #klic").val() == '' && $("form:first #klic2").val() == '') {
                $("form:first #klic2").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #klic2").after('<span class="form-error-msg">! Zadejte klíčové slovo</span>');
                $("form:first #klic2").focus();
                return false;
            }
            
            var re = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/
            if ($("form:first #termin").val()!='' && !re.test($("form:first #termin").val())) {
                $("form:first #termin").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #termin").after('<span class="form-error-msg">! Zadejte termín ve tvaru RRRR-MM-DD</span>');
                $("form:first #termin").focus();
                return false;
            }
        });

        $('#id_zadavatel').change(function(){
            var id_zadavatel = $('#id_zadavatel option:selected').val();

            $('#klic').empty();
            
            if (id_zadavatel) {
                $('<option value="">..načítám...</option>').appendTo('#klic');
            } else {
                $('<option value="">--vyberte--</option>').appendTo('#klic');
                return;
            }
            
            $.ajax({
                type: "GET",
                url: "../klice.php",
                data: "id_zadavatel="+id_zadavatel,
                dataType: "json",
                success: function(json) {
                    $('#klic').empty();
                    $('<option value="">--vyberte--</option>').appendTo('#klic');
                    $(json.klice).each(function(){
                        var t = this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                        $('<option value="'+t+'">'+t+'</option>').appendTo('#klic');
                    })
                    $('#klic').val(-1);
                }
            })
        });        
    });
