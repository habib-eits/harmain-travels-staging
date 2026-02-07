<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Http\Controllers\User;

use App\Http\Controllers\Accounts;
 use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// CRM CONTROLLERS
use App\Http\Controllers\PnrController;

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ChartOfAccount;
use App\Http\Controllers\LeadController;
 use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\StaffController;
 use App\Http\Controllers\BranchController;
use App\Http\Controllers\UmrahController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\ShirkaController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\ProvinceController;


use App\Http\Controllers\SubServiceController;
use App\Http\Controllers\VisaDetailController;
use App\Http\Controllers\VisaMasterController;
use App\Http\Controllers\VisaTariffController;
use App\Http\Controllers\HotelTariffController;
use App\Http\Controllers\LeadActivityController;
use App\Http\Controllers\VoucherContentController;
use App\Http\Controllers\TransportTariffController;
use App\Http\Controllers\UmrahInvoiceHotelController;
use App\Http\Controllers\TravelProReportingController;
use App\Http\Controllers\UmrahInvoiceMasterController;
use App\Http\Controllers\UmrahInvoicePassengerController;
use App\Http\Controllers\UmrahInvoiceTransportController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\GroupTicketPurchaseController;
use App\Http\Controllers\GroupTicketSaleController;
use App\Http\Controllers\PartyController;

Route::get('/search-party', function () {
    $search = request('q');
    $parties = DB::table('party')
        ->where('PartyName', 'LIKE', "%{$search}%")
        ->orWhere('Phone', 'LIKE', "%{$search}%")
        ->limit(10)
        ->get(['PartyID', 'PartyName', 'Phone']); // Limit the results for efficiency
    return response()->json($parties);
});

Route::get('/',[Accounts::class,'Login']);
Route::get('/Login',[Accounts::class,'Login']);
Route::post('/UserVerify',[Accounts::class,'UserVerify']);


Route::group(['middleware' => ['CheckAdmin']], function () {

	Route::view('/projects/create', 'projects.create');
	Route::view('/projects/form', 'projects.form');
	Route::post('projects/media', [Controller::class, 'storeMedia'])->name('projects.storeMedia');
	Route::post('projects/store', [Controller::class, 'store'])->name('projects.store');
	Route::get('projects/index', [Controller::class, 'index'])->name('projects.index');
	Route::get('projects/documents/{document}', [Controller::class, 'getDocument'])->name('projects.getDocument');


	Route::get('paymentSummary',[Accounts::class,'paymentSummary'] );
	route::post('/paymentSummary1/',[Accounts::class,'paymentSummary1']);


	Route::resource('parties', PartyController::class);






 
Route::get('/Dashboard/{branch_id?}',[Accounts::class,'Dashboard']);

Route::get('/AdminDashboard', AdminDashboard::class,'AdminDashboard');


Route::get('/get_specific_invoice/{id}', [Accounts::class, 'GetSpecificInvoice']);
Route::get('/get_specific_invoice1/{id}', [Accounts::class, 'GetSpecificInvoice1']);
Route::get('/Invoice',[Accounts::class,'Invoice']);

Route::get('/InvoiceCreate',[Accounts::class,'InvoiceCreate']);
Route::get('/ajax_invoice',[Accounts::class,'ajax_invoice']);
Route::get('/ajax_accounts_by_category', [Accounts::class, 'ajax_getAccountsByCategory']);
Route::get('/ajax_get_voucher_number', [Accounts::class, 'ajax_getVoucherNumber']);
// Route::post('/model-voucher-save',[Accounts::class, 'modelVoucherSave']);
Route::post('/modelVoucherSave', [Accounts::class, 'modelVoucherSave']);
route::get('/InvoicePDFView/{id}/{download?}',[Accounts::class,'InvoicePDFView']);


Route::post('/InvoiceSave',[Accounts::class,'InvoiceSave']);

route::get('/InvoiceRefund/{id}',[Accounts::class,'InvoiceRefund']);

route::get('/InvoiceEdit/{id}',[Accounts::class,'InvoiceEdit']);

route::get('/InvoiceUnlock/{id}',[Accounts::class,'InvoiceUnlock']);
route::get('/Invoicelock/{id}',[Accounts::class,'Invoicelock']);

route::get('/InvoiceView/{id}',[Accounts::class,'InvoiceView']);

route::get('/InvoicePDF/{id}/{download?}',[Accounts::class,'InvoicePDF']);


Route::get('/ItemWiseSale/',[Accounts::class,'ItemWiseSale']);
Route::post('/ItemWiseSale2/',[Accounts::class,'ItemWiseSale2']);
Route::get('/ItemWiseSale1/',[Accounts::class,'ItemWiseSale1']);

Route::post('/ajax_party_validate',[Accounts::class,'ajax_party_validate']);
Route::post('/ajax_party_save',[Accounts::class,'ajax_party_save']);
Route::post('/ajax_party_save2',[Accounts::class,'ajax_party_save2']);

Route::post('/InvoiceUpdate',[Accounts::class,'InvoiceUpdate']);
route::get('/InvoiceDelete/{id}',[Accounts::class,'InvoiceDelete']);
Route::post('/Ajax_Balance',[Accounts::class,'Ajax_Balance']);

Route::post('/Ajax_Balance_party',[Accounts::class,'Ajax_Balance_party']);
Route::post('/Ajax_VHNO',[Accounts::class,'Ajax_VHNO']);

Route::get('/Voucher',[Accounts::class,'Voucher']);
Route::get('/VoucherCreate/{vouchertype}',[Accounts::class,'VoucherCreate']);

Route::post('/VoucherSave',[Accounts::class,'VoucherSave']);
Route::get('/ajax_voucher',[Accounts::class,'ajax_voucher']);
Route::get('/VoucherEdit/{id}',[Accounts::class,'VoucherEdit']);
Route::post('/VoucherUpdate',[Accounts::class,'VoucherUpdate']);
Route::get('/VoucherDelete/{id}',[Accounts::class,'VoucherDelete']);
Route::get('/VoucherView/{id}',[Accounts::class,'VoucherView']);
Route::get('/VoucherView2/{id}',[Accounts::class,'VoucherView2']);

Route::get('/JV/',[Accounts::class,'JV']);
Route::post('/JVSave/',[Accounts::class,'JVSave']);



Route::get('/Item',[Accounts::class,'Item']);
Route::post('/ItemSave',[Accounts::class,'ItemSave']);
Route::get('/ItemEdit/{id}',[Accounts::class,'ItemEdit']);
Route::post('/ItemUpdate/',[Accounts::class,'ItemUpdate']);
Route::get('/ItemDelete/{id}',[Accounts::class,'ItemDelete']); 

Route::get('/BalanceSheetDetail/{ChartOfAccountID}/{StartDat}/{EndDate}',[Accounts::class,'BalanceSheetDetail']);

Route::get('/JournalEntries/{ChartOfAccountID}/{StartDat}/{EndDate}',[Accounts::class,'JournalEntries']);


Route::get('/User',[User::class,'Show']);
Route::post('/UserSave',[User::class,'UserSave']);
Route::get('/UserEdit/{id}',[User::class,'UserEdit']);
Route::post('/UserUpdate/',[User::class,'UserUpdate']);
Route::get('/UserDelete/{id}',[User::class,'UserDelete']); 



Route::get('/Supplier',[Accounts::class,'Supplier']);
Route::post('/SaveSupplier',[Accounts::class,'SaveSupplier']);
Route::get('/SupplierEdit/{id}',[Accounts::class,'SupplierEdit']);
Route::post('/SupplierUpdate/',[Accounts::class,'SupplierUpdate']);
Route::get('/SupplierDelete/{id}',[Accounts::class,'SupplierDelete']);


// Route::get('/Parties',[Accounts::class,'Parties']);
// Route::post('/SaveParties',[Accounts::class,'SaveParties']);
// Route::get('/PartiesEdit/{id}',[Accounts::class,'PartiesEdit']);
// Route::post('/PartiesUpdate/',[Accounts::class,'PartiesUpdate']);
// Route::get('/PartiesDelete/{id}',[Accounts::class,'PartiesDelete']);

Route::get('/ExpenseDetail/',[Accounts::class,'ExpenseDetail']);

Route::get('/CheckUserRole1/{userid},{tablename},{action}',[Accounts::class,'CheckUserRole1']);




Route::get('/table',[Accounts::class,'table']);
Route::get('/datatable',[Accounts::class,'datatable']);


// ===================Expense Section=====================================
Route::get('/Expense',[Accounts::class,'Expense']);
route::get('/ExpenseCreate/',[Accounts::class,'ExpenseCreate']);
Route::get('/ajax_Expense',[Accounts::class,'ajax_Expense']);

Route::post('/ExpenseSave',[Accounts::class,'ExpenseSave']);
route::get('/ExpenseEdit/{id}',[Accounts::class,'ExpenseEdit']);
route::get('/ExpensePDF/{id}',[Accounts::class,'ExpensePDF']);
route::get('/ExpenseView/{id}',[Accounts::class,'ExpenseView']);
route::get('/ExpenseViewPDF/{id}',[Accounts::class,'ExpenseViewPDF']);

Route::post('/ExpenseUpdate',[Accounts::class,'ExpenseUpdate']);
route::get('/ExpenseDelete/{id}',[Accounts::class,'ExpenseDelete']); 


route::get('/ExpenseReport/',[Accounts::class,'ExpenseReport']); 
Route::post('/ExpenseReport1',[Accounts::class,'ExpenseReport1']);

// end of expense section routes==============================================

// Petty Cash

Route::get('/PettyCashCreate',[Accounts::class,'PettyCashCreate']);
Route::get('/PettyCash',[Accounts::class,'PettyCash']);
Route::get('/ajax_pettycash',[Accounts::class,'ajax_pettycash']);
Route::post('/PettyCashSave',[Accounts::class,'PettyCashSave']);
route::get('/PettyCashEdit/{id}',[Accounts::class,'PettyCashEdit']);
Route::post('/PettyCashUpdate',[Accounts::class,'PettyCashUpdate']);
route::get('/PettyCashDelete/{id}',[Accounts::class,'PettyCashDelete']);
Route::post('/Ajax_PVHNO',[Accounts::class,'Ajax_PVHNO']);


// Route::get('/ChartOfAcc/',[Accounts::class,'ChartOfAcc']);

Route::get('/ChartOfAcc/',[ChartOfAccount::class,'ChartOfAcc']);
route::post('/ChartOfAccountSave/',[ChartOfAccount::class,'ChartOfAccountSave']);
route::post('/ChartOfAccountSaveL3/',[ChartOfAccount::class,'ChartOfAccountSaveL3']);
route::get('/ChartOfAccountDelete/{ChartOfAccountID}',[ChartOfAccount::class,'ChartOfAccountDelete']);
route::get('/ChartOfAccountEdit/{id}',[ChartOfAccount::class,'ChartOfAccountEdit']);
route::post('/ChartOfAccountUpdate/',[ChartOfAccount::class,'ChartOfAccountUpdate']);




route::get('/PartyLedger/',[Accounts::class,'PartyLedger']);
route::post('/PartyLedger1/',[Accounts::class,'PartyLedger1']);
route::post('/PartyLedger1PDF/',[Accounts::class,'PartyLedger1PDF']);
route::get('/PartySalesLedger3/{PartyID}',[Accounts::class,'PartySalesLedger3']);

Route::get('/PartyBalanceList/',[Accounts::class,'PartyBalanceList']);

route::get('/SupplierLedger/',[Accounts::class,'SupplierLedger']);
route::get('/AdjustmentBalance/',[Accounts::class,'AdjustmentBalance']);
route::post('/AdjustmentBalanceSave/',[Accounts::class,'AdjustmentBalanceSave']);

route::get('/SupplierBalance/',[Accounts::class,'SupplierBalance']);
route::post('/SupplierBalance1/',[Accounts::class,'SupplierBalance1']);
route::post('/SupplierBalance1PDF/',[Accounts::class,'SupplierBalance1PDF']);


route::get('/PartyList/',[Accounts::class,'PartyList']);
route::get('/PartyListPDF/',[Accounts::class,'PartyListPDF']);
route::get('/OutStandingInvoice/',[Accounts::class,'OutStandingInvoice']);
route::post('/OutStandingInvoice1/',[Accounts::class,'OutStandingInvoice1']);
route::post('/OutStandingInvoice1PDF/',[Accounts::class,'OutStandingInvoice1PDF']);


route::get('/PartyWiseSale/',[Accounts::class,'PartyWiseSale']);
route::post('/PartyWiseSale1/',[Accounts::class,'PartyWiseSale1']);
route::post('/PartyWiseSale1PDF/',[Accounts::class,'PartyWiseSale1PDF']);

route::get('/YearlyPartyBalance/',[Accounts::class,'YearlyPartyBalance']);
route::post('/YearlyPartyBalance1/',[Accounts::class,'YearlyPartyBalance1']);




route::get('/PartyBalance/',[Accounts::class,'PartyBalance']);
route::post('/PartyBalance1/',[Accounts::class,'PartyBalance1']);
route::post('/PartyBalance1PDF/',[Accounts::class,'PartyBalance1PDF']);

route::get('/PartyYearlyBalance/',[Accounts::class,'PartyYearlyBalance']);
route::post('/PartyYearlyBalance1/',[Accounts::class,'PartyYearlyBalance1']);
route::post('/PartyYearlyBalance1PDF/',[Accounts::class,'PartyYearlyBalance1PDF']);


// supplier reports

route::get('/SupplierLedger/',[Accounts::class,'SupplierLedger']);
route::post('/SupplierLedger1/',[Accounts::class,'SupplierLedger1']);
route::post('/SupplierLedger1PDF/',[Accounts::class,'SupplierLedger1PDF']);

route::post('/SupplierLedgerExcelExport/',[Accounts::class,'SupplierLedgerExcelExport']);
route::post('/PartyLedgerExcelExport/',[Accounts::class,'PartyLedgerExcelExport']);//-----------------

route::get('/SupplierWiseSale/',[Accounts::class,'SupplierWiseSale']);
route::post('/SupplierWiseSale1/',[Accounts::class,'SupplierWiseSale1']);
route::post('/SupplierWiseSale1PDF/',[Accounts::class,'SupplierWiseSale1PDF']);

route::get('/TaxReport/',[Accounts::class,'TaxReport']);
route::post('/TaxReport1/',[Accounts::class,'TaxReport1']);
route::post('/TaxReport1PDF/',[Accounts::class,'TaxReport1PDF']);

route::get('/SalemanInvoiceBalance/',[Accounts::class,'SalemanInvoiceBalance']);
route::post('/SalemanInvoiceBalance1/',[Accounts::class,'SalemanInvoiceBalance1']);
route::get('/SalemanInvoiceList/{user}/{start}/{end}/',[Accounts::class,'SalemanInvoiceList']);


route::get('/SalemanReport/',[Accounts::class,'SalemanReport']);
route::post('/SalemanReport1/',[Accounts::class,'SalemanReport1']);
route::post('/SalemanReport1PDF/',[Accounts::class,'SalemanReport1PDF']);

route::get('/AirlineSummary/',[Accounts::class,'AirlineSummary']);
route::post('/AirlineSummary1/',[Accounts::class,'AirlineSummary1']);
route::post('/AirlineSummary1PDF/',[Accounts::class,'AirlineSummary1PDF']);

// accounts report

route::get('/VoucherReport/',[Accounts::class,'VoucherReport']);
route::post('/VoucherReport1/',[Accounts::class,'VoucherReport1']);
route::post('/VoucherReport1PDF/',[Accounts::class,'VoucherReport1PDF']);

route::get('/CashbookReport/',[Accounts::class,'CashbookReport']);
route::post('/CashbookReport1/',[Accounts::class,'CashbookReport1']);
route::post('/CashbookReport1PDF/',[Accounts::class,'CashbookReport1PDF']);

route::get('/DaybookReport/',[Accounts::class,'DaybookReport']);
route::post('/DaybookReport1/',[Accounts::class,'DaybookReport1']);
route::post('/DaybookReport1PDF/',[Accounts::class,'DaybookReport1PDF']);


route::get('/GeneralLedger/',[Accounts::class,'GeneralLedger']);
route::post('/GeneralLedger1/',[Accounts::class,'GeneralLedger1']);
route::post('/GeneralLedger1PDF/',[Accounts::class,'GeneralLedger1PDF']);

route::get('/TrialBalance/',[Accounts::class,'TrialBalance']);
route::post('/TrialBalance1/',[Accounts::class,'TrialBalance1']);
route::post('/TrialBalance1PDF/',[Accounts::class,'TrialBalance1PDF']);


route::get('/TrialBalanceActivity/',[Accounts::class,'TrialBalanceActivity']);
route::post('/TrialBalanceActivity1/',[Accounts::class,'TrialBalanceActivity1']);
route::post('/TrialBalanceActivity1PDF/',[Accounts::class,'TrialBalanceActivity1PDF']);

route::get('/BalanceSheet/',[Accounts::class,'BalanceSheet']);
route::post('/BalanceSheet1/',[Accounts::class,'BalanceSheet1']);


route::get('/TicketRegister/',[Accounts::class,'TicketRegister']);
route::post('/TicketRegister1/',[Accounts::class,'TicketRegister1']);
route::post('/TicketRegister1PDF/',[Accounts::class,'TicketRegister1PDF']);


Route::get('/SalemanTicketRegister/',[Accounts::class,'SalemanTicketRegister']);
Route::post('/SalemanTicketRegister1/',[Accounts::class,'SalemanTicketRegister1']);


route::get('/InvoiceSummary/',[Accounts::class,'InvoiceSummary']);
route::post('/InvoiceSummary1/',[Accounts::class,'InvoiceSummary1']);
route::post('/InvoiceSummary1PDF/',[Accounts::class,'InvoiceSummary1PDF']);

route::get('/ProfitAndLoss/',[Accounts::class,'ProfitAndLoss']);
route::post('/ProfitAndLoss1/',[Accounts::class,'ProfitAndLoss1']);


route::get('/ReconcileReport/',[Accounts::class,'ReconcileReport']);
route::post('/ReconcileReport1/',[Accounts::class,'ReconcileReport1']);

route::get('/ReconcileUpdate/{status}/{id}',[Accounts::class,'ReconcileUpdate']);

Route::post('/ReconcileStatus',[Accounts::class,'Ajax_ReconcileStatus']);


Route::get('/Salesman/',[Accounts::class,'Salesman']);
Route::post('/SalesmanSave/',[Accounts::class,'SalesmanSave']);
Route::get('/SalesmanEdit/{id}',[Accounts::class,'SalesmanEdit']);
Route::post('/SalesmanUpdate/',[Accounts::class,'SalesmanUpdate']);
Route::get('/SalesmanDelete/{id}',[Accounts::class,'SalesmanDelete']);


// ..............attachment iframe for all invoices ......
Route::get('/Attachment/{vhno?}', [Accounts::class, 'Attachment']);
Route::post('AttachmentSave', [Accounts::class, 'AttachmentSave']);
Route::get('AttachmentDelete/{id}/{filename}', [Accounts::class, 'AttachmentDelete']);
Route::get('AttachmentRead', [Accounts::class, 'AttachmentRead']);





 





 

route::get('/tmp/',[Accounts::class,'tmp']); 

Route::get('/Logout',[Accounts::class,'Logout']);

Route::get('/upload/', function (){

	return view ('upload');
});

 route::post('/upload1',[Accounts::class,'upload1']);


route::get('/Role/{UserID}',[Accounts::class,'Role']);
 route::post('/RoleSave',[Accounts::class,'RoleSave']);
 route::get('/RoleView/{UserID}',[Accounts::class,'RoleView']);
 route::post('/RoleUpdate',[Accounts::class,'RoleUpdate']);

 route::get('/checkUserRole/{UserID}',[Accounts::class,'checkUserRole']);


 route::get('/UserProfile',[Accounts::class,'UserProfile']);
 route::get('/ChangePassword',[Accounts::class,'ChangePassword']);
 route::post('/UpdatePassword',[Accounts::class,'UpdatePassword']);


Route::get('/ajax_party_list/',[Accounts::class,'ajax_party_list']);


// ----------------------- CRM ROUTES -----------------------------

// CRM LINKS

     // --------------------------------Compaign Routes----------------------------
	 Route::get('campaigns', [CampaignController::class, 'index'])->name('campaign.index');
	 Route::post('campaignCreate', [CampaignController::class, 'store'])->name('campaign.store');
	 Route::get('campaignEdit/{id}', [CampaignController::class, 'edit'])->name('campaign.edit');
	 Route::post('campaignUpdate', [CampaignController::class, 'update'])->name('campaign.update');
	 Route::get('campaignDelete/{id}', [CampaignController::class, 'delete'])->name('campaign.delete');
 
	  // --------------------------------Branch Routes----------------------------
	 Route::get('branches', [BranchController::class, 'index'])->name('branch.index');
	 // Route::get('createbranch', [BranchController::class, 'create'])->name('branch.create');
	 Route::post('storebranch', [BranchController::class, 'store'])->name('branch.store');
	 Route::get('branchEdit/{id}', [BranchController::class, 'edit'])->name('branch.edit');
	 Route::post('branchUpdate', [BranchController::class, 'update'])->name('branch.update');
	 Route::get('branchDelete/{id}', [BranchController::class, 'delete'])->name('branch.delete');
 
	 // --------------------------------Lead Routes----------------------------
	 Route::get('leads', [LeadController::class, 'index'])->name('lead.index');
	 Route::get('createlead', [LeadController::class, 'create'])->name('lead.create');
	 Route::post('storelead', [LeadController::class, 'store'])->name('lead.store');
	 Route::get('viewlead/{id}', [LeadController::class, 'show'])->name('lead.show');
	 Route::get('editlead/{id}', [LeadController::class, 'edit'])->name('lead.edit');
	 Route::post('updatelead/{id}', [LeadController::class, 'update'])->name('lead.update');
	 Route::post('addLeadNote/', [LeadController::class, 'addLeadNote'])->name('lead.addNote');
	 Route::get('leadDelete/{id}', [LeadController::class, 'delete'])->name('lead.delete');
	 Route::post('bulkDeleteLeads', [LeadController::class, 'bulkDeleteLeads'])->name('lead.bulkDelete');
	 Route::post('bulkReassignLeads', [LeadController::class, 'bulkReassignLeads'])->name('lead.bulkReassign');
	 Route::post('bulkReassignNewLeads', [LeadController::class, 'bulkReassignNewLeads'])->name('lead.bulkReassignNew');
	 Route::post('importlead', [LeadController::class, 'import'])->name('lead.import');
	 Route::get('download/{file}', [LeadController::class, 'downloadFile'])->name('downloadFile');


	 Route::get('/fetch-leads', [LeadController::class, 'fetchLeads'])->name('fetch-leads');



	 Route::get('ajaxGetAgents/{id?}', [AjaxController::class, 'ajaxGetAgents']);
 
 
	 Route::get('ajaxGetLeads/', [AjaxController::class, 'ajaxGetLeads'])->name('check-database');
	 Route::get('ajaxGetBookingPayment/', [AjaxController::class, 'ajaxGetBookingPayment'])->name('check-booking');
 
	 // --------------------------------Lead Activity Routes----------------------------
 
	 Route::resource('lead-activity', LeadActivityController::class);
 
 
 
 
	 // --------------------------------Staff Routes----------------------------
	 Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
	 // Route::get('createstaffmember', [StaffController::class, 'create'])->name('staff.create');
	 Route::post('storestaffmember', [StaffController::class, 'store'])->name('staff.store');
	 Route::get('staffMemberEdit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
	 Route::post('staffMemberUpdate', [StaffController::class, 'update'])->name('staff.update');
	 Route::get('staffMemberDelete/{id}', [StaffController::class, 'delete'])->name('staff.delete');
 
 
	 // --------------------------------Services Routes----------------------------
	 Route::get('services', [ServiceController::class, 'index'])->name('service.index');
	 Route::post('serviceCreate', [ServiceController::class, 'store'])->name('service.store');
	 Route::get('serviceEdit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
	 Route::post('serviceUpdate', [ServiceController::class, 'update'])->name('service.update');
	 Route::get('serviceDelete/{id}', [ServiceController::class, 'delete'])->name('service.delete');
	 Route::get('ajaxGetServices/{id?}', [AjaxController::class, 'ajaxGetServices']);
	
	 // --------------------------------SubServices Routes----------------------------
	 Route::get('subServices', [SubServiceController::class, 'index'])->name('subService.index');
	 Route::post('subServiceCreate', [SubServiceController::class, 'store'])->name('subService.store');
	 Route::get('subServiceEdit/{id}', [SubServiceController::class, 'edit'])->name('subService.edit');
	 Route::post('subServiceUpdate', [SubServiceController::class, 'update'])->name('subService.update');
	 Route::get('subServiceDelete/{id}', [SubServiceController::class, 'delete'])->name('subService.delete');
	 Route::get('ajaxGetSubservices/{id?}', [AjaxController::class, 'ajaxGetSubservices']);
 
	 // --------------------------------Status Routes----------------------------
	 Route::get('statuses', [StatusController::class, 'index'])->name('status.index');
	 Route::post('statusCreate', [StatusController::class, 'store'])->name('status.store');
	 Route::get('statusEdit/{id}', [StatusController::class, 'edit'])->name('status.edit');
	 Route::post('statusUpdate', [StatusController::class, 'update'])->name('status.update');
	 Route::get('statusDelete/{id}', [StatusController::class, 'delete'])->name('status.delete');
 
	   // --------------------------------Qualified Status Routes----------------------------
	 Route::get('qualifiedStatuses', [StatusController::class, 'qualifiedStatusIndex'])->name('qualifiedStatus.index');
	 Route::post('qualifiedStatusCreate', [StatusController::class, 'qualifiedStatusStore'])->name('qualifiedStatus.store');
	 Route::get('qualifiedStatusEdit/{id}', [StatusController::class, 'qualifiedStatusEdit'])->name('qualifiedStatus.edit');
	 Route::post('qualifiedStatusUpdate', [StatusController::class, 'qualifiedStatusUpdate'])->name('qualifiedStatus.update');
	 Route::get('qualifiedStatusDelete/{id}', [StatusController::class, 'qualifiedStatusDelete'])->name('qualifiedStatus.delete');
 
	 Route::get('/Booking', [BookingController::class, 'index']);
	 Route::get('/calendar', [BookingController::class, 'calendar']);
 
	 Route::get('/ajax_booking', [BookingController::class, 'ajax_booking']);
	 Route::get('/BookingCreate/{id}', [BookingController::class, 'BookingCreate']);
	 Route::post('/BookingSave', [BookingController::class, 'BookingSave']);
 
	 Route::post('/BookingStore', [BookingController::class, 'store']);
	 Route::post('/BookingUpdate', [BookingController::class, 'update']);
	 Route::get('/BookingDelete/{id}', [BookingController::class, 'destroy']);
	 Route::post('/BookingDraged/', [BookingController::class, 'BookingDraged']);
 
	 Route::get('/BookingEdit/{id}', [BookingController::class, 'BookingEdit']);
	 Route::post('/BookingUpdate1', [BookingController::class, 'BookingUpdate1']);
 
	 Route::get('/BookingPayment/',[BookingController::class,'BookingPayment']);
	 Route::get('/BookingStatus/{id}',[BookingController::class,'BookingStatus']);
	 Route::get('/BookingView/{id}',[BookingController::class,'BookingView']);
	 Route::get('/InvoiceDetailList/{itemid}/{startdate}/{enddate}',[Accounts::class,'InvoiceDetailList']);

Route::get('/query/',[Accounts::class,'query']); 

Route::get('/Log/',[Accounts::class,'Log']);
Route::post('/Log1/',[Accounts::class,'Log1']);


// ..............Estimate.............
route::get('/Estimate/',[EstimateController::class,'Estimate']);

route::get('/EstimateCreate/',[EstimateController::class,'EstimateCreate']);

route::post('/EstimateSave/',[EstimateController::class,'EstimateSave']);
route::get('/ajax_estimate/',[EstimateController::class,'ajax_estimate']);

route::get('/EstimateDelete/{id}',[EstimateController::class,'EstimateDelete']);
route::get('/EstimateView/{id}',[EstimateController::class,'EstimateView']);
route::get('/EstimateEdit/{id}',[EstimateController::class,'EstimateEdit']);
route::post('/EstimateUpdate/',[EstimateController::class,'EstimateUpdate']);

route::get('/EstimateViewPDF/{id}',[EstimateController::class,'EstimateViewPDF']);
route::get('/ajax_party_ledger/{partyid}',[Accounts::class,'ajax_party_ledger']);


// ..............UmrahCreate.............
route::get('/Umrah/{id?}',[UmrahController::class,'Umrah']);

route::get('/UmrahCreate/',[UmrahController::class,'UmrahCreate']);
//for ajax file upload with progress bar first we have to validate form
route::post('/UmrahValidate1/',[UmrahController::class,'UmrahValidate1']);

route::post('/UmrahSave/',[UmrahController::class,'UmrahSave'])->name('umrah.save'); ;
route::get('/ajax_umrah/',[UmrahController::class,'ajax_umrah']);

route::get('/UmrahDelete/{id}',[UmrahController::class,'UmrahDelete']);
route::get('/UmrahView/{id}',[UmrahController::class,'UmrahView']);
route::get('/UmrahEdit/{id}',[UmrahController::class,'UmrahEdit']);
route::post('/UmrahUpdate/',[UmrahController::class,'UmrahUpdate']);

route::get('/UmrahPDF/{id}/{download?}',[UmrahController::class,'UmrahPDF']);
route::get('/UmrahPDFView/{id}',[UmrahController::class,'UmrahPDFView']);

route::get('/UmrahRefund/{id}',[UmrahController::class,'UmrahRefund']);

Route::get('/UmrahReport/',[UmrahController::class,'UmrahReport']);
Route::post('/UmrahReport1/',[UmrahController::class,'UmrahReport1']);
Route::get('/UmrahReport1PDF/{supplierid}/{startdate}/{enddate}/{type}/{itemid?}',[UmrahController::class,'UmrahReport1PDF']);
Route::get('/UmrahReport2PDF/{supplierid}/{startdate}/{enddate}/{type}/{itemid?}',[UmrahController::class,'UmrahReport2PDF']);


// ............Company............
Route::get('/Company', [CompanyController::class, 'Company']);
Route::post('/SaveCompany', [CompanyController::class, 'SaveCompany']);
Route::get('/CompanyEdit/{id}', [CompanyController::class, 'CompanyEdit']);
Route::post('/CompanyUpdate/', [CompanyController::class, 'CompanyUpdate']);
Route::get('/CompanyDelete/{id}', [CompanyController::class, 'CompanyDelete']);

Route::get('migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    dd("Migration Done");
});

Route::get('clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    dd("Cache is cleared");
});

Route::get('optimize-clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    dd("Optimize is completed");

});

Route::get('key-generate', function () {
    \Illuminate\Support\Facades\Artisan::call('key:generate');
    dd("key is generated");

});

Route::get('storage-link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    dd("Storage has been Linked");

});

Route::get('db-seed', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    dd("DB is seeded");
});


Route::get('/printers', [UmrahController::class, 'listPrinters'])->name('printers');
Route::post('/print', [UmrahController::class, 'printFromPrinter'])->name('printFromPrinter');
 		 
 // END OF CRM LINKS
 
 

route::post('/invoice_knokoff/',[Accounts::class,'invoice_knokoff']);

Route::resource('hotel', HotelController::class);
Route::resource('location', LocationController::class);
Route::resource('hoteltariff', HotelTariffController::class);
Route::resource('transporttariff', TransportTariffController::class);
Route::resource('visatariff', VisaTariffController::class);

// umrah pro routes
Route::resource('umrah-invoice-master',UmrahInvoiceMasterController::class);




Route::get('umrah-invoice-master-delete2/{id}',[UmrahInvoiceMasterController::class,'destroy2'])->name('umrah.invoice.delete2');


Route::get('partyRefNumber/{partyID}',[UmrahInvoiceMasterController::class,'partyRefNumber']);

Route::post('umrah-invoice-voucher', [UmrahInvoiceMasterController::class, 'umrah_invoice_voucher'])
    ->name('umrah-invoice-voucher-store');

Route::post('update-all-pax-rate', [UmrahInvoicePassengerController::class, 'updateAllPaxRate'])
        ->name('umrah-invoice-passenger.updateAllPaxRate');

Route::resource('umrah-invoice-passenger',UmrahInvoicePassengerController::class);

Route::get('ajax_index',[UmrahInvoicePassengerController::class,'ajax_index'])->name('ajax.index');
Route::get('voucher_power_search',[UmrahInvoicePassengerController::class,'voucher_power_search'])->name('voucher.power.search');



Route::resource('umrah-invoice-transport',UmrahInvoiceTransportController::class);
Route::resource('umrah-invoice-hotel',UmrahInvoiceHotelController::class);
route::post('umrahSavePayment',[UmrahInvoiceMasterController::class,'umrahSavePayment']);
route::get('ajax_umrah_invoice_balance/{id}',[UmrahInvoiceMasterController::class,'ajax_umrah_invoice_balance']);


Route::resource('visa-master',VisaMasterController::class);

Route::get('visa-master-print/{id}', [VisaMasterController::class, 'print'])->name('visa-master.print');

Route::post('visa-detail/update-all-records/{InvoiceMasterID}', [VisaDetailController::class, 'updateAllRecords'])->name('visa-detail.updateAllRecords');
Route::resource('visa-detail',VisaDetailController::class);


Route::resource('sector', SectorController::class);
Route::resource('shirka', ShirkaController::class);
Route::resource('packages', PackagesController::class);
Route::resource('voucher-content', VoucherContentController::class);
Route::resource('airlines', AirlineController::class);

Route::resource('group-ticket-purchase', GroupTicketPurchaseController::class);
Route::get('group-ticket-purchase-data', [GroupTicketPurchaseController::class, 'getData'])->name('group-ticket-purchase.data');

Route::resource('group-ticket-sale', GroupTicketSaleController::class);
Route::get('group-tickets-sale-data', [GroupTicketSaleController::class, 'getData'])->name('group-ticket-sale.data');

route::get('/getAirlineCode/{ticket_no}',[AjaxController::class,'getAirlineCode'])->name('getAirlineCode');


route::get('/getPnr/{pnr}',[PnrController::class,'getPnr'])->name('getPnr');

// PNR Resource Routes
Route::resource('pnr', PnrController::class);
route::get('/getPartyRefNo/{party_id}',[AjaxController::class,'getPartyRefNo'])->name('getPartyRefNo');

route::get('/getTransportRates/{start_date}/{end_date}/{sector}/{vehicle_type}/{vehicle_status}/{supplier_id}/{party_id?}',[AjaxController::class,'getTransportRates'])->name('getTransportRates');

route::get('/getHotelRates/{start_date}/{end_date}/{location}/{roomt_type}/{room_status}/{hotel_id}/{supplier_id}/{party_id?}',[AjaxController::class,'getHotelRates'])->name('getHotelRates');



//voucher.print
route::get('/umrah_voucher_invoice/{id}',[UmrahInvoiceMasterController::class,'umrah_voucher_invoice'])->name('umrah_voucher_print');


// travel pro reporting


route::get('/hotel-hcn-search/',[TravelProReportingController::class,'hotel_hcn_search'])->name('hotel.hcn.search');
route::post('/hotel-hcn-search1/',[TravelProReportingController::class,'hotel_hcn_search1'])->name('hotel.hcn.search1');
route::post('/update-hotel-hcn/',[TravelProReportingController::class,'update_hotel_hcn'])->name('update.hotel.hcn');


route::get('/transport-brn-search/',[TravelProReportingController::class,'transport_brn_search'])->name('transport.brn.search');
route::post('/transport-brn-search1/',[TravelProReportingController::class,'transport_brn_search1'])->name('transport.brn.search1');
route::post('/update-transport-brn/',[TravelProReportingController::class,'update_transport_brn'])->name('update.transport.brn');


route::get('/view_hotel_voucher/',[TravelProReportingController::class,'view_hotel_voucher'])->name('view.hotel.voucher');
route::get('/ajax_umrah_report_index/',[TravelProReportingController::class,'ajax_umrah_report_index'])->name('ajax.umrah.report.index');

Route::get('Umrah-Voucher-view/{id}/{contentType}', [UmrahInvoiceMasterController::class, 'showExtra'])->name('umrah.voucher.view');



route::get('/view_hotel_invoice/',[TravelProReportingController::class,'view_hotel_invoice'])->name('view.hotel.invoice');
route::get('/ajax_umrah_report_index2/',[TravelProReportingController::class,'ajax_umrah_report_index2'])->name('ajax.umrah.report.index2');



Route::get('view-hotel-voucher-invoice/{id}/{type}', [TravelProReportingController::class, 'view_hotel_voucher_invoice'])->name('view.hotel.voucher.invoice');


//Hotel Voucher Sale Register Report


Route::post('hotel-voucher-sale-register2', [TravelProReportingController::class, 'hotel_voucher_sale_register2'])->name('hotel.voucher.sale.register2');



Route::get('hotel-voucher-sale-register', [TravelProReportingController::class, 'hotel_voucher_sale_register'])->name('hotel.voucher.sale.register');


//hotel-wise-payment-report
Route::get('hotel-wise-payment-report', [TravelProReportingController::class, 'hotel_wise_payment_report'])->name('hotel.wise.payment.report');

Route::post('hotel-wise-payment-report2', [TravelProReportingController::class, 'hotel_wise_payment_report2'])->name('hotel.wise.payment.report2');

Route::get('hotel-wise-profit-report', [TravelProReportingController::class, 'hotel_wise_profit_report'])->name('hotel.wise.profit.report');


//transort-wise-payment-report
Route::get('transport-wise-payment-report', [TravelProReportingController::class, 'transport_wise_payment_report'])->name('transport.wise.payment.report');

Route::post('transport-wise-payment-report2', [TravelProReportingController::class, 'transport_wise_payment_report2'])->name('transport.wise.payment.report2');





Route::get('group_ticket_register', [TravelProReportingController::class, 'group_ticket_register'])->name('group.ticket.register');


Route::post('group_ticket_register2', [TravelProReportingController::class, 'group_ticket_register2'])->name('group.ticket.register2');


Route::get('getBalanceOfGroupticket/{pnr}', [AjaxController::class, 'getBalanceOfGroupticket'])->name('getBalanceOfGroupticket');


 
Route::match(['get', 'post'], '/VoucherSalemanReport', [TravelProReportingController::class, 'VoucherSalemanReport'])->name('voucerh.saleman.report');

Route::match(['get', 'post'], '/VoucherSalemanSummaryReport', [TravelProReportingController::class, 'VoucherSalemanSummaryReport'])->name('voucher.saleman.summary.report');

// Route::match(['get', 'post'], '/OutstandingVoucher', [TravelProReportingController::class, 'OutstandingVoucher'])
// ->name('outstanding.voucher');


//fetch visa detail by ajax in voucher form
Route::get('/get-visa-detail', [App\Http\Controllers\UmrahInvoicePassengerController::class, 'getVisaDetail'])->name('get.visa.detail');


// end of travel pro reporting







}); // end of checkadmin middleware


Route::get('umrah_voucher_qrcode/{id}',[UmrahInvoiceMasterController::class,'umrah_voucher_qrcode'])->name('umrah_voucher_qrcode');


