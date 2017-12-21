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
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});
Route::get('/config-cache', function() {
    Artisan::call('config:cache');
});


Route::get('/auth',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::get('auth/login',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::post('auth/post/login',array('as'=>'Sign in' , 'uses' =>'SystemAuthController@authPostLogin'));
Route::post('auth/registration',array('as'=>'Registration' , 'uses' =>'SystemAuthController@authRegistration'));
Route::post('auth/forget/password',array('as'=>'Forgot Password' , 'uses' =>'SystemAuthController@authForgotPasswordConfirm'));
Route::get('auth/set/new/password/{user_id}/verify',array('as'=>'Forgot Password Verify' , 'uses' =>'SystemAuthController@authSystemForgotPasswordVerification'));
Route::post('auth/post/new/password/',array('as'=>'New Password Submit' , 'uses' =>'SystemAuthController@authSystemNewPasswordPost'));

Route::get('auth/admin/logout/{name_slug}',array('as'=>'Logout' , 'uses' =>'SystemAuthController@authLogout'));
Route::group(['middleware' => ['admin_auth']], function () {
    Route::get('admin/dashboard', array('as' =>'Admin Dashboard', 'uses' => 'AdminController@index'));

    //profile
    Route::get('admin/profile',array('as'=>'Profile' , 'uses' =>'AdminController@Profile'));
    Route::post('admin/profile/update',array('as'=>'Profile Update' , 'uses' =>'AdminController@ProfileUpdate'));
    Route::post('admin/profile/image/update',array('as'=>'Profile Image Update' , 'uses' =>'AdminController@ProfileImageUpdate'));
    Route::post('admin/change/password',array('as'=>'User Change Password' , 'uses' =>'AdminController@UserChangePassword'));
    #UserManagement
    Route::get('admin/user/management',array('as'=>'User Management' , 'uses' =>'AdminController@UserManagement'));
    #CreateUser
    Route::post('admin/user/create',array('as'=>'Create User' , 'uses' =>'AdminController@CreateUser'));
    #ChangeUserStatus
    Route::get('admin/change/user/status/{user_id}/{status}',array('as'=>'Change User Status' , 'uses' =>'AdminController@ChangeUserStatus'));

    //company
    Route::get('admin/company', array('as' =>'Company', 'uses' => 'CompanyController@index'));
    Route::get( 'admin/company/create', array('as' =>'Company', 'uses' => 'CompanyController@create'));
    Route::post('admin/company/store', array('as' =>'Company', 'uses' => 'CompanyController@store'));
    Route::get('admin/company/show/{id}', array('as' =>'Company', 'uses' => 'CompanyController@show'));
    Route::get('admin/company/edit/{id}', array('as' =>'Company', 'uses' => 'CompanyController@edit'));
    Route::post('admin/company/update/{id}', array('as' =>'Company', 'uses' => 'CompanyController@update'));
    Route::get('admin/company/delete/{id}', array('as' =>'Company', 'uses' => 'CompanyController@delete'));
    Route::get('admin/company/search/name', array('as'=>'Company', 'uses' => 'CompanyController@searchByCompanyName'));
    Route::get('admin/company/search/mobile', array('as'=>'Company', 'uses' => 'CompanyController@searchByMobile'));
    //campaign category
    Route::get('admin/campaign/category', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@index'));
    Route::post('admin/campaign/category/store', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@store'));
    Route::get('admin/campaign/category/show/{id}', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@show'));
    Route::get('admin/campaign/category/edit/{id}', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@edit'));
    Route::post('admin/campaign/category/update/{id}', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@update'));
    Route::get('admin/campaign/category/delete/{id}', array('as' =>'Campaign Category', 'uses' => 'CampaignCategoryController@delete'));
    Route::get('admin/campaign/category/search/name', array('as'=>'Campaign Category', 'uses' => 'CampaignCategoryController@searchByCategoryName'));

    //survey campaign
    Route::get('admin/survey/campaign', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@index'));
    Route::get('admin/survey/campaign/create', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@create'));
    Route::post('admin/survey/campaign/store', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@store'));
    Route::get('admin/survey/campaign/show/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@show'));
    Route::get('admin/survey/campaign/edit/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@edit'));
    Route::post('admin/survey/campaign/update/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@update'));
    Route::get('admin/survey/campaign/delete/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@delete'));
    Route::get('admin/survey/campaign/publish/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@publish'));
    Route::get('admin/survey/campaign/unpublish/{id}', array('as' =>'Survey Campaign', 'uses' => 'SurveyCampaignController@unpublish'));
    Route::get('admin/survey/campaign/search/title', array('as'=>'Survey Campaign', 'uses' => 'SurveyCampaignController@searchByCampaignTitle'));

    //question
    Route::get('admin/question', array('as'=>'Question', 'uses'=>'QuestionController@index'));
    Route::get('admin/question/create/{campaign_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@create'));
    Route::get('admin/question/ajax/create/{campaign_id}/{page_number}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxCreate'));
    Route::get('admin/question/ajax/check/masking/remasking/{campaign_id}/{page_number}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@checkMaskingRemasking'));
    Route::post('admin/question/store', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@store'));
    Route::get('question/page/number/check/{cam_id}/{page_number}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxPageNumberCheck'));
    Route::get('mask/question/input/option/value/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@maskQuestionInputOptionValue'));
    Route::get('remask/question/input/option/value/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@reMaskQuestionInputOptionValue'));
    Route::get('admin/question/edit/{campaign_id}/{question_id}', array('as' =>'Question', 'uses' => 'QuestionController@edit'));
    Route::post('admin/question/update/{campaign_id}/{question_id}', array('as' =>'Question', 'uses' => 'QuestionController@update'));
    Route::get('admin/question/delete/{campaign_id}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@questionDelete'));



    //edit question option group
    Route::get('admin/question/option/group/add/{campaign_id}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@addQuestionOptionGroup'));
    Route::post('admin/question/option/group/store/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@storeQuestionOptionGroup'));
    Route::get('admin/question/option/group/edit/{id}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@editQuestionOptionGroup'));
    Route::post('admin/question/option/group/update/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@UpdateQuestionOptionGroup'));
    Route::get('admin/question/option/group/delete/{id}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@questionOptionGroupDelete'));

    // branch filter
    Route::get('admin/branch/question/ajax/create/{campaign_id}/{page_number}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxCreateBranch'));
    Route::get('ajax/question/option/{question_id}/', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxQuestionOption'));
    Route::get('admin/branch/question/ajax/json/{campaign_id}/{page_number}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxQuestionOptionJsonData'));
    Route::get('admin/branch/question/ajax/edit/{campaign_id}/{page_number}/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxEditBranch'));
    Route::get('ajax/delete/branch/condition/question/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@deleteBranchingConditionQuestion'));
    Route::post('admin/question/branch/condition/update/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@branchUpdate'));


    //label name add edit delete
    Route::get('admin/question/option/group/label/create/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@optionGroupDetails'));
    Route::post('admin/question/option/group/label/store/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@optionGroupDetailsSubmit'));

    Route::get('admin/question/option/group/label/edit/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@editOptionLabel'));
    Route::post('admin/question/option/group/label/update/{id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@updateOptionLabel'));

    Route::get('admin/question/option/group/label/delete/{id}', array('as' =>'Question', 'uses' => 'QuestionController@deleteLabel'));


    Route::get('admin/question/value/ajax/{question_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxQuestionValueById'));
    Route::post('admin/question/branch/store/{campaign_id}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@branchStore'));
    Route::get('admin/question/branch/check/ajax/{campaign_id}/{page_number}', array('as'=>'Survey Campaign', 'uses'=>'QuestionController@ajaxCheckBranch'));

    /*Route::get('admin/question/input/type', array('as'=>'Question', 'uses'=>'QuestionController@getAllQuestionInputTypeByAjax'));*/
    Route::get('admin/question/show/{question_id}', array('as' =>'Survey Campaign', 'uses' => 'QuestionController@show'));
    Route::get('admin/question/option/group/delete/{id}', array('as' =>'Question', 'uses' => 'QuestionController@deleteQuestionOptionGroup'));


    //check max, min
    Route::get('check/max/min/{question_option_group_id}/{value}', array('as' =>'Question', 'uses' => 'AnswerController@checkMinMax'));

    //answer
    Route::get('admin/question/answer', array('as'=>'Survey Campaign', 'uses'=>'AnswerController@index'));
    Route::get('admin/question/answer/create/{campaign_id}/{page_number}/{user_id}', array('as'=>'Survey Campaign', 'uses'=>'AnswerController@create'));
    Route::post('admin/question/answer/store/{user_id}', array('as'=>'Question', 'uses'=>'AnswerController@store'));
    //Route::get('admin/api', array('as'=>'Survey Campaign', 'uses'=>'ApiController@getAccessToken'));

    Route::get('web/answer/form', array('as'=>'Home', 'uses'=>'AnswerController@ansForm'));
    //Page Setting
    Route::get('campaign/page/setting/{cam_id}/{page_number}', array('as'=>'Home', 'uses'=>'QuestionController@pageSetup'));

    Route::get('campaign/page/setting/after/{cam_id}/{page_number}', array('as'=>'Home', 'uses'=>'QuestionController@pageSetupAfter'));

    Route::get('campaign/page/delete/{id}/{campaign_id}/{page_number}', array('as'=>'Home',
        'uses'=>'QuestionController@pageDelete')
    );


    Route::get('check/request', array('as'=>'Home', 'uses'=>'QuestionController@checkRequest'));




});
Route::get('/', array('as'=>'Home', 'uses'=>'SurveyController@index'));
Route::get('sign-in', array('as'=>'Sign In', 'uses'=>'SurveyController@signIn'));
Route::post('login/post', array('as'=>'Sign In', 'uses'=>'SurveyController@signInPost'));
Route::get('sign-up', array('as'=>'Sign Up', 'uses'=>'SurveyController@signUp'));
Route::post('register/post', array('as'=>'Sign Up', 'uses'=>'SurveyController@signUpPost'));
Route::get('contact-us', array('as'=>'Contact Us', 'uses'=>'SurveyController@contactUs'));
Route::post('send/mail', array('as'=>'Send Mail', 'uses'=>'SurveyController@senMail'));
Route::get('logout/{name_slug}',array('as'=>'Logout' , 'uses' =>'SurveyController@logout'));
/*Route::get('call/logs', array('as'=>'Call Logs', 'uses'=>'SurveyController@getCallLogs'));
Route::get('sms/logs', array('as'=>'SMS Logs', 'uses'=>'SurveyController@getSMSLogs'));*/
#Error pages
Route::get('/error/404', array('as' =>'Oops! You are stuck at 404', 'uses' => 'ErrorController@Error404'));
Route::get('/error/500', array('as' =>'Oops! You are stuck at 500', 'uses' => 'ErrorController@Error500'));
Route::get('/error/503', array('as' =>'Oops! You are stuck at 503', 'uses' => 'ErrorController@Error503'));

