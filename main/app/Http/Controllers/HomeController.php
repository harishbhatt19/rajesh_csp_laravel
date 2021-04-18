<?php

namespace App\Http\Controllers;
use App\Classes\ApiManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Excel;
use Image;

use Auth;
use DataTables;

use App\B2b_user;
use App\User;
use App\Model\Api_operator;
use App\Model\Api_operator_group;
use App\Model\Api_operator_group_table;
use App\Model\B2b_api_configuration;
use App\Model\B2b_app_all_recharge_transaction_history;
use App\Model\B2b_mobile_banner;
use App\Model\B2b_money_request;
use App\Model\B2b_wl_operator_16552380;
use App\Model\Bank_detail;

use App\Model\B2b_dmt_slab;



use App\Model\Category;
use App\Model\UserData;
use App\Model\Pond;
use App\Model\UserDataForcastSalinity;
use App\Model\UserDataForcastWater;

class HomeController extends Controller
{
    public function __construct(ApiManager $apiManager)
    {
        $this->middleware('auth');
        $this->apiManager = $apiManager;
    }

    public function index() {
        
        if(Auth::User()->id == 57){
            $totalrecharge = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->sum('amount');
            
            $from = date('Y-m-d'. ' 00:00:00');
            $to = date('Y-m-d'. ' 23:59:59');            
            $todayrecharge = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->whereBetween('created_at', array($from, $to))
                        ->sum('amount');
            
            $totalfundtransfer = B2b_money_request::where('status','1')
                        ->sum('amount');
            $todayfundtransfer = B2b_money_request::where('status','1')
                        ->whereBetween('created_at', array($from, $to))
                        ->sum('amount');
            $totalactiveuser = User::where('status','1')->where('id','!=','57')->count();
            $totaluser = User::where('id','!=','57')->count();
            $totalprofit = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->where('b2b_user_id',Auth::User()->id)
                        ->sum('commission');
        }elseif(Auth::User()->user_type == 6){
            $totalrecharge = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->where('b2b_user_id',Auth::User()->id)
                        ->sum('amount');
            
            $from = date('Y-m-d'. ' 00:00:00');
            $to = date('Y-m-d'. ' 23:59:59');            
            $todayrecharge = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->where('b2b_user_id',Auth::User()->id)
                        ->whereBetween('created_at', array($from, $to))
                        ->sum('amount');
            
            $totalprofit = B2b_app_all_recharge_transaction_history::where('status','1')
                        ->where(function($query){
                            $query->where('rtype','MobileRecharge')->orWhere('rtype','DTH');
                        })
                        ->where('b2b_user_id',Auth::User()->id)
                        ->sum('commission');            
        }else{
            
        }
        
        return view('pages.dashboard',compact('totalprofit','totalrecharge','todayrecharge','todayfundtransfer','totalfundtransfer','totalactiveuser','totaluser'));
    }
 
    public function getAddEmployee() {
        return view('pages.employee.add_employee');
    }
    
    public function postAddEmployee(Request $request) 
    {
        if($request->employee_id) {
            $user = User::find($request->employee_id);
            $msg = "Data updated successfully."; 
        }else{
            
            $checkMobile = User::where('mobile', $request->mobile)->first();
        
            if ($checkMobile) {
                Session::flash('error', 'Mobile number already in use!');
                return redirect()->back()->withInput();
            }
            
            $user = new User();    
            $msg = "Data added successfully.";
            
            $user->password = bcrypt($request->password);
        }
        
        $user->name = trim(ucfirst($request->name));
        $user->mobile = trim($request->mobile);
        $user->status = trim($request->status);
        $user->user_type = 3;
        $user->save();
        
        Session::flash('success', $msg);
        return redirect()->back();
    }
    
    public function getManageEmployee() {
        return view('pages.employee.manage_employee');
    }
    
