<?php
/**
 * Plugin Name:       RSShooing Sports Ammo Club
 * Description:       Library to Create automatic recurring charges via Authorize.net
 * Author:            Nick Stone
 * Version:           1.0.6
 */
require_once "autoload.php";



use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
date_default_timezone_set('America/Los_Angeles');

define("AUTHORIZENET_LOG_FILE", "phplogs");

function createSubscriptionAmmo($numbers, $exp,$cvv, $Fname, $Lname, $addy, $city, $state,$zip, $country, $email, $phone)
{
  /* Create a merchantAuthenticationType object with authentication details
     retrieved from the constants file */
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName("YOURKEY");
  $merchantAuthentication->setTransactionKey("YOURKEY");
  
  // Set the transaction's refId
  $refId = 'ref' . time();

  // Subscription Type Info
  $subscription = new AnetAPI\ARBSubscriptionType();
  $subscription->setName("Ammo Subscription");

  $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
  $interval->setLength(30);
  $interval->setUnit("days");

  $paymentSchedule = new AnetAPI\PaymentScheduleType();
  $paymentSchedule->setInterval($interval);
  $currentDateTime = new DateTime('now');
  $currentDate = $currentDateTime->format('Y-m-d');
  echo $currentDate;
  $paymentSchedule->setStartDate(new DateTime($currentDate));
  $paymentSchedule->setTotalOccurrences("12");
  $paymentSchedule->setTrialOccurrences("1");

  $subscription->setPaymentSchedule($paymentSchedule);
  $subscription->setAmount("35.00");
  $subscription->setTrialAmount("35.00");
  
  $creditCard = new AnetAPI\CreditCardType();
  $creditCard->setCardNumber($numbers);
  $creditCard->setExpirationDate($exp);
  $creditCard->setcardCode($cvv); 

  $payment = new AnetAPI\PaymentType();
  $payment->setCreditCard($creditCard);
  $subscription->setPayment($payment);


  $order = new AnetAPI\OrderType();
  $order->setInvoiceNumber(bin2hex(openssl_random_pseudo_bytes(9)));        
  $order->setDescription("RS Shooting Sports Ammo Club"); 
  $subscription->setOrder($order); 
  
  $billTo = new AnetAPI\NameAndAddressType();
  $billTo->setFirstName($Fname);
  $billTo->setLastName($Lname);
  $billTo->setAddress($addy); 
  $billTo->setState($state);
  $billTo->setZip($zip);
  $billTo->setCountry($country);

  $customerData = new AnetAPI\CustomerType();
  $customerData->setType("individual");
  $customerData->setEmail($email);
  //$customerData->setPhoneNumber($phone);
  


  $subscription->setBillTo($billTo);
  $subscription->setCustomer($customerData);

  $request = new AnetAPI\ARBCreateSubscriptionRequest();
  $request->setmerchantAuthentication($merchantAuthentication);
  $request->setRefId($refId);
  $request->setSubscription($subscription);
  $controller = new AnetController\ARBCreateSubscriptionController($request);

  $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
  
  if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
  {
      echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
      return true; 
   }
  else
  {
      echo "ERROR :  Invalid response\n";
      $errorMessages = $response->getMessages()->getMessage();
      echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
      return false; 
   }

  return false;
}

function createSubscription($numbers, $exp,$cvv, $Fname, $Lname, $addy, $city, $state,$zip, $country, $email, $phone)
{
  /* Create a merchantAuthenticationType object with authentication details
     retrieved from the constants file */
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName("YOURNAME");
  $merchantAuthentication->setTransactionKey("YOURKEY");
  
  // Set the transaction's refId
  $refId = 'ref' . time();

  // Subscription Type Info
  $subscription = new AnetAPI\ARBSubscriptionType();
  $subscription->setName("Simulation Subscription");

  $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
  $interval->setLength(30);
  $interval->setUnit("days");

  $paymentSchedule = new AnetAPI\PaymentScheduleType();
  $paymentSchedule->setInterval($interval);
  $currentDateTime = new DateTime('now');
  $currentDate = $currentDateTime->format('Y-m-d');
  echo $currentDate;
  $paymentSchedule->setStartDate(new DateTime($currentDate));
  $paymentSchedule->setTotalOccurrences("12");
  $paymentSchedule->setTrialOccurrences("1");

  $subscription->setPaymentSchedule($paymentSchedule);
  $subscription->setAmount("35.00");
  $subscription->setTrialAmount("0.00");
  
  $creditCard = new AnetAPI\CreditCardType();
  $creditCard->setCardNumber($numbers);
  $creditCard->setExpirationDate($exp);
  $creditCard->setcardCode($cvv); 

  $payment = new AnetAPI\PaymentType();
  $payment->setCreditCard($creditCard);
  $subscription->setPayment($payment);


  $order = new AnetAPI\OrderType();
  $order->setInvoiceNumber(bin2hex(openssl_random_pseudo_bytes(9)));        
  $order->setDescription("RS Shooting Sports Simulation Club"); 
  $subscription->setOrder($order); 
  
  $billTo = new AnetAPI\NameAndAddressType();
  $billTo->setFirstName($Fname);
  $billTo->setLastName($Lname);
  $billTo->setAddress($addy); 
  $billTo->setState($state);
  $billTo->setZip($zip);
  $billTo->setCountry($country);

  $customerData = new AnetAPI\CustomerType();
  $customerData->setType("individual");
  $customerData->setEmail($email);
  //$customerData->setPhoneNumber($phone);
  


  $subscription->setBillTo($billTo);
  $subscription->setCustomer($customerData);

  $request = new AnetAPI\ARBCreateSubscriptionRequest();
  $request->setmerchantAuthentication($merchantAuthentication);
  $request->setRefId($refId);
  $request->setSubscription($subscription);
  $controller = new AnetController\ARBCreateSubscriptionController($request);

  $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
  
  if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
  {
      echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
      return true; 
   }
  else
  {
      echo "ERROR :  Invalid response\n";
      $errorMessages = $response->getMessages()->getMessage();
      echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
      return false; 
   }

  return false;
}




?>