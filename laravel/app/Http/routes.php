<?php
/**
 * This file handles all of the application routing.
 *
 * Controllers go in /laravel/app/Http/Controllers/
 * Models go in /laravel/app/ (EX: User)
 */
Route::get('/Admin', ['as' => 'admin.home', 'uses' => 'AdminController@do_home']);
Route::any('/Admin/listUsers', ['as' => 'admin.listusers', 'uses' => 'AdminController@do_listUsers']);
Route::any('/Admin/listRoles', ['as' => 'admin.listroles', 'uses' => 'AdminController@do_listRoles']);
Route::any('/Admin/addUser', ['as' => 'admin.adduser', 'uses' => 'AdminController@do_addUser']);
Route::any('/Admin/editUser/{user_id}', ['as' => 'admin.edituser', 'uses' => 'AdminController@do_editUser'])->where('user_id', '\d+');
Route::any('/Admin/addRole', ['as' => 'admin.addrole', 'uses' => 'AdminController@do_addRole']);
Route::any('/Admin/editRole/{user_id}', ['as' => 'admin.editrole', 'uses' => 'AdminController@do_editRole'])->where('role_id', '\d+');
Route::any('/signIn', ['as' => 'admin.signin', 'uses' => 'AdminController@do_signIn']);
Route::any('/signOut', ['as' => 'admin.signout', 'uses' => 'AdminController@do_signOut']);
