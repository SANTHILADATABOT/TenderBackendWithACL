<?php

use App\Http\Controllers\UlbMasterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllerTemp;
use App\Http\Controllers\StateMasterController;
use App\Http\Controllers\CountryMasterController;
use App\Http\Controllers\UnitMasterController;
use App\Http\Controllers\CityMasterController;
use App\Http\Controllers\DistrictMasterController;
use App\Http\Controllers\CustomerCreationMainController;
use App\Http\Controllers\CustomerCreationProfileController;
use App\Http\Controllers\CustomerCreationContactPersonController;
use App\Http\Controllers\CustomerCreationSWMProjectStatusController;
use App\Http\Controllers\CompetitorProfileCreationController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\CustomerSubCategoryController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\ULBDetailsController;
use App\Http\Controllers\CompetitorDetailsBranchesController;
use App\Http\Controllers\CustomerCreationBankDetailsController;
use App\Http\Controllers\BidCreationCreationController;
use App\Http\Controllers\CompetitorDetailsTurnOverController;
use App\Http\Controllers\CompetitorDetailsCompanyNetWorthController;
use App\Http\Controllers\CompetitorDetailsLineOfBusinessController;
use App\Http\Controllers\BidCreationCreationDocsController;
use App\Http\Controllers\CompetitorDetailsProsConsController;
use App\Http\Controllers\TenderTypeMasterController;
use App\Http\Controllers\CompetitorDetailsQualityCertificatesController;
use App\Http\Controllers\TenderCreationController;
use App\Http\Controllers\CompetitorDetailsWorkOrderController;
use App\Http\Controllers\AttendanceTypeMasterController;
use App\Http\Controllers\BidManagementWorkOrderMobilizationAdvanceController;
use App\Http\Controllers\BidManagementWorkOrderProjectDetailsController;
use App\Http\Controllers\BidManagementWorkOrderWorkOrderController;
use App\Http\Controllers\BidManagementWorkOrderCommunicationFilesController;
use App\Http\Controllers\BidManagementWorkOrderLetterOfAcceptenceController;
use App\Http\Controllers\BidManagementTenderStatusBiddersController; // replaced this by TenderStatusBiddersController
use App\Http\Controllers\TenderStatusBiddersController; // currently used Controller
use App\Http\Controllers\TenderStatusTechEvaluationController;
use App\Http\Controllers\BidmanagementPreBidQueriesController;
use App\Http\Controllers\BidmanagementCorrigendumPublishController;
use App\Http\Controllers\BidCreationTenderParticipationController;
use App\Http\Controllers\BidCreationTenderFeeController;
use App\Http\Controllers\BidCreationEMDController;
use App\Http\Controllers\BidCreationBidSubmittedStatusController;
use App\Http\Controllers\FileDownloadHandlingController;
use App\Http\Controllers\TenderStatusFinancialEvaluationsController;
use App\Http\Controllers\TenderStatusContractAwardedController;
use App\Http\Controllers\BidManagementTenderOrBidStausController;
use App\Http\Controllers\CommunicationfilesmasterController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
// use App\Models\CompetitorDetailsWorkOrder;
use App\Http\Controllers\CallTypeController;
use App\Http\Controllers\CalltobdmController;
use App\Http\Controllers\ZoneMasterController;
use App\Http\Controllers\BusinessForecastController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\AttendanceEntryController;
use App\Http\Controllers\AttendanceTypeController;
use App\Http\Controllers\CallCreationController;
use App\Http\Controllers\CallLogFilesController;
use App\Http\Controllers\OtherExpenseSubController;
use App\Http\Controllers\OtherExpenseController;
use App\Http\Controllers\CallHistoryController;
use App\Http\Controllers\DayWiseReportController;
use App\Http\Controllers\AttendanceRegisterController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login1', [UserControllerTemp::class, 'login1']);
Route::post('validtetoken', [UserControllerTemp::class, 'validateToken']);
Route::post('getrolesandpermision', [UserControllerTemp::class, 'getRolesAndPermissions']);

