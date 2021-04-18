<?php

namespace App\Http\Controllers;
use App\Classes\ApiManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Excel;

use Auth;
use DataTables;
use App\User;
use App\Model\Api_operator;
use App\Model\Api_operator_group;
use App\Model\Api_operator_group_table;
use App\Model\B2b_api_holder;
use App\Model\B2b_app_all_recharge_transaction_history;
use App\Model\B2b_dmt_add_beneficiary;
use App\Model\B2b_money_request;

use App\Model\B2b_dmt_slab;
use App\Model\B2b_dmt_history;

use App\Http\Controllers\PaytmChecksum;


class ServicesController extends Controller
{
    public function __construct(ApiManager $apiManager)
    {
        $this->middleware('auth');
        $this->apiManager = $apiManager;
    }
    
    public function recharge() {
        $op = Api_operator::where('service_id','1')->orWhere('service_id','4')->get();
        return view('pages.services.recharge',compact('op'));
    }
    
    public function postRecharge(Request $request) {
        $mobile = $request->mobile;
        $amount = $request->amount;
        $opcode = $request->opcode;
        $rtype = $request->rtype;
        
        $rid = $this->apiManager->transactionId();
        
        $string = ['user_id'=>Auth::User()->id,'amount'=>$amount,'mobile'=>$mobile,'opcode'=>$opcode,'order_id'=>$rid,'paymentmode'=>'CASH','rtype'=>$rtype,'modetype'=>'WEB','admin_id'=>Auth::User()->admin_id];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bcashRecharge");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        $op = json_decode($server_output);
        $mm = $op->status;
        
        if($mm){
            Session::flash('success', $op->message);
        }else{
            Session::flash('error', $op->message);
        }
        return redirect()->back();
    }
    
    public function dthrecharge() {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.services.dthrecharge',compact('op'));
    }
    
    public function getmypassbook() {
        
        return view('pages.report.mypassbook');
    }
    
    public function getuserpassbook($user_id) {
        
        return view('pages.report.userpassbook',compact('user_id'));
    }
    
