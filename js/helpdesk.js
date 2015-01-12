
function helpdesk_ajax(elm) {
    if (!confirm('Opravdu smazat?')) {
        return false
    }        
    var tmp = elm.attr('href').split('?')
    $.ajax({
        type: "POST",
        url: tmp[0],
        data: tmp[1],
        dataType: "json",
        success: function(msg) {
            if (msg.status=='ok') {
                elm.parents('tr').find('td').css('background','#66ff00')
                setTimeout(function() {
                    elm.parents('tr').find('td').css('background','')
                    elm.parents('tr').fadeOut('slow')
                },1000)
            } else {
                elm.parents('tr').find('td').css('background','#ff3333')
                setTimeout(function() {
                    elm.parents('tr').find('td').css('background','')
                },1000)
            }
        },
        error: function(msg) {
            elm.parents('tr').find('td').css('background','#ff3333')
            setTimeout(function() {
                elm.parents('tr').find('td').css('background','')
            },1000)
        }
    })
    return false
}