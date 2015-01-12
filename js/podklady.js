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

    function fakturovano(typ)
    {
        var data = 'typ='+typ;
        var sel = $("input:checkbox[name='id_podklad[]']:checked");
        
        if (!sel.length) {
            alert('Zaškrtněte podklady, které jsou vyfakturovány');
            return false;
        }
        
        sel.each(function(){
            data += '&id_podklad[]='+$(this).val();                                                                  
        })
        
        $.ajax({
            type: "GET",
            url: "../fakturovano.php",
            data: data,
            dataType: "text",
            success: function(text) {
                sel.each(function(){
                    $tr = $(this).parent().parent();
                    if (typ=='zf')
                        $tr.find(" input[name='fakturaz']").attr('checked', true);
                    else
                        $tr.find(" input[name='fakturad']").attr('checked', true);
                })
                
                var toffset = $('table').offset();
                var tw = $('table').width();
                var th = $('table').height();
                
                var top = (toffset.top + 50);
                var left = (toffset.left + tw - 100)/2;
                
                //todo: mozna to nastylovat v css
                $('<div>').css({backgroundColor:'#ff9900',border:'1px solid #fff',color:'#fff',textAlign:'center',fontSize:'1.8em',padding:'30px 45px 30px 45px',position:'absolute',top:top,left:left})
                          .append('<strong>Hotovo</strong>')
                          .appendTo('#main')
                          .show('fast').animate({opacity: 1.0}, 1500).hide('fast');
            }
        })
    }

    function zaplaceno(typ)
    {
        var data = 'typ='+typ;
        var sel = $("input:checkbox[name='id_podklad[]']:checked");
        
        if (!sel.length) {
            alert('Zaškrtněte podklady, které jsou zaplaceny');
            return false;
        }
        
        // test, zda-li se nepokousime platit neco co jeste nebylo fakturovano
        var allow = true;
        sel.each(function(){
            if (typ=='zz') {
                var $tr = $(this).parent().parent();
                allow = $tr.find(" input[name='fakturaz']").is(':checked') || false;
            }
            if (typ=='dz') {
                var $tr = $(this).parent().parent();
                allow = $tr.find(" input[name='fakturad']").is(':checked') || false;
            }
            if (!allow) {
                alert('Některé podklady ještě nejsou vyfakturovány.');
                return false;
            }
            data += '&id_podklad[]='+$(this).val();
        })
        
        if (!allow || data=='typ='+typ) return false; // neni co zpracovavat
        
        $.ajax({
            type: "GET",
            url: "../zaplaceno.php",
            data: data,
            dataType: "text",
            success: function(text) {
                sel.each(function(){
                    $tr = $(this).parent().parent();
                    if (typ=='zz')
                        $tr.find(" input[name='zaplacenz']").attr('checked', true);
                    else
                        $tr.find(" input[name='zaplacend']").attr('checked', true);
                })
                
                var toffset = $('table').offset();
                var tw = $('table').width();
                var th = $('table').height();
                
                var top = (toffset.top + 50);
                var left = (toffset.left + tw - 100)/2;
                
                //todo: mozna to nastylovat v css
                $('<div>').css({backgroundColor:'#ff9900',border:'1px solid #fff',color:'#fff',textAlign:'center',fontSize:'1.8em',padding:'30px 45px 30px 45px',position:'absolute',top:top,left:left})
                          .append('<strong>Hotovo</strong>')
                          .appendTo('#main')
                          .show('fast').animate({opacity: 1.0}, 1500).hide('fast');
            }
        })
    }
    
    // prepina barvu zf,zz,df,dz
    function toggleButtons() {
        var l = $("table tr td input[name='id_podklad[]']:checked").length;
        if (l) {
            $("#zf").css('color','#ff0000');
            $("#zz").css('color','#ff0000');
            $("#df").css('color','#ff0000');
            $("#dz").css('color','#ff0000');
        } else {
            $("#zf").css('color','');
            $("#zz").css('color','');
            $("#df").css('color','');
            $("#dz").css('color','');
        }
    }
    
    $(document).ready(function(){
        $('#splnenood').datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So']})
        $('#splnenood').datepicker('option', 'monthNames', ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'])
        $('#splnenodo').datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So']})
        $('#splnenodo').datepicker('option', 'monthNames', ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'])
        
        // ovladani +
        $("table#podklady tr td a[href^='#']").each(function(){
            var re = /^#[0-9]+$/
            if (re.test($(this).attr('href'))) {
                //$(this).css({'padding':'0 2px 0 2px','text-decoration':'none'});
                $(this).click(function(){
                    var tr = $(this).parent().parent().next();
                    if (tr.css('display')=='none') {
                        //$(this).text('-');
                        tr.css({display:'table-row'});
                    } else {
                        //$(this).text('+');
                        tr.css({display:'none'});
                    }
                    
                    return false;
                });
            }
        })
        
        // show/hide search form                      
        $('legend a').click(
            function(){
                var h = $('fieldset p:first').css('display');
                
                if (h=='none') {
                    $('fieldset').css({border:'1px silver solid'});
                    $('fieldset p').show();
                } else {
                    $('fieldset').css({border:0});
                    $('fieldset p').hide();
                }
            }
        )

        if (!/(filtr=1)/.test(window.location.search)){
            $('fieldset').css({border:0});
            $('fieldset p').hide();
        }
        
        $('#zf').click(function(){
            fakturovano('zf');
            return false;
        })

        $('#df').click(function(){
            fakturovano('df');
            return false;
        })

        $('#zz').click(function(){
            zaplaceno('zz');
            return false;
        })

        $('#dz').click(function(){
            zaplaceno('dz');
            return false;
        })
        
        // zaskrtnout vse
        $('#vse').click(function(){
            var c = $(this).is(':checked') || false;
            $('table#podklady tr:not(:first,:last)').each(function(){
                var $i = $(this).find('input:first:checkbox');
                $i.attr('checked', c);
                if (c) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            })
            toggleButtons();
        })
        
        $('table#podklady tr:not(:first,:last)').click(function(){
            var $i = $(this).find('input:first:checkbox');
            $i.attr('checked', !$i.is(':checked'));
            $(this).toggleClass('selected');
            toggleButtons();
        })

        $('table#podklady tr:not(:first,:last) input:checkbox:enabled').click(function(){
            $(this).attr('checked', !$(this).is(':checked'));
        })
        
        $('table#podklady tr:not(:first,:last)').hover(
            function(){
                $(this).addClass('orange');
            },
            function(){
                $(this).removeClass('orange');
            }
        )
        
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
