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

        $('#prihlasitjako').change(function(){
            var l = {
                'demo': { 'user':'demo', 'pass':'xworktime' },
                'klient': { 'user':'klient', 'pass':'klient' },
                'dodavatel': { 'user':'dodavatel', 'pass':'dodavatel' }
            };
            var l = l[$(this).val()];
            $('#prist_jmeno').val(l['user']);
            $('#prist_heslo').val(l['pass']);
        });        
    });