Route::post('logout', [UserControllerTemp::class, 'logout']);
Route::post('createState', [UserControllerTemp::class, 'login1']);
Route::get('country/list', [CountryMasterController::class, 'getList']);
Route::get('country/list/{savedcountry}', [CountryMasterController::class, 'getListofcountry']);
Route::get('customersubcategory/list/{profileid}', [CustomerSubCategoryController::class, 'getList']);

Route::get('state/list/{id}', [StateMasterController::class, 'getStateList']);
Route::get('state-list/{id}', [StateMasterController::class, 'getStates']);
Route::get('state/list/{id}/{category}/{savedstate}', [StateMasterController::class, 'getStateListOptions']);
Route::get('unit/list', [UnitMasterController::class, 'getunitList']);
Route::get('state/zonefilteredlist/{cid}/{id}', [StateMasterController::class, 'getZoneFilteredStateList']);

Route::get('tendercreation/list/{id}', [TenderCreationController::class, 'getTenderList']);
Route::get('tendercreation-list/{id}', [TenderCreationController::class, 'getTender']);


// Route::get('customer/list', [CustomerCreationMainController::class, 'getList']);



// Route::get('state/list/{id}', [StateMasterController::class, 'getStateList']);

// Route::get('tendertype/{id}', [TenderTypeMasterController::class, 'show']);
Route::get('tendertype/list', [TenderTypeMasterController::class, 'getList']);
// Route::get('state/list/{id}/{category}/{savedstate}', [StateMasterController::class, 'getStateListOptions']);


Route::get('district/list/{countryid}/{stateid}', [DistrictMasterController::class, 'getDistrictList']);
Route::get('district/list/{countryid}/{stateid}/{saveddistrict}', [DistrictMasterController::class, 'getDistrictListofstate']);
Route::get('city/list/{countryid}/{stateid}/{districtid}/{savedcity}', [CityMasterController::class, 'getCityList']);
Route::get('ulb-list/{savedulb}', [CustomerCreationProfileController::class, 'getUlbs']);
Route::post('customercreationmain/getmainid', [CustomerCreationMainController::class, 'getMainid']);
Route::post('customercreation/profile', [CustomerCreationProfileController::class, 'getProfileFromData']);
Route::get('customercreation/getcustno/{stateid}', [CustomerCreationProfileController::class, 'getCustNo']);
Route::get('customercreation/profile/getFormNo', [CustomerCreationProfileController::class, 'getFormNo']);
Route::post('customer/list', [CustomerCreationProfileController::class, 'getList']);
Route::get('tendercreation/list', [TenderTypeMasterController::class, 'getList']);
Route::get('customerOptions', [CustomerCreationProfileController::class, 'getOptions']);

// Route::get('customercreation/contact/getFormNo', [CustomerCreationContactPersonController::class, 'getFormNo']);
Route::post('customercreationcontact/getlist', [CustomerCreationContactPersonController::class, 'getlist']);
Route::post('customercreationbankdetails/getlist', [CustomerCreationBankDetailsController::class, 'getlist']);
Route::post('customercreationsmwprojectstatus/getlist', [CustomerCreationSWMProjectStatusController::class, 'getlist']);
Route::get('projecttype/list/{profileid}', [ProjectTypeController::class, 'getList']);
Route::get('projecttype/list', [ProjectTypeController::class, 'getListofProjectType']);
Route::get('projectstatus/list/{profileid}', [ProjectStatusController::class, 'getList']);
Route::get('competitorprofile/getcompno/{compid}', [CompetitorProfileCreationController::class, 'getCompNo']);
Route::get('competitorbranch/branchlist/{compid}', [CompetitorDetailsBranchesController::class, 'getbranchList']);
Route::get('competitordetails/turnoverlist/{compid}', [CompetitorDetailsTurnOverController::class, 'getTurnOverList']);
Route::get('competitordetails/networthlist/{compid}', [CompetitorDetailsCompanyNetWorthController::class, 'getNetWorthList']);
Route::get('competitordetails/lineofbusinesslist/{compid}', [CompetitorDetailsLineOfBusinessController::class, 'getLineOfBusinessList']);
Route::get('competitordetails/prosconslist/{compid}', [CompetitorDetailsProsConsController::class, 'getProsConsList']);
Route::get('competitordetails/qclist/{compid}', [CompetitorDetailsQualityCertificatesController::class, 'getQCList']);
Route::post('bidcreation/creation/docupload/list', [BidCreationCreationDocsController::class, 'getUplodedDocList']);
Route::post('bidcreation/creation/docupload/{id}', [BidCreationCreationDocsController::class, 'update']);
Route::get('download/BidDocs/{fileName}', [BidCreationCreationDocsController::class, 'download']);


