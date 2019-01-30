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

				// Sanitize data.
				munipay.orderID = parseInt( munipay.orderID )
				munipay.userID = parseInt( munipay.userID )

				this.accounts()
				this.requests()
			},

			requests: function() {
				this.saveRequest()
				this.addNewRequest()
			},

			saveRequest: function() {
				var app = this

				app.wrap.on( 'click', '.order-check-save', function( event ) {
					event.preventDefault()

					var button = $( this )

					button.prop( 'disabled', true )

					if ( 0 === munipay.orderID ) {
						app.saveOrder( button )
						return
					}

					app.saveCheck( button )
				})
			},

			saveCheck: function( button ) {
				var app  = this,
					data = button.closest( 'form' ).serializeJSON()

				data.order_id     = munipay.orderID
				data.requester_id = munipay.userID

				app.post( 'create_check', data )
					.always(function() {
						button.prop( 'disabled', false )
					})
					.done( function() {
						if ( result && ! result.success ) {
							return
						}
					})
			},

			saveOrder: function( button ) {
				var app  = this,
					data = $( '#order-requester' ).serializeJSON()

				data.requester_id = munipay.userID
				app.post( 'create_order', data )
					.always(function() {
						button.prop( 'disabled', false )
					})
					.done( function() {
						if ( result && ! result.success ) {
							return
						}

						munipay.orderID = parseInt( result.orderID )
						button.trigger( 'click' )
					})
			},

			addNewRequest: function() {
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
			},

			post: function( action, data, method ) {
				return $.ajax({
					url: munipay.endpoint,
					type: method || 'POST',
					dataType: 'json',
					data: $.extend( true, {
						action: 'munipay_' + action,
						security: munipay.security
					}, data )
				});
			}
		};

		munipayApp.init();
	});

})( jQuery );
