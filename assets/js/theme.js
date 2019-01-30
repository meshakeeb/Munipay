/*!
 * Munipay.io
 *
 * @version 1.0.0
 * @author  Boltmedia
 */
;(function( $ ) {

	'use strict';

	// Document Ready
	$(function() {

		var munipayApp = {

			init: function() {
				this.total   = 0
				this.wrap    = $( '#orders' )
				this.cloneMe = $( '.order-check-0' )

				this.accounts()
				this.requests()
			},

			requests: function() {
				var app = this
				$( '.order-request-add' ).on( 'click', function( event ) {
					event.preventDefault()

					var button = $( this ),
						request = app.cloneMe.clone()

					app.total += 1
					request = $( '<div/>' ).append( request ).html()
					request = request
								.replace( /order-check-0/g, 'order-check-' + app.total )
								.replace( /check-heading-0/g, 'check-heading-' + app.total )

					app.wrap.append( request )
				})
			},

			accounts: function() {
				// Add account.
				this.wrap.on( 'click', '.order-check-account-add', function( event ) {
					event.preventDefault()

					var button = $( this ),
						account = button.closest( '.check-account' ),
						newAccount = account.clone(),
						newIndex = account.parent().find( '.check-account' ).length

					newIndex = parseInt( Math.random( newIndex, 100 ) * 100 )

					newAccount.find( 'input' ).each( function() {
						var input = $( this )
						input.attr( 'id', input.attr( 'id' ).replace( 0, newIndex ) )
						input.attr( 'name', input.attr( 'name' ).replace( 0, newIndex ) )
						input.val( '' )
					})

					newAccount.attr( 'data-index', newIndex )
					account.parent().append( newAccount )
				})

				// Remove account.
				this.wrap.on( 'click', '.order-check-account-remove', function( event ) {
					event.preventDefault()

					$( this ).closest( '.check-account' ).remove()
				})
			}
		};

		munipayApp.init();
	});

})( jQuery );