    public function getAddUser() {
        $packages = Api_operator_group::where('user_id',Auth::User()->id)->get();
        return view('pages.user.add_user',compact('packages'));
    }
    public function postAddUser(Request $request) {
        
        $user_id = Auth::User()->id;
        if(Auth::User()->user_type == 4 && Auth::User()->id == 57){
            $admin = 57;
        }elseif(Auth::User()->user_type == 4){
            $admin = Auth::User()->id;
        }else{
            $admin = Auth::User()->admin_id;
        }
        
        $fname = $request->fname;
        $lname = $request->lname;
        $cname = $request->cname;
        $user_type = $request->user_type;
        $package = $request->package;
        $mobile = $request->mobile;
        $username = $request->username;
        $email = $request->email;
        $pwd = $request->password;
        $string = ['firstname'=>$fname,
                    'lastname'=>$lname,
                    'cmpy_name'=>$cname,
                    'email'=>$email,
                    'username'=>$username,
                    'mobile'=>$mobile,
                    'u_id'=>rand(111111, 999999),
                    'group_id'=>$package,
                    'usertype'=>$user_type,
                    'city'=>0,
                    'state'=>0,
                    'admin_id'=>$admin,
                    'password'=>$pwd,
                    'user_id'=>$user_id];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/b2baddcustomer");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        Session::flash('success', 'User Created.');
        return redirect()->back();            
    }
    public function geteditUser($id) {
        $employeedata = User::where('mob_no',$id)->first();
        
        if($employeedata){
           if(Auth::User()->user_type == 4){
               $packages = Api_operator_group::where('user_id',$employeedata->uplevel_id)->get();
               $users = User::where('user_type','!=',3)->where('user_type','!=',6)->get();
           }else{
               $packages = Api_operator_group::where('user_id',Auth::User()->id)->get();
               $users = '';
           } 
        }
        
        return view('pages.user.edit_user',compact('packages','employeedata','users'));
    }
    
    public function posteditUser(Request $request) {
        
        if(Auth::User()->user_type == 4 && Auth::User()->id == 57){
            $admin = 57;
        }elseif(Auth::User()->user_type == 4){
            $admin = Auth::User()->id;
        }else{
            $admin = Auth::User()->admin_id;
        }
        
        $current_id = Auth::User()->id;
        $fname = $request->fname;
        $lname = $request->lname;
        $cname = $request->cname;
        $user_type = $request->user_type;
        $package = $request->package;
        $mobile = $request->mobile;
        $username = $request->username;
        $email = $request->email;
        
        $string = ['firstname'=>$fname,
                    'lastname'=>$lname,
                    'cmpy_name'=>$cname,
                    'email'=>$email,
                    'username'=>$username,
                    'mob_no'=>$mobile,
                    'group_name'=>$package,
                    'user_type'=>$user_type,
                    'admin_id'=>$admin,];
        $update = User::where('id',$request->user_id)->update($string);
        
        if(isset($request->uplevel_id) && !empty($request->uplevel_id)){
            $string2 = ["uplevel_id"=>$request->uplevel_id];
            $update = User::where('id',$request->user_id)->update($string2);
        }
        
        if(isset($request->password) && !empty($request->password)){
            $pwd = $request->password;
            $bcrpwd = bcrypt($request->password);
            $mdfpwd = md5($request->password);
            $string1 = ["password"=>$mdfpwd,"bcr_password"=>$bcrpwd,"show_password"=>$pwd];
            $update = User::where('id',$request->user_id)->update($string1);
        }         
        
        Session::flash('success', 'User Updated');
        return redirect()->back();
    }
    
    public function getManageUser() {
        if(Auth::User()->user_type == 4 && Auth::User()->id == 57){
            $users = User::where('id','!=','57')->get();
        }else{
            $users = User::where('uplevel_id',Auth::User()->id)->get();
        }
        
        return view('pages.user.manage_user',compact('users'));
    }
    
    public function getAddPackage() {
        return view('pages.package.add_package');
    }
    public function postAddPackage(Request $request) {
        $user_id = Auth::User()->id;
        $name = $request->pname;
        
        $string = ['user_id'=>Auth::User()->id,
                    'group_name'=>$name];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.".env('API_URL')."/api/add_group");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        Session::flash('success', 'Package Created.');
        return redirect()->back();
    }
    
    public function getaddfundUser($id) {
        $users = User::where('id',$id)->first();
        if($users){
            
        }else{
             Session::flash('error', 'User Not Found');
            return redirect()->back();
        }
        
        return view('pages.user.add_fund_user',compact('users'));
    }
    
    public function postaddfundUser(Request $request) {
        $admin_id = Auth::User()->id;
        $user_id = $request->user_id;
        $amount = $request->addfund;
        $order_id = $this->apiManager->transactionId();
        $string = ['user_id'=>$user_id,
                    'order_id'=>$order_id,
                    'bank_ref'=>'Admin',
                    'details'=>'Admin Fund Transfer',
                    'admin_id'=>$admin_id,
                    'name'=>'Admin',
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
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
        
    }
    
    public function postaddpointUser(Request $request) {
        $admin_id = Auth::User()->id;
        $user_id = $request->user_id;
        $point = $request->point;
        $point2 = $request->point2;
        $current = User::where('id',$user_id)->first();
        if($current){
            
        
            $user = User::find($user_id);
            if($point){
               $user->point =  $current->point + $point;
            }
            
            if($point2){
                $user->point2 =  $current->point2 + $point2;
            }
            $user->save();
        }
        $msg_ntf = "Point added";
            $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getAddoperator() {
        return view('pages.settings.add_operator');
    }
    
    public function postAddoperator(Request $request) {
        $name = $request->name;
        $code = $request->code;
        $type = $request->type;
        $data = explode("-",$type);
        
        $op = new Api_operator();
        $op->OperatorDescritpion = $name;
        $op->RechargeType = $data[0];
        $op->status = 1;
        $op->opcode = $code;
        $op->service_id = $data[1];
        $op->op_image = "";
        $op->save();
        
        $op1 = new B2b_wl_operator_16552380();
        $op1->OperatorDescritpion = $name;
        $op1->RechargeType = $data[0];
        $op1->status = 1;
        $op1->opcode = $code;
        $op1->service_id = $data[1];
        $op1->op_image = "";
        $op1->save();
        
        $grp = Api_operator_group::get();
        
        foreach($grp as $gr){
            $gop = new Api_operator_group_table();
            $gop->opid = $op->id;
            $gop->group_id = $gr->id;
            $gop->sale_commission_type = 'flat';
            $gop->service_charge_type = 'flat';
            $gop->buy_coms_type = 'flat';
            $gop->buy_sc_type = 'flat';
            $gop->OperatorDescritpion = $name;
            $gop->service_id = $data[1];
            $gop->save();
        }
        
        // exit;
        $msg_ntf = "operator added";
            $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getAddnews() {
        $admin_id = Auth::User()->id;
        $employeedata = User::where('id',$admin_id)->where('user_type',4)->first();
        return view('pages.settings.add_news',compact('employeedata'));
    }
    
    public function postAddnews(Request $request) {
        $admin_id = Auth::User()->id;
        $news = $request->news;
        
        $current = User::where('id',$admin_id)->where('user_type',4)->first();
        if($current){
            $user = User::find($current->id);
            $user->news = $news;
            $user->save();
            
            $msg_ntf = "News Updated";
            $sts_ntf = "success";
        }else{
            $msg_ntf = "Not Allow";
            $sts_ntf = "error";
        }
        
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getmanagebank() {
        
        return view('pages.settings.manage_bank');
    }
    
    public function getmanagebankDataAjax(Request $request) { 


        $query = Bank_detail::query();
        $data = $query->select('bank_details.*')
            ->where('bank_details.user_id',Auth::User()->id)
            ->get();
        
        return DataTables::of($data)->make(true);
    }
    
    public function getAddbank() {
        
        return view('pages.settings.add_bank');
    }
    
    public function postAddbank(Request $request) {
        $user_id = Auth::User()->id;
        
        $bank = new Bank_detail();
        $bank->bank_name = $request->bank_name;
        $bank->bank_ifsc = $request->bank_ifsc;
        $bank->bank_branch = $request->bank_branch;
        $bank->acc = $request->acc;
        $bank->acc_type = $request->acc_type;
        $bank->save();
        
        $msg_ntf = "Bank Added";
            $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getmanagepackage() {
        
        return view('pages.package.manage_package');
    }
    
    public function getmanagepackageDataAjax(Request $request) { 


        $query = Api_operator_group::query();
        $data = $query->select('api_operator_groups.*')
            ->where('api_operator_groups.user_id',Auth::User()->id)
            ->orderBy('api_operator_groups.id','desc')
            ->get();
        
        return DataTables::of($data)->make(true);
    }
    
    public function getAddDmtSlab($id) {
        $check = Api_operator_group::where('id',$id)->where('user_id',Auth::User()->id)->first();
        if($check){
            
        }else{
            $msg_ntf = "Sorry Something Wrong!!";
            $sts_ntf = "error";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
        }
        return view('pages.package.add_slab',compact('id'));
    }
    
    public function postAddDmtSlab(Request $request) {
        $check = Api_operator_group::where('id',$request->grpid)->where('user_id',Auth::User()->id)->first();
        if($check){
            
        }else{
            $msg_ntf = "Sorry Something Wrong!!";
            $sts_ntf = "error";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
        }
        
        $sl = new B2b_dmt_slab();
        $sl->group_id = $request->grpid;
        $sl->slab_name = $request->slab_name;
        $sl->to_amount = $request->samt;
        $sl->from_amount = $request->eamt;
        $sl->commission = $request->fee;
        $sl->commission_type = $request->type;
        $sl->rtype = 'DMT';
        $sl->dmt_type = '0';
        $sl->opcode = 'DMT';
        $sl->save();
        
        $msg_ntf = "Slab Added....";
            $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
        
    }
    
    public function getmanageslab($id) {
        
        return view('pages.package.manage_slab',compact('id'));
    }
    
    public function getmanageslabajaxdata(Request $request) { 


        $query = B2b_dmt_slab::query();
        $data = $query->select('b2b_dmt_slabs.*')
            ->where('group_id',$request->group_id)
            ->orderBy('id','desc')
            ->get();
        
        return DataTables::of($data)->make(true);
    }
    
    public function postDeleteSlab(Request $request)
    {
        $delete = B2b_dmt_slab::where("id", $request->id)->delete();

        if($delete){
            return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }
    
    public function getmanagecommission($id) {
        $apis = B2b_api_configuration::where('api_holder','57')->get();
        $checkapi = $this->apiManager;
        return view('pages.package.manage_commission',compact('id','apis','checkapi'));
    }
    
    public function getmanagecommissionDataAjax(Request $request) { 
        $grpid = $request->grpid;

        $query = Api_operator_group_table::query();
        $data = $query->select('api_operator_group_tables.*','api_operator.OperatorDescritpion As opname','api_operator.RechargeType As rtype')
            ->join('api_operator','api_operator.opid','api_operator_group_tables.opid')
            ->where('api_operator_group_tables.group_id',$grpid)
            ->orderBy('api_operator_group_tables.id','ASC')
            ->get();
        
        return DataTables::of($data)->make(true);
    }
    public function postupdatecommission(Request $request) {
        $grpid = $request->rowid;
        $sale_commission = $request->sale_commission;
        $sale_commission_type = $request->sale_commission_type;
        
        $Api_operator_group_table = Api_operator_group_table::find($grpid);
        $Api_operator_group_table->sale_commission = $sale_commission;
        $Api_operator_group_table->sale_commission_type = $sale_commission_type;
        $Api_operator_group_table->save();
        
        
        $msg_ntf = "commission Updated";
        $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getAddbanner() {
        $allbanner = B2b_mobile_banner::get();
        return view('pages.settings.add_banner',compact('allbanner'));
    }
    
    public function postAddbanner(Request $request) {
        if($request->file('banner')){
            
            $destinationPath = public_path('/uploads/banner/');
            $imagename = 'banner'. time() . '.' . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($destinationPath,$imagename);
            
            $file = new B2b_mobile_banner();
            $file->img = env('IMAGE_URL').'uploads/banner/'.$imagename;
            $file->save();
            
        }
        
        $msg_ntf = "Banner Uploaded";
        $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getdeletebanner($id) {
        
        $allbanner = B2b_mobile_banner::find($id);
        $allbanner->delete();
        
        $msg_ntf = "Banner Deleted";
        $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function getdeleteUser($id) {
        if($admin_id = Auth::User()->user_type == 4){
            $allbanner = User::find($id);
            $allbanner->delete();
            $msg_ntf = "User Deleted";
            $sts_ntf = "success";
        }else{
            $msg_ntf = "Access Denied";
            $sts_ntf = "error";
        }
        
        
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function apilist() {
        $ops = Api_operator::get();
        $apis = B2b_api_configuration::where('api_holder','57')->get();
        
        $findopcode = $this->apiManager;
        return view('pages.settings.api_list',compact('ops','apis','findopcode'));
    }
    
    public function postopcodeupdate(Request $request) {
        
        $apiid = $request->apiid;
        $colunm = 'opcode'.$apiid;
        $op = B2b_wl_operator_16552380::where('opid',$request->opid)->update([$colunm => $request->opcode]);
        
        
        $msg_ntf = "OPCode Update.";
        $sts_ntf = "success";
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    public function postapiactive(Request $request) {
        if($admin_id = Auth::User()->user_type == 4){
            $rowid = $request->rowid;
            $apiid = $request->apid;
            $apis = B2b_api_configuration::get();
            foreach($apis as $r){
                $colunm = "status".$r->id;
                $Api = Api_operator_group_table::find($rowid);
                $Api->$colunm = 0;
                $Api->save();
            }
            
            $colunm1 = "status".$apiid;
            $Api1 = Api_operator_group_table::find($rowid);
            $Api1->$colunm1 = $request->activee;
            $Api1->save();
                
            $msg_ntf = "API Status Update";
            $sts_ntf = "success";
        }else{
            $msg_ntf = "Access Denied";
            $sts_ntf = "error";
        }
        
        Session::flash($sts_ntf, $msg_ntf);
        return redirect()->back();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    







    
//=========================================================================================================    
//=========================================================================================================    
    public function getManageEmployeeData() {    
        $data = User::select("*")->where('user_type','3')->get();
        return DataTables::of($data)->make(true);
    }

    public function postDeleteEmployee(Request $request)
    {
        $delete = User::where("id", $request->id)->where('user_type',3)->delete();

        if($delete){
            return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }
    
    public function getEditEmployee($id)
    {
        $employeedata = User::where('id',$id)->first();
        return view('pages.employee.add_employee',compact('employeedata'));
    }
    
    
    public function getAddCategory() {
        return view('pages.category.add_category');
    }
    
    public function postAddCategory(Request $request) 
    {
        if($request->category_id) {
            $data = Category::find($request->category_id);
            $msg = "Category updated successfully."; 
        }else{
            
            $check = Category::where('category_name', $request->category_name)->first();
            
            if ($check) {
                Session::flash('error', 'Category already available!');
                return redirect()->back()->withInput();
            }
                
            $data = new Category();    
            $msg = "Data added successfully.";
        }
        
        $data->category_name = trim($request->category_name);
        $data->status = trim($request->status);
        $data->save();
        
        Session::flash('success', $msg);
        return redirect()->back();
    }
    
    public function getManageCategories() {
        return view('pages.category.manage_categories');
    }
    
    public function getManageCategoriesData() {    
        $data = Category::select("*")->get();
        return DataTables::of($data)->make(true);
    }

    public function postDeleteCategory(Request $request)
    {
        $delete = Category::where("id", $request->id)->delete();

        if($delete){
            return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }

    public function getEditCategory($id)
    {
        $categorydata = Category::where('id',$id)->first();
        return view('pages.category.add_category',compact('categorydata'));
    }
    
    
    
    // ponds
    public function getAddPond() {
        
        $categories = Category::where('status',1)->get();
        return view('pages.ponds.add_pond',compact('categories'));
    }
    
    public function postAddPond(Request $request) 
    {
        if($request->pond_id) {
            $data = Pond::find($request->pond_id);
            $msg = "Data updated successfully."; 
        }else{
            $data = new Pond();    
            $msg = "Data added successfully.";
        }
        
        $data->category_id = trim($request->category_id);
        $data->pond_name = trim($request->pond_name);
        $data->status = trim($request->status);
        $data->save();
        
        Session::flash('success', $msg);
        return redirect()->back();
    }
    
    public function getManagePonds() {
        return view('pages.ponds.manage_ponds');
    }
    
    public function getManagePondsData() {    
        $data = Pond::select("ponds.*","categories.category_name")
        ->join('categories','categories.id','ponds.category_id')
        ->where('categories.status',1)->get();
        return DataTables::of($data)->make(true);
    }

    public function postDeletePond(Request $request)
    {
        $delete = Pond::where("id", $request->id)->delete();

        if($delete){
            return json_encode(array("status" => true, "message" => "Record Deleted"));
        }else {
            return json_encode(array("status" => false, "message" => "Record not found."));
        }
    }
    
    public function getEditPond($id)
    {
        $pondsdata = Pond::where('id',$id)->first();
        
        $categories = Category::where('status',1)->get();
        return view('pages.ponds.add_pond',compact('categories','pondsdata'));
    }
    
    
    //user data
    public function getAddUserData() {
        $categories = Category::where('status','1')->get();
        return view('pages.user_data.add_user_data',compact('categories'));
    }
    


    public function getManageUserData() {
        return view('pages.user_data.manage_user_data');
    }

    public function getManageUserDataAjax(Request $request) { 

        $from = date('Y-m-d'. ' 00:00:00', strtotime($request->startdate));
        $to = date('Y-m-d'. ' 23:59:59', strtotime($request->enddate));
    

        $query = UserData::query();
        $data = $query->select('user_data.*','users.name as user_name','users.mobile','categories.category_name','ponds.pond_name')
            ->join('users','users.id','user_data.user_id')
            ->join('ponds','ponds.id','user_data.pond_id')
            ->join('categories','categories.id','ponds.category_id')
            ->whereBetween('user_data.created_at', array($from, $to))
            ->orderBy('user_data.id','desc')
            ->get();
        return DataTables::of($data)->make(true);
    }
    
    
    
    
    public function getAddSalinityData() {
        return view('pages.salinity_data.add_salinity_data');
    }
    
    
    public function getAddWaterData() {
        return view('pages.water_data.add_water_data');
    }
    

    public function importSalinityExcel(Request $request)
    {
        $request->validate(['import_file' => 'required']);

        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path)->get();
        // dd($data->count());
        if($data->count()){
            $prvsslinity = 0;
            foreach ($data[0] as $key => $value) {
                // echo date("Y-m-d", strtotime($value->date));
                // exit;
                $catid = Category::where('category_name',$value->category_id)->first();
                $pond = Pond::where("category_id", $catid->id)->where('pond_name',$value->pond_id)->first();
                
                if($pond){
                    if(gettype($value->salinity) == 'string' || gettype($value->salinity) == 'NULL'){
                        $currentsalinity = $prvsslinity;
                    }else{
                        $currentsalinity = $value->salinity;
                    }
                    $arr[] = ['user_id' => Auth::User()->id, 
                    'category_id' => $catid->id, 
                    'pond_id' => $pond->id, 
                    'salinity' => $currentsalinity, 
                    'date' => date("Y-m-d", strtotime($value->date))];
                    if(!empty($arr)){
                        UserDataForcastSalinity::insert($arr);
                        
                    }
                    unset($arr);
                    $prvsslinity = $currentsalinity;
                }
            }
            // print_r($arr);exit;
            // if(!empty($arr)){
            //     UserDataForcastSalinity::insert($arr);
            //     UserData::insert($arr);
            // }
        }

 
        Session::flash('success', 'Record inserted successfully.');
        return back();
        // return back()->with('success', 'Insert Record successfully.');
    }


    public function downloadSalinityExcelSheet($type)
    {
        $file_name = 'SALINITY_FORCAST'.now();
        $data = UserDataForcastSalinity::select('category_id','pond_id','salinity','date')->get()->toArray();
        return Excel::create($file_name, function($excel) use ($data) {

            $excel->sheet('salinity data', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });

        })->download($type);
    }




    public function getManageSalinityForcast() {
        return view('pages.salinity_data.manage_salinity_forcast_data');
    }
    
    public function getManageSalinityForcastData() {    
        $data = UserDataForcastSalinity::select("user_data_forcast_salinity.*","users.name","users.mobile","categories.category_name","ponds.pond_name")
        ->join('users','users.id','user_data_forcast_salinity.user_id')
        ->join('ponds','ponds.id','user_data_forcast_salinity.pond_id')
        ->join('categories','categories.id','ponds.category_id')
        ->where('categories.status',1)->get();
        return DataTables::of($data)->make(true);
    }









    public function importWaterExcel(Request $request)
    {
        $request->validate(['import_file' => 'required']);

        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path)->get();

        if($data->count()){

            foreach ($data[0] as $key => $value) {

                $arr[] = ['user_id' => Auth::User()->id, 
                'category_id' => $value->category_id, 
                'pond_id' => $value->pond_id, 
                'water' => $value->water, 
                'date' => $value->date];
            }

            if(!empty($arr)){
                UserDataForcastWater::insert($arr);
            }
        }

 
        Session::flash('success', 'Record inserted successfully.');
        return back();
        // return back()->with('success', 'Insert Record successfully.');
    }


    public function downloadWaterExcelSheet($type)
    {
        $file_name = 'WATER_FORCAST'.now();
        $data = UserDataForcastWater::select('category_id','pond_id','water','date')->get()->toArray();
        return Excel::create($file_name, function($excel) use ($data) {

            $excel->sheet('water forcast', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });

        })->download($type);
    }

    public function getManageWaterForcast() {
        return view('pages.water_data.manage_water_forcast_data');
    }
    
    public function getManageWaterForcastData() {    
        $data = UserDataForcastWater::select("user_data_forcast_water.*","users.name","users.mobile","categories.category_name","ponds.pond_name")
        ->join('users','users.id','user_data_forcast_water.user_id')
        ->join('ponds','ponds.id','user_data_forcast_water.pond_id')
        ->join('categories','categories.id','ponds.category_id')
        ->where('categories.status',1)->get();
        return DataTables::of($data)->make(true);
    }
    
    public function getMapData() {
        $categories = Category::where('status',1)->get();
        
        $actull="";
        $forcast = "";
        return view('pages.map.mapdata',compact('categories','actull','forcast'));
    }
    
    public function postMapData(Request $request) {
        $categories = Category::where('status',1)->get();
        
        $dt1 = $request->dt1;
        $dt2 = $request->dt2;
        $from = date('Y-m-d', strtotime($dt1));
        $to = date('Y-m-d', strtotime($dt2));
        $pond = $request->pond;
        $cat = $request->cat;
        $actull = array();
        $forcast = array();
        if($pond == 'all'){
            $data_pond = Pond::where('category_id',$cat)->get();
            foreach($data_pond as $p){
                
            
                $data = UserData::select('salinity','date_of_measurement as date','time')
                            ->whereBetween('user_data.date_of_measurement', array($from, $to))
                            ->where('category_id',$cat)
                            ->where('pond_id',$p->id)
                            ->orderBy('user_data.date_of_measurement','ASC')
                            ->get();
                $xz = "";
                foreach($data as $r){
                
                    $xz .= "{ x: new Date(".date('Y,m,d',strtotime($r->date))."), y: ".$r->salinity." },";
                }
                
                $actull[] = $xz;
                
                $data2 = UserDataForcastSalinity::select('salinity','date')
                        ->whereBetween('user_data_forcast_salinity.date', array($from, $to))
                        ->where('category_id',$cat)
                        ->where('pond_id',$p->id)
                        ->orderBy('user_data_forcast_salinity.date','ASC')
                        ->get();
            
                $xz1 = "";
                foreach($data2 as $r1){
                
                    $xz1 .= "{ x: new Date(".date('Y,m,d',strtotime($r1->date))."), y: ".$r1->salinity." },";
                }
                
                $forcast[] = $xz1;
                
            }
        }
        else{
            $data = UserData::select('salinity','date_of_measurement as date','time')
                        ->whereBetween('user_data.date_of_measurement', array($from, $to))
                        ->where('category_id',$cat)
                        ->where('pond_id',$pond)
                        ->orderBy('user_data.date_of_measurement','ASC')
                        ->get();
            //   dd($data);      
            $xz = "";
            foreach($data as $r){
            
                $xz .= "{ x: new Date(".date('Y,m,d',strtotime($r->date))."), y: ".$r->salinity." },";
            }
            $actull[] = $xz;
            $data2 = UserDataForcastSalinity::select('salinity','date')
                        ->whereBetween('user_data_forcast_salinity.date', array($from, $to))
                        ->where('category_id',$cat)
                        ->where('pond_id',$pond)
                        ->orderBy('user_data_forcast_salinity.date','ASC')
                        ->get();
            
            $xz1 = "";
            foreach($data2 as $r1){
            
                $xz1 .= "{ x: new Date(".date('Y,m,d',strtotime($r1->date))."), y: ".$r1->salinity." },";
            }  
            $forcast[] = $xz1;
        }
        
        return view('pages.map.mapdata',compact('categories','actull','forcast'));
        
    }

   

    

}