<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("Paytm_PHP_Checksum-master/PaytmChecksum.php");

// $paytmParams = array();

// $paytmParams["body"] = array(
//   "requestType"  => "Payment",
//   "mid"      => "SUVIDH73338318695696",
//   "websiteName"  => "-",
//   "orderId"    => "ORDERID_98765",
//   "callbackUrl"  => "https://merchant.com/callback",
//   "txnAmount"   => array(
//     "value"   => "1.00",
//     "currency" => "INR",
//   ),
//   "userInfo"   => array(
//     "custId"  => "CUST_001",
//   ),
// );

// /*
// * Generate checksum by parameters we have in body
// * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
// */
// $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "lq%zedTkZpz4XXab");

// $paytmParams["head"] = array(
//   "signature" => $checksum
// );
// // echo $checksum;
// $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

// /* for Staging */
// // $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

// /* for Production */
// $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
// $response = curl_exec($ch);
// print_r($response);

$paytmParams = array();

$paytmParams["subwalletGuid"]      = "271aa32e-b0d4-4b4d-a285-6f8cabb10f58";
$paytmParams["orderId"]            = "45135231";
$paytmParams["beneficiaryAccount"] = "259427666688";
$paytmParams["beneficiaryIFSC"]    = "INDB0000079";
$paytmParams["amount"]             = "1.00";
$paytmParams["purpose"]            = "SALARY_DISBURSEMENT";
$paytmParams["date"]               = "2020-10-20";

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature($post_data, "lq%zedTkZpz4XXab");

$x_mid      = "SUVIDH73338318695696";
$x_checksum = $checksum;

/* for Staging */
// $url = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

/* for Production */
$url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$response = curl_exec($ch);
print_r($response);
