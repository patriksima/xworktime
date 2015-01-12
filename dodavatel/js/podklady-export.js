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
    });
