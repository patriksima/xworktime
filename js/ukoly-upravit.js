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

    var rychlost = 1.00;
    
    
    $(document).ready(function(){
        var status = $('#status option:selected').val();
        var id_dodavatel = $('#id_dodavatel option:selected').val();              
        $.ajax({
            type: "GET",
            url: "../rychlost.php",
            data: "id_dodavatel="+id_dodavatel,
            dataType: "json",
            success: function(json) {
                rychlost = parseFloat(json.rychlost);
            }
        })
                      
        $('form:first').submit(function(){
            $('form:first input,select,textarea').css({backgroundColor:''});
            $('form:first span.form-error-msg').empty();
            
            if ($("form:first #id_zadavatel").val() == '') {
                $("form:first #id_zadavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_zadavatel").after('<span class="form-error-msg">! Vyberte zadavatele</span>');
                $("form:first #id_zadavatel").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if ($("form:first #status").val() == 'dokonceny' &&
                !re.test($("form:first #sazbaz").val()) &&
                !re.test($("form:first #sazbaz2").val())) {
                $("form:first #sazbaz2").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #sazbaz2").after('<span class="form-error-msg">! Zadejte sazbu zadavatele</span>');
                $("form:first #sazbaz2").focus();
                return false;
            }

            if ($("form:first #id_dodavatel").val() == '') {
                $("form:first #id_dodavatel").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #id_dodavatel").after('<span class="form-error-msg">! Vyberte dodavatele</span>');
                $("form:first #id_dodavatel").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if ($("form:first #status").val() == 'dokonceny' &&
                !re.test($("form:first #sazbad").val())) {
                $("form:first #sazbad").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #sazbad").after('<span class="form-error-msg">! Zadejte sazbu dodavatele</span>');
                $("form:first #sazbad").focus();
                return false;
            }
            
            if ($("form:first #nazev").val() == '') {
                $("form:first #nazev").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #nazev").after('<span class="form-error-msg">! Zadejte název úkolu</span>');
                $("form:first #nazev").focus();
                return false;
            }
            
            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if ($("form:first #status").val() == 'dokonceny' &&
                !re.test($("form:first #hodinyd").val())) {
                $("form:first #hodinyd").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #hodinyd").after('<span class="form-error-msg">! Zadejte počet hodin</span>');
                $("form:first #hodinyd").focus();
                return false;
            }

            var re = /^[0-9]+([,|.][0-9]+){0,1}$/
            if ($("form:first #status").val() == 'dokonceny' &&
                !re.test($("form:first #hodinyz").val())) {
                $("form:first #hodinyz").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #hodinyz").after('<span class="form-error-msg">! Zadejte počet hodin</span>');
                $("form:first #hodinyz").focus();
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
            
            if ($("form:first #status").val() == 'dokonceny' &&
                !re.test($("form:first #termin").val())) {
                $("form:first #termin").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #termin").after('<span class="form-error-msg">! Zadejte termín ve tvaru RRRR-MM-DD</span>');
                $("form:first #termin").focus();
                return false;
            }
        });

        $('#id_zadavatel').change(function(){
            var id_zadavatel = $('#id_zadavatel option:selected').val();

            $('#klic').empty();
            $('#sazbaz').empty();
            
            if (id_zadavatel) {
                $('<option value="">..načítám...</option>').appendTo('#klic');
                $('<option value="">..načítám...</option>').appendTo('#sazbaz');
            } else {
                $('<option value="">--vyberte--</option>').appendTo('#klic');
                $('<option value="">--vyberte--</option>').appendTo('#sazbaz');
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
            $.ajax({
                type: "GET",
                url: "../sazby.php",
                data: "id_zadavatel="+id_zadavatel+"&list=1",
                dataType: "json",
                success: function(json) {
                    $('#sazbaz').empty();
                    $('<option value="">--vyberte--</option>').appendTo('#sazbaz');
                    $.each(json.sazbaz, function(i,item){
                        $('<option value="'+parseInt(item[0])+'">'+parseInt(item[0])+' - '+item[1]+'</option>').appendTo('#sazbaz');
                    })
                    $('#sazbaz').val(-1);
                }
            })                    
        });

        $('#id_dodavatel').change(function(){
            var id_dodavatel = $('#id_dodavatel option:selected').val();

            if (id_dodavatel) {
                $('#sazbad').val('načítám...');
            } else {
                $('#sazbad').val('');
                return;
            }
            
            $.ajax({
                type: "GET",
                url: "../sazby.php",
                data: "id_dodavatel="+id_dodavatel,
                dataType: "json",
                success: function(json) {
                    $('#sazbad').val(parseInt(json.sazbad));
                }
            })
        });
        
        $('#status').change(function(){
            if (status == 'dokonceny') {
                if (!confirm('Úkol je již dokončen!\nZměna statusu dokončeného úkolu smaže, možná již zaúčtovaný, podklad!\nOpravdu chcete změnit status?')) {
                    $('#status').val(status);
                    return false;
                }
            }
            if ($('#status').val() == 'dokonceny') {
                var id_zadavatel = $('#id_zadavatel option:selected').val();
                
                $('#sazbaz').empty();
                $('<option value="">..načítám...</option>').appendTo('#sazbaz');
                
                $.ajax({
                    type: "GET",
                    url: "../sazby.php",
                    data: "id_zadavatel="+id_zadavatel+"&list=1",
                    dataType: "json",
                    success: function(json) {
                        $('#sazbaz').empty();
                        $('<option value="">--vyberte--</option>').appendTo('#sazbaz');
                        $.each(json.sazbaz, function(i,item){
                            $('<option value="'+parseInt(item[0])+'">'+parseInt(item[0])+' - '+item[1]+'</option>').appendTo('#sazbaz');
                        })
                        $('#sazbaz').val(-1);
                    }
                })                    
                
                var id_dodavatel = $('#id_dodavatel option:selected').val();
                
                rychlost = 1;
                
                $('#sazbad').val('načítám...');
                
                $.ajax({
                    type: "GET",
                    url: "../sazby.php",
                    data: "id_dodavatel="+id_dodavatel,
                    dataType: "json",
                    success: function(json) {
                        $('#sazbad').val(parseInt(json.sazbad));
                    }
                })
                $.ajax({
                    type: "GET",
                    url: "../rychlost.php",
                    data: "id_dodavatel="+id_dodavatel,
                    dataType: "json",
                    success: function(json) {
                        rychlost = parseFloat(json.rychlost);
                    }
                })
                $('form:first #sazbaz').parent().parent().show();
                $('form:first #sazbad').parent().parent().show();
                $('form:first #hodinyz').parent().parent().show();
                $('form:first #hodinyd').parent().parent().show();
            } else {
                $('form:first #sazbaz').parent().parent().hide();
                $('form:first #sazbad').parent().parent().hide();
                $('form:first #hodinyz').parent().parent().hide();
                $('form:first #hodinyd').parent().parent().hide();
            }
        })        
        
        $('#hodinyd').keyup(function(){
            // zad.hodiny = dod.hodiny * koeficient rychlosti dod.
            // zaokrouhleno na ctvrtky smerem nahoru
            var hod = parseFloat(rychlost*parseFloat($('#hodinyd').val()));
            var flr = Math.floor(hod);
            var des = hod - flr;
            
            if (des == 0.0) hod = flr;
            else if (des <= 0.25) hod = flr + 0.25;
            else if (des <= 0.50) hod = flr + 0.50;
            else if (des <= 0.75) hod = flr + 0.75;
            else hod = flr + 1.0;
            
            if (!isNaN(hod)) $('#hodinyz').val(hod);
        });
    });
