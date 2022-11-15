<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Http\Controllers\EmailEditorController;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
Route::get('cetakstel', function(Illuminate\Http\Request $request){
	$PDFData = $request;
	$PDF = new \App\Services\PDF\PDFService();
	return $PDF->cetakSTEL($PDFData);
});

Route::get('cetakKontrak', function(Illuminate\Http\Request $request){
	$PDFData = $request->session()->get('key_contract');
	$PDF =  new \App\Services\PDF\PDFService();
	return $PDF->cetakKontrak($PDFData);
});

Route::get('cetakSPB', function(Illuminate\Http\Request $request){
	$PDFData = $request->session()->get('key_exam_for_spb');
	$PDF = new \App\Services\PDF\PDFService();
	return $PDF->cetakSPB($PDFData);
});

Route::post('/editPengujian', 'PengujianController@edit');
// Route::get('/pengujian/{id}/edit', 'PengujianController@edit');
Route::get('/pengujian/{id}/detail', 'PengujianController@detail');
Route::get('/pengujian/{id}/pembayaran', 'PengujianController@pembayaran');
Route::get('/pengujian/download/{id}/{attach}/{jns}', 'PengujianController@download');
Route::get('/pengujian/{id}/downloadSPB', 'PengujianController@downloadSPB');
Route::get('/pengujian/{id}/downloadLaporanPengujian', 'PengujianController@downloadLaporanPengujian');
Route::get('/pengujian/{id}/downloadSertifikat', 'PengujianController@downloadSertifikat');
Route::get('/products/{id}/stel', 'ProductsController@downloadStel');
Route::post('/pengujian/pembayaran', 'PengujianController@uploadPembayaran');
Route::post('/pengujian/tanggaluji', 'PengujianController@updateTanggalUji');
Route::get('/cetakPengujian/{id}', 'PengujianController@details');

Route::get('/cetakKuitansi/{id}', 'IncomeController@cetakKuitansi');
Route::get('/cetakUjiFungsi/{id}', 'ExaminationController@cetakUjiFungsi');
Route::get('/cetakTechnicalMeeting/{id}', 'ExaminationController@cetakTechnicalMeeting');
Route::get('/', 'PermohonanController@createPermohonan');
Route::get('/health', function (){
	return 'ok';
}); 
Route::post('/client/login', 'ClientController@authenticate');
Route::get('/client/logout', 'ClientController@logout');
Route::get('/language/{lang}', 'HomeController@language');
Route::get('/about', 'HomeController@about');
Route::get('/sertifikasi', 'HomeController@sertifikasi');
Route::get('/contact', 'HomeController@contact');
Route::get('/procedure', 'HomeController@procedure');
Route::get('/process', 'HomeController@process')->middleware(['client']);
Route::get('/purchase_history', 'ProductsController@purchase_history')->middleware(['client']);
Route::get('/pengujian', 'PengujianController@index')->middleware(['client']);
Route::get('/products', 'ProductsController@index')->middleware(['client']);
Route::get('/detailprocess/rentChamber', 'ChamberController@index');
Route::get('/chamber_history', 'ChamberController@purchase_history')->middleware(['client']);
Route::get('/chamber_history/{id}/pembayaran', 'ChamberController@pembayaran');
Route::get('/payment_confirmation_chamber/{id}', 'ChamberController@payment_confirmation');
Route::post('/doCheckoutChamber', 'ChamberController@doCheckout');
Route::get('/cancel_va_chamber/{id}', 'ChamberController@api_cancel_va');
Route::get('/detailprocess/{id}', 'HomeController@detail_process');
Route::get('/editprocess/{jenis_uji}/{id}', 'HomeController@edit_process');
Route::get('/faq', 'HomeController@faq');

Route::group(['prefix' => '/admin', 'middlewareGroups' => 'web'], function () {
	Route::auth();
	Route::get('/logout', 'UserController@logout');
	Route::get('/', 'DashboardController@index');
	Route::get('/examination/download/{id}', 'ExaminationController@downloadForm');
	Route::get('/examination/media/download/{id}', 'ExaminationController@downloadRefUjiFile');
	Route::get('/examination/media/download/{id}/{name}', 'ExaminationController@downloadMedia');
	Route::get('/stel/media/{id}', 'STELController@viewMedia');
	Route::get('/company/media/{id}/{name}', 'CompanyController@viewMedia');
	Route::resource('/device', 'DeviceController');
	Route::get('/devicenc', 'DevicencController@index');
	Route::get('/devicenc/{id}/{reason}/moveData', 'DevicencController@moveData');
	Route::get('/examination/revisi/{id}', 'ExaminationController@revisi');
	Route::get('/examination/harddelete/{id}/{page}/{reason}', 'ExaminationController@destroy');
	Route::get('/examination/resetUjiFungsi/{id}/{reason}', 'ExaminationController@resetUjiFungsi');
	Route::get('/examination/ijinkanUjiFungsi/{id}', 'ExaminationController@ijinkanUjiFungsi');
	Route::post('/examination/revisi', 'ExaminationController@updaterevisi');
	Route::post('/examination/{id}/tanggalkontrak', 'ExaminationController@tanggalkontrak');
	Route::post('/examination/{id}/generateSPBParam', 'ExaminationController@generateSPBParam');
	Route::post('/examination/{id}/generateEquipParam', 'ExaminationController@generateEquipParam');
	Route::post('/examination/{id}/tandaterima', 'ExaminationController@tandaterima');
	Route::get('/examination/generateEquip', 'ExaminationController@generateEquip');
	Route::get('/examination/generateSPB', 'ExaminationController@generateSPB');
	Route::post('/examination/{id}/generateFromTPN', 'ExaminationController@generateFromTPN');
	Route::post('/examination/{id}/generateTaxInvoiceSPB', 'ExaminationController@generateTaxInvoice');
	Route::get('/examination/{id}/deleteRevLapUji', 'ExaminationController@deleteRevLapUji');
	Route::post('/examination/generateSPB', 'ExaminationController@generateSPBData');
	Route::put('/user/profile/{id}', 'UserController@updateProfile');
	Route::resource('/article', 'ArticleController');
	Route::resource('/examination', 'ExaminationController');
	Route::get('stel/createMaster', 'STELController@createMaster');
	Route::post('stel/storeMaster', 'STELController@storeMaster');
	Route::resource('/stel', 'STELController');
	Route::get('stel/create/{stels_master_id}', 'STELController@create');
	Route::resource('/charge', 'ExaminationChargeController');
	Route::resource('/newcharge', 'NewExaminationChargeController');
	Route::get('/newcharge/{id}/createDetail', 'NewExaminationChargeController@createDetail');
	Route::post('/newcharge/{id}/postDetail', 'NewExaminationChargeController@postDetail');
	Route::get('/newcharge/{id}/editDetail/{exam_id}', 'NewExaminationChargeController@editDetail');
	Route::post('/newcharge/{id}/updateDetail/{exam_id}', 'NewExaminationChargeController@updateDetail');
	Route::post('/newcharge/{id}/deleteDetail/{exam_id}', 'NewExaminationChargeController@deleteDetail');
	Route::resource('/calibration', 'CalibrationChargeController');
	Route::resource('/company', 'CompanyController');
	Route::resource('/user', 'UserController');
	Route::resource('/userin', 'UserinController');
	Route::resource('/usereks', 'UsereksController');
	Route::resource('/slideshow', 'SlideshowController');
	Route::resource('/certification', 'CertificationController');
	Route::resource('/popupinformation', 'PopUpInformationController');
	Route::resource('/footer', 'FooterController');
	Route::resource('/labs', 'ExaminationLabController');
	Route::resource('/myexam', 'MyExaminationController');
	Route::get('/feedback/{id}/reply', 'FeedbackController@reply');
	Route::post('/feedback/{id}/destroy', 'FeedbackController@destroy');
	Route::post('/feedback/reply', 'FeedbackController@sendEmailReplyFeedback');
	Route::get('/feedback', 'FeedbackController@index');
	Route::get('/downloadUsman', 'DashboardController@downloadUsman');
	Route::post('/user/{id}/softDelete', 'UserController@softDelete');
	Route::post('/userin/{id}/softDelete', 'UserinController@softDelete');
	Route::post('/usereks/{id}/softDelete', 'UsereksController@softDelete');
	Route::get('/analytic', 'AnalyticController@index');
	Route::resource('/role', 'RoleController');
	Route::get('/downloadbukti/{id}', 'SalesController@viewMedia');
	Route::get('/downloadstelwatermark/{id}', 'SalesController@viewWatermark');
	// Route::get('/analytic', function(){
		// $visitor = Tracker::currentSession();
		// echo"<pre>";print_r($visitor);
	// });
	Route::resource('/privilege', 'PrivilegeController');
	Route::get('/topdashboard', 'TopDashboardController@index');
	Route::post('/topdashboard/searchGrafik', 'TopDashboardController@searchGrafik');
	Route::resource('/testimonial', 'TestimonialController');
	Route::resource('/tempcompany', 'TempCompanyController');
	Route::get('/tempcompany/media/{id}/{name}', 'TempCompanyController@viewMedia');
	
	Route::get('/adm_exam_autocomplete/{query}', 'ExaminationController@autocomplete')->name('adm_exam_autocomplete');
	Route::get('/adm_exam_done_autocomplete/{query}', 'ExaminationDoneController@autocomplete')->name('adm_exam_done_autocomplete');
	Route::get('/adm_dev_autocomplete/{query}', 'DevClientController@autocomplete')->name('dev_client_autocomplete');
	Route::get('/adm_feedback_autocomplete/{query}', 'FeedbackController@autocomplete')->name('adm_feedback_autocomplete');
	Route::get('/adm_article_autocomplete/{query}', 'ArticleController@autocomplete')->name('adm_article_autocomplete');
	Route::get('/adm_stel_autocomplete/{query}', 'STELController@autocomplete')->name('adm_stel_autocomplete');
	Route::get('/adm_charge_autocomplete/{query}', 'ExaminationChargeController@autocomplete')->name('adm_charge_autocomplete');
	Route::get('/adm_calibration_autocomplete/{query}', 'CalibrationChargeController@autocomplete')->name('adm_calibration_autocomplete');
	Route::get('/adm_slideshow_autocomplete/{query}', 'SlideshowController@autocomplete')->name('adm_slideshow_autocomplete');
	Route::get('/adm_labs_autocomplete/{query}', 'ExaminationLabController@autocomplete')->name('adm_labs_autocomplete');
	Route::get('/adm_company_autocomplete/{query}', 'CompanyController@autocomplete')->name('adm_company_autocomplete');
	Route::get('/adm_temp_company_autocomplete/{query}', 'TempCompanyController@autocomplete')->name('adm_temp_company_autocomplete');
	Route::get('/adm_user_autocomplete/{query}', 'UserController@autocomplete')->name('adm_user_autocomplete');
	Route::get('/adm_footer_autocomplete/{query}', 'FooterController@autocomplete')->name('adm_footer_autocomplete');
	Route::get('/adm_inc_autocomplete/{query}', 'IncomeController@autocomplete')->name('adm_inc_autocomplete');
	
	Route::post('/examination/{id}/generateSPKCode', 'ExaminationController@generateSPKCodeManual');
	Route::resource('/log', 'LogController');
	Route::resource('/log_administrator', 'LogController');
	Route::get('/backup', 'BackupController@index');
	Route::get('/backup/{id}/delete', 'BackupController@destroy');
	Route::get('/backup/{id}/media', 'BackupController@viewmedia');
	Route::get('/backup/{id}/restore', 'BackupController@restore');
	
	Route::resource('/examinationdone', 'ExaminationDoneController');
	
	Route::resource('/income', 'IncomeController@index');
	
	Route::post('/myexam/{id}/tanggalkontrak', 'MyExaminationController@tanggalkontrak');
	Route::post('/myexam/{id}/generateSPBParam', 'MyExaminationController@generateSPBParam');
	Route::get('/myexam/generateSPB', 'MyExaminationController@generateSPB');
	Route::post('/myexam/generateSPB', 'MyExaminationController@generateSPBData');
	Route::post('/myexam/{id}/generateSPKCode', 'MyExaminationController@generateSPKCodeManual');
	Route::get('/history', 'HistoryController@index');
	Route::resource('/equipment', 'EquipmentController');
	Route::resource('/sales', 'SalesController');
	Route::get('/sales/{id}/upload', 'SalesController@upload');
	Route::get('/sales/{id}/{reason}/deleteProduct', 'SalesController@deleteProduct');
	Route::post('/sales/{id}/generateKuitansi', 'SalesController@generateKuitansi');
	Route::post('/sales/{id}/generateTaxInvoice', 'SalesController@generateTaxInvoice');
	Route::resource('/question', 'QuestionController');
	Route::resource('/questionerquestion', 'QuestionerQuestionController');
	Route::resource('/questionpriv', 'QuestionprivController');
	Route::get('/kuitansi', 'IncomeController@kuitansi');
	Route::get('/kuitansi/create', 'IncomeController@create');
	Route::post('/kuitansi/generateKuitansi', 'IncomeController@generateKuitansiManual');
	Route::post('/kuitansi', 'IncomeController@store');
	Route::get('/kuitansi/{id}/detail', 'IncomeController@detail');
	Route::get('/downloadkuitansistel/{id}', 'SalesController@downloadkuitansistel');
	Route::get('/downloadfakturstel/{id}', 'SalesController@downloadfakturstel');
	
	Route::resource('/spk', 'SPKController');
	Route::resource('/faq', 'FaqController');
	
	Route::get('/all_notifications', 'NotificationController@indexAdmin');

	Route::resource('/functiontest', 'FunctionTestController');
	Route::resource('/generalSetting', 'GeneralSettingController');
	Route::resource('/spb', 'SPBController');
	Route::resource('/nogudang', 'NoGudangController');
	Route::resource('/feedbackncomplaint', 'FeedbackComplaintController');
	Route::resource('/fakturpajak', 'FakturPajakController');
	Route::resource('/videoTutorial', 'VideoTutorialController');
	Route::post('/orderSlideshow', 'SlideshowController@orderSlideshow');
	Route::resource('/email_editors', 'EmailEditorController');
	Route::resource('/chamber', 'ChamberAdminController');
	Route::get('/chamber/delete/{id}/{reasonOfDeletion}', 'ChamberAdminController@deleteChamber');
	Route::post('/email_editors/update_logo_signature', 'EmailEditorController@updateLogoSignature');

	Route::resource('/examinationcancel', 'ExaminationCancelController');

	Route::post('/chamber/generateKuitansiChamber', 'ChamberAdminController@generateKuitansi');
	Route::post('/chamber/generateTaxInvoiceChamber', 'ChamberAdminController@generateTaxInvoice');

	Route::resource('/sidang', 'SidangController');
	Route::get('sidang/create/{sidang_id}', 'SidangController@create');
	Route::get('/sidang/delete/{id}/{reasonOfDeletion}', 'SidangController@destroy');
	Route::get('sidang/updateExamination/{sidang_id}', 'SidangController@updateExamination');
	Route::get('sidang/resetExamination/{sidang_id}', 'SidangController@resetExamination');
	Route::get('sidang/{sidang_id}/print', 'SidangController@print');
	Route::get('sidang/{sidang_id}/download', 'SidangController@download');
	Route::get('sidang/{sidang_id}/excel', 'SidangController@excel');
	Route::get('sidang/{sidang_id}/reset', 'SidangController@resetExamination');

	Route::resource('/approval', 'ApprovalController');
	Route::get('/approval/assign/{id}/{password}', 'ApprovalController@assign');
});
	// Route::get('/uploadCertification', 'UploadProductionController@uploadCertification');
	// Route::get('/uploadCompany', 'UploadProductionController@uploadCompany');
	// Route::get('/uploadDevice', 'UploadProductionController@uploadDevice');
	// Route::get('/uploadExamination', 'UploadProductionController@uploadExamination');
	// Route::get('/uploadExaminationAttach', 'UploadProductionController@uploadExaminationAttach');
	// Route::get('/uploadFooter', 'UploadProductionController@uploadFooter');
	// Route::get('/uploadPopUpInformation', 'UploadProductionController@uploadPopUpInformation');
	// Route::get('/uploadSlideshow', 'UploadProductionController@uploadSlideshow');
	// Route::get('/uploadStel', 'UploadProductionController@uploadStel');
	// Route::get('/uploadStelSales1', 'UploadProductionController@uploadStelSales1');
	// Route::get('/uploadStelSales2', 'UploadProductionController@uploadStelSales2');
	// Route::get('/uploadStelAttach', 'UploadProductionController@uploadStelAttach');
	// Route::get('/uploadTempCompany', 'UploadProductionController@uploadTempCompany');
	// Route::get('/uploadUser', 'UploadProductionController@uploadUser');
	// Route::get('/deletePengujian', 'UploadProductionController@deletePengujian');

	Route::get('/adm_dashboard_autocomplete/{query}', 'DashboardController@autocomplete')->name('adm_dashboard_autocomplete');
	
	Route::get('/examination/excel', 'ExaminationController@excel');
	Route::get('/device/excel', 'DeviceController@excel');
	Route::get('/devicenc/excel', 'DevicencController@excel');
	Route::get('/company/excel', 'CompanyController@excel');
	Route::post('/company/importExcel', 'CompanyController@importExcel');
	Route::get('/income/excel', 'IncomeController@excel');
	Route::get('/log/excel', 'LogController@excel');
	Route::get('/log_administrator/excel', 'LogController@excel');
	Route::get('/examinationdone/excel', 'ExaminationDoneController@excel');
	Route::get('/sales/excel', 'SalesController@excel');

	Route::get('/spb/excel', 'SPBController@excel');
	Route::get('/nogudang/excel', 'NoGudangController@excel');
	Route::get('/stel/excel', 'STELController@excel');
	Route::get('/functiontest/excel', 'FunctionTestController@excel');
	Route::get('/charge/excel', 'ExaminationChargeController@excel');
	Route::get('/calibration/excel', 'CalibrationChargeController@excel');
	Route::get('/spk/excel', 'SPKController@excel');
	Route::get('/feedbackncomplaint/excel', 'FeedbackComplaintController@excel');

Route::post('/submitPermohonan', 'PermohonanController@submit');
Route::post('/uploadPermohonan', 'PermohonanController@upload');
Route::post('/uploadPermohonanEdit', 'PermohonanController@uploadEdit');
Route::post('/cekPermohonan', 'PermohonanController@cekSNjnsPengujian');
Route::post('/getPemohon', 'PermohonanController@getInfo');
Route::post('/downloadFile', 'PermohonanController@downloadFile');
Route::post('/updatePermohonan', 'PermohonanController@update');
Route::get('/cetakPermohonan/{exam_id}', 'PermohonanController@cetak');
Route::post('/cekLogin', 'ClientController@cekLogin');
Route::resource('/pengujian', 'PengujianController');
Route::get('/pengujian/{id}/detail', 'PengujianController@detail');
Route::post('/testimonial', 'PengujianController@testimonial');
Route::post('/cekAmbilBarang', 'PengujianController@cekAmbilBarang');
Route::post('/reqCancel', 'PengujianController@reqCancel');
Route::get('{path}', 'STELClientController@index')->where('path', '(STEL|S-TSEL|STD|PERDIRJEN|PERMENKOMINFO|OTHER)');
Route::resource('/Chargeclient', 'ExaminationChargeClientController');
Route::get('/Chargeclient', 'ExaminationChargeClientController@index');
Route::resource('/CalibrationChargeclient', 'CalibrationChargeClientController');
Route::get('/CalibrationChargeclient', 'CalibrationChargeClientController@index');
Route::resource('/NewChargeclient', 'ExaminationNewChargeClientController');
Route::get('/NewChargeclient', 'ExaminationNewChargeClientController@index');
Route::resource('/Devclient', 'DevClientController');
Route::get('/Devclient', 'DevClientController@index');
Route::get('/client/profile', 'ProfileController@index');
Route::post('/client/profile', 'ProfileController@update');
Route::post('/client/company', 'ProfileController@updateCompany');
Route::get('/client/password/resetPass', function () {
   return view('client.passwords.email');
});
Route::post('/client/password/email', 'ResetPasswordController@postEmail');
Route::get('/client/password/reset/{token}', 'ResetPasswordController@getReset');
Route::post('/client/password/reset', 'ResetPasswordController@postReset');

Route::post('/filterPengujian', 'PengujianController@filter');
Route::post('/filterSTEL', 'STELClientController@filter');
Route::post('/filterCharge', 'ExaminationChargeClientController@filter');
Route::post('/filterNewCharge', 'ExaminationNewChargeClientController@filter');
Route::get('/register', 'ProfileController@register');
Route::post('/client/register', 'ProfileController@insert');
Route::post('/checkRegisterEmail', 'ProfileController@checkRegisterEmail');
Route::get('/signUp', 'ProfileController@signUp');

Route::post('/global/search', 'HomeController@search');
Route::post('/client/feedback', 'PermohonanController@feedback');

Route::get('/client/downloadUsman', 'HomeController@downloadUsman');
Route::get('/client/downloadDecisionPPh23', 'PengujianController@downloadDecisionPPh23');
Route::get('mylogsbl', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

 
Route::get('/stel_autocomplete/{query}/{type}', 'STELClientController@autocomplete')->name('stel_autocomplete');
Route::get('/stsel_autocomplete/{query}/{type}', 'STELClientController@autocomplete')->name('stsel_autocomplete');
Route::get('/charge_client_autocomplete/{query}', 'ExaminationChargeClientController@autocomplete')->name('charge_client_autocomplete');
Route::get('/new_charge_client_autocomplete/{query}', 'ExaminationNewChargeClientController@autocomplete')->name('new_charge_client_autocomplete');
Route::get('/dev_client_autocomplete/{query}', 'DevClientController@autocomplete')->name('dev_client_autocomplete');
Route::get('/pengujian_autocomplete/{query}', 'PengujianController@autocomplete')->name('pengujian_autocomplete');
 

Route::group(['prefix' => '/v1', 'middlewareGroups' => 'api'], function () {
	Route::get('/companies', 'v1\CompanyAPIController@getCompanies');
	Route::get('/customer', 'v1\CustomerAPIController@getCustomer');
	Route::get('/stel', 'v1\StelAPIController@getStelData');
	Route::get('/checkBillingTPN', 'v1\StelAPIController@checkBillingTPN');
	Route::get('/checkTaxInvoiceTPN', 'v1\StelAPIController@checkTaxInvoiceTPN');
	Route::get('/checkKuitansiTPN', 'v1\StelAPIController@checkKuitansiTPN');
	Route::get('/checkReturnedTPN', 'v1\StelAPIController@checkReturnedTPN');
	Route::get('/device', 'v1\DeviceAPIController@getDeviceData');
	Route::get('/examination', 'v1\ExaminationAPIController@getExaminationData');
	Route::get('/examination/applicants', 'v1\ExaminationAPIController@getExaminationByApplicants');
	Route::get('/examination/companies', 'v1\ExaminationAPIController@getExaminationByCompany');
	Route::get('/examination/devices', 'v1\ExaminationAPIController@getExaminationByDevice');
	Route::get('/spk', 'v1\ExaminationAPIController@getSpk');
	Route::get('/function_test', 'v1\ExaminationAPIController@getFunctionTest');
	Route::get('/examination_histories', 'v1\ExaminationAPIController@getExaminationHistory');
	Route::post('/updateFunctionDate', 'v1\ExaminationAPIController@updateFunctionDate');
	Route::post('/updateEquipLoc', 'v1\ExaminationAPIController@updateEquipLoc');
	Route::post('/updateDeviceTE', 'v1\ExaminationAPIController@updateDeviceTE');
	Route::post('/updateFunctionStat', 'v1\ExaminationAPIController@updateFunctionStat');
	Route::post('/updateSpkStat', 'v1\ExaminationAPIController@updateSpkStat');
	Route::post('/updateSpk', 'v1\ExaminationAPIController@updateSpk');
	Route::post('/sendLapUji', 'v1\ExaminationAPIController@sendLapUji');
	Route::post('/updateSidangQa', 'v1\ExaminationAPIController@updateSidangQa');
	Route::post('/sendSertifikat', 'v1\ExaminationAPIController@sendSertifikat');
	Route::post('/sendSPK', 'v1\ExaminationAPIController@sendSPK');
	Route::post('/sendSPKHistory', 'v1\ExaminationAPIController@sendSPKHistory');
	Route::get('/checkSPKCreatedOTR', 'v1\ExaminationAPIController@checkSPKCreatedOTR');
	Route::get('/checkBillingSPBTPN', 'v1\ExaminationAPIController@checkBillingTPN');
	Route::get('/checkTaxInvoiceSPBTPN', 'v1\ExaminationAPIController@checkTaxInvoiceTPN');
	Route::get('/checkKuitansiSPBTPN', 'v1\ExaminationAPIController@checkKuitansiTPN');
	Route::get('/checkReturnedSPBTPN', 'v1\ExaminationAPIController@checkReturnedTPN');
	Route::get('/spbReminder', 'v1\ExaminationAPIController@spbReminder');
	Route::get('/getDateRentedChamber', 'v1\ChamberAPIController@getDateRented');
	Route::get('/checkBillingCHMBTPN', 'v1\ChamberAPIController@checkBillingTPN');
	Route::get('/checkTaxInvoiceCHMBTPN', 'v1\ChamberAPIController@checkTaxInvoiceTPN');
	Route::get('/checkKuitansiCHMBTPN', 'v1\ChamberAPIController@checkKuitansiTPN');
	Route::get('/checkReturnedCHMBTPN', 'v1\ChamberAPIController@checkReturnedTPN');
	Route::get('/checkDeliveredCHMBTPN', 'v1\ChamberAPIController@checkDeliveredTPN');
	Route::get('/cronDeleteChamber', 'v1\ChamberAPIController@cronDeleteChamber');
});

Route::get('/do_backup', 'BackupController@backup'); 

Route::get('/login', 'ProfileController@login');
 
Route::resource('/products', 'ProductsController');
Route::get('/payment_confirmation/{id}', 'ProductsController@payment_confirmation');
Route::get('/payment_confirmation_spb/{id}', 'PengujianController@payment_confirmation');
Route::get('/resend_va/{id}', 'ProductsController@api_resend_va');
Route::get('/resend_va_spb/{id}', 'PengujianController@api_resend_va');
Route::get('/cancel_va/{id}', 'ProductsController@api_cancel_va');
Route::get('/cancel_va_spb/{id}', 'PengujianController@api_cancel_va');
Route::post('/doCancel', 'ProductsController@doCancel');
Route::post('/doCancelSPB', 'PengujianController@doCancel');
Route::get('/payment_status', 'ProductsController@payment_status');
Route::get('/checkout', 'ProductsController@checkout');
Route::post('/doCheckout', 'ProductsController@doCheckout');
Route::post('/doCheckoutSPB', 'PengujianController@doCheckout');
Route::get('/payment_detail/{id}', 'ProductsController@payment_detail');
Route::get('/test_notification', 'ProductsController@test_notification');
Route::get('/upload_payment/{id}', 'ProductsController@upload_payment');
Route::post('/pembayaranstel', 'ProductsController@pembayaranstel');

Route::post('/checkKuisioner', 'PengujianController@checkKuisioner');
Route::post('/insertKuisioner', 'PengujianController@insertKuisioner');
Route::post('/insertComplaint', 'PengujianController@insertComplaint');

Route::get('/client/downloadkuitansistel/{id}', 'ProductsController@downloadkuitansistel');
Route::get('/client/downloadfakturstel/{id}', 'ProductsController@downloadfakturstel');
Route::get('/client/downloadstelwatermark/{id}', 'ProductsController@viewWatermark');

Route::get('/cetakFormBarang/{id}', 'ExaminationController@cetakFormBarang');
Route::get('cetakTandaTerima', function(Illuminate\Http\Request $request){
	$PDFData = $request->session()->get('key_tanda_terima');
	$PDF = new \App\Services\PDF\PDFService();
	return $PDF->cetakTandaTerima($PDFData);
});
Route::get('/cetakKepuasanKonsumen/{id}', 'ExaminationDoneController@cetakKepuasanKonsumen');
Route::get('/cetakComplaint/{id}', 'ExaminationDoneController@cetakComplaint');
Route::post('/updateNotif', 'NotificationController@updateNotif');
Route::get('/all_notifications', 'NotificationController@index');
Route::resource('/chamber', 'ChamberController');
Route::get('/cetakTiketChamber/{id}', 'ChamberController@cetakTiket');
Route::get('/downloadkuitansichamber/{id}', 'ChamberController@downloadkuitansi');
Route::get('/downloadfakturchamber/{id}', 'ChamberController@downloadfaktur');
Route::get('/test', 'TestController@index'); //todo daniel delete
Route::get('/approval/{id}', 'AuthentikasiController@index');
