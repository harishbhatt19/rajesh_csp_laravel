@extends('layouts.app_aeps')
@section('title')
   AEPS
@endsection
@section('content')
<?php
/**
 * Sample Code to encrypt header and body request in core PHP
 * 
 */
function encrypt_aeps($json_data, $Key, $salt = null) {
    $salt = $salt ? : openssl_random_pseudo_bytes(8);
    list($key, $iv) = evpkdf($Key, $salt);
    $ct = openssl_encrypt($json_data, 'aes-256-cbc', $key, true, $iv);
    return encode($ct, $salt);
}

function evpkdf($Key, $salt) {
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx . $Key . $salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv = substr($salted, 32, 16);
    return [$key, $iv];
}

function decrypt_aeps($base64, $Key) {
list($ct, $salt) = decode($base64);
if ($ct == "snderr") {
    return false;
}
list($key, $iv) = evpkdf($Key, $salt);
$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

return $data;
}

function decode($base64) {
$data = base64_decode($base64);
$ct = "snderr";
$salt = "snderr";

if (substr($data, 0, 8) !== "Salted__") {
    return [$ct, $salt];
}

$salt = substr($data, 8, 8);
$ct = substr($data, 16);

return [$ct, $salt];
}

function encode($ct, $salt) {
    return base64_encode("Salted__" . $salt . $ct);
}
date_default_timezone_set("Asia/Kolkata");

$header_data = array(
        "merchantId"=>"6184723",
        "Timestamp"=>date("Y-m-d H:i:s"),
        "merchantKey"=>"Hi069X6E3JjFInaQNXoDfOddDjZN4aQQzFvIUoydN4w="
    );
$headerSecretKey = "bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=";    
$header_json = json_encode($header_data);
$header = encrypt_aeps($header_json,$headerSecretKey);
// echo $header;
$body_data = array(
        "AgentId"=>"AR50091",
        "merchantService"=>"AEPS",
        "Version"=>"1.0",
        "Mobile"=>8140666688,
        "Email"=>"bizz@gmail.com"
    );
$body_json = json_encode($body_data);
// echo $body_json;
$contentSecretKey = "mbL62BGbApgQ5cyzp7xvAaBZAAe2oDwhJ1p3rcb284U="; 
$body = encrypt_aeps($body_json,$contentSecretKey);  



// $send_data = array(
//         "enc_header"=>$header,
//         "enc_parameters"=>$body
//     );
// // From URL to get redirected URL 
// $url = 'https://test-payout.payworldindia.com/aeps'; 
  
// // Initialize a CURL session. 
// $ch = curl_init(); 
  
// // Grab URL and pass it to the variable. 
// curl_setopt($ch, CURLOPT_URL, $url); 
  
// // Catch output (do NOT print!) 
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send_data));  
// // Return follow location true 
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
// $html = curl_exec($ch); 
  
// // Getinfo or redirected URL from effective URL 
// $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 
  
// // Close handle 
// curl_close($ch); 
// echo "Original URL:   " . $url . "<br/>"; 
// echo "Redirected URL: " . $redirectedUrl . "<br/>"; 
?>
<form action="https://test-payout.payworldindia.com/aeps" method="post" id="frm1">
  <input type="hidden" class="form-control" id="email" name="enc_header" value="<?php echo $header; ?>">
  <input type="hidden" class="form-control" id="email" name="enc_parameters" value="<?php echo $body; ?>">
  <!--<button type="submit" class="btn btn-default">AEPS</button>-->
</form>
<script>
$(document).ready(function(){
     $("#frm1").submit();
});
</script>
@endsection
@section('customjs')
@endsection