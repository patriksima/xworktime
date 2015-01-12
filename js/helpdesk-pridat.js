    $(document).ready(function(){
        var cache = {},
			lastXhr;

        $('#typ').autocomplete({
            source: function( request, response ) {
				var term = request.term
				if ( term in cache ) {
					response( cache[ term ] )
					return
				}
                request.type='typy'
				lastXhr = $.getJSON( 'ajax.php', request, function( data, status, xhr ) {
					cache[ term ] = data
					if ( xhr === lastXhr ) {
						response( data )
					}
				})
			},
			focus: function( event, ui ) {
				$(event.target).val(ui.item.label)
				return false
			},
            select: function(event, ui) {
                $(event.target).val(ui.item.label)
                return false
            }
        })        
    })
