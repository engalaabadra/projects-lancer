//in page dashboard
Route::any('/store-user-in-mention-table/{userid}/comment/{comment_id}/post/{post_id}/sphere/{sphere_id}', 'UserController@storeUserInMentionTable');
Route::get('/get-all-users/', 'UserController@getAllUsers');
Route::get('/show-user/{email}', 'UserController@showUser');
Route::get('/show-user-email/{id}', 'UserController@showEmailUser')->where(['id'=>'[0-9]+']);
Route::get('/get-user-mention-comment/{comment}/post/{id}/sphere', 'UserController@getUserMentionComment')->where(['id'=>'[0-9]+']);
