<?php

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    
        return redirect('/home');
    
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

//Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/downloadPDF','HomeController@downloadPDF');

    Route::get('export', 'HomeController@export')->name('export');
    Route::get('importExportView', 'HomeController@importExportView');
    Route::post('import', 'HomeController@import')->name('import');



    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/changeuser/{user}', 'HomeController@changeuser')->name('users.change')->middleware('permission:users.change');

	
    Route::get('ajaxrequest', 'HomeController@ajaxrequest')->name('ajaxrequest')->middleware('permission:delays.create|statements.create|attendance.scan|attendance.create');
    
    Route::get('importfromintranet', 'HomeController@importDataFromIntranet')->name('importfromintranet');//->middleware('permission:users.import');
	
    Route::post('statements/store', 'StatementController@store')->name('statements.store')->middleware('permission:statements.create');
    Route::get('statements', 'StatementController@index')->name('statements.index')->middleware(['permission:statements.index|statements.show']);
    Route::get('statements/create', 'StatementController@create')->name('statements.create')->middleware('permission:statements.create');
    Route::get('statements/sign', 'StatementController@sign')->name('statements.sign')->middleware('permission:statements.sign');
    Route::get('statements/{statement}/edit', 'StatementController@edit')->name('statements.edit')->middleware('permission:statements.edit');
    Route::put('statements/{statement}', 'StatementController@update')->name('statements.update')->middleware('permission:statements.edit');
    Route::get('statements/{statement}', 'StatementController@show')->name('statements.show')->middleware('permission:statements.show');
    Route::delete('statements/{statement}', 'StatementController@destroy')->name('statements.destroy')->middleware('permission:statements.destroy');
    

    Route::post('delays/store', 'DelayController@store')->name('delays.store')->middleware('permission:delays.create');
    Route::get('delays', 'DelayController@index')->name('delays.index')->middleware(['permission:delays.index|delays.show']);
    // Route::get('delays/create', 'DelayController@create')->name('delays.create')->middleware('permission:delays.create');
    Route::get('delays/sign', 'DelayController@sign')->name('delays.sign')->middleware('permission:delays.sign');
    Route::get('delays/{statement}/edit', 'DelayController@edit')->name('delays.edit')->middleware('permission:delays.edit');
    Route::put('delays/{statement}', 'DelayController@update')->name('delays.update')->middleware('permission:delays.edit');
    Route::get('delays/{statement}', 'DelayController@show')->name('delays.show')->middleware('permission:delays.show');
    Route::delete('delays/{statement}', 'DelayController@destroy')->name('delays.destroy')->middleware('permission:delays.destroy');
      

    Route::get('attendance/register', 'AttendanceController@register')->name('attendance.register')->middleware('permission:attendance.create');
    Route::post('attendance/store', 'AttendanceController@store')->name('attendance.store')->middleware('permission:attendance.create');
    Route::get('attendance/send', 'AttendanceController@send')->name('attendance.send')->middleware('permission:attendance.send');
    Route::get('attendance/delays', 'AttendanceController@delays')->name('attendance.delays')->middleware('permission:attendance.index');
    Route::post('attendance/emit', 'AttendanceController@emit')->name('attendance.emit')->middleware('permission:attendance.send');
    Route::get('attendance/scan', 'AttendanceController@showscan')->name('attendance.scan')->middleware('permission:attendance.scan');
    Route::get('attendance/myqr', 'AttendanceController@getQRCode')->name('attendance.myqr');
    Route::get('attendance/download', 'AttendanceController@download')->name('attendance.download')->middleware(['permission:attendance.index']);
    Route::get('attendance/export', 'AttendanceController@export')->name('attendance.export')->middleware(['permission:attendance.index']);
    
    // Route::post('attendance/process', 'AttendanceController@registerscan')->name('attendance.process')->middleware('permission:attendance.scan');
    // Route::get('attendance', 'AttendanceController@index')->name('attendance.index')->middleware('permission:attendance.index');
    // Route::put('attendance/{attendance}', 'AttendanceController@update')->name('attendance.update')->middleware('permission:attendance.edit');
    // Route::get('attendance/{attendance}', 'AttendanceController@show')->name('attendance.show')->middleware('permission:attendance.show');
    // Route::delete('attendance/{attendance}', 'AttendanceController@destroy')->name('attendance.destroy')->middleware('permission:attendance.destroy');
    // Route::get('attendance/{attendance}/edit', 'AttendanceController@edit')->name('attendance.edit')->middleware('permission:attendance.edit');

    Route::get('familyschools/create', 'FamilySchoolController@create')->name('familyschools.create')->middleware('permission:familyschools.create');
    Route::post('familyschools/store', 'FamilySchoolController@store')->name('familyschools.store')->middleware('permission:familyschools.create');
    Route::get('familyschools/{familyschool}/edit', 'FamilySchoolController@edit')->name('familyschools.edit')->middleware('permission:familyschools.edit');

    Route::get('familyschools/register', 'FamilySchoolController@register')->name('familyschools.register')->middleware('permission:familyschools.create');
    Route::get('familyschools/send', 'FamilySchoolController@send')->name('familyschools.send')->middleware('permission:familyschools.send');
    Route::get('familyschools/scan', 'FamilySchoolController@showscan')->name('familyschools.scan')->middleware('permission:familyschools.scan');


    Route::resource('user', 'UserController', ['except' => ['show']]);
    
    Route::resource('roles','RoleController');
    
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

});