//Pin confirm
Route::get('pin/confirm/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirm'));
Route::post('pin/confirm/post/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirmPost'));

Route::get('resend/pin/confirm/code/{user_mobile}/{client_otp}',array('as'=>'Resend Code For User' , 'uses' =>'SurveyController@ResendSMSForPinNumber'));


//check max, min
Route::get('check/max/min/{question_option_group_id}/{value}', array('as' =>'Question', 'uses' => 'AnswerController@checkMinMax'));

Route::group(['middleware' => ['normal_user_auth']], function () {
    /*Route::get('normal/user/dashboard', array('as' =>'User Dashboard', 'uses' => 'NormalUserController@index'));
    //profile
    Route::get('normal/user/profile',array('as'=>'Profile' , 'uses' =>'NormalUserController@Profile'));
    Route::post('normal/user/profile/update',array('as'=>'Profile Update' , 'uses' =>'NormalUserController@ProfileUpdate'));
    Route::post('normal/user/profile/image/update',array('as'=>'Profile Image Update' , 'uses' =>'NormalUserController@ProfileImageUpdate'));
    Route::post('normal/user/change/password',array('as'=>'User Change Password' , 'uses' =>'NormalUserController@UserChangePassword'));*/

    //profile
    Route::get('profile/edit/{user_id}',array('as'=>'Profile' , 'uses' =>'SurveyController@editProfile'));
    Route::post('profile/update/{user_id}',array('as'=>'Profile' , 'uses' =>'SurveyController@updateProfile'));
    Route::get('change/password/{user_id}',array('as'=>'Profile' , 'uses' =>'SurveyController@changePassword'));
    Route::post('password/update/{user_id}',array('as'=>'Profile' , 'uses' =>'SurveyController@updatePassword'));

    //Pin confirm
    Route::get('pin/confirm/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirm'));
    Route::post('pin/confirm/post/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirmPost'));



    Route::get('all/campaign', array('as'=>'All Campaign', 'uses'=>'SurveyController@getAllCampaign'));


    //Pin confirm
   /* Route::get('pin/confirm/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirm'));
    Route::post('pin/confirm/post/{mobile_number}', array('as' =>'Pin Number Confirm', 'uses' => 'SurveyController@PinConfirmPost'));*/

    //Answer
    Route::get('question/answer/{campaign_id}/{page_number}/{user_id}', array('as'=>'Answer', 'uses'=>'SurveyController@showCampaign'));
    Route::post('question/answer/post/{user_id}', array('as'=>'Answer', 'uses'=>'SurveyController@campaignAnswerPost'));

    //answer from app
    Route::get('app/answer/{cam_id}/{page_number}/{user_id}', array('as'=>'Answer', 'uses'=>'SurveyController@answerFromApp'));
    Route::post('app/answer/store/{user_id}', array('as'=>'Answer', 'uses'=>'SurveyController@storeWebApp'));

    Route::get('check/max/min/{question_option_group_id}/{value}', array('as' =>'Question', 'uses' => 'AnswerController@checkMinMax'));


});