// Route::post('competitordetails/competitorqcertificate/updatewithimage'
Route::get('competitordetails/wolist/{compid}', [CompetitorDetailsWorkOrderController::class, 'getWOList']);
Route::post('bidcreation/creation/bidlist', [BidCreationCreationController::class, 'getBidList']);

Route::get('moilization/getMobList/{mobId}', [BidManagementWorkOrderMobilizationAdvanceController::class, 'getMobList']);
Route::get('ProjectDetails/getProList/{proid}', [BidManagementWorkOrderProjectDetailsController::class, 'getProList']);

Route::post('bidcreation/prebidqueries/docupload/list', [BidmanagementPreBidQueriesController::class, 'getUplodedDocList']);
Route::get('download/prebidqueriesdocs/{fileName}', [BidmanagementPreBidQueriesController::class, 'download']);
Route::post('bidcreation/prebidqueries/docupload/{id}', [BidmanagementPreBidQueriesController::class, 'update']);

Route::get('workorder/getComList/{comId}', [BidManagementWorkOrderCommunicationFilesController::class, 'getComList']);
Route::get('tenderstatus/getbidder/{id}', [BidManagementTenderStatusBiddersController::class, 'getBidders']);
Route::post('tenderstatus/updatestatus/{id}', [BidManagementTenderStatusBiddersController::class, 'updateStatus']);

Route::post('bidcreation/corrigendumpublish/docupload/list', [BidmanagementCorrigendumPublishController::class, 'getUplodedDocList']);
Route::get('download/corrigendumpublishdocs/{fileName}', [BidmanagementCorrigendumPublishController::class, 'download']);
Route::post('bidcreation/corrigendumpublish/docupload/{id}', [BidmanagementCorrigendumPublishController::class, 'update']);
//brindha updated on 21-01-2023
Route::get('bidcreation/creation/live_tenders', [BidCreationCreationController::class, 'live_tender']);
Route::get('bidcreation/creation/fresh_tenders', [BidCreationCreationController::class, 'fresh_tender']);
Route::get('bidcreation/creation/awarded_tenders', [BidCreationCreationController::class, 'awarded_tenders']);

Route::get('download/tenderfeedocs/{id}', [BidCreationTenderFeeController::class, 'getdocs']);
Route::get('download/emdfeedocs/{id}', [BidCreationEMDController::class, 'getdocs']);
Route::get('download/userfile/{id}', [UserControllerTemp::class, 'getdocs']);
Route::get('download/bidsubmittedstatusdocs/{id}', [BidCreationBidSubmittedStatusController::class, 'getdocs']);
Route::get('download/BidManagementTenderOrBidStausDocs/{id}', [BidManagementTenderOrBidStausController::class, 'getdocs']);


// Route::post('bidcreation/getWorkList/list', [BidmanagementCorrigendumPublishController::class, 'getWorkList']);
Route::get('download/workorderimage/{woid}', [BidManagementWorkOrderWorkOrderController::class, 'wodownload']);
Route::get('download/agreementimage/{agid}', [BidManagementWorkOrderWorkOrderController::class, 'agdownload']);
Route::get('download/sitehandoverimage/{shoid}', [BidManagementWorkOrderWorkOrderController::class, 'shodownload']);
Route::post('workorder/creation/Workorder/update/{workid}', [BidManagementWorkOrderWorkOrderController::class, 'update']);
Route::get('workorder/creation/Workorder/getimagename/{workid}', [BidManagementWorkOrderWorkOrderController::class, 'getimagename']);
Route::post('download/files', [FileDownloadHandlingController::class, 'download']);

Route::get('download/letterofacceptance/workorderimage/{woid}', [BidManagementWorkOrderLetterOfAcceptenceController::class, 'wodownload']);
Route::post('letteracceptance/creation/update/{id}', [BidManagementWorkOrderLetterOfAcceptenceController::class, 'update']);

