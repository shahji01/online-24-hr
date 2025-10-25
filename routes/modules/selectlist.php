<?php

Route::group(['prefix' => 'slal','before' => 'csrf'], function () {
    Route::get('/stateLoadDependentCountryId', 'SelectListLoadAjaxController@stateLoadDependentCountryId');
    Route::get('/cityLoadDependentStateId', 'SelectListLoadAjaxController@cityLoadDependentStateId');
    Route::get('/getEmployeeProjectList', 'SelectListLoadAjaxController@getEmployeeProjectList');
    Route::get('/getEmployeeCategoriesList', 'SelectListLoadAjaxController@getEmployeeCategoriesList');
	Route::get('/getSubDepartment','SelectListLoadAjaxController@getSubDepartment');
	
	Route::get('/viewEmployeeLoansList','SelectListLoadAjaxController@viewEmployeeLoansList');

	Route::get('/getEmployee','SelectListLoadAjaxController@getEmployee');
    Route::get('/getEmployeeMultiple','SelectListLoadAjaxController@getEmployeeMultiple');
	Route::get('/getSubDepartments','SelectListLoadAjaxController@getSubDepartments');

    Route::get('/locationsList','SelectListLoadAjaxController@locationsList');
    Route::get('/locationsListUsingId','SelectListLoadAjaxController@locationsListUsingId');
    Route::get('/locationsListWithAllOption','SelectListLoadAjaxController@locationsListWithAllOption');
    Route::get('/getGradeByDesignation','SelectListLoadAjaxController@getGradeByDesignation');


});