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
            
            if ($("form:first input[name='nazev']").val() == '') {
                $("form:first input[name='nazev']").css({backgroundColor:ERR_BGCOLOR});
                $("form:first input[name='nazev']").after('<span class="form-error-msg">! Zadejte název klienta</span>');
                return false;
            }
        })
        
       $("form:first a[href='#']").click(
            function(){
                if ($(this).text()=='-' && $('form:first tr').length>4) {
                    $(this).parent().parent().empty();
                    $('form:first tr:empty').remove();
                }
                if ($(this).text()=='+') {
                    var n = $('form:first tr').length-2;
                    var clone = $('form:first tr:eq(1)').clone(true);
                    $(clone).find('label:eq(0)').attr({for:'sazbanazev'+n});
                    $(clone).find('input:eq(0)').attr({id:'sazbanazev'+n});
                    $(clone).find('input:eq(1)').attr({id:'sazba'+n});
                    $(clone).find('input:eq(0)').val('');
                    $(clone).find('input:eq(1)').val('');
                    $(this).parent().parent().after(clone);
                }
                return false;
            }
        )
    });
