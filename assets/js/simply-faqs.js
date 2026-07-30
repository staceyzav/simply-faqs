( function() {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function() {

		// ── Accordion ──────────────────────────────────────────────────────────
		document.querySelectorAll( '.sf-faq__question' ).forEach( function( btn ) {
			btn.addEventListener( 'click', function() {
				var expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
				var answer   = btn.nextElementSibling;

				// Close all other open FAQs in the same block
				if ( ! expanded ) {
					var block = btn.closest( '.sf-faqs-block' );
					if ( block ) {
						block.querySelectorAll( '.sf-faq__question[aria-expanded="true"]' ).forEach( function( other ) {
							other.setAttribute( 'aria-expanded', 'false' );
							other.nextElementSibling.classList.remove( 'is-open' );
						} );
					}
				}

				btn.setAttribute( 'aria-expanded', ! expanded );
				answer.classList.toggle( 'is-open', ! expanded );
			} );
		} );

		// ── Category filters ───────────────────────────────────────────────────
		document.querySelectorAll( '.sf-filters' ).forEach( function( filters ) {
			var block = filters.closest( '.sf-faqs-block' );
			if ( ! block ) return;

			var faqs = block.querySelectorAll( '.sf-faq' );

			filters.querySelectorAll( '.sf-filter-btn' ).forEach( function( btn ) {
				btn.addEventListener( 'click', function() {
					// Update active state
					filters.querySelectorAll( '.sf-filter-btn' ).forEach( function( b ) {
						b.classList.remove( 'is-active' );
					} );
					btn.classList.add( 'is-active' );

					var cat = btn.dataset.category;

					faqs.forEach( function( faq ) {
						// Close any open answers when switching category
						var q = faq.querySelector( '.sf-faq__question' );
						var a = faq.querySelector( '.sf-faq__answer' );
						if ( q ) q.setAttribute( 'aria-expanded', 'false' );
						if ( a ) a.classList.remove( 'is-open' );

						if ( ! cat || faq.dataset.category === cat ) {
							faq.classList.remove( 'is-hidden' );
						} else {
							faq.classList.add( 'is-hidden' );
						}
					} );
				} );
			} );
		} );

	} );

} )();
