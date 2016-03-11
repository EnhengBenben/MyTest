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
Route::get('/session_free', function () {
    //return route('application');
    session()->put('login',null);
    session()->put('application_id',null);
}
);
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::post('auth/login', ['uses' => 'Auth\AuthController@postLogin']);
Route::post('/get_code','CodeController@sendVerifyCode');

//微信端
Route::post('/wx/code','CodeController@sendVerifyCode');
Route::post('/wx/code_change','CodeController@sendVerifyCode_change');
Route::post('/wx/sign_up','WeChatController@create');
Route::post('/wx/change','WeChatController@update');
Route::post('/wx/log_in','WeChatController@login');
Route::put('/wx/result','WeChatController@show');
Route::put('/wx/confirm_accept','WeChatController@accept');
Route::put('/wx/confirm_reject','WeChatController@reject');
Route::put('wx/course/','WeChatController@course');
Route::get('wx/footer', function() {
    return env('HOSPITAL_NAME');
});
//for appliers
Route::get('/', function () {
    return view('application.index');
});
Route::get('/newuser_signup',function() {
    return view('application.sign_up');
});
Route::post('/new_user','ApplicationController@create');
Route::get('/class', ['as' => 'class', 'uses' => 'ClientController@classIndex']);
Route::post('/class-dt', ['as' => 'class-dt', 'uses' => 'ClientController@classDt']);
//photo
Route::get('photo/{year}/{id}', ['as' => 'photo', 'uses' => 'ClientController@GetPhoto']);
//electronic_table
Route::get('electronic_table/{id}',['as'=>"electronic_table",'uses'=>'ApplicationController@electronic_table']);
//course description
Route::get('/course/description/{id}', ['as' => 'description', 'uses' => 'ClientController@description']);
Route::group(['middleware' => 'auth'], function () {

    Route::get('/apply', ['as' => 'apply', 'uses' => 'ClientController@apply']);
    Route::get('info/{id}', ['as' => 'info_course', 'uses' => 'ClientController@info_course']);
    Route::get('course_enroll/{id}', ['uses' => 'ClientController@course_enroll']);
    Route::get('/query', function () {
            return redirect(route('apply'));
    });
    Route::get('/info', ['as' => 'info', 'uses' => 'ClientController@info']);
    Route::post('/information', ['uses' => 'ClientController@store']);
    Route::get('/edit', ['as' => 'edit', 'uses' => 'ClientController@editRefresh']);
    Route::post("/edit/{course_id}", ['uses' => 'ClientController@edit']);
    Route::put('/information', ['uses' => 'ClientController@update']);
    Route::post('/query', ['uses' => 'ClientController@query']);

//upload printed application
    Route::post("/upload/{id}", ['uses' => 'ClientController@upload']);
//preview
    Route::get('/preview/{flag}', ['as' => 'preview', 'uses' => 'ClientController@preview']);
//docx
    Route::get('download_enrollment/{id}', ['uses' => 'ClientController@download_enrollment']);
    Route::get('download_application/{id}', ['uses' => 'ClientController@download_application']);
//agree
    Route::get('/agree/{id}', ['uses' => 'ClientController@agree']);
    Route::get('/refuse/{id}', ['uses' => 'ClientController@refuse']);
});
//refuse
//for admin


