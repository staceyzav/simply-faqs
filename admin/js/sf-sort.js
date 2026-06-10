jQuery( function ( $ ) {

	var $tbody = $( '#the-list' );
	if ( ! $tbody.length ) return;

	$tbody.sortable( {
		items:  'tr',
		handle: '.sf-drag-handle',
		axis:   'y',
		helper: function ( e, tr ) {
			// Keep column widths stable while dragging
			tr.children().each( function () {
				$( this ).width( $( this ).width() );
			} );
			return tr;
		},
		start: function ( e, ui ) {
			ui.item.addClass( 'sf-dragging' );
		},
		stop: function ( e, ui ) {
			ui.item.removeClass( 'sf-dragging' );
			saveOrder();
		},
	} );

	function saveOrder() {
		var order = [];
		$tbody.find( 'tr' ).each( function () {
			var id = $( this ).attr( 'id' );
			if ( id ) {
				order.push( id.replace( 'post-', '' ) );
			}
		} );

		$.post( sfSort.ajaxurl, {
			action: 'sf_save_order',
			nonce:  sfSort.nonce,
			order:  order,
		} );
	}

} );
