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
