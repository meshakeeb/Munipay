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

				this.misc()
				this.accounts()
				this.requests()
				this.hasBundle()
				this.reviewOrder()
			},

			misc: function() {
				if ( window.location.hash ) {
					$( window.location.hash ).collapse( 'show' );
				}

				if ( 'undefined' !== typeof jQuery.fn.datepicker ) {
					$( '.js-datepicker', this.wrap ).datepicker()

					$( '.report-datepicker' ).datepicker({
						dateFormat: 'mm/dd/yy'
					})
				}

				$( document.body ).on( 'submit', '.needs-validation', function( event ) {
					if ( false === this.checkValidity() ) {
						event.preventDefault()
						event.stopPropagation()
					}

					this.classList.add( 'was-validated' )
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

				this.wrap.on( 'input', '.cost-center, .network', function( event ) {
					event.preventDefault()

					var input = $( this ),
						group = input.closest( '.check-account' ),
						state = input.val().length > 0

					if ( input.hasClass( 'cost-center' ) ) {
						group.find( '.network' ).prop( 'disabled', state )
						group.find( '.activity-code' ).prop( 'disabled', state )
					} else if ( input.hasClass( 'network' ) ) {
						group.find( '.cost-center' ).prop( 'disabled', state )
						group.find( '.activity-code' ).prop( 'required', state )
					}
				})
			},

			requests: function() {
				this.saveRequest()
				this.deleteRequest()
				this.bundleFields()
				this.addNewRequest()
				this.boxTitleHandler()

				this.wrap.on( 'change', '.custom-file-input', function() {
					var input = $( this )

					if ( input.val() ) {
						input.next().text( input.val().match( /[\/\\]([\w\d\s\.\-\(\)]+)$/ ).pop() )
					} else {
						input.next().text( 'Choose file...' )
					}
				})
			},

			bundleFields: function() {
				var app = this

				app.wrap.on( 'change', '.request-delivery-method', function( event ) {
					app.hasBundle()
				})

				app.wrap.on( 'change', '.request-delivery-method', function( event ) {
					var select = $( this ),
						method = select.val(),
						requestDate = new Date( $( '#request_date' ).val() ),
						projectedDate = new Date( requestDate ),
						addDays = '2' === method ? 1 : 2

					// Add Days
					projectedDate.setDate( projectedDate.getDate() + addDays )
					if ( 0 === projectedDate.getDay() ) {
						projectedDate.setDate( projectedDate.getDate() + addDays )
					}

					if ( 6 === projectedDate.getDay() ) {
						projectedDate.setDate( projectedDate.getDate() + addDays + 1 )
					}

					select.closest( 'form' ).find( '[name="request_delivery_date"]' ).val( $.datepicker.formatDate( 'MM d, yy', projectedDate ) )
				})
			},

			hasBundle: function() {
				var bundleWrap = $( '#order-bundle' )

				this.wrap.find( '.request-delivery-method' ).each(function() {
					var select = $( this ),
						value  = select.val()

					if ( '3' === value ) {
						munipay.hasBundle = true
						bundleWrap.removeClass( 'd-none' )
						return false
					}

					munipay.hasBundle = false
					bundleWrap.addClass( 'd-none' )
				})

				bundleWrap.find( 'input,select' ).on( 'change', function() {
					var input = $( this ),
						selector = '.' + input.attr( 'id' )

					$( selector ).val( input.val() )
				})

				if ( munipay.hasBundle ) {
					var checkZero = $( '.order-check-0' )
					bundleWrap.find( 'input,select' ).each(function() {
						var input = $( this ),
							selector = '.' + input.attr( 'id' )

						checkZero.find( selector ).val( input.val() )
					})
				}
			},

			deleteRequest: function() {
				var app = this

				app.wrap.on( 'click', '.order-check-delete', function( event ) {
					event.preventDefault()
					if ( false === confirm( 'Are you sure?' ) ) {
						return
					}

					var button = $( this ),
						data = {
							check_id: button.parent().find( '[name="check_id"]' ).val()
						},
						rows = button.closest( '.order-check' )

					button.prop( 'disabled', true )
					rows.addClass( 'disabled' )

					app.post( 'delete_check', data )
						.done( function( result ) {
							if ( result && ! result.success ) {
								return
							}

							rows.remove()
						})
				})
			},

			saveRequest: function() {
				var app = this

				app.wrap.on( 'submit', '.needs-validation', function( event ) {
					event.preventDefault()

					if ( false === this.checkValidity() ) {
						this.classList.add( 'was-validated' )
						return
					}

					var button = $( this ).find( '.order-check-save' )

					button.prop( 'disabled', true )

					if ( 0 === munipay.orderID ) {
						app.saveOrder( button )
						return
					}

					app.saveCheck( button, this )
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
					.done( function( result ) {
						if ( result && ! result.success ) {
							return
						}

						munipay.orderID = parseInt( result.orderID )
						button.trigger( 'click' )
					})
			},

			saveCheck: function( button, form ) {
				var app  = this,
					formData = new FormData( form )

				formData.append( 'order_id', munipay.orderID )
				formData.append( 'requester_id', munipay.userID )
				formData.append( 'action', 'munipay_update_check' )
				formData.append( 'security', munipay.security )

				button.addClass( 'button--loading disabled' )
				$.ajax({
					type:'POST',
					url: munipay.endpoint,
					processData: false,
					contentType: false,
					async: false,
					cache: false,
					data: formData
				})
				.always(function() {
					button.prop( 'disabled', false )
					button.removeClass( 'button--loading disabled' )
				})
				.done( function( result ) {
					if ( result && ! result.success ) {
						return
					}

					button.html( '<span>' + munipay.l10n.button_update + '</span>' )
					button.prev( 'input' ).val( result.checkID )
					button.closest( '.order-check' ).addClass( 'saved' )
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

					request = app.wrap.find( '.order-check:last' )
					$( '.js-datepicker', request ).datepicker()
					$( '.request-delivery-method', request ).trigger( 'change' )
				})
			},

			boxTitleHandler: function() {
				var app = this
				app.wrap.on( 'input', '[name="payee_name"], [name="request_amount"]', function( event ) {
					var form = $( this ).closest( '.order-check' ),
						title =[
							'Request',
							form.find( '[name="payee_name"]' ).val(),
							app.formatMoney( form.find( '[name="request_amount"]' ).val() )
						].filter(Boolean).join( ' &ndash; ' )

					form.find( '.order-check-title-text' ).html( title )
				})

				app.wrap.on( 'change', '[name="approver"]', function( event ) {
					var select = $( this ),
						user = munipay.users[ select.val() ],
						form = select.closest( 'form' )

					form.find( '[name="approver_email"]' ).val( user.email )
					form.find( '[name="approver_phone"]' ).val( user.phone )
				})
			},

			reviewOrder: function() {
				var app = this,
					orderTotal = $( '#order-total-amount' )

				$( '.order-check-remove' ).on( 'click', function( event ) {
					event.preventDefault()
					if ( false === confirm( 'Are you sure?' ) ) {
						return
					}

					var button = $( this ),
						data = {
							order_id: munipay.orderID,
							check_id: button.data( 'check-id' )
						},
						rows = button.closest( 'ul' ).find( '.check-' + button.data( 'check-id' ) )

					button.prop( 'disabled', true )
					rows.addClass( 'disabled' )

					app.post( 'delete_check', data )
						.done( function( result ) {
							if ( result && ! result.success ) {
								return
							}

							rows.remove()
							orderTotal.html( result.orderTotal )
						})
				})
			},

			formatMoney: function( amount ) {
				if ( '' === amount ) {
					return ''
				}
				return '$' + parseFloat( amount ).toFixed( 2 ).toLocaleString()
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
				})
			}
		};

		munipayApp.init();
	});

	$('#user_login').attr('placeholder','Username');
	$('.login-action-login #user_login').attr('placeholder','Username or Email Address');
	$('#user_pass').attr('placeholder','Password');
	$('#user_email').attr('placeholder','Email Address');


})( jQuery );