    public function getuserpassbookDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
        $user = $request->user_id;

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 && Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->leftJoin('b2b_users','b2b_app_all_recharge_transaction_histories.b2b_user_id','b2b_users.id')
            ->leftJoin('api_operator','b2b_app_all_recharge_transaction_histories.opcode','api_operator.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',$user)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 4){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_users.admin_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 2){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_users.uplevel_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function reportRecharge() {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.report.reportrecharge',compact('op'));
    }
    
    public function getReportRechargeDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','MobileRecharge')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id','!=','57')
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 2){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','MobileRecharge')
            ->where('b2b_users.uplevel_id',Auth::User()->id)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','MobileRecharge')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function userreportRecharge($user_id) {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.report.user_reportrecharge',compact('op','user_id'));
    }
    
    public function getuserReportRechargeDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
        $user = $request->user_id;

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','MobileRecharge')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',$user)
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','MobileRecharge')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function reportDthRecharge() {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.report.reportdthrecharge',compact('op'));
    }
    
    public function getReportDthRechargeDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id','!=','57')
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 2){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->where('b2b_users.uplevel_id',Auth::User()->id)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function userreportDthRecharge($user_id) {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.report.user_reportdthrecharge',compact('op','user_id'));
    }
    
    public function getuserReportDthRechargeDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
        $user = $request->user_id;

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',$user)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DTH')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function reportAddmoney() {
        
        return view('pages.report.addmoney');
    }
    
    public function getReportAddmoneyDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_money_request::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name')
            ->join('b2b_app_all_recharge_transaction_histories','b2b_app_all_recharge_transaction_histories.transaction_id','b2b_money_requests.transaction_id')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name')
            ->join('b2b_app_all_recharge_transaction_histories','b2b_app_all_recharge_transaction_histories.transaction_id','b2b_money_requests.transaction_id')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function addMoney() {
        
        return view('pages.addmoney.addmoney');
    }
    
    public function paytmAddMoneyres(Request $request) {
        require_once("paytm/lib/config_paytm.php");
        require_once("paytm/lib/encdec_paytm.php");
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $data = new B2b_api_holder();
        $data->b2b_user_id = 0;
        $data->name = json_encode($paramList);
        $data->status = 0;
        $data->date = date('Y-m-d');
        $data->time = date('h:i:s');
        $data->save();
        
        if($paramList['STATUS'] == 'Success'){
        $admin_id = '57';
        $user_id = Auth::User()->id;
        $amount = $paramList['TXNAMOUNT'];
        $order_id = $this->apiManager->transactionId();
        
        $string = ['user_id'=>$user_id,
                    'order_id'=>$order_id,
                    'bank_ref'=>$paramList['TXNID'],
                    'details'=>'Paytm Fund Transfer BANKTXNID:'.$paramList['BANKTXNID'].', ORDTXN:'.$paramList['ORDERID'],
                    'admin_id'=>$admin_id,
                    'name'=>'Paytm',
                    'transfer_type'=>'Direct',
                    'amount'=>$amount,
                    'enter_date'=>date("Y-m-d"),
                    'wtype'=>0,
                    'uplevel_id'=>$admin_id,
                    ];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bmoneyrequest");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        $data = json_decode($server_output);
        
        if($data->status){
            $string1 = ['user_id'=>$user_id,
                    'rid'=>$data->rid];
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bmoneyrequestaccept");
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $string1);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            $server_output1 = curl_exec ($ch1);
            curl_close ($ch1);
            // print_r($server_output1);exit;
            $data1 = json_decode($server_output1);
            
            $msg_ntf = "Fund Added";
            $sts_ntf = "success";
        }else{
            $msg_ntf = "Fund Not Add";
            $sts_ntf = "error";
        }
        }else{
            $msg_ntf = $paramList['RESPMSG'];
            $sts_ntf = "error";
        }
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect("/");
    }
    
    public function paytmAddMoney() {
        
        return view('pages.addmoney.paytmaddmoney');
    }
    
    public function postpaytmAddMoney(Request $request) {
        require_once("paytm/lib/config_paytm.php");
        require_once("paytm/lib/encdec_paytm.php");
        $amount = $request->amount;
        
        $checkSum = "";
        $paramList = array();
        
        $ORDER_ID = $this->apiManager->transactionId();
        $CUST_ID = Auth::User()->id;
        $INDUSTRY_TYPE_ID = "-";
        $CHANNEL_ID = "WEB";
        $TXN_AMOUNT = $amount;
        
        $checkSum = "";
        $paramList = array();
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["CALLBACK_URL"] = "https://csp.suvidhakendra.com/paytm-add-money-res";
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        
        $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
        
        return view('pages.addmoney.paytmaddmoneyred',compact('paramList','checkSum'));
        
    }
    public function postAddMoney(Request $request) {
        $bank = $request->bank;
        $type = $request->type;
        $date = $request->date;
        $bank_txn = $request->bank_txn;
        $amount = $request->amount;
        $note = $request->note;
        $rid = $this->apiManager->transactionId();
        
        $string = ['user_id'=>Auth::User()->id,
                    'amount'=>$amount,
                    'order_id'=>$rid,
                    'name'=>$bank,
                    'transfer_type'=>$type,
                    'bank_ref'=>$bank_txn,
                    'enter_date'=>$date,
                    'details'=>$note,
                    'wtype'=>0,
                    'admin_id'=>Auth::User()->admin_id,
                    'uplevel_id'=>Auth::User()->uplevel_id];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bmoneyrequest");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        $op = json_decode($server_output);
        $mm = $op->status;
        if($mm){
            Session::flash('success', $op->meassage);
        }else{
            Session::flash('error', $op->meassage);
        }
        return redirect()->back();
    }
    
    public function pendingMoney() {
        
        return view('pages.addmoney.pendingmoney');
    }
    
    public function approveMoney($id) {
        
        
        $query = B2b_money_request::where('transaction_id',$id)->where('uplevel_id',Auth::User()->id)->where('status',0)->first();
        if($query){
            
            $string1 = ['user_id'=>$query->user_id,
                    'rid'=>$query->id];
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bmoneyrequestaccept");
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $string1);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            $server_output1 = curl_exec ($ch1);
            curl_close ($ch1);
            // print_r($server_output1);exit;
            $data1 = json_decode($server_output1);
            
            $sts_ntf ="success";
            $msg_ntf = "Approve";
            
        }else{
            $sts_ntf ="error";
            $msg_ntf = "Data not found";
        }
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function declineMoney($id) {
        $query = B2b_money_request::where('id',$id)->where('uplevel_id',Auth::User()->id)->where('status',0)->first();
        if($query){
            $money = B2b_money_request::find($id);
            $money->status = 2;
            $money->save();
            
            $sts_ntf ="success";
            $msg_ntf = "Decline";
        }else{
            $sts_ntf ="error";
            $msg_ntf = "Data not found";
        }
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function pendingMoneyDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_money_request::query();
        
            $data = $query->select('b2b_money_requests.*','b2b_users.firstname as fname','b2b_users.mob_no','b2b_users.username')
            ->join('b2b_users','b2b_users.id','b2b_money_requests.user_id')
            ->whereBetween('b2b_money_requests.created_at', array($from, $to))
            ->where('b2b_money_requests.uplevel_id',Auth::User()->id)
            ->where('b2b_money_requests.status','0')
            ->orderBy('b2b_money_requests.id','desc')
            ->get();
        
        
        return DataTables::of($data)->make(true);
    }
    
    public function aeps() {
        return view('pages.services.aeps');
    }
    public function aepsframe() {
        return view('pages.services.aepsframe');
    }
    
    public function reportpending() {
        
        return view('pages.report.reportpending');
    }
    
    public function getReportpendingDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH')->orWhere('rtype','Electricity');
                        })
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->where(function($query){
                            $query->where('b2b_app_all_recharge_transaction_histories.status','1')->orWhere('b2b_app_all_recharge_transaction_histories.status','0');
                        })
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = "";
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function changestatussuccsess($tid) {
        $query = B2b_app_all_recharge_transaction_history::query();
        $data = $query->where('transaction_id',$tid)
                    ->where(function($query){
                            $query->where('status','1')->orWhere('status','0');
                        })
                    ->first();
        
        if($data){
            
            $url = "https://api.".env('API_URL')."/api/ManuallyChangestatus?transtype=SUCCESS&rid=".$data->transaction_id."&txid=0&apiname=".$data->apiname."&rpid=0";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $Get_Response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($Get_Response);
            
            if($data->STATUS){
                $sts_ntf = "success";
                $msg_ntf = "Status Changes.";
            }else{
                $sts_ntf = "error";
                $msg_ntf = "Something Wrong";
            }
            
            
        }else{
            
            $sts_ntf = "error";
            $msg_ntf = "Transection not find";
        }
        
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function changestatusfail($tid) {
        $query = B2b_app_all_recharge_transaction_history::query();
        $data = $query->where('transaction_id',$tid)
                    ->where(function($query){
                            $query->where('status','1')->orWhere('status','0');
                        })
                    ->first();
        // print_r($data);exit;
        if($data){
            
            $url = "https://api.".env('API_URL')."/api/ManuallyChangestatus?transtype=FAILED&rid=".$data->transaction_id."&txid=0&apiname=".$data->apiname."&rpid=0";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $Get_Response = curl_exec($ch);
            curl_close($ch);
            // print_r($Get_Response);exit;
            $data = json_decode($Get_Response);
            
            if($data->STATUS){
                $sts_ntf = "success";
                $msg_ntf = "Status Changes.";
            }else{
                $sts_ntf = "error";
                $msg_ntf = "Something Wrong";
            }
            
            
        }else{
            
            $sts_ntf = "error";
            $msg_ntf = "Transection not find";
        }
        
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function reportpoint() {
        
        return view('pages.report.reportpoint');
    }
    public function getReportpointDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.point','!=','0')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = '';
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function getmycommission() {
        $comm = Api_operator_group_table::select('api_operator.OperatorDescritpion as opname', 'api_operator.RechargeType as rtypeop', 'api_operator_group_tables.*')
                ->join('api_operator','api_operator.opid','api_operator_group_tables.opid')
                ->where('api_operator_group_tables.group_id',Auth::User()->group_name)
                ->orderBy('api_operator_group_tables.id','ASC')
                ->get();
        return view('pages.report.mycommission',compact('comm'));
    }
    
    public function electricity() {
        $op = Api_operator::Where('service_id','7')->get();
        return view('pages.services.electricity',compact('op'));
    }
    
    public function postelectricity(Request $request) {
        $mobile = $request->mobile;
        $amount = $request->amount;
        $opcode = $request->opcode;
        $rtype = $request->rtype;
        
        $rid = $this->apiManager->transactionId();
        
        $string = ['user_id'=>Auth::User()->id,'amount'=>$amount,'mobile'=>$mobile,'opcode'=>$opcode,'order_id'=>$rid,'paymentmode'=>'CASH','rtype'=>$rtype,'modetype'=>'WEB','admin_id'=>Auth::User()->admin_id];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2bcashRecharge");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        $op = json_decode($server_output);
        $mm = $op->status;
        
        if($mm){
            Session::flash('success', $op->message);
        }else{
            Session::flash('error', $op->message);
        }
        return redirect()->back();
    }
    
    public function gas() {
        $op = Api_operator::Where('service_id','6')->get();
        return view('pages.services.gas',compact('op'));
    }
    
    public function water() {
        $op = Api_operator::Where('service_id','8')->get();
        return view('pages.services.water',compact('op'));
    }
    
    
    public function Moneytransfer($id) {
        $check = B2b_dmt_add_beneficiary::where('b2b_user_id',Auth::User()->id)->where('benCode',$id)->first();
        if($check){
            
            return view('pages.services.moneytransfer',compact('id','check'));
        }else{
            
           $sts = "error";
            $msg = "Please Select Benf Id";
           Session::flash($sts, $msg);
            return redirect()->back(); 
        }
        
        
        
        
    }
    
    public function postMoneytransfer(Request $request,$id) {
        
        $amount = $request->amount;
        $txntype = $request->txntype;
        
        $check = B2b_dmt_add_beneficiary::where('b2b_user_id',Auth::User()->id)->where('benCode',$id)->first();
        
        if($check){
            $b2b_user = User::where('status', '1')->where('id', Auth::User()->id)->first();
            if($b2b_user){
                $slab = B2b_dmt_slab::where('group_id', $b2b_user->group_name)->where('to_amount','<=', $amount)->where('from_amount','>=', $amount)->where('rtype', 'DMT')->first();
                if($slab){
                    
                }else{
                    $sts = "error";
                    $msg = "DMT Not Active";
                    Session::flash($sts, $msg);
                    return redirect('view-beneficiaries');
                }
                if($slab->commission_type == 'FLAT'){
                    $fee = $slab->commission;
                }else{
                    $fee = $slab->commission*$amount/100;
                }
                $totalamount = $amount + $fee;
                if($totalamount < $b2b_user->balance){
                    
                }else{
                    $sts = "error";
                    $msg = "User Low balance";
                    Session::flash($sts, $msg);
                    return redirect('view-beneficiaries');
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
                        $order->b2b_user_id = Auth::User()->id;
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
                        $order->b2b_user_id = Auth::User()->id;
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
                    
                    
                    $sts = "success";
                    $msg = "Transection Procced";
                }else{
                    date_default_timezone_set('asia/kolkata');
                        $order = new B2b_app_all_recharge_transaction_history();
                        $order->b2b_user_id = Auth::User()->id;
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
                    $sts = "error";
                $msg = "Transection Faild";
                }
                
                
                Session::flash($sts, $msg);
                return redirect('view-beneficiaries');
            
            }else{
                $sts = "error";
                $msg = "User Not Found";
                Session::flash($sts, $msg);
                return redirect('view-beneficiaries');
            }
        }else{
             $sts = "error";
            $msg = "Something Wrong!! Transection Not procced...";
           Session::flash($sts, $msg);
            return redirect()->back(); 
        }
        
    }
    
    public function getAddbeneficiaries() {
        
        return view('pages.services.addbenf');
    }
    
    public function postAddbeneficiaries(Request $request) {
        $mobile = $request->mobile;
        $acc_holder = $request->acc_holder;
        $acc = $request->acc;
        $ifsc = $request->ifsc;
        
        $bencode = $this->apiManager->generateBencode();
        
        $check = B2b_dmt_add_beneficiary::where('b2b_user_id',Auth::User()->id)->where('ben_account',$acc)->get();
        
        if($check){
            $acc = new B2b_dmt_add_beneficiary();
            $acc->b2b_user_id = Auth::User()->id;
            $acc->benCode = $bencode;
            $acc->ben_account = $request->ben_account;
            $acc->benifsc = $request->benifsc;
            $acc->ben_name = $request->ben_name;
            $acc->benMobile = $request->benMobile;
            $acc->save();
            $sts = "success";
            $msg = "Beneficiary account added";
        }else{
            $sts = "error";
            $msg = "Account Already Added";
        }
        
        
        
        Session::flash($sts, $msg);
        return redirect()->back();
    }
    
    public function getViewbeneficiaries() {
        
        return view('pages.services.viewbenf');
    }
    
    public function viewbeneficiariesdata(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_dmt_add_beneficiary::query();
        
            
        
        $data = $query->where('b2b_user_id',Auth::User()->id)
                    // ->whereBetween('created_at', array($from, $to))
                    ->get();
        
        
        return DataTables::of($data)->make(true);
    }
    
    public function reportdmt() {
        
        return view('pages.report.reportdmt');
    }
    
    public function getReportdmtDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','b2b_dmt_add_beneficiaries.benMobile','b2b_dmt_add_beneficiaries.ben_name','b2b_dmt_add_beneficiaries.ben_account','b2b_dmt_add_beneficiaries.benifsc')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('b2b_dmt_add_beneficiaries','b2b_dmt_add_beneficiaries.benCode','b2b_app_all_recharge_transaction_histories.bencode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DMT')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id','!=','57')
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 2){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','b2b_dmt_add_beneficiaries.benMobile','b2b_dmt_add_beneficiaries.ben_name','b2b_dmt_add_beneficiaries.ben_account','b2b_dmt_add_beneficiaries.benifsc')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('b2b_dmt_add_beneficiaries','b2b_dmt_add_beneficiaries.benCode','b2b_app_all_recharge_transaction_histories.bencode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DMT')
            ->where('b2b_users.uplevel_id',Auth::User()->id)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','b2b_dmt_add_beneficiaries.benMobile','b2b_dmt_add_beneficiaries.ben_name','b2b_dmt_add_beneficiaries.ben_account','b2b_dmt_add_beneficiaries.benifsc')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('b2b_dmt_add_beneficiaries','b2b_dmt_add_beneficiaries.benCode','b2b_app_all_recharge_transaction_histories.bencode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','DMT')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    
    public function reportelectricity() {
        $op = Api_operator::where('service_id','2')->get();
        return view('pages.report.reportelectricity',compact('op'));
    }
    
    public function getReportelectricityDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = B2b_app_all_recharge_transaction_history::query();
        if(Auth::User()->user_type == 4 || Auth::User()->id == 57){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','Electricity')
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id','!=','57')
            ->where('b2b_app_all_recharge_transaction_histories.rech_user','1')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }elseif(Auth::User()->user_type == 2){
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.rtype','Electricity')
            ->where('b2b_users.uplevel_id',Auth::User()->id)
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }else{
            $data = $query->select('b2b_app_all_recharge_transaction_histories.*','b2b_users.firstname as user_name','api_operator.OperatorDescritpion as opname')
            ->join('b2b_users','b2b_users.id','b2b_app_all_recharge_transaction_histories.b2b_user_id')
            ->join('api_operator','api_operator.opcode','b2b_app_all_recharge_transaction_histories.opcode')
            ->whereBetween('b2b_app_all_recharge_transaction_histories.created_at', array($from, $to))
            ->where('b2b_app_all_recharge_transaction_histories.b2b_user_id',Auth::User()->id)
            ->where('b2b_app_all_recharge_transaction_histories.rtype','Electricity')
            ->orderBy('b2b_app_all_recharge_transaction_histories.id','desc')
            ->get();
        }
        
        return DataTables::of($data)->make(true);
    }
    
    public function postDeletebeneficiaries(Request $request)
    {
        $delete = B2b_dmt_add_beneficiary::where("benCode", $request->id)->delete();

        if($delete){
            return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }
    
}