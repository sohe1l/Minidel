<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Omnipay\Omnipay;

class PaypalController extends Controller
{
    
    public function paymentTest(){



      $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('hello_api1.minidel.com');
        $gateway->setPassword('HS84QTJ68XEAHBQK');
        $gateway->setSignature('Ai1PaghZh5FmBLCDCTQpwG8jB264A.0Eo9.c2EXbj1UG4AuVmjR6IWsO');
        $gateway->setTestMode(false);


$formData = ['number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2016', 'cvv' => '123'];


$params = array(
    'cancelUrl' => 'http://c.io/pages/',
    'returnUrl' => 'http://c.io/pages/2',
    'name'  => 'Your Purchase',
    'description' => 'Your Description',
    'amount' => '15.99',
    'currency' => 'USD',
    'card' => $formData
);



$response = $gateway->purchase($params)->send();

if ($response->isSuccessful()) {
    // payment was successful: update database
    print_r($response);
} elseif ($response->isRedirect()) {
    // redirect to offsite payment gateway
    return $response->redirect();

} else {
    // payment failed: display message to customer
    dd( $response->getMessage());
    dd("ekse");
}




dd("end");
/*


$gateway = Omnipay::create('PayPal_Rest');

// Initialise the gateway
$gateway->initialize(array(
    'clientId' => 'MyPayPalClientId',
    'secret'   => 'MyPayPalSecret',
    'testMode' => true, // Or false when you are ready for live transactions
));

// Create a credit card object
// DO NOT USE THESE CARD VALUES -- substitute your own
// see the documentation in the class header.
$card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth'           => '01',
            'expiryYear'            => '2020',
            'cvv'                   => '123',
            'billingAddress1'       => '1 Scrubby Creek Road',
            'billingCountry'        => 'AU',
            'billingCity'           => 'Scrubby Creek',
            'billingPostcode'       => '4999',
            'billingState'          => 'QLD',
));

// Do a purchase transaction on the gateway
$transaction = $gateway->purchase(array(
    'amount'        => '10.00',
    'currency'      => 'AUD',
    'description'   => 'This is a test purchase transaction.',
    'card'          => $card,
));
$response = $transaction->send();
if ($response->isSuccessful()) {
    echo "Purchase transaction was successful!\n";
    $sale_id = $response->getTransactionReference();
    echo "Transaction reference = " . $sale_id . "\n";
}

*/




$gateway = Omnipay::create('PayPal_Rest');
//$gateway->setApiKey('abc123');

$gateway->setClientId('hello_api1.minidel.com');
$gateway->setSecret('HS84QTJ68XEAHBQK');
$gateway->setToken('Ai1PaghZh5FmBLCDCTQpwG8jB264A');
//$gateway->setTestMode(false);

$formData = ['number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2016', 'cvv' => '123'];


$params = array(
    'cancelUrl' => 'http://c.io/pages/',
    'returnUrl' => 'http://c.io/pages/2',
    'name'  => 'Your Purchase',
    'description' => 'Your Description',
    'amount' => '15.99',
    'currency' => 'AED',
    'card' => $formData
);



$response = $gateway->purchase($params)->send();

dd($response);

if ($response->isSuccessful()) {
    // payment was successful: update database
    print_r($response);
} elseif ($response->isRedirect()) {
    // redirect to offsite payment gateway
    return $response->redirect();

} else {
    // payment failed: display message to customer
    dd( $response->getMessage());
    dd("ekse");
}



dd("end");

/*



        

        $response = Omnipay::purchase([
            'amount'    => '100.00',
            'returnUrl' => 'http://bobjones.com/payment/return',
            'cancelUrl' => 'http://bobjones.com/payment/cancel',
            'card'      => $cardInput
        ])->send();

        dd($response->getMessage());

*/

    }



}
