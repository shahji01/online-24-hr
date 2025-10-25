<?php
//Start Users
Route::group(['prefix' => 'users','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/u', 'UsersController@toDayActivity');
    Route::get('/createMainMenuTitleForm','UsersController@createMainMenuTitleForm');
    Route::get('/createSubMenuForm','UsersController@createSubMenuForm');
    Route::get('/createUsersForm', 'UsersController@createUsersForm');
    Route::get('/createRoleForm','UsersController@createRoleForm');
    Route::get('/viewRoleList','UsersController@viewRoleList');
    Route::get('/viewEmployeePrivileges/{id}','UsersController@viewEmployeePrivileges');
    Route::get('/cloneEmployeePrivileges/{id}','UsersController@cloneEmployeePrivileges');
    Route::get('/editUserProfile','UsersController@editUserProfile');
    Route::get('/editMyProfile','UsersController@editMyProfile');
    Route::get('/editSubMenuDetailForm', 'UsersController@editSubMenuDetailForm');

});

Route::group(['prefix' => 'udc','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/viewMainMenuTitleList','UsersDataCallController@viewMainMenuTitleList');
    Route::get('/viewSubMenuList','UsersDataCallController@viewSubMenuList');
});

Route::group(['prefix' => 'uad','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::post('/addMainMenuTitleDetail','UsersAddDetailController@addMainMenuTitleDetail');
    Route::post('/addSubMenuDetail','UsersAddDetailController@addSubMenuDetail');
    Route::post('/addRoleDetail','UsersAddDetailController@addRoleDetail');

    /*Edit Routes*/
    Route::post('/editUserPasswordDetail','UsersEditDetailController@editUserPasswordDetail');
    Route::post('/editUserRoleDetail','UsersEditDetailController@editUserRoleDetail');
    Route::post('/editApprovalCodeDetail','UsersEditDetailController@editApprovalCodeDetail');
    Route::post('/editSubMenuDetail','UsersEditDetailController@editSubMenuDetail');
    Route::post('/editUserProfileDetail','UsersEditDetailController@editUserProfileDetail');




});
//End Users
