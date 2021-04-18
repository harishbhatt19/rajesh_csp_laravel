<?php

/*
|--------------------------------------------------------------------------
| Web Routes - ISHWARSINH BHATI
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('auth/login');
    return redirect()->guest('login');
});

Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

//services
Route::get('recharge', ['as' => 'get:recharge', 'uses' => 'ServicesController@recharge']);
Route::post('recharge', ['as' => 'post:recharge', 'uses' => 'ServicesController@postRecharge']);
Route::get('dthrecharge', ['as' => 'get:dthrecharge', 'uses' => 'ServicesController@dthrecharge']);

Route::get('electricity', ['as' => 'get:electricity', 'uses' => 'ServicesController@electricity']);
Route::post('electricity', ['as' => 'post:electricity', 'uses' => 'ServicesController@postelectricity']);

Route::get('gas', ['as' => 'get:gas', 'uses' => 'ServicesController@gas']);
Route::get('water', ['as' => 'get:water', 'uses' => 'ServicesController@water']);

Route::get('aeps', ['as' => 'get:aeps', 'uses' => 'ServicesController@aeps']);
Route::get('aepsframe', ['as' => 'get:aepsframe', 'uses' => 'ServicesController@aepsframe']);

Route::get('moneytransfer/{id}', ['as' => 'get:moneytransfer', 'uses' => 'ServicesController@Moneytransfer']);
Route::post('moneytransfer/{id}', ['as' => 'post:moneytransfer', 'uses' => 'ServicesController@postMoneytransfer']);
Route::get('add-beneficiaries', ['as' => 'get:add_beneficiaries', 'uses' => 'ServicesController@getAddbeneficiaries']);
Route::post('add-beneficiaries', ['as' => 'post:add_beneficiaries', 'uses' => 'ServicesController@postAddbeneficiaries']);
Route::get('view-beneficiaries', ['as' => 'get:view_beneficiaries', 'uses' => 'ServicesController@getViewbeneficiaries']);
Route::get('view-beneficiaries-data-ajax', ['as' => 'get:view_beneficiaries_data_ajax', 'uses' => 'ServicesController@viewbeneficiariesdata']);
Route::post('delete-beneficiaries', ['as' => 'post:delete_beneficiaries', 'uses' => 'ServicesController@postDeletebeneficiaries']);

//add money
Route::get('add-money', ['as' => 'get:add_money', 'uses' => 'ServicesController@addMoney']);
Route::post('add-money', ['as' => 'post:add_money', 'uses' => 'ServicesController@postAddMoney']);
Route::get('paytm-add-money', ['as' => 'get:paytm_add_money', 'uses' => 'ServicesController@paytmAddMoney']);
Route::post('paytm-add-money', ['as' => 'post:paytm_add_money', 'uses' => 'ServicesController@postpaytmAddMoney']);
Route::post('paytm-add-money-res', ['as' => 'post:paytm_add_money_res', 'uses' => 'ServicesController@paytmAddMoneyres']);
Route::get('pending-money', ['as' => 'get:pending_money', 'uses' => 'ServicesController@pendingMoney']);
Route::get('pending-money-data-ajax', ['as' => 'get:pending_money_data_ajax', 'uses' => 'ServicesController@pendingMoneyDataAjax']);
Route::get('approve-money/{id}', ['as' => 'get:approve_money', 'uses' => 'ServicesController@approveMoney']);
Route::get('decline-money/{id}', ['as' => 'get:decline_money', 'uses' => 'ServicesController@declineMoney']);


//report
Route::get('report-point', ['as' => 'get:report_point', 'uses' => 'ServicesController@reportpoint']);
Route::get('report-point-data-ajax', ['as' => 'get:report_point_data_ajax', 'uses' => 'ServicesController@getReportpointDataAjax']);
Route::get('report-rechare', ['as' => 'get:report_recharge', 'uses' => 'ServicesController@reportRecharge']);
Route::get('report-rechare-data-ajax', ['as' => 'get:report_recharge_data_ajax', 'uses' => 'ServicesController@getReportRechargeDataAjax']);
Route::get('report-dth-rechare', ['as' => 'get:report_dth_recharge', 'uses' => 'ServicesController@reportDthRecharge']);
Route::get('report-dth-rechare-data-ajax', ['as' => 'get:report_dth_recharge_data_ajax', 'uses' => 'ServicesController@getReportDthRechargeDataAjax']);
Route::get('report-addmoney', ['as' => 'get:report_addmoney', 'uses' => 'ServicesController@reportAddmoney']);
Route::get('report-addmoney-data-ajax', ['as' => 'get:report_addmoney_data_ajax', 'uses' => 'ServicesController@getReportAddmoneyDataAjax']);
Route::get('report-pending', ['as' => 'get:report_pending', 'uses' => 'ServicesController@reportpending']);
Route::get('report-pending-data-ajax', ['as' => 'get:report_pending_data_ajax', 'uses' => 'ServicesController@getReportpendingDataAjax']);
Route::get('change-status-succsess/{id}', ['as' => 'get:change_status_succsess', 'uses' => 'ServicesController@changestatussuccsess']);
Route::get('change-status-fail/{id}', ['as' => 'get:change_status_fail', 'uses' => 'ServicesController@changestatusfail']);
Route::get('report-electricity', ['as' => 'get:report_electricity', 'uses' => 'ServicesController@reportelectricity']);
Route::get('report-electricity-data-ajax', ['as' => 'get:report_electricity_data_ajax', 'uses' => 'ServicesController@getReportelectricityDataAjax']);

Route::get('report-dmt', ['as' => 'get:report_dmt', 'uses' => 'ServicesController@reportdmt']);
Route::get('report-dmt-data-ajax', ['as' => 'get:report_dmt_data_ajax', 'uses' => 'ServicesController@getReportdmtDataAjax']);


Route::get('user-report-rechare/{id}', ['as' => 'get:user_report_recharge', 'uses' => 'ServicesController@userreportRecharge']);
Route::get('user-report-rechare-data-ajax', ['as' => 'get:user_report_recharge_data_ajax', 'uses' => 'ServicesController@getuserReportRechargeDataAjax']);

Route::get('user-report-dth-rechare/{id}', ['as' => 'get:user_report_dth_recharge', 'uses' => 'ServicesController@userreportDthRecharge']);
Route::get('user-report-dth-rechare-data-ajax', ['as' => 'get:user_report_dth_recharge_data_ajax', 'uses' => 'ServicesController@getuserReportDthRechargeDataAjax']);

Route::get('mypassbook', ['as' => 'get:mypassbook', 'uses' => 'ServicesController@getmypassbook']);

Route::get('userpassbook/{id}', ['as' => 'get:userpassbook', 'uses' => 'ServicesController@getuserpassbook']);
Route::get('userpassbook-data-ajax', ['as' => 'get:userpassbook_data_ajax', 'uses' => 'ServicesController@getuserpassbookDataAjax']);

Route::get('mycommission', ['as' => 'get:mycommission', 'uses' => 'ServicesController@getmycommission']);


//employee routes
Route::get('add-employee', ['as' => 'get:add_employee', 'uses' => 'HomeController@getAddEmployee']);
Route::post('add-employee', ['as' => 'post:add_employee', 'uses' => 'HomeController@postAddEmployee']);
Route::get('manage-employee', ['as' => 'get:manage_employee', 'uses' => 'HomeController@getManageEmployee']);
Route::get('manage-employee-data', ['as' => 'get:manage_employee_data', 'uses' => 'HomeController@getManageEmployeeData']);
Route::post('delete-employee', ['as' => 'post:delete_employee', 'uses' => 'HomeController@postDeleteEmployee']);
Route::get('edit-employee/{id}', ['as' => 'get:edit_employee', 'uses' => 'HomeController@getEditEmployee']);

//user
Route::get('add-user', ['as' => 'get:add_user', 'uses' => 'HomeController@getAddUser']);
Route::post('add-user', ['as' => 'post:add_user', 'uses' => 'HomeController@postAddUser']);
Route::get('edit-user/{id}', ['as' => 'get:edit_user', 'uses' => 'HomeController@geteditUser']);
Route::post('edit-user', ['as' => 'post:edit_user', 'uses' => 'HomeController@posteditUser']);
Route::get('delete-user/{id}', ['as' => 'get:delete_user', 'uses' => 'HomeController@getdeleteUser']);

Route::get('manage-user', ['as' => 'get:manage_user', 'uses' => 'HomeController@getManageUser']);
Route::get('add-fund-user/{id}', ['as' => 'get:add_fund_user', 'uses' => 'HomeController@getaddfundUser']);
Route::post('add-fund-user', ['as' => 'post:add_fund_user', 'uses' => 'HomeController@postaddfundUser']);
Route::post('add-point-user', ['as' => 'post:add_point_user', 'uses' => 'HomeController@postaddpointUser']);

//group
Route::get('add-package', ['as' => 'get:add_package', 'uses' => 'HomeController@getAddPackage']);
Route::post('add-package', ['as' => 'post:add_package', 'uses' => 'HomeController@postAddPackage']);

//settings
Route::get('add-news', ['as' => 'get:add_news', 'uses' => 'HomeController@getAddnews']);
Route::post('add-news', ['as' => 'post:add_news', 'uses' => 'HomeController@postAddnews']);
Route::get('manage-package', ['as' => 'get:manage_package', 'uses' => 'HomeController@getmanagepackage']);
Route::get('manage-package-data-ajax', ['as' => 'get:manage_package_data_ajax', 'uses' => 'HomeController@getmanagepackageDataAjax']);
Route::get('manage-commission/{id}', ['as' => 'get:manage_commission', 'uses' => 'HomeController@getmanagecommission']);
Route::get('manage-commission-data-ajax', ['as' => 'get:manage_commission_data_ajax', 'uses' => 'HomeController@getmanagecommissionDataAjax']);
Route::post('update-commission', ['as' => 'post:update_commission', 'uses' => 'HomeController@postupdatecommission']);
Route::get('add-banner', ['as' => 'get:add_banner', 'uses' => 'HomeController@getAddbanner']);
Route::post('add-banner', ['as' => 'post:add_banner', 'uses' => 'HomeController@postAddbanner']);
Route::get('delete-banner/{id}', ['as' => 'get:delete_banner', 'uses' => 'HomeController@getdeletebanner']);
Route::get('add-bank', ['as' => 'get:add_bank', 'uses' => 'HomeController@getAddbank']);
Route::post('add-bank', ['as' => 'post:add_bank', 'uses' => 'HomeController@postAddbank']);
Route::get('manage-bank', ['as' => 'get:manage_bank', 'uses' => 'HomeController@getmanagebank']);
Route::get('manage-bank-data-ajax', ['as' => 'get:manage_bank_data_ajax', 'uses' => 'HomeController@getmanagebankDataAjax']);
Route::get('add-operator', ['as' => 'get:add_operator', 'uses' => 'HomeController@getAddoperator']);
Route::post('add-operator', ['as' => 'post:add_operator', 'uses' => 'HomeController@postAddoperator']);

Route::get('api-list', ['as' => 'get:api_list', 'uses' => 'HomeController@apilist']);
Route::post('opcode-update', ['as' => 'post:opcode_update', 'uses' => 'HomeController@postopcodeupdate']);

Route::post('api-active', ['as' => 'post:api_active', 'uses' => 'HomeController@postapiactive']);

Route::get('add-dmt-slab/{id}', ['as' => 'get:add_dmt_slab', 'uses' => 'HomeController@getAddDmtSlab']);
Route::post('add-dmt-slab', ['as' => 'post:add_dmt_slab', 'uses' => 'HomeController@postAddDmtSlab']);

Route::get('manage-slab/{id}', ['as' => 'get:manage_slab', 'uses' => 'HomeController@getmanageslab']);
Route::get('manage-slab-ajax-data', ['as' => 'get:manage_slab_ajax_data', 'uses' => 'HomeController@getmanageslabajaxdata']);
Route::post('delete-slab', ['as' => 'post:delete_slab', 'uses' => 'HomeController@postDeleteSlab']);






//categories routes
Route::get('add-category', ['as' => 'get:add_category', 'uses' => 'HomeController@getAddCategory']);
Route::post('add-category', ['as' => 'post:add_category', 'uses' => 'HomeController@postAddCategory']);
Route::get('manage-categories', ['as' => 'get:manage_categories', 'uses' => 'HomeController@getManageCategories']);
Route::get('manage-categories-data', ['as' => 'get:manage_categories_data', 'uses' => 'HomeController@getManageCategoriesData']);
Route::post('delete-category', ['as' => 'post:delete_category', 'uses' => 'HomeController@postDeleteCategory']);
Route::get('edit-category/{id}', ['as' => 'get:edit_category', 'uses' => 'HomeController@getEditCategory']);

//pond routes
Route::get('add-pond', ['as' => 'get:add_pond', 'uses' => 'HomeController@getAddPond']);
Route::post('add-pond', ['as' => 'post:add_pond', 'uses' => 'HomeController@postAddPond']);
Route::get('manage-ponds', ['as' => 'get:manage_ponds', 'uses' => 'HomeController@getManagePonds']);
Route::get('manage-ponds-data', ['as' => 'get:manage_ponds_data', 'uses' => 'HomeController@getManagePondsData']);
Route::post('delete-pond', ['as' => 'post:delete_pond', 'uses' => 'HomeController@postDeletePond']);
Route::get('edit-pond/{id}', ['as' => 'get:edit_pond', 'uses' => 'HomeController@getEditPond']);

//user data
Route::get('add-user-data', ['as' => 'get:add_user_data', 'uses' => 'HomeController@getAddUserData']);
Route::post('add-user-data', ['as' => 'post:add_user_data', 'uses' => 'HomeController@postAddUserData']);
Route::get('manage-user-data', ['as' => 'get:manage_user_data', 'uses' => 'HomeController@getManageUserData']);
Route::get('manage-user-data-ajax', ['as' => 'get:manage_user_data_ajax', 'uses' => 'HomeController@getManageUserDataAjax']);


//salinity data
Route::get('add-salinity-data', ['as' => 'get:add_salinity_data', 'uses' => 'HomeController@getAddSalinityData']);
Route::post('add-salinity-data', ['as' => 'post:add_salinity_data', 'uses' => 'HomeController@importSalinityExcel']);
Route::get('download-salinity-excel-sheet/{type}', ['as' => 'get:download_salinity_excel_sheet', 'uses' => 'HomeController@downloadSalinityExcelSheet']);

Route::get('manage-salinity-forcast', ['as' => 'get:manage_salinity_forcast', 'uses' => 'HomeController@getManageSalinityForcast']);
Route::get('manage-salinity-forcast-data', ['as' => 'get:manage_salinity_forcast_data', 'uses' => 'HomeController@getManageSalinityForcastData']);


Route::get('add-water-data', ['as' => 'get:add_water_data', 'uses' => 'HomeController@getAddWaterData']);
Route::post('add-water-data', ['as' => 'post:add_water_data', 'uses' => 'HomeController@importWaterExcel']);
Route::get('download-water-excel-sheet/{type}', ['as' => 'get:download_water_excel_sheet', 'uses' => 'HomeController@downloadWaterExcelSheet']);

Route::get('manage-water-forcast', ['as' => 'get:manage_water_forcast', 'uses' => 'HomeController@getManageWaterForcast']);
Route::get('manage-water-forcast-data', ['as' => 'get:manage_water_forcast_data', 'uses' => 'HomeController@getManageWaterForcastData']);

Route::get('getmapdata', ['as' => 'get:get_map_data', 'uses' => 'HomeController@getMapData']);
Route::post('getmapdata', ['as' => 'post:get_map_data', 'uses' => 'HomeController@postMapData']);