Route::post('/workorder/creation/communicationfiles/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'store']);
Route::post('/workorder/creation/communicationfileUpload', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfileUpload']);
Route::post('/workorder/creation/communicationfileUploadlist', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfileUploadlist']);
Route::get('/workorder/creation/communicationfiledelete/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'communicationfiledelete']);

Route::get('/competitorprofile/getlastcompno/{id}', [CompetitorProfileCreationController::class, 'getLastCompno']);
Route::get('/competitordetails/commFilesList/{id}', [BidManagementWorkOrderCommunicationFilesController::class, 'getComList']);
Route::get('/download/competitorqcertificate/{id}', [CompetitorDetailsQualityCertificatesController::class, 'download']);
Route::get('/download/competitorworkorder/{id}/{type}', [CompetitorDetailsWorkOrderController ::class, 'download']);

Route::get('/file-import', [ImportCustomerController::class, 'importView'])->name('import-view');

Route::post('/legacystatement', [BidCreationCreationController::class, 'getlegacylist']);

Route::get('/bidcreation/creation/getlastbidno/{id}', [BidCreationCreationController::class, 'getLastBidno']);
Route::get('/customercreation/getstatecode/{id}', [StateMasterController::class, 'getStateCode']);
Route::get('/tendertrack/list', [TenderCreationController::class, 'gettendertrack']);
Route::post('/tendertrack/creation/tracklist', [TenderCreationController::class, 'gettrackList']);
Route::get('tenderstatus/complist', [CompetitorProfileCreationController::class, 'getListOfComp']);
Route::get('bidmanagement/tenderstatus/acceptedbidders/{id}', [TenderStatusBiddersController::class, 'getAcceptedBidders']);
Route::post('tenderstatus/bidderstenderstatus/{id}', [TenderStatusBiddersController::class, 'BiddersTenderStatus']);
Route::get('technicalevalution/qualifiedlist/{id}', [TenderStatusTechEvaluationController::class, 'getQualifiedList']);
Route::get('unitmasters/getUnitList', [UnitMasterController::class, 'getListofUnits']);
Route::get('tenderstatus/techevaluation/{id}', [TenderStatusTechEvaluationController::class, 'getTechEvaluationList']);
Route::get('/tenderstatus/techevaluation/download/{id}', [TenderStatusTechEvaluationController::class, 'download']);
Route::get('/tenderstatus/financialevaluation/getleastbidder/{id}', [TenderStatusFinancialEvaluationsController::class, 'getleastbidder']);
Route::put('tenderstatus/techevaluation/{id}', [TenderStatusTechEvaluationController::class, 'update']);
Route::get('financialevaluation/getstoreddata/{id}',[TenderStatusFinancialEvaluationsController::class,'getStoredFinEvalData']);

Route::get('/tenderstatus/awardontract/download/{id}', [TenderStatusContractAwardedController::class, 'download']);
Route::post('communicationfilesmaster/list', [CommunicationfilesmasterController::class, 'docList']);
Route::delete('communicationfilesmaster/deletedoc/{id}', [CommunicationfilesmasterController::class, 'deletefile']);
Route::get('download/communicationfilesmaster/{id}', [CommunicationfilesmasterController::class, 'download']);

Route::get('/dashboard/getCallCountAnalysis', [CallCreationController::class, 'getCallCountAnalysis']);//Dashborad contents based on bdmcalldetails
Route::get('/dashboard/ulbdetails', [ULBDetailsController::class, 'getulbyearlydetails']);//Dashborad contents based on ulbdetails
Route::get('/dashboard/bidanalysis', [ULBDetailsController::class, 'getbidanalysis']);//Dashborad contents based on ulbdetails
Route::get('/dashboard/tenderanalysis', [ULBDetailsController::class, 'tenderanalysis']);//Dashborad contents based on ulbdetails
Route::get('bidcreation/creation/projectstatus', [BidCreationCreationController::class, 'projectstatus']);// returns running  & completed projects count for dashboard
Route::get('/dashboard/ulbpopdetails', [ULBDetailsController::class, 'getulbpopulationdetails']);//Dashborad contents based on ulbdetails
Route::post('ulbreport/ulblist', [ULBDetailsController::class, 'getulbreport']);//ulb report page  
Route::post('ulbreport/populb', [ULBDetailsController::class, 'setpopupUlb']);//ulb popup page  


Route::post('usertype', [UserTypeController::class, 'store']);
Route::get('usertype', [UserTypeController::class, 'index']);
Route::get('usertype/options', [UserTypeController::class, 'getoptions']);
// Route::get('userOptions', [UserControllerTemp::class, 'getoptions']);
Route::get('bdmoptions', [UserControllerTemp::class, 'getBdmUsersList']);
Route::get('bdmlist', [UserControllerTemp::class, 'getBdmList']);

Route::get('employeelist', [UserControllerTemp::class, 'getEmployeeList']);
Route::post('getbdmdetails', [UserControllerTemp::class, 'getbdmdetails']); // Collecting particular BDM User details, to dispaly BDM name and All
Route::post('filteredcustomerlist', [CustomerCreationProfileController::class, 'getFilteredCustomerList']);


Route::get('usertype/{id}', [UserTypeController::class, 'show']);
Route::put('usertype/{id}', [UserTypeController::class, 'update']);
Route::delete('usertype/{id}', [UserTypeController::class, 'destroy']);

Route::get('menus', [MenuController::class, 'getMenus']);
Route::get('menu/options', [MenuController::class, 'getoptions']);
Route::get('rolehaspermission/{tokenid}', [UserControllerTemp::class, 'getRolehasPermission']);

Route::post('setpermission', [PermissionController::class, 'store']);
Route::get('userpermissions', [PermissionController::class, 'getPermissionList']);
Route::delete('userpermission/{role_id}', [PermissionController::class, 'destroy']);
Route::get('permisions/{usertype}', [PermissionController::class, 'getSavedData']);
Route::get('usertypeOptionsForPermission', [PermissionController::class, 'getoptions']);
Route::get('/calltype/list',[CallTypeController::class, 'getCallTypeList']);

Route::get('bizzlist/list/{id}', [CallCreationController::class, 'getBizzList']);
Route::get('statuslist/list/{id}', [CallCreationController::class, 'getStatusList']);
Route::get('calldownload/{id}/{fileName}', [CallCreationController::class, 'download']);
Route::post('callupload', [CallCreationController::class, 'callfileupload']);
Route::get('user/list', [CallCreationController::class, 'getUserList']);
Route::get('procurementlist/list', [CallCreationController::class, 'getProcurementList']);

Route::get('callcreation/doclist/{id}', [CallLogFilesController::class, 'getUplodedDocList']);
Route::get('callcreation/docdownload/{id}', [CallLogFilesController::class, 'download']);
Route::get('dashboard/callcount', [CallLogFilesController::class, 'getCallCounts']);
Route::post('callcreation/callnolist', [CallCreationController::class, 'usersCallList']);
Route::get('expensetype/list', [ExpenseTypeController::class, 'getExpenseTypeList']);

Route::get('otherexpsubfiledownload/{id}/{fileName}', [OtherExpenseSubController::class, 'download']);
Route::get('callcreation/getCallMainList/{token}', [CallCreationController::class, 'getCallMainList']);
Route::get("getcallhistory/list/{id}",[CallHistoryController::class,'getCallHistory']);
Route::POST("calltobdm/updateAssignedCustomer",[CalltobdmController::class,'updateAssignedCustomer']);
Route::post('getdaywisereport/list',[DayWiseReportController::class,'getDayWiseReport']);

//attendanceregisterroutes
// Route::post('attendanceregister',[AttendanceRegisterController::class,'store']);
 Route::post('attendanceregister/fileList',[AttendanceRegisterController::class,'getFilesList']);
// Route::get('attendanceregister/{id}',[AttendanceRegisterController::class,'show']);
// Route::put('attendanceregister/{id}', [AttendanceRegisterController::class,'update']);
// Route::delete('attendanceregister/{id}',[AttendanceRegisterController::class,'destroy']);
Route::get('userlist', [AttendanceRegisterController::class,'UserList']);
Route::get('attendancetypelist', [AttendanceTypeController::class,'getAttendanceTypeList']);
Route::get('attendancefile/{id}/{fileName}', [AttendanceRegisterController::class, 'download']);
Route::delete('destroyfile/{id}',[AttendanceRegisterController::class,'destroyFile']);
Route::post('getempleave/list',[AttendanceRegisterController::class,'getEmployeeLeaveList']);//For Attendance Report


/*********************************
 * other expesive Naveen
 */
Route::get('expensetype/list', [ExpenseTypeController::class, 'getExpenseTypeList']);
Route::get('otherexpsubfiledownload/{id}/{fileName}', [OtherExpenseSubController::class, 'download']);
Route::get('callcreation/getCallMainList/{token}', [CallCreationController::class, 'getCallMainList']);     
Route::get('customernamelist', [ExpenseTypeController::class, 'customerNameList']);
Route::post('callnumber', [ExpenseTypeController::class, 'CallNumber']);
Route::get('expansetypelist/{expid}', [ExpenseTypeController::class, 'ExpanseTypeList']);
Route::post('fileupload/{id}', [ExpenseTypeController::class, 'Fileupload']);
Route::post('/expensestore', [ExpenseTypeController::class, 'Expensestore']);
Route::post('/expenseinv', [ExpenseTypeController::class, 'ExpInvoice']);
Route::get('expenseshow/{id}',[ExpenseTypeController::class,'Expenseshow']);
Route::get('downloadfile/{filename}', [ExpenseTypeController::class,'downloadFile']);
Route::post('expenseshowupdate/{id}',[ExpenseTypeController::class,'Expenseshowupdate']);
Route::delete('expensedestroy/{id}',[ExpenseTypeController::class,'Expensedestroy']);
Route::post('/expensesub', [ExpenseTypeController::class, 'ExpSub']);
Route::post('/editsub', [ExpenseTypeController::class, 'EditSub']);
Route::post('/subupdate', [ExpenseTypeController::class, 'SubUpdate']);
Route::get('/otherexpensesubdel/{id}', [ExpenseTypeController::class, 'Expensedestroy']);
Route::post('/mainlist', [ExpenseTypeController::class, 'Mainlist']);
Route::get('/updatedl/{id}', [ExpenseTypeController::class, 'GetDel']);
Route::delete('deleteMain/{id}',[ExpenseTypeController::class,'deleteMain']);
Route::post('/finalSubmit', [ExpenseTypeController::class, 'finalSubmit']);
Route::post('expenses/staffList', [ExpenseTypeController::class, 'get_staff_name_limits']);
Route::post('/getlimit', [ExpenseTypeController::class, 'lmitAmount']);
/******************************* */

Route::post('expensesapp/expapp', [ExpensesApprovalController::class, 'index']);
Route::post('expensesapp/staffList', [ExpensesApprovalController::class, 'get_staff_name']);
Route::post('expensesapp/getsublist', [ExpensesApprovalController::class, 'showsub']);
Route::post('expensesapp/storeData', [ExpensesApprovalController::class, 'store']);
Route::post('expensesapp/popupsub', [ExpensesApprovalController::class, 'popupsub']);
Route::post('expensesapp/UpdateApproval', [ExpensesApprovalController::class, 'UpdateApproval']);



/*
## Resource Laravel Routes Example
Route::post(['ulb',[UlbMasterController::class,'store']]);//
Route::get(['ulb/{id}',[UlbMasterController::class,'show']]);
Route::get(['ulb/edit/{id}',[UlbMasterController::class,'edit']]);//
Route::put/patch(['ulb/{id}',[UlbMasterController::class,'update']]);
## put=>If the record exists then update else create a new record
## Patch =>update/modify
Route::delete(['ulb/{id}',[UlbMasterController::class,'destroy']]);
*/

Route::resources([
    'ulb' => UlbMasterController::class,
    'state' => StateMasterController::class,
    'country' => CountryMasterController::class,
    'tendertype' => TenderTypeMasterController::class,
    'unit' => UnitMasterController::class,
    'tendercreation' => TenderCreationController::class,
    'city' => CityMasterController::class,
    'district' => DistrictMasterController::class,
    'customercreationmain' => CustomerCreationMainController::class,
    'customercreationprofile' => CustomerCreationProfileController::class,
    'customercreationcontact' => CustomerCreationContactPersonController::class,
    'customercreationsmwprojectstatus' => CustomerCreationSWMProjectStatusController::class,
    'competitorprofile' => CompetitorProfileCreationController::class,
    'competitorbranch' => CompetitorDetailsBranchesController::class,
    'competitorturnover' => CompetitorDetailsTurnOverController::class,
    'competitornetworth' => CompetitorDetailsCompanyNetWorthController::class,
    'competitorlineofbusiness' => CompetitorDetailsLineOfBusinessController::class,
    'competitorproscons' => CompetitorDetailsProsConsController::class,
    'competitorqcertificate' => CompetitorDetailsQualityCertificatesController::class,
    'competitorworkorder' => CompetitorDetailsWorkOrderController::class,
    'projecttype' => ProjectTypeController::class,
    'customersubcategory' => CustomerSubCategoryController::class,
    'projectstatus' => ProjectStatusController::class,
    'customercreationulbdetails' => ULBDetailsController::class,
    'customercreationbankdetails' => CustomerCreationBankDetailsController::class,
    'bidcreation/creation' => BidCreationCreationController::class,
    'bidcreation/creation/docupload' => BidCreationCreationDocsController::class,
    'tenderstatus' => BidManagementTenderStatusBiddersController::class,
    'workorder/creation/communicationfiles' => BidManagementWorkOrderCommunicationFilesController::class,
    'communicationfiles/docupload' => CommunicationDocController::class,
    'mobilization/creation' => BidManagementWorkOrderMobilizationAdvanceController::class,
    'ProjectDetails/Creation' => BidManagementWorkOrderProjectDetailsController::class,
    'workorder/creation/Workorder' => BidManagementWorkOrderWorkOrderController::class,
    'bidcreation/prebidqueries/docupload' => BidmanagementPreBidQueriesController::class,
    'bidcreation/corrigendumpublish/docupload' => BidmanagementCorrigendumPublishController::class,
    'bidcreation/tenderparticipation' => BidCreationTenderParticipationController::class,
    'bidcreation/bidsubmission/tenderfee' => BidCreationTenderFeeController::class,
    'bidcreation/bidsubmission/emdfee' => BidCreationEMDController::class,
    'bidcreation/bidsubmission/bidsubmittedstatus' => BidCreationBidSubmittedStatusController::class,
    'letteracceptance/creation' => BidManagementWorkOrderLetterOfAcceptenceController::class,
    'tenderstatus/techevaluation' => TenderStatusTechEvaluationController::class,
    'financialevaluation' => TenderStatusFinancialEvaluationsController::class,
    'bigmanagement/tenderstatus/status' => BidManagementTenderOrBidStausController::class, 
    'tenderstatusbidders' => TenderStatusBiddersController::class,
    'tenderstatus/awardcontract' => TenderStatusContractAwardedController::class,
    'attendanceTypeMaster' => AttendanceTypeMasterController::class,
    'attendanceentry' => AttendanceEntryController::class,
    'communicationfilesmaster' => CommunicationfilesmasterController::class,
    'usercreation' => UserControllerTemp::class,
    'calltype' => CallTypeController::class,
    'calltobdm' => CalltobdmController::class,
    'bizzforecast' => BusinessForecastController::class,
    'zonemaster' => ZoneMasterController::class,
    'expensetype' => ExpenseTypeController::class,
    'attendancetype'=> AttendanceTypeController::class,
    'callcreation' => CallCreationController::class,
    'callfileupload'=> CallLogFilesController::class,
    'callhistory'=> CallHistoryController::class,
    'otherexpense' => OtherExpensesController::class,
    'otherexpensesub' => OtherExpenseSubController::class,
    'attendanceregister'=>AttendanceRegisterController::class,
]);




//File uplaod Default location has been set by below line in config/filesystems.php file
//'root' => public_path()."/uploads",

//Can create a new folder inside public/uploads path
//$file->storeAs('competitor/qc', $fileName, 'public');  
