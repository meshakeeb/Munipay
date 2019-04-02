<?php
/**
 * Authorize payment processor.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

defined( 'ABSPATH' ) || exit;

/**
 * Authorize class.
 */
class Authorize {

	/**
	 * Checkout form instance.
	 *
	 * @var Checkout
	 */
	private $form = null;

	/**
	 * Class constructor.
	 *
	 * @param Checkout $form Checkout instance.
	 */
	public function __construct( $form ) {
		if ( ! defined( 'AUTHORIZENET_LOG_FILE' ) ) {
			define( 'AUTHORIZENET_LOG_FILE', 'phplog' );
		}

		$this->form = $form;
	}

	/**
	 * Process payment.
	 */
	public function process() {
		$errors        = $this->form->errors;
		$response      = $this->get_response();
		$current_order = $this->form->order;

		if ( ! is_null( $response ) ) {
			$tresponse = $response->getTransactionResponse();

			// Check to see if the API request was successfully received and acted upon.
			if ( 'ok' === \strtolower( $response->getMessages()->getResultCode() ) ) {
				if ( ! is_null( $tresponse ) && ! is_null( $tresponse->getMessages() ) ) {
					update_post_meta( $current_order->get_id(), 'authorize_net_transaction_id', $tresponse->getTransId() );
					update_post_meta( $current_order->get_id(), 'authorize_net_transaction_auth_code', $tresponse->getAuthCode() );
					$errors->add( 'success', $tresponse->getMessages()[0]->getDescription() );
					return true;
				} else {
					if ( ! is_null( $tresponse->getErrors() ) ) {
						$message = 'Transaction Failed : ' . $tresponse->getErrors()[0]->getErrorText();
						$errors->add( $tresponse->getErrors()[0]->getErrorCode(), $message );
					}
				}
			} else {
				// Or, print errors if the API request wasn't successful.
				if ( ! is_null( $tresponse ) && ! is_null( $tresponse->getErrors() ) ) {
					$message = 'Transaction Failed : ' . $tresponse->getErrors()[0]->getErrorText();
					$errors->add( $tresponse->getErrors()[0]->getErrorCode(), $message );
				} else {
					$message = 'Transaction Failed : ' . $response->getMessages()->getMessage()[0]->getText();
					$errors->add( $response->getMessages()->getMessage()[0]->getCode(), $message );
				}
			}
		} else {
			$errors->add( 'no_response', 'No response returned' );
		}

		return false;
	}

	/**
	 * Create credit card.
	 *
	 * @param array $args Hold credit card data.
	 */
	public function set_credit_card( $args ) {
		$credit_card = new AnetAPI\CreditCardType;
		$credit_card->setCardNumber( $args['number'] );
		$credit_card->setExpirationDate( $args['expire'] );
		$credit_card->setCardCode( $args['ccv'] );

		// Add the payment data to a paymentType object.
		$this->credit_card = new AnetAPI\PaymentType;
		$this->credit_card->setCreditCard( $credit_card );
	}

	/**
	 * Get server response.
	 *
	 * @return mixed
	 */
	private function get_response() {
		$current_order = $this->form->order;
		$current_user  = wp_get_current_user();

		// Create order information.
		$order = new AnetAPI\OrderType;
		$order->setInvoiceNumber( $current_order->get_id() );
		$order->setDescription( 'Munipay # ' . $current_order->get_id() );

		// Set the customer's Bill To address.
		$customer_address = new AnetAPI\CustomerAddressType;
		$customer_address->setFirstName( $current_order->get_requester_name() );
		$customer_address->setCompany( $current_order->get_requester_name() );
		$customer_address->setAddress( $current_user->get( 'payment_address' ) );
		$customer_address->setCity( $current_user->get( 'payment_city' ) );
		$customer_address->setState( $current_user->get( 'payment_state' ) );
		$customer_address->setZip( $current_user->get( 'payment_zipcode' ) );
		$customer_address->setCountry( $current_user->get( 'payment_country' ) );

		// Set the customer's identifying information.
		$customer_data = new AnetAPI\CustomerDataType;
		$customer_data->setType( 'individual' );
		$customer_data->setId( $current_user->ID );
		$customer_data->setEmail( $current_order->get_requester_email() );

		// Add values for transaction settings.
		$duplicate_window_setting = new AnetAPI\SettingType;
		$duplicate_window_setting->setSettingName( 'duplicateWindow' );
		$duplicate_window_setting->setSettingValue( MUNIPAY_SANDBOX ? '6' : '60' );

		// Create a TransactionRequestType object and add the previous objects to it.
		$transaction_request_type = new AnetAPI\TransactionRequestType;
		$transaction_request_type->setTransactionType( 'authCaptureTransaction' );
		$transaction_request_type->setAmount( $current_order->get_total( 'raw' ) );
		$transaction_request_type->setOrder( $order );
		$transaction_request_type->setPayment( $this->credit_card );
		$transaction_request_type->setBillTo( $customer_address );
		$transaction_request_type->setCustomer( $customer_data );
		$transaction_request_type->addToTransactionSettings( $duplicate_window_setting );

		// Assemble the complete transaction request.
		$request = new AnetAPI\CreateTransactionRequest;
		$request->setMerchantAuthentication( $this->get_merchant() );
		$request->setRefId( 'ref-' . time() );
		$request->setTransactionRequest( $transaction_request_type );

		// Create the controller and get the response.
		$controller = new AnetController\CreateTransactionController( $request );

		return $controller->executeWithApiResponse( $this->get_endpoint() );
	}

	/**
	 * Create a merchantAuthenticationType object with authentication details.
	 *
	 * @return merchantAuthenticationType
	 */
	private function get_merchant() {
		$credentials = $this->get_credentials();
		$merchant    = new AnetAPI\MerchantAuthenticationType;

		$merchant->setName( $credentials['login_id'] );
		$merchant->setTransactionKey( $credentials['transaction_key'] );

		return $merchant;
	}

	/**
	 * Get service end-point.
	 *
	 * @return string
	 */
	private function get_endpoint() {
		return MUNIPAY_SANDBOX ? \net\authorize\api\constants\ANetEnvironment::SANDBOX : \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
	}

	/**
	 * Get credentials.
	 *
	 * @return array
	 */
	private function get_credentials() {
		$credentials = [
			'sandbox'    => [
				'login_id'        => '8KrGf4Z8KS',
				'transaction_key' => '6a67W3zJ883K8TmD',
			],
			'production' => [
				'login_id'        => '4QD5a2h32k',
				'transaction_key' => '38VqLH797yTp5Y6m',
			],
		];

		return MUNIPAY_SANDBOX ? $credentials['sandbox'] : $credentials['production'];
	}
}
