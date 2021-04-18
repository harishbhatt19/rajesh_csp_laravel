<?php

namespace App\Http\Controllers;

use App\Classes\ApiManager;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\User;
use App\Model\EmailHistory; 
use App\Model\Pond;
use App\Model\Category;
use App\Model\UserData;

use App\Model\B2b_api_holder;
use App\Model\B2b_app_all_recharge_transaction_history;
use App\Model\B2b_dmt_add_beneficiary;
use App\Model\B2b_money_request;

use App\Model\B2b_dmt_slab;
use App\Model\B2b_dmt_history;

class ApiController extends Controller
{
    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }
    
    public function postAddbeneficiaries(Request $request) {
        $mobile = $request->mobile;
        $acc_holder = $request->acc_holder;
        $acc = $request->acc;
        $ifsc = $request->ifsc;
        $user_id = $request->user_id;
        
        $check = User::where('id',$user_id)->first();
        if($check){
            
        }else{
            return response()->json(['status' => false,'msg' => 'User not found']);
        }
        
        $bencode = $this->apiManager->generateBencode();
        
        $check = B2b_dmt_add_beneficiary::where('b2b_user_id',$user_id)->where('ben_account',$acc)->get();
        
       $acc = new B2b_dmt_add_beneficiary();
            $acc->b2b_user_id = $user_id;
            $acc->benCode = $bencode;
            $acc->ben_account = $request->acc;
            $acc->benifsc = $request->ifsc;
            $acc->ben_name = $request->acc_holder;
            $acc->benMobile = $request->mobile;
            $acc->save();
            $sts = "true";
            $msg = "Beneficiary account added";
        
        
        
        // Session::flash($sts, $msg);
        // return redirect()->back();
        
        return response()->json(['status' => $sts,'msg' => $msg]);
    }
    
    public function getViewbeneficiaries(Request $request) { 
        $user_id = $request->user_id;
        
        $check = User::find($user_id);
        if(!$check){
            return response()->json(['status' => true,'msg' => 'User Not Found.']);
        }

        $query = B2b_dmt_add_beneficiary::query();
        
            
        
        $data = $query->where('b2b_user_id',$user_id)
                    ->get();
        
        $count = count($data);
        
        $sts = "true";
        $msg = "Beneficiary";
        // if($count == 1){
        //     // $a = array($data);
        //     return response()->json(['status' => $sts,'msg' => $msg,'data'=>$data]);
        // }
        
        
        return response()->json(['status' => $sts,'msg' => $msg,'data'=>$data]);
    }
    
    public function postMoneytransfer(Request $request) {
        $user_id = $request->user_id;
        $amount = $request->amount;
        $txntype = $request->txntype;
        $id = $request->benCode;
        
        $check = B2b_dmt_add_beneficiary::where('b2b_user_id',$user_id)->where('benCode',$id)->first();
        
        if($check){
            $b2b_user = User::where('status', '1')->where('id', $user_id)->first();
            if($b2b_user){
                $slab = B2b_dmt_slab::where('group_id', $b2b_user->group_name)->where('to_amount','<=', $amount)->where('from_amount','>=', $amount)->where('rtype', 'DMT')->first();
                if($slab){
                    
                }else{
                    $sts = "false";
                    $msg = "DMT Not Active";
                    return response()->json(['status' => $sts,'msg' => $msg]);
                }
                if($slab->commission_type == 'FLAT'){
                    $fee = $slab->commission;
                }else{
                    $fee = $slab->commission*$amount/100;
                }
                $totalamount = $amount + $fee;
                if($totalamount < $b2b_user->balance){
                    
                }else{
                    $sts = "false";
                    $msg = "User Low balance";
                    return response()->json(['status' => $sts,'msg' => $msg]);
                }
                $rid = $this->apiManager->transactionId();
                
                $url = "https://partners.hypto.in/api/transfers/initiate?amount=".$amount."&payment_type=".$txntype."&ifsc=".$check->benifsc."&number=".$check->ben_account."&note=SVK&beneficiary_name=".urlencode($check->ben_name)."&reference_number=".$rid;
                        
                $apicall = $this->apiManager->hpytoPostApiCall($url);
                $data = json_decode($apicall);
                if($data->success){
                    
                    
                    $cbalance = $b2b_user->balance;
                    $finalbalance = $cbalance - $amount - $fee;
                    $b2b_user->balance = $finalbalance;
                    $b2b_user->save();
                    if($data->data->status == 'PENDING'){
                        date_default_timezone_set('asia/kolkata');
                        $order = new B2b_app_all_recharge_transaction_history();
                        $order->b2b_user_id = $user_id;
                        $order->transaction_id = $rid;
                        $order->current_balance = $cbalance;
                        $order->sub_amount = $amount;
                        $order->sell_commission = $fee;
                        $order->buy_commission = $fee;
                        $order->opcode = 'DMT';
                        $order->commission = $fee;
                        $order->total_balance = $finalbalance;
                        $order->final_balance = $finalbalance;
                        $order->mobile = $check->benMobile;
                        $order->amount = $amount;
                        $order->date = date("Y-m-d");
                        $order->time = date("h:i:s");
                        $order->benCode =$id;
                        $order->status = 0;
                        $order->rtype = 'DMT';
                        $order->type = 'Debit';
                        $order->modetype = 'B2BAPP';
                        $order->apiname = '1';
                        $order->rech_user = 1;
                        $order->save();
                    }else{
                        date_default_timezone_set('asia/kolkata');
                        $order = new B2b_app_all_recharge_transaction_history();
                        $order->b2b_user_id = $user_id;
                        $order->transaction_id = $rid;
                        $order->current_balance = $cbalance;
                        $order->sub_amount = $amount;
                        $order->sell_commission = $amount;
                        $order->buy_commission = $amount;
                        $order->opcode = 'DMT';
                        $order->benCode =$id;
                        $order->commission = $amount;
                        $order->total_balance = $finalbalance;
                        $order->final_balance = $finalbalance;
                        $order->mobile = $check->benMobile;
                        $order->amount = $amount;
                        $order->date = date("Y-m-d");
                        $order->time = date("h:i:s");
                        $order->status = 1;
                        $order->rtype = 'DMT';
                        $order->type = 'Debit';
                        $order->modetype = 'B2BAPP';
                        $order->apiname = '1';
                        $order->rech_user = 1;
                        $order->save();
                    }
                    
                    
                    $sts = "true";
                    $msg = "Transection Procced";
                }else{
                    date_default_timezone_set('asia/kolkata');
                        $order = new B2b_app_all_recharge_transaction_history();
                        $order->b2b_user_id = $user_id;
                        $order->transaction_id = $rid;
                        $order->current_balance = 0;
                        $order->sub_amount = 0;
                        $order->sell_commission = 0;
                        $order->buy_commission = 0;
                        $order->opcode = 'DMT';
                        $order->benCode =$id;
                        $order->commission = 0;
                        $order->total_balance = 0;
                        $order->final_balance = 0;
                        $order->mobile = $check->benMobile;
                        $order->amount = 0;
                        $order->date = date("Y-m-d");
                        $order->time = date("h:i:s");
                        $order->status = 2;
                        $order->rtype = 'DMT';
                        $order->type = 'Debit';
                        $order->modetype = 'B2BAPP';
                        $order->apiname = '1';
                        $order->rech_user = 1;
                        $order->save();
                    $sts = "false";
                $msg = "Transection Faild";
                }
                
                
                return response()->json(['status' => $sts,'msg' => $msg]);
            
            }else{
                $sts = "false";
                $msg = "User Not Found";
                return response()->json(['status' => $sts,'msg' => $msg]);
            }
        }else{
             $sts = "false";
            $msg = "Something Wrong!! Transaction Not procced...";
           return response()->json(['status' => $sts,'msg' => $msg]);
        }
        
    }
    
    public function getReportdmt(Request $request) { 
        $user_id = $request->user_id;
        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','b2b_dmt_add_beneficiaries.benMobile','b2b_dmt_add_beneficiaries.ben_name','b2b_dmt_add_beneficiaries.ben_account','b2b_dmt_add_beneficiaries.benifsc')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('b2b_dmt_add_beneficiaries','b2b_dmt_add_beneficiaries.benCode','b2b_app_all_recharge_transaction_histories.bencode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',$user_id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DMT')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        
        $sts = "true";
            $msg = "report";
           return response()->json(['status' => $sts,'msg' => $msg,'data'=>$data]);
    }
    
    public function getbbpsuser(Request $request)
    {
        $plainText = '<?xml version="1.0" encoding="UTF-8"?><billerInfoRequest><billerId>NA7420055XSZ41</billerId></billerInfoRequest>';
        $key = "B2092B6AD934D5C2E07AD9D6068A9645";
        $encrypt_xml_data = $this->apiManager->encrypt($plainText, $key);
        
        $data['accessCode'] = "AVYY09TN56SS23SIWJ";
        $data['requestId'] = $this->apiManager->generateRandomString();
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = "EM02";
        
        $parameters = http_build_query($data);
        
        $url = "https://stgapi.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        
        $response = $this->apiManager->decrypt($result, $key);
        return $response;
        return response()->json(['success' => true,'message' => $response]);
    }
    
    public function getbbpsbill(Request $request)
    {
        $plainText = '<?xml version="1.0" encoding="UTF-8"?>
<billFetchRequest>
   <agentId>CC01CC01513515340681</agentId>
   <agentDeviceInfo>
      <ip>192.168.2.73</ip>
      <initChannel>AGT</initChannel>
      <mac>01-23-45-67-89-ab</mac>
   </agentDeviceInfo>
   <customerInfo>
      <customerMobile>9898990084</customerMobile>
      <customerEmail></customerEmail>
      <customerAdhaar></customerAdhaar>
      <customerPan></customerPan>
   </customerInfo>
   <billerId>OTME00005XXZ43</billerId>
   <inputParams>
      <input>
         <paramName>a</paramName>
         <paramValue>10</paramValue>
      </input>
      <input>
         <paramName>a b</paramName>
         <paramValue>20</paramValue>
      </input>
      <input>
         <paramName>a b c</paramName>
         <paramValue>30</paramValue>
      </input>
      <input>
         <paramName>a b c d</paramName>
         <paramValue>40</paramValue>
      </input>
      <input>
         <paramName>a b c d e</paramName>
         <paramValue>50</paramValue>
      </input>
   </inputParams>
</billFetchRequest>';
        $key = "B2092B6AD934D5C2E07AD9D6068A9645";
        $encrypt_xml_data = $this->apiManager->encrypt($plainText, $key);
        
        $data['accessCode'] = "AVYY09TN56SS23SIWJ";
        $data['requestId'] = $this->apiManager->generateRandomString();
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = "EM02";
        
        $parameters = http_build_query($data);
        
        $url = "https://stgapi.billavenue.com/billpay/extBillCntrl/billFetchRequest/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        
        $response = $this->apiManager->decrypt($result, $key);
        return $response;
        return response()->json(['success' => true,'message' => $response]);
    }
    
    public function getbbpsbillpay(Request $request)
    {
        $plainText = '<?xml version="1.0" encoding="UTF-8"?>
<billPaymentRequest>
    <agentId>CC01CC01513515340681</agentId>
    <billerAdhoc>true</billerAdhoc>
    <agentDeviceInfo>
        <ip>192.168.2.73</ip>
        <initChannel>AGT</initChannel>
        <mac>01-23-45-67-89-ab</mac>
    </agentDeviceInfo>
    <customerInfo>
        <customerMobile>9898990083</customerMobile>
        <customerEmail></customerEmail>
        <customerAdhaar></customerAdhaar>
        <customerPan></customerPan>
    </customerInfo>
    <billerId>OTNS00005XXZ43</billerId>
   <inputParams>
      <input>
         <paramName>a</paramName>
         <paramValue>10</paramValue>
      </input>
      <input>
         <paramName>a b</paramName>
         <paramValue>20</paramValue>
      </input>
      <input>
         <paramName>a b c</paramName>
         <paramValue>30</paramValue>
      </input>
      <input>
         <paramName>a b c d</paramName>
         <paramValue>40</paramValue>
      </input>
      <input>
         <paramName>a b c d e</paramName>
         <paramValue>50</paramValue>
      </input>
   </inputParams>
   <amountInfo>
       <amount>100000</amount>
       <currency>356</currency>
       <custConvFee>0</custConvFee>
       <amountTags></amountTags>
   </amountInfo>
   <paymentMethod>
       <paymentMode>Cash</paymentMode>
       <quickPay>Y</quickPay>
       <splitPay>N</splitPay>
   </paymentMethod>
   <paymentInfo>
       <info>
           <infoName>Remarks</infoName>
           <infoValue>Received</infoValue>
       </info>
   </paymentInfo>
</billPaymentRequest>
';
        $key = "B2092B6AD934D5C2E07AD9D6068A9645";
        $encrypt_xml_data = $this->apiManager->encrypt($plainText, $key);
        
        $data['accessCode'] = "AVYY09TN56SS23SIWJ";
        $data['requestId'] = $this->apiManager->generateRandomString();
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = "EM02";
        
        $parameters = http_build_query($data);
        
        $url = "https://stgapi.billavenue.com/billpay/extBillPayCntrl/billPayRequest/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        
        $response = $this->apiManager->decrypt($result, $key);
        return $response;
        return response()->json(['success' => true,'message' => $response]);
    }
    
    public function employeeLogin(Request $request)
    {
        $user = User::where('mobile', $request->get('mobile'))->where('user_type',3)->limit(1)->first();

        if (!$user) {
            return response()->json(['success' => false,'message' => 'User not found..!']);
        }
            
        if (Auth::attempt(array('mobile' => $request->mobile, 'password' => $request->password)))
        {
            $user = User::where('mobile', $request->get('mobile'))->limit(1)->first();
            
            if ($user->status != 1) 
            {
                return response()->json([ 'success' => false,  'message' => 'Your account not activated!']);
            }

            return response()->json([ 'success' => true, 'message' => 'Login successfully!', 'data' => $user ]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'Invalid username or password..!']);
        }
    }
    
    public function getCategories(Request $request)
    {
        $data = Category::select('id','category_name')->where('status',1)->get();
        return response()->json(['success' => true, 'message' => 'Categories', 'data' => $data]);
    }
    
    public function getPonds(Request $request)
    {
        if($request->get('category_id')) {
            $data = Pond::select('id','pond_name')->where('category_id',$request->category_id)->where('status',1)->get();
            return response()->json(['success' => true, 'message' => 'Ponds', 'data' => $data]);
        }else{
            return response()->json(['success' => false, 'message' => 'Please enter category']);
        }
    }
    public function getDaterangeSalinity(Request $request)
    {
        $dt1 = $request->dt1;
        $dt2 = $request->dt2;
        $from = date('Y-m-d', strtotime($dt1));
        $to = date('Y-m-d', strtotime($dt2));
        $pond = $request->pond;
        $cat = $request->cat;
        $data = UserData::select('salinity','date_of_measurement as date','time')
                    ->whereBetween('user_data.date_of_measurement', array($from, $to))
                    ->where('category_id',$cat)
                    ->where('pond_id',$pond)
                    ->get();
        
        return response()->json(['success' => true, 'message' => 'Ponds', 'data' => $data]);            
                    
    }
    
    public function addUserData(Request $request)
    {
        $data = new UserData();
        $data->user_id = $request->user_id;
        $data->pond_id = $request->pond_id;
        $data->category_id = $request->category_id;
        $data->salinity = $request->salinity;
        $data->waterlevel = $request->waterlevel;
        $data->date_of_measurement = $request->date_of_measurement;
        $data->time = $request->time;
        $data->hour = $request->hour;
        $data->minute = $request->minute;
        $data->lat = $request->lat;
        $data->lng = $request->lng;
        $data->save();
        
        if($data) {
            return response()->json(['success' => true, 'message' => 'User data added']);
        }
        else{
            return response()->json(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
    
    public function getUserData(Request $request)
    {
        if($request->get('user_id')) {
            $data = UserData::select('user_data.*','categories.category_name','ponds.pond_name')
            ->join('ponds','ponds.id','user_data.pond_id')
            ->join('categories','categories.id','ponds.category_id')
            ->where('user_id',$request->user_id)
            ->orderBy('user_data.id','desc')
            ->get();
            return response()->json(['success' => true, 'message' => 'Userdata', 'data' => $data]);
        }else{
            return response()->json(['success' => false, 'message' => 'Please user id']);
        }
    }
    
    public function getDaterangeUserData(Request $request)
    {
        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    
        if($request->get('user_id')) {
            $data = UserData::select('user_data.*','categories.category_name','ponds.pond_name')
            ->join('ponds','ponds.id','user_data.pond_id')
            ->join('categories','categories.id','ponds.category_id')
            ->where('user_id',$request->user_id)
            ->whereBetween('user_data.created_at', array($from, $to))
            ->orderBy('user_data.id','desc')
            ->get();
            return response()->json(['success' => true, 'message' => 'Userdata', 'data' => $data]);
        }else{
            return response()->json(['success' => false, 'message' => 'Please user id']);
        }
    }

    
    public function postSendEmail(Request $request)
    {
        $emails = explode(',',$request->emails);
        $matchto = "@tatachemicals.com";
        $data = [];
        
        $from="no-reply@bizz.website";
        $to="bizz.website@gmail.com";
        
        if ($request->hasFile('excel_file'))
        {
            $name        = $_FILES['excel_file']['name'];
            $get_name = explode('.',$name);
            $file = $request->file('excel_file');
            $filename =  $name;//$get_name[0].$file->getClientOriginalExtension();//'TATA_'.date("Y_m_d_h_i_s").'.'. $file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/excels/');
            $file->move($destinationPath, $filename);
            
            $tmp_name    = $_FILES['excel_file']['tmp_name'];
            
            $size        = $_FILES['excel_file']['size'];
            $type        = $_FILES['excel_file']['type'];
            $error       = $_FILES['excel_file']['error'];
        }
        
        $fileurl = env('APP_URL').'uploads/excels/'.$filename;
    
        foreach($emails as $email) 
        {   
            $email = trim($email);
            
            if(strpos($email, $matchto)) {
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($data, $email);
                    
                    $save = new EmailHistory();
                    $save->email = $email;
                    $save->filename = $filename;
                    $save->save();
                
                    //emailcode
                    
                    $from_email         = $from; 
                    $recipient_email    = $email; 
                    
                    $sender_name    = "TATA";
                    $reply_to_email = $from;  
                    $subject        = $filename; 
                    $message        = "TATA DATA"; 
                    
                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    
                    // $link = urlencode($fileurl);
                    // $namm = "TATACHM".rand(11111,99999);
                    // $json = file_get_contents("https://cutt.ly/api/api.php?key=a07cbeef1b4dc1ce0f8e39f0ec14d55c59715&short=$link&name=$namm");
                    // $dd = json_decode ($json, true);
                    // print_r($dd);exit;
                    // $emailink = $dd["url"]["shortLink"];
                    // More headers
                    $headers .= 'From: <'.$from_email.'>' . "\r\n";
                    $body = "<b>TCL Salt-Pan Report</b> <br><a href='$fileurl' download> Download $filename</a>";
                    
                    // $handle = fopen($fileurl, "r"); 
                    // $content = fread($handle, $size);
                    // fclose($handle);
                    
                    // $encoded_content = chunk_split(base64_encode($content)); //file_get_contents($fileurl)
                    // $boundary = md5(uniqid(time()));
                    
                    // $headers = "From:<".$from_email.">\r\n";
                    // $headers .= "Reply-To: ".$reply_to_email."\r\n"; 
                    // $headers .= "MIME-Version: 1.0\r\n"; 
                    
                    // $headers .= "Content-Type: multipart/mixed;\r\n";
                    // $headers .= "This is a multi-part message in MIME format.\r\n";
                    // $headers .= "--".$boundary."\r\n";//"boundary = $boundary\r\n"; 
                    
                    
                    // //plain text  
                    // // $body = "--$boundary\r\n"; 
                    // $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n"; 
                    // $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";  
                    // $headers .= chunk_split(base64_encode($message));  
                    
                    // //attachment 
                    // $headers .= "--$boundary\r\n"; 
                    // $headers .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
                    // // $body .="Content-Type: $type; name=".$filename."\r\n"; 
                    
                    // $headers .="Content-Transfer-Encoding: base64\r\n"; 
                    // $headers .="Content-Disposition: attachment; filename=".$filename."\r\n";
                    // // $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";  
                    // $headers .= $encoded_content."\r\n\r\n"; // Attaching the encoded file with email 
                    // $headers .= "--$boundary--"; 
                    
                    
                    
                    
                    $sentMailResult = mail($recipient_email, $subject, $body, $headers); 
                    
                    // if($sentMailResult )  
                    // { 
                    // echo "File Sent Successfully."; 
                    // unlink($name); // delete the file after attachment sent. 
                    // } 
                    // else
                    // { 
                    // die("Sorry but the email could not be sent. 
                    // Please go back and try again!"); 
                    // } 
                                 
                    
                    
            
                }
            } 
        }
        return response()->json(['success' => true, 'message' => 'Email sent','data' => $data]);
    }
    
    public function postDeletebeneficiaries(Request $request)
    {
        $delete = B2b_dmt_add_beneficiary::where("benCode", $request->bencode)->where("b2b_user_id", $request->user_id)->delete();

        if($delete){
            return response()->json(['success' => true, 'message' => 'Record Deleted','data' => '']);
            // return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return response()->json(['success' => true, 'message' => 'Record not found.','data' => '']);
            // return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }

}