<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::group(['namespace' => 'API','middleware' => ['check_access_token']], function(){
//     Route::group(['mddleware' => ['check_token']], function(){
//         Route::get('/testapi', function(){
//             return 'hello';
//         });
//         // Route::get('get-comments/conversation/{conversation_id}/sphere/{sphere_id}','CommentController@getCommentsConversation');

//     });

// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return 'hello';
    return $request->user();
});

Route::any('/get-data-events-user-for-calender/', 'UserController@getDataEventsUserForCalender');
Route::any('/get-data-tasks-user-for-calender/', 'UserController@getDataTasksUserForCalender');

//list post 
Route::get('/posts','PostsSphereController@index');
//list single post
Route::get('/details-post/{id}/{sphere_id}','PostsSphereController@showPostsSphere');
Route::get('/comments-post/{id}','PostsSphereController@commentsPost');
// create new post
Route::post('/add-post-sphere/{sphereId}/{userId}','PostsSphereController@storePostsSphere');
//update
Route::put('/edit-post-sphere/{sphereId}/{userId}','PostsSphereController@destroyPostsSphere');
//delete
Route::delete('/post/{id}/{sphereId}/{userId}','PostsSphereController@destroyPostsSphere');


Route::get('/get-all-posts-home/','PostsSphereController@getAllPostsInHome');

//delete
Route::delete('/post-user/{postId}/{userId}','ProfileUserController@destroy');





// create new post
Route::post('/add-post-home/{userId}','PostsHomeController@storePostsDashboard');

//in page posts home//


Route::get('/comments-post-dashboard/{post_id}/{sphereid}','PostsHomeController@detailsPostDshboard');

//update
Route::put('/edit-post-home/{userId}','PostsHomeController@storePostsDashboard');
//delete
Route::delete('/post/{id}/{sphereId}/{userId}','PostsHomeController@destroyPostsDashboard');

//comments//
//list comments
Route::get('/comments','CommentController@index');
//list single comment
Route::get('/get-comment/{comment_id}/{post_id}/{sphereId}','CommentController@showPostsSphere');
// create new comment
Route::post('/post-comment/{post_id}/{sphereId}/{userid}','CommentController@storePostsSphere');
//update
Route::put('/post-comment/{comment_id}/{post_id}/{sphereId}/{userId}','CommentController@updatePostsSphere');
//delete
Route::delete('/delete-comment/{comment_id}/{post_id}/{sphereId}/{userId}','CommentController@destroyPostsSphere');


//comments in specific post that it in dashboard post
// create new comment
Route::post('/post-comment/{post_id}/{userid}','CommentController@storeGeneral');
//update
Route::put('/post-comment/{comment_id}/{post_id}/','CommentController@updateGeneral');

//delete
Route::delete('/delete-comment/{comment_id}/{post_id}/','CommentController@destroyGeneral');

//in page project details//
// Route::put('/post-comment/{comment_id}/{sphereId}/{projectId}','CommentController@updateCommentProject');

//view details project
Route::any('/view-details-project/{sphere_id}/{project_id}','ProjectDetailsController@indexProjectDetails');
Route::any('/comments-project/{sphere_id}/{project_id}','ProjectDetailsController@commentsProject');

Route::post('/add-comment-on-project/{sphere_id}/{project_id}/{user_id}','ProjectDetailsController@addCommentOnProject');
Route::put('/update-comment-project/{comment_id}/{sphere_id}/{project_id}/{userid}','ProjectDetailsController@updateCommentProject');
Route::delete('/delete-comment-project/{comment_id}/{sphere_id}/{project_id}/{userid}','ProjectDetailsController@deleteCommentProject');

//

// for category tasks
Route::get('/category/project/{project_id}/sphere/{sphereId}', 'CategoryController@indexCategory');
Route::resource('/category', 'CategoryController');
Route::resource('/task', 'TaskController');
Route::delete('/delete-task/{id}/{user_id}', 'TaskController@deleteCategory');
Route::put('/update-task/{id}/{category_id}/{user_id}/', 'TaskController@updateCategory');
Route::post('/add-task/{category_id}/{user_id}/{sphereId}/{projectId}', 'TaskController@storeCategory');
Route::get('/category/{category}/tasks/project/{project_id}/sphere/{sphere_id}', 'CategoryController@tasksCategory');
Route::get('/check-member/{userId}/category/{category}/task/{taskId}/project/{project_id}/sphere/{sphere_id}', 'CategoryController@checkMemberJoinInTasksCategory');
Route::get('get-members/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getMembersSphereProjectCategory')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::get('/get-members-to-invitation-join-task/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getMembersSphereProjectToJoinInTaskCategory');
Route::get('get-data-member/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getdataMemberSphereProjectCategory');
Route::put('/assign-task-for-member/task/{taskId}/member/{mescmdmber_id}/sphere/{sphere_id}/project/{project_id}/user/{userId}', 'UserController@assignInTaskCategory');
Route::put('/cancel-asigned/task/{taskId}/sphere/{sphereId}/project/{projectId}/', 'UserController@cancelAssignTaskCategory');
Route::put('/cancel-invit/task/{taskId}/category/{categoryId}/sphere/{sphereId}/project/{projectId}/', 'UserController@cancelInvitTaskCategory');

//conversations
 Route::get('/get-data-user-comments/conversation/{conversation_id}/sphere/{sphere_id}','CommentController@getDataCommentsConversation');
 Route::get('get-info-user/{user_id}/','CommentController@getInfoUser');
 Route::post('/add-comment-on-conversation/{sphere_id}/{conversationId}/{userId}','ConversationsController@addCommentConversation');
 Route::post('/add-reply-comment-on-conversation/{sphere_id}/{conversationId}/comment/{commentId}/user/{userid}','ConversationsController@addReplyCommentConversation');
 Route::put('/update-comment-conversation/{comment_id}/{sphere_id}/{conversationId}','ConversationsController@updateCommentConversation');
 Route::put('/update-reply-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{reply_id}/{userId}','ConversationsController@updateReplyCommentConversation');
 Route::delete('/delete-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{userid}','ConversationsController@deleteCommentConversation')->where(['comment_id'=> '[0-9]+','sphere_id'=>'[0-9]+','conversationId'=>'[0-9]+','userid'=>'[0-9]+']);
 Route::delete('/delete-reply-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{reply_id}/{userid}','ConversationsController@deleteReplyCommentConversation')->where(['comment_id'=> '[0-9]+','sphere_id'=>'[0-9]+','conversationId'=>'[0-9]+','reply_id'=>'[0-9]+','userid'=>'[0-9]+']);
//tasks in project tool manager
 Route::post('/add-description-into-task/{taskId}/{userId}','TaskController@addDescriptionTask')->where(['taskId'=> '[0-9]+','userId'=>'[0-9]+']);
 Route::get('get-comments/task/{category_id}/{task_id}/sphere/{sphere_id}/{project_id}','CommentController@getCommentsTask')->where(['category_id'=> '[0-9]+','task_id'=>'[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+']);
 Route::post('/add-comment-on-task/{category_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@addCommentTask')->where(['category_id'=> '[0-9]+','sphere_id'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::put('/update-comment-task/{category_id}/{comment_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@updateCommentTask')->where(['category_id'=> '[0-9]+','sphere_id'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::delete('/delete-comment-task/{category_id}/{comment_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@deleteCommentTask')->where(['category_id'=> '[0-9]+','sphere_id'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::get('/get-description-task/{task_id}','TaskController@getDescriptionTask')->where(['task_id'=> '[0-9]+']);
 //in page dashboard 
Route::any('/store-user-in-mention-table/{userid}/comment/{comment_id}/post/{post_id}/sphere/{sphere_id}', 'UserController@storeUserInMentionTable')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::get('/get-all-users/', 'UserController@getAllUsers');
Route::get('/show-user/{email}', 'UserController@showUser');
Route::get('/show-user-email/{id}', 'UserController@showEmailUser')->where(['id'=> '[0-9]+']);
Route::get('/get-user-mention-comment/{comment}/post/{id}/sphere', 'UserController@getUserMentionComment')->where(['id'=> '[0-9]+']);


