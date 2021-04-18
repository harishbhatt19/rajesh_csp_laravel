<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

use Auth;
use App\User;
use App\Model\Api_operator_group_table;
use App\Model\B2b_app_all_recharge_transaction_history;
use App\Model\B2b_all_transaction_queue;
use App\Model\B2b_money_request;
use App\Model\B2b_wl_operator_16552380;
use App\Model\B2b_dmt_add_beneficiary;

class ApiManager
{
    public function transactionId() {

        $txn = rand(1000000000, 9999999999);

        $check_trans = B2b_app_all_recharge_transaction_history::where('transaction_id',$txn)->get();
        $check_trans_queue = B2b_all_transaction_queue::where('transaction_id',$txn)->get();
        $check_money_req = B2b_money_request::where('transaction_id',$txn)->get();

        if(count($check_trans) > 0 || count($check_money_req) > 0 || count($check_trans_queue) > 0)
        {
            return $this->transactionId();
        }

        return $txn;
    }
    public function findopcode($opid,$apiid) {
        
        $op = B2b_wl_operator_16552380::where('opid',$opid)->first();
        $colunm = 'opcode'.$apiid;
        return $op->$colunm;
    }
    
    public function apistatus($rowid,$apiid) {
        $api = Api_operator_group_table::where('id',$rowid)->first();
        return $api->status.$apiid;
    }
    
    //*********** Encryption Function *********************
    public function encrypt($plainText, $key) {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }
    
    //*********** Decryption Function *********************
    public function decrypt($encryptedText, $key) {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
    
    //*********** Padding Function *********************
    public function pkcs5_pad($plainText, $blockSize) {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }
    
    //********** Hexadecimal to Binary function for php 4.0 version ********
    public function hextobin($hexString) {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }
    
            $count += 2;
        }
        return $binString;
    }
    
    //********** To generate ramdom String ********
    public function generateRandomString($length = 35) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function generateBencode($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        $check = B2b_dmt_add_beneficiary::where('benCode',$randomString)->get();
        if(count($check) > 0)
        {
            return $this->generateBencode();
        }
        
        return $randomString;
    }
    
    public function hpytoPostApiCall($url) {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
        "Authorization: c69b7574-fbe8-4ed7-987d-13c5dee419dc",
        "Content-Type: application/json"),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}