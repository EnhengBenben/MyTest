$.fn.dataTable.TableTools.buttons.download = $.extend(
    true,
    {},
    $.fn.dataTable.TableTools.buttonBase,
    {
        "sButtonText": "Download",
        "sUrl":      "",
        "sType":     "POST",
        "fnData":    false,
        "fnClick": function( button, config ) {
            var dt = new $.fn.dataTable.Api( this.s.dt );
            var data = dt.ajax.params() || {};

            // Optional static additional parameters
            // data.customParameter = ...;
            data.mode = 'export';

            if ( config.fnData ) {
                config.fnData( data );
            }

            var iframe = $('<iframe/>', {
                id: "RemotingIFrame"
            }).css( {
                border: 'none',
                width: 0,
                height: 0
            } )
                .appendTo( 'body' );

            var contentWindow = iframe[0].contentWindow;
            contentWindow.document.open();
            contentWindow.document.close();

            var form = contentWindow.document.createElement( 'form' );
            form.setAttribute( 'method', config.sType );
            form.setAttribute( 'action', config.sUrl );

            var input = contentWindow.document.createElement( 'input' );
            input.name = 'json';
            input.value = JSON.stringify( data );

            form.appendChild( input );
            contentWindow.document.body.appendChild( form );
            form.submit();
        }
    }
);