<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\AgentController;
use App\Http\Controllers\admin\BranchController;
use App\Http\Controllers\admin\CountryController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\DurationController;
use App\Http\Controllers\admin\FollowupController;
use App\Http\Controllers\admin\SurveyorController;
use App\Http\Controllers\admin\FrequencyController;
use App\Http\Controllers\admin\ReferenceController;
use App\Http\Controllers\admin\Admin_userController;
use App\Http\Controllers\admin\Title_RankController;
use App\Http\Controllers\admin\Enquiry_ModeController;
use App\Http\Controllers\admin\IndustryTypeController;
use App\Http\Controllers\admin\Storage_ModeController;
use App\Http\Controllers\admin\Storage_TypeController;
use App\Http\Controllers\admin\ApprovedAgentController;
use App\Http\Controllers\admin\Customer_TypeController;
use App\Http\Controllers\admin\Survey_assignController;
use App\Http\Controllers\admin\Surveyor_TypeController;
use App\Http\Controllers\admin\UserPermissionController;
use App\Http\Controllers\admin\Surveyor_TimeZoneController;


// Route::get('/', function () {
//     return view('welcome');
// });

/*------Front routes start ------*/




/*------End Front routes start ------*/

Route::get('/config-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return 'Config cache cleared';
});



// Route::get('/admin', function () {
//     return view('admin.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    // echo "Welcome Admin";exit;
    if (Auth::user()->surveyor == 1) {
        // echo "Welcome Surveyor!";exit;
        return view('admin.surveyordashboard');
    } else {
        return view('admin.dashboard');
    }
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::resource('/folllowup_date', '\App\Http\Controllers\admin\HomeController');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/adminuser', '\App\Http\Controllers\admin\Admin_userController');
    Route::get('/admin/delete_admin', [Admin_userController::class, 'destroy'])->name('delete_admin');

    Route::resource('/userpermission', '\App\Http\Controllers\admin\UserPermissionController');
    Route::get('delete_permission', [UserPermissionController::class, 'delete_permission'])->name('delete_permission');
     Route::get('destroyPermission', [UserPermissionController::class, 'destroyPermission'])->name('destroyPermission');


     Route::resource('/country', '\App\Http\Controllers\admin\CountryController');
     Route::get('/admin/delete_country', [CountryController::class,'destroy'])->name('delete_country');

     Route::resource('/customer_type', '\App\Http\Controllers\admin\Customer_TypeController');
     Route::get('/admin/delete_customer_type', [Customer_TypeController::class,'destroy'])->name('delete_customer_type');

     Route::resource('/title_rank', '\App\Http\Controllers\admin\Title_RankController');
     Route::get('/admin/delete_title_rank', [Title_RankController::class,'destroy'])->name('delete_title_rank');

     Route::resource('/storage_type', '\App\Http\Controllers\admin\Storage_TypeController');
     Route::get('/admin/delete_storage_type', [Storage_TypeController::class,'destroy'])->name('delete_storage_type');

     Route::resource('/storage_mode', '\App\Http\Controllers\admin\Storage_ModeController');
     Route::get('/admin/delete_storage_mode', [Storage_ModeController::class,'destroy'])->name('delete_storage_mode');

     Route::resource('/enquiry_mode', '\App\Http\Controllers\admin\Enquiry_ModeController');
     Route::get('/admin/delete_enquiry_mode', [Enquiry_ModeController::class,'destroy'])->name('delete_enquiry_mode');

     Route::resource('/service', '\App\Http\Controllers\admin\ServiceController');
     Route::get('/admin/delete_service', [ServiceController::class,'destroy'])->name('delete_service');

     Route::resource('/branch', '\App\Http\Controllers\admin\BranchController');
     Route::get('/admin/delete_branch', [BranchController::class,'destroy'])->name('delete_branch');

     Route::resource('/surveyor', '\App\Http\Controllers\admin\SurveyorController');
     Route::get('/admin/delete_surveyor', [SurveyorController::class,'destroy'])->name('delete_surveyor');

     Route::post('surveyor_check_mail', 'App\Http\Controllers\admin\SurveyorController@surveyor_check_mail');
     Route::post('surveyor_edit_check_mail', 'App\Http\Controllers\admin\SurveyorController@surveyor_edit_check_mail');

     Route::resource('/surveyor_time_zone', '\App\Http\Controllers\admin\Surveyor_TimeZoneController');
     Route::get('/admin/delete_surveyor_time_zone', [Surveyor_TimeZoneController::class,'destroy'])->name('delete_surveyor_time_zone');

     Route::post('/surveyor_time_selected', [FollowupController::class, 'getTimeZones']);

     Route::resource('/surveyor_type', '\App\Http\Controllers\admin\Surveyor_TypeController');
     Route::get('/admin/delete_surveyor_type', [Surveyor_TypeController::class,'destroy'])->name('delete_surveyor_type');

     Route::resource('/survey_assign', '\App\Http\Controllers\admin\Survey_assignController');
     Route::get('/admin/survey_assign', [Survey_assignController::class,'index'])->name('survey_assign');

     Route::resource('/agent', '\App\Http\Controllers\admin\AgentController');
     Route::get('/admin/delete_agent', [AgentController::class,'destroy'])->name('delete_agent');
     Route::get('remove_agent_att/{agent_id}/{id}', [AgentController::class, 'remove_agent_att'])->name('remove_agent_att');
     Route::get('add_new_inq/{agent_id}/{attr_id}', [AgentController::class, 'agent_add_inq'])->name('add_new_inq');

     Route::match (['get','post'],'agent_filter','App\Http\Controllers\admin\AgentController@index')->name('agent_filter');

     Route::get('/download-agent', 'App\Http\Controllers\admin\AgentController@download')->name('download.agent');

     Route::get('bulk_agent', 'App\Http\Controllers\admin\AgentController@bulk_agent')->name('bulk_agent');

     Route::post('upload', 'App\Http\Controllers\admin\AgentController@upload')->name('upload');

     Route::get('costing/{id}', [FollowupController::class, 'costing'])->name('followup.costing');
     Route::post('costing_information', '\App\Http\Controllers\admin\FollowupController@costing_information')->name('costing_information');


     Route::get('survey_info/{id}', [FollowupController::class, 'survey_info'])->name('followup.survey_info');
     Route::post('survey_information', '\App\Http\Controllers\admin\FollowupController@survey_information')->name('survey_information');

     Route::resource('/followup', '\App\Http\Controllers\admin\FollowupController');
     Route::get('/admin/delete_followup', [FollowupController::class,'destroy'])->name('delete_followup');

     Route::post('/followup_data', [FollowupController::class,'followup_data'])->name('followup_data');
     Route::post('/followup_data', [FollowupController::class,'followup_data'])->name('followup_data');

     Route::match (['get','post'],'/followup-filter','App\Http\Controllers\admin\FollowupController@index')->name('followup-filter');

     Route::post('get_quote', '\App\Http\Controllers\admin\FollowupController@get_quote')->name('get_quote');

    Route::get('get_quote_pdf/{id}', [FollowupController::class, 'get_quote_pdf'])->name('followup.get_quote_pdf');

    Route::get('repeated_inq/{id}', [FollowupController::class, 'repeated_inq'])->name('followup.repeated_inq');

    Route::post('add_repeated_inq', [FollowupController::class, 'add_repeated_inq'])->name('followup.add_repeated_inq');

    Route::get('/surveyor_form/{id}', '\App\Http\Controllers\admin\FollowupController@surveyor_form')->name('surveyor_form');

    Route::get('/luggage_item', '\App\Http\Controllers\admin\FollowupController@luggage_item')->name('luggage_item');

    Route::get('/selected_items', '\App\Http\Controllers\admin\FollowupController@selected_items')->name('selected_items');

    Route::post('/item_form_data', '\App\Http\Controllers\admin\FollowupController@item_form_data')->name('item_form_data');

    Route::post('followup_form', '\App\Http\Controllers\admin\FollowupController@followup_form');

    Route::post('status_change', '\App\Http\Controllers\admin\FollowupController@status_change');

    Route::get('filter_data', '\App\Http\Controllers\admin\FollowupController@filter_data');

    Route::get('get_quote_form/{id}', [FollowupController::class, 'get_quote_form'])->name('followup.get_quote_form');

    Route::post('get_quote', '\App\Http\Controllers\admin\FollowupController@get_quote')->name('get_quote');


    Route::post('/agent_att_replace', [FollowupController::class,'agent_att_data'])->name('agent_att_replace');

    Route::post('/agent_att_data_replace', [FollowupController::class,'agent_data_assign'])->name('agent_att_data_replace');

    Route::resource('/industry-type', '\App\Http\Controllers\admin\IndustryTypeController');
    Route::get('/admin/delete-industry-type', [IndustryTypeController::class,'destroy'])->name('industry-type.delete');

    Route::resource('/reference', '\App\Http\Controllers\admin\ReferenceController');
    Route::get('/admin/delete-reference', [ReferenceController::class,'destroy'])->name('reference.delete');

    Route::resource('/approved-agents', '\App\Http\Controllers\admin\ApprovedAgentController');
    Route::get('/admin/delete-approved-agent', [ApprovedAgentController::class,'destroy'])->name('approved-agent.delete');

    Route::resource('/frequencies', '\App\Http\Controllers\admin\FrequencyController');
    Route::get('/admin/delete-frequencies', [FrequencyController::class,'destroy'])->name('frequencies.delete');


    Route::resource('/durations', '\App\Http\Controllers\admin\DurationController');
    Route::get('/admin/delete-durations', [DurationController::class,'destroy'])->name('durations.delete');

    Route::post('approve-status', '\App\Http\Controllers\admin\AgentController@is_approved_status');
    Route::post('check-email-exits', '\App\Http\Controllers\admin\AgentController@check_email_exits');
    Route::get('agent-detail/{agent_id}',[AgentController::class,'agent_detail'])->name('agent-detail');

});

Route::get('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';