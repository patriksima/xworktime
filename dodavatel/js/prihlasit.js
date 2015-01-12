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
        $("form:first #prist_jmeno").focus();              
        $('form:first').submit(function(){
                               
            $('form:first input,select,textarea').css({backgroundColor:''});
            $('form:first div').remove();
            
            if ($("form:first #prist_jmeno").val() == '') {
                $("form:first #prist_jmeno").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_jmeno").after('<div class="contentplace"><div class="topplace"><img src="../img/GtFTpGm-0.png" alt="" width="10" height="10" class="placeborder" style="display: none" /></div><p>Zadejte přístupové jméno</p><div class="bottomplace"><img src="../img/YcIW1vn-3.png" alt="" width="10" height="10" class="placeborder" style="display: none" /></div></div>');
                $("form:first div").fadeIn('slow');
                $("form:first #prist_jmeno").focus();
                return false;
            }
            
            if ($("form:first #prist_heslo").val() == '') {
                $("form:first #prist_heslo").css({backgroundColor:ERR_BGCOLOR});
                $("form:first #prist_heslo").after('<div class="contentplace"><div class="topplace"><img src="../img/GtFTpGm-0.png" alt="" width="10" height="10" class="placeborder" style="display: none" /></div><p>Zadejte přístupové heslo</p><div class="bottomplace"><img src="../img/YcIW1vn-3.png" alt="" width="10" height="10" class="placeborder" style="display: none" /></div></div>');
                $("form:first div").fadeIn('slow');
                $("form:first #prist_heslo").focus();
                return false;
            }
        });        
    });