/*Route::group(['middleware' => 'auth'], function () {
    //course control
    Route::get('course',['as'=>'course','uses'=>'CourseController@index']);
    Route::post('course-dt',['as'=>'course-dt','uses'=>'CourseController@indexDt']);
    Route::get('course/edit/{id}',['as'=>'course.edit','uses'=>'CourseController@edit']);
    Route::put('course/{id}',['uses'=>'CourseController@update']);
    Route::get('course/delete_attachment/{id}/{name}',['uses'=>'CourseController@delete_attachment']);
    Route::get('course/unpublish/{id}',['as'=>'course.unpublish','uses'=>'CourseController@unpublish']);
    Route::get('course/publish/{id}',['as'=>'course.publish','uses'=>'CourseController@publish']);
    Route::get('course/add',['as'=>'course.add','uses'=>'CourseController@add']);
    Route::post('course',['uses'=>'CourseController@store']);
    Route::get('course_unpub',['as'=>'course_unpub','uses'=>'CourseController@index_unpub']);
    Route::post('course_unpub-dt',['as'=>'course_unpub-dt','uses'=>'CourseController@indexDt_unpub']);
        //course to application
    Route::get('appliers/{id}',['uses'=>'CourseController@appliers']);
        //course to student
    Route::get('students/{id}',['uses'=>'CourseController@students']);
    //application control
    Route::get('application', ['as' => 'application', 'uses' => 'ApplicationController@index']);
    Route::post('application-dt', ['as' => 'application-dt', 'uses' => 'ApplicationController@indexDt']);
    Route::get('/preview_admin/{id}/{course_name}',['as'=>'preview_admin','uses'=>'ApplicationController@preview']);
    Route::get('electronic_table/{id}',['as'=>"electronic_table",'uses'=>'ApplicationController@electronic_table']);
    Route::get('electronic_view/{id}/{status}',['as'=>'electronic_view','uses'=>'ApplicationController@electronic_view']);
    Route::get('/recommender/{id}',['as'=>'recommender','uses'=>'ApplicationController@addRecommender']);
    Route::put('/recommender/{id}',['uses'=>'ApplicationController@RecommenderUpdate']);
    Route::get('application/pass/{id}',['as'=>'application.pass','uses'=>'ApplicationController@pass']);
    Route::get('application/reject/{id}',['as'=>'application.reject','uses'=>'ApplicationController@reject']);
    Route::post('application/operate/{id}',['uses'=>'ApplicationController@operate']);
    Route::get('application_cancel',['as'=>'application_cancel','uses'=>'ApplicationController@index_cancel']);
    Route::post('application_cancel-dt', ['as' => 'application_cancel-dt', 'uses' => 'ApplicationController@indexDt_cancel']);
    Route::get('application_unSubmit',['as'=>'application_unSubmit','uses'=>'ApplicationController@index_unSubmit']);
    Route::post('application_unSubmit-dt', ['as' => 'application_unSubmit-dt', 'uses' => 'ApplicationController@indexDt_unSubmit']);
    //积分设置
        //技术职称
    Route::get('score/tech_duty',['as'=>'score.tech_duty','uses'=>'ApplicationController@tech_duty']);
    Route::post('tech_duty-dt',['as'=>'tech_duty-dt','uses'=>'ApplicationController@tech_dutyDt']);
    Route::get('tech_duty/edit/{id}',['as'=>'tech_duty.edit','uses'=>'ApplicationController@TechEdit']);
    Route::put('tech_duty/{id}',['uses'=>'ApplicationController@TechUpdate']);
    Route::get('tech_duty/delete/{id}',['uses'=>'ApplicationController@TechDelete']);
    Route::get('tech_duty/add',['uses'=>'ApplicationController@TechAdd']);
    Route::post('tech_duty',['uses'=>'ApplicationController@TechStore']);
        //学历
    Route::get('score/degree',['as'=>'score.degree','uses'=>'ApplicationController@degree']);
    Route::post('degree-dt',['as'=>'degree-dt','uses'=>'ApplicationController@degreeDt']);
    Route::get('degree/edit/{id}',['as'=>'degree.edit','uses'=>'ApplicationController@degreeEdit']);
    Route::put('degree/{id}',['uses'=>'ApplicationController@degreeUpdate']);
    //Route::get('tech_duty/delete/{id}',['uses'=>'ApplicationController@TechDelete']);
    Route::get('degree/add',['uses'=>'ApplicationController@degreeAdd']);
    Route::post('degree',['uses'=>'ApplicationController@degreeStore']);
        //医院级别
    Route::get('score/org_rank',['as'=>'score.org_rank','uses'=>'ApplicationController@org_rank']);
    Route::post('org_rank-dt',['as'=>'org_rank-dt','uses'=>'ApplicationController@org_rankDt']);
    Route::get('org_rank/edit/{id}',['as'=>'org_rank.edit','uses'=>'ApplicationController@org_rankEdit']);
    Route::put('org_rank/{id}',['uses'=>'ApplicationController@org_rankUpdate']);
    //Route::get('tech_duty/delete/{id}',['uses'=>'ApplicationController@TechDelete']);
    Route::get('org_rank/add',['uses'=>'ApplicationController@org_rankAdd']);
    Route::post('org_rank',['uses'=>'ApplicationController@org_rankStore']);
        //行政职务
    Route::get('score/admin_duty',['as'=>'score.admin_duty','uses'=>'ApplicationController@admin_duty']);
    Route::post('admin_duty-dt',['as'=>'admin_duty-dt','uses'=>'ApplicationController@admin_dutyDt']);
    Route::get('admin_duty/edit/{id}',['as'=>'admin_duty.edit','uses'=>'ApplicationController@admin_dutyEdit']);
    Route::put('admin_duty/{id}',['uses'=>'ApplicationController@admin_dutyUpdate']);
    //Route::get('tech_duty/delete/{id}',['uses'=>'ApplicationController@TechDelete']);
    Route::get('admin_duty/add',['uses'=>'ApplicationController@admin_dutyAdd']);
    Route::post('admin_duty',['uses'=>'ApplicationController@admin_dutyStore']);
        //地域
    Route::get('score/region',['as'=>'score.region','uses'=>'ApplicationController@region']);
    Route::post('region-dt',['as'=>'region-dt','uses'=>'ApplicationController@regionDt']);
    Route::get('region/edit/{id}',['as'=>'region.edit','uses'=>'ApplicationController@regionEdit']);
    Route::put('region/{id}',['uses'=>'ApplicationController@regionUpdate']);
    //Route::get('tech_duty/delete/{id}',['uses'=>'ApplicationController@TechDelete']);
    Route::get('region/add',['uses'=>'ApplicationController@regionAdd']);
    Route::post('region',['uses'=>'ApplicationController@regionStore']);
        //
    //student control
    Route::get('contact',['as'=>'contact','uses'=>'StudentController@index']);
    Route::post('contact-dt',['as'=>'contact-dt','uses'=>'StudentController@indexDt']);
    Route::post('contact-export',
        ['as' => 'admin.contact.index_export', 'uses' => 'StudentController@indexExport']);
});

*/

