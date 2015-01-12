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
    
    $(document).ready(function(){
        
        $('#splnenood').datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So']})
        $('#splnenood').datepicker('option', 'monthNames', ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'])
        $('#splnenodo').datepicker({dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So']})
        $('#splnenodo').datepicker('option', 'monthNames', ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'])
        
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

        if (/(filtr=2)/.test(window.location.search)){
            $('fieldset').css({border:0});
            $('fieldset p').hide();
        }
                
        $('table#podklady tr:not(:first,:last)').hover(
            function(){
                $(this).addClass('orange');
            },
            function(){
                $(this).removeClass('orange');
            }
        )
        
        $("table#podklady tr td a").click(function(){document.location=$(this).attr('href');return false;})
    });
