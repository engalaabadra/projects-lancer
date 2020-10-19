<?php
use Illuminate\Support\Facades\Route;
//for pages user without login // index page that it conatin on login , reg
Route::any('/view-all-spheres','SpheresController@getAllSpheres');

//*********** for admin ***********//
Route::any('/admin/reg','AdminController@regAdmin');
Route::any('/admin/login','AdminController@loginAdmin');
Route::any('/admin/logout','AdminController@logoutAdmin');
Route::any('/admin/view-spheres/','SpheresController@getSpheresForAdmin');
Route::any('/admin/activate-sphere/{sphereid}','SpheresController@activateStatusSphere');
Route::any('/admin/deactivate-sphere/{sphereid}','SpheresController@deActivateStatusSphere');
Route::any('/admin/update-sphere/{sphereid}','SpheresController@editSphereByAdminOnly');
//pages admin
Route::get('/admin/view-users','AdminController@viewUsers');
Route::get('/admin/show-answers-user/{userid}','AdminController@showAnswersUser');
////////////////////////////////////////////////////////////////////////////////////
//*********** for user ***************//
//for functionalties for register user
Route::any('/user/index','UserController@index');
Route::any('/user/reg','UserController@regUser');
Route::any('/user/login','UserController@loginUser')->name('login');
Route::any('/user/forgot-password','UserController@forgotPassword');
Route::get('/user/confirm/{code}','UserController@confirmAccount');
Route::any( '/user/answer-on-questions-reg','UserController@AnswerOnQuestionsReg');
Route::any( '/user/answer-on-questions','UserController@AnswerOnQuestions');
Route::get('/admin/activate-account-user/{email}','AdminController@activateAccountUser');
Route::get('/admin/deactivate-account-user/{email}','AdminController@deActivateAccountUser');
Route::any('/user/logout','UserController@logoutUser');
Route::get('/get-memberName/{memberId}', 'UserController@getMemberName');
//for OAuth
Route::get('user/auth/facebook/callback', 'UserController@handleProviderCallbackFacebook');
Route::get('user/auth/github/callback', 'UserController@handleProviderCallbackGithub');
Route::get('user/auth/google/callback', 'UserController@handleProviderCallbackGoogle');
Route::get('user/auth/facebook/', 'UserController@redirectToProviderFacebook');
Route::get('user/auth/github/', 'UserController@redirectToProviderGithub');
Route::get('user/auth/google/', 'UserController@redirectToProviderGoogle');
Route::get('/show-all-users/{sphere_id}', 'UserController@showAllUsers');
Route::any('/get-data-tasks-user-for-calender/', 'UserController@getDataTasksUserForCalender');
Route::any('/get-data-events-user-for-calender/', 'UserController@getDataEventsUserForCalender');
//for profile user
Route::any('/user/update-password/{user_id}','UserController@updatePassword')->where(['user_id'=> '[0-9]+']);
Route::any('/user/edit-profile/{id}','UserController@editProfile')->where(['id'=> '[0-9]+']);
Route::any('/user/change-image/{id}','UserController@changeImage')->where(['id'=> '[0-9]+']);
Route::any('/user/change-cover-image/{id}','UserController@changeImageCover')->where(['id'=> '[0-9]+']);
/////////////////**********************/////////////////////////
////for member profile page
Route::any('/user/view-profile-member/{membername}','UserController@viewProfileMember');
/////////////////**********************/////////////////////////
////--functionalty in page profile user
Route::any('follow-member/{userId}/{followerId}','UserController@followMember');
Route::any('/accept-follow/{userId}/{followerId}','UserController@acceptFollow');
Route::any('/decline-follow/{userId}/{followerId}','UserController@declineFollow');
Route::any('/decline-follow-member/{userId}/{followerId}','UserController@declineFollowMember');
/////////////////**********************/////////////////////////
////containing user profile page 
Route::get('/user/view-profile/{email}','UserController@getDataUser');
Route::get('/user/view-profile/{email}/followers','UserController@viewFollowersUser');
Route::get('/user/change-cover-image/{userId}','UserController@editUserCoverImage')->where(['userId'=> '[0-9]+']);
Route::get('/user/get-all-invitations/{userId}','UserController@getAllInvitations')->where(['userId'=> '[0-9]+']);

/////////////////**********************/////////////////////////
//===== dashboard =====//
Route::get('/user/dashboard','PostsSphereController@getAllPostsDashboard');
Route::get('/user/details-post-dashboard/{post_id}/{sphereId}','PostsSphereController@detailsPostDashboard')->where(['post_id'=> '[0-9]+','sphere_id'=>'[0-9]+']);
Route::get('/user/details-post/{post_id}/','PostsSphereController@detailsPost')->where(['post_id'=> '[0-9]+']);
//***********for pages in sphere ***************//
///seacrh data
Route::any('/search-data/', 'UserController@searchData');
////add sub sphere , project
Route::any('/user/add-sphere/','SpheresController@createSphere');
Route::any('/user/add-project/{sub_sphere_id}','ProjectsController@addProjectIntoSubSphere')->where(['sub_sphere_id'=> '[0-9]+']);
////edit sphere , project 
Route::any('/user/edit-project/{projectId}/{sphereId}','ProjectsController@editProject')->where(['projectId'=> '[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/user/edit-image-project/{projectId}/{sphereId}','ProjectsController@editProjectImage')->where(['projectId'=> '[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/user/edit-sphere/{sphereId}','SpheresController@editSphere')->where(['sphereId'=> '[0-9]+']);
Route::any('/user/edit-image-sphere/{sphereId}','SpheresController@editSphereImage')->where(['sphereId'=> '[0-9]+']);
Route::any('/user/edit-cover-image-sphere/{sphereId}','SpheresController@editSphereCoverImage')->where(['sphereId'=> '[0-9]+']);
//***********for pages in sphere (route main page sphere) ***************//
////posts sphere
Route::any('/posts-sphere/{sphereid}','PostsSphereController@PostsInSphere')->where(['sphereid'=> '[0-9]+']);
Route::any('/sphere/{sphere_id}/posts','SpheresController@PostsSphere')->where(['sphere_id'=> '[0-9]+']);
Route::any('/sphere/{sphere_id}/post/{post_id}/comment','SpheresController@CommentsOnPostsSphere')->where(['sphere_id'=> '[0-9]+','post_id'=>'[0-9]+']);
Route::get('/get-posts-sphere/{sphere_id}','SpheresController@getPostSphere')->where(['sphere_id'=> '[0-9]+']);
Route::get('/details-post/{post_id}/{sphereId}','PostsSphereController@detailsPost')->where(['post_id'=> '[0-9]+','sphere_id'=>'[0-9]+']);
////projects sphere(route projects sphere)
Route::any('/sphere/{sphere_id}/projects','SpheresController@ProjectsSphere')->where(['sphere_id'=> '[0-9]+']);
Route::any('view-details-project/{sphere_id}/{project_id}','ProjectsController@viewDetailsProject')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+'])->name('project.details');
//page conversations
Route::get('/sphere/{sphereid}/conversations/','ConversationsController@getAllConversations')->where(['sphereid'=> '[0-9]+']);
Route::get('/view-conversation/{conversationid}/sphere/{sphereid}','ConversationsController@viewConversation')->where(['conversation_id'=> '[0-9]+','sphere_id'=>'[0-9]+']);
Route::any('/user/add-project/conversation/{conversation_id}/sphere/{sphereId}/user/{userId}/topic/{topic_id}','ConversationsController@addProjectInConversation')->where(['sphereId'=> '[0-9]+','userId'=>'[0-9]+','topic_id'=>'[0-9]+']);
Route::any('/user/add-new-topic/conversation/{conversation_id}/sphere/{sphereId}/user/{userId}','ConversationsController@addNewTopicInConversation')->where(['conversation_id'=> '[0-9]+','sphereId'=>'[0-9]+','userId'=>'[0-9]+']);
Route::any('/user/add-new-topic/specific-conversation/sphere/{sphereId}/user/{userId}','ConversationsController@addNewTopicInSpecificConversation')->where(['sphereId'=> '[0-9]+','userId'=>'[0-9]+']);
Route::any('/user/add-new-conversation/sphere/{sphereId}/user/{userId}','ConversationsController@addNewConversation')->where(['sphereId'=> '[0-9]+','userId'=>'[0-9]+']);
Route::any('/user/close-topic/{topicId}','ConversationsController@closeMyTopic')->where(['topicId'=> '[0-9]+']);
Route::post('/add-user-view-into-conversation/{conversationId}/sphere/{sphere_id}/','ConversationsController@addViewConversation')->where(['conversationId'=> '[0-9]+','sphere_id'=>'[0-9]+']);
//page events
Route::get('/sphere/{sphereid}/events','EventsController@getAllEvents')->where(['sphereid'=> '[0-9]+']);
Route::get('/view-details-event/{sphereid}/{event_id}','EventsController@viewDetailsEvent');
Route::any('/create-room/{sphereid}','ConversationsController@createRoom')->where(['sphereid'=>'[0-9]+']);
Route::any('/schedule-event-upcoming/{sphereid}','EventsController@scheduleEventUpcoming')->where(['sphereid'=>'[0-9]+']);
Route::any('/schedule-event-previous/{sphereid}','EventsController@scheduleEventPrevious')->where(['sphereid'=>'[0-9]+']);
Route::post('/typeing-event/', 'ChatController@typingEvent');
////surveys sphere
Route::any('/sphere/{sphere_id}/surveys','SurveysController@getSurveys')->where(['sphere_id'=>'[0-9]+']);
Route::any('/answer-survey/{survey_id}/{sphere_id}','SurveysController@answerSurvey')->where(['survey_id'=>'[0-9]+']);
Route::any('/add-survey/{sphere_id}','SurveysController@addSurveyUser')->where(['sphere_id'=>'[0-9]+']);
Route::any('/delete-my-survey/{sphere_id}/{survey_id},SurveysController@deleteMySurvey');
Route::any('/delete-my-answer-on-survey/{sphere_id}/{survey_id}/{userid},SurveysController@deleteMyAnswerOnSurvey');
Route::any('/get-answers-survey/{sphere_id}/{survey_id}','SurveysController@getAllAnswersSurvey')->where(['sphere_id'=> '[0-9]+','survey_id'=>'[0-9]+']);
Route::any('/get-all-surveys-joined-it','SurveysController@getSurveysJoinedIt');
////votes projects in sphere
Route::get('/main-page-sphere/projects-in-sphere-to-votes/{sphere_id}','VotesController@ProjectsInSphereToVotes')->where(['sphere_id'=>'[0-9]+']);
Route::any('/main-page-sphere/add-vote-on-project-in-sphere/{sphere_id}/{project_id}','VotesController@addVoteOnProjectInSphere')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+']);
////tasks project in sphere
Route::any('/sphere/{sphere_id}/tasks','TaskController@getPageTasksSphere')->where(['sphere_id'=>'[0-9]+']);
Route::any('/all-tasks-projects-sphere/{sphere_id}/{project_id}','TaskController@getAllTasksSphere')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+']);
Route::any('/add-to-arena/{sphere_id}/{project_id}/{user_id}','ConversationsController@addToArena')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
//arena sphere
Route::get('/get-arena/{sphereId}', 'SpheresController@getArenaSphere')->where(['sphereId'=>'[0-9]+']);
////route my tasks sphere , my tasks in whole website
Route::get('/sphere/{sphereId}/my-tasks', 'TaskController@getMyTasksSphere')->where(['sphereId'=>'[0-9]+']);
Route::get('/user/my-tasks', 'TaskController@getMyTasks');
////functionalty send task into profile user
Route::get('/accept-my-task/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@acceptMyTask')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/finished-my-task/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@finishedMyTask')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/disagree-my-task/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@disagreeMyTask')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/accept-task-that-finished/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@acceptTaskThatFinished')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/disagree-task-that-finished/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@disagreeTaskThatFinished')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/view-details-my-task/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsMyTask');
Route::get('/view-details-task-member-finished/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsTaskFinished');
Route::get('/view-details-task-member-in-progress/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsTaskInprogress')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/view-details-task-member-in-completed/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsTaskCompleted');
Route::get('/view-details-my-task-finished/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsMyTaskFinished')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/view-details-my-task-in-progress/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsMyTaskInprogress')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/view-details-my-task-in-completed/{id}/{sphere_id}/{project_id}/{user_id}/{member_id}','TaskController@ViewDetailsMyTaskCompleted')->where(['id'=> '[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+','member_id'=>'[0-9]+']);
Route::get('/cancel-my-task-that-finished/{id}','TaskController@cancelMyTaskThatFinished')->where(['id'=> '[0-9]+']);
Route::get('/view-details-task/{task_id}/{project_id}/{sphere_id}','TaskController@ViewDetailsTask')->where(['task_id'=> '[0-9]+','project_id'=>'[0-9]+','sphere_id'=>'[0-9]+']);
Route::get('/view-details-your-task/{task_id}/{project_id}/{sphere_id}','TaskController@ViewDetailsYourTask')->where(['task_id'=> '[0-9]+','project_id'=>'[0-9]+','sphere_id'=>'[0-9]+']);
////--send invitation to join in sphere for profile user
Route::any('/invit-member/{userid}/sphere/{sphereid}','UserController@invitationMember')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/accept-inivitation/{userid}/{sphereid}','UserController@acceptInivitation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/decline-inivitation/{userid}/{sphereid}','UserController@declineInivitation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+']);
////--send requsting   join in sphere from   user into admins or founder a sphere or any person in sphere
Route::any('/request-join-into-sphere/{userid}/{sphereid}','SpheresController@requestJoiningIntoSphere')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+']);//from user
// Route::any('/view-success-request-join-into-sphere/','SpheresController@successYourRequestJoinIntoSphere');//from user
Route::any('/view-details-request-join-sphere/sphere/{sphereid}/{userid}','SpheresController@viewDetailsRequestJoiningIntoSphere');//in page founder or any person in sphere
Route::any('/accept-on-request-join-sphere/{userid}/{sphereid}','UserController@acceptRequestJoining')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/decline-on-request-join-sphere/{userid}/{sphereid}','UserController@declineRequestJoining')->where(['user_id'=> '[0-9]+','sphere_id'=>'[0-9]+']);
//routes for cancel
Route::any('/cancel-my-invitation-into-sphere/{userid}/{sphereId}','UserController@cancelMyInvitationIntoSphere');
Route::any('/cancel-my-invitation-into-project/{userid}/{projectId}/{sphereId}','UserController@cancelMyInvitationIntoProject')->where(['userid'=> '[0-9]+','projectId'=>'[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-invitation-into-event/{userid}/{eventId}/{sphereId}','UserController@cancelMyInvitationIntoEvent')->where(['userid'=> '[0-9]+','eventId'=>'[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-invitation-into-conversation/{userid}/{conversationId}/{sphereId}','UserController@cancelMyInvitationIntoConversation')->where(['userid'=> '[0-9]+','conversationId'=>'[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-request-joining-into-conversation/{userid}/{conversationId}/{sphereId}','UserController@cancelMyRequestJoiningntoConversation')->where(['userid'=> '[0-9]+','conversationId'=>'[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-request-joining-into-sphere/{userid}/{sphereId}','UserController@cancelMyRequestJoiningntoSphere')->where(['userid'=> '[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-request-joining-into-event/{userid}/{eventId}/{sphereId}','UserController@cancelMyRequestJoiningntoEvent')->where(['userid'=> '[0-9]+','eventId'=>'[0-9]+','sphereId'=>'[0-9]+']);
Route::any('/cancel-my-request-joining-into-project/{userid}/{projectId}/{sphereId}','UserController@cancelMyRequestJoiningntoProject')->where(['userid'=> '[0-9]+','projectId'=>'[0-9]+','sphereId'=>'[0-9]+']);
////--send requsting   join in project from   user into admins or founder a sphere or any person in sphere
Route::any('/request-join-into-project/{userid}/{sphereid}/{projectid}','SpheresController@requestJoiningIntoProject')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','projectid'=>'[0-9]+']);//from user
Route::any('/view-details-request-join-project/{sphereid}/{projectid}/{userid}','SpheresController@viewDetailsRequestJoiningIntoProject');//in page founder or any person in sphere
Route::any('/accept-on-request-join-project/{userid}/{sphereid}/{projectid}','UserController@acceptRequestJoiningIntoProject')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','projectid'=>'[0-9]+']);
Route::any('/decline-on-request-join-project/{userid}/{sphereid}/{projectid}','UserController@declineRequestJoiningIntoProject')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','projectid'=>'[0-9]+']);
////--send requsting   join in conversation from   user into admins or founder a sphere or any person in sphere
Route::any('/request-join-into-conversation/{userid}/{sphereid}/{conversationid}','SpheresController@requestJoiningIntoConversation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','conversationid'=>'[0-9]+']);//from user
Route::any('/view-details-request-join-conversation/{sphereid}/{conversationid}/{userid}','SpheresController@viewDetailsRequestJoiningIntoConversation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','conversationid'=>'[0-9]+','userid'=>'[0-9]+']);//in page founder or any person in sphere
Route::any('/accept-on-request-join-conversation/{userid}/{sphereid}/{conversationid}','UserController@acceptRequestJoiningIntoConversation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','conversationid'=>'[0-9]+']);
Route::any('/decline-on-request-join-conversation/{userid}/{sphereid}/{conversationid}','UserController@declineRequestJoiningIntoConversation')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','conversationid'=>'[0-9]+']);
////--send requsting   join in event from   user into admins or founder a sphere or any person in sphere
Route::any('/request-join-into-event/{userid}/{sphereid}/{eventid}','SpheresController@requestJoiningIntoEvent')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','eventid'=>'[0-9]+']);//from user
Route::any('/view-details-request-join-event/{sphereid}/{userid}/{eventid}','SpheresController@viewDetailsRequestJoiningIntoEvent')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','eventid'=>'[0-9]+']);//in page founder or any person in sphere
Route::any('/accept-on-request-join-event/{userid}/{sphereid}/{eventid}','UserController@acceptRequestJoiningIntoEvent')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','eventid'=>'[0-9]+']);
Route::any('/decline-on-request-join-event/{userid}/{sphereid}/{eventid}','UserController@declineRequestJoiningIntoEvent')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','eventid'=>'[0-9]+']);
////--send invitation in project 
Route::any('/invit-member/{member_id}/sphere/{sphere_id}/project/{project_id}','UserController@invitationIntoProject')->where(['member_id'=> '[0-9]+','project_id'=>'[0-9]+']);
Route::any('/accept-inivitation-into-project/{userid}/project/{projectid}/sphere/{sphereid}','UserController@acceptInivitationIntoProject')->where(['userid'=> '[0-9]+','projectid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/decline-inivitation-into-project/{userid}/project/{projectid}/sphere/{sphereid}','UserController@declineInivitationIntoProject')->where(['userid'=> '[0-9]+','projectid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/view-details-invitation-join-sphere/{sphereid}','UserController@viewDetailsInivitationJoinSphere')->where(['sphereid'=>'[0-9]+']);
Route::any('/view-details-invitation-join-project/{projectid}/sphere/{sphereid}','UserController@viewDetailsInivitationJoinProject')->where(['projectid'=> '[0-9]+','sphereid'=>'[0-9]+']);
////--send invitation in event 
Route::any('/invit-member/{member_id}/sphere/{sphere_id}/event/{event_id}','UserController@invitationIntoEvent')->where(['member_id'=> '[0-9]+','sphere_id'=>'[0-9]+','event_id'=>'[0-9]+']);
Route::any('/accept-inivitation-into-event/{userid}/event/{eventid}/sphere/{sphereid}','UserController@acceptInivitationIntoEvent')->where(['userid'=> '[0-9]+','eventid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/decline-inivitation-into-event/{userid}/event/{eventid}/sphere/{sphereid}','UserController@declineInivitationIntoEvent')->where(['userid'=> '[0-9]+','eventid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/view-details-invitation-join-event/{eventid}/sphere/{sphereid}','UserController@viewDetailsInivitationJoinEvent')->where(['eventid'=> '[0-9]+','sphereid'=>'[0-9]+']);
////--send invitation in conversation 
Route::any('/invit-member/{member_id}/sphere/{sphere_id}/conversation/{conversation_id}','UserController@invitationIntoConversation')->where(['member_id'=> '[0-9]+','sphere_id'=>'[0-9]+','conversation_id'=>'[0-9]+']);
Route::any('/accept-inivitation-into-conversation/{userid}/conversation/{conversationid}/sphere/{sphereid}','UserController@acceptInivitationIntoConversation')->where(['userid'=> '[0-9]+','conversationid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/decline-inivitation-into-conversation/{userid}/conversation/{conversationid}/sphere/{sphereid}','UserController@declineInivitationIntoConversation')->where(['userid'=> '[0-9]+','conversationid'=>'[0-9]+','sphereid'=>'[0-9]+']);
Route::any('/view-details-invitation-join-conversation/{conversationid}/sphere/{sphereid}','UserController@viewDetailsInivitationJoinConversation');
////--send invitation in task 
Route::post('/invit-member/{member_id}/sphere/{sphere_id}/project/{project_id}/task/{task_id}/category/{category_id}','UserController@invitationIntoTask');
Route::post('/get-data-task-member/{member_id}/sphere/{sphere_id}/project/{project_id}/task/{task_id}/category/{category_id}','UserController@getDataTaskMember');
Route::any('/accept-inivitation-into-task/{userid}/task/{taskid}/sphere/{sphereid}/project/{project_id}/category/{category_id}','UserController@acceptInivitationIntoTask');
Route::any('/decline-inivitation-into-task/{userid}/task/{taskid}/sphere/{sphereid}/project/{project_id}','UserController@declineInivitationIntoTask')->where(['userid'=> '[0-9]+','taskid'=>'[0-9]+','sphereid'=>'[0-9]','project_id'=>'[0-9]+']);
Route::any('/view-details-invitation-join-task/{taskid}/sphere/{sphereid}/project/{project_id}/category/{category_id}','UserController@viewDetailsInivitationJoinTask');
////--send requsting   join in task from   user into admins or founder a sphere or any person in sphere
Route::any('/request-join-into-task/{userid}/{sphereid}/{taskid}/project/{project_id}','SpheresController@requestJoiningIntoTask')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','taskid'=>'[0-9]','project_id'=>'[0-9]+']);//from user
Route::any('/view-details-request-join-task/{sphereid}/{taskid}/{userid}/project/{project_id}','SpheresController@viewDetailsRequestJoiningIntoTask')->where(['sphereid'=> '[0-9]+','taskid'=>'[0-9]+','userid'=>'[0-9]','project_id'=>'[0-9]+']);//in page founder or any person in sphere
Route::any('/accept-on-request-join-task/{userid}/{sphereid}/{taskid}/project/{project_id}','UserController@acceptRequestJoiningIntoTask')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','taskid'=>'[0-9]','project_id'=>'[0-9]+']);
Route::any('/decline-on-request-join-task/{userid}/{sphereid}/{taskid}/project/{project_id}','UserController@declineRequestJoiningIntoTask')->where(['userid'=> '[0-9]+','sphereid'=>'[0-9]+','taskid'=>'[0-9]','project_id'=>'[0-9]+']);


//for chat
Route::get('/route-messages/{followerId}/{userId}','MessagesController@isExistChat')->where(['followerId'=> '[0-9]+','userId'=>'[0-9]+']);
Route::post('/store-message','RoomController@store');
Route::get('show-conversation-me/{userId}/member/{memberId}','RoomController@show')->where(['userId'=> '[0-9]+','memberId'=>'[0-9]+']);
//for route for project management
Route::get('/sphere/{sphereId}/project/{projectId}/tasks', 'TaskController@getTasksProject')->where(['sphereId'=> '[0-9]+','projectId'=>'[0-9]+']);
Route::get('/sphere/{sphereId}/my-tasks', 'TaskController@getMyTasksSphere')->where(['sphereId'=> '[0-9]+']);
Route::get('/user/my-tasks', 'TaskController@myTasks');
Route::get('/user/my-tasks-created-by-me', 'TaskController@getMyTasksCreatedByMe');
Route::get('/user/my-tasks-assign-for-me', 'TaskController@getMyTasksAssignForMe');
Route::get('/user/my-tasks-closed', 'TaskController@getMyTasksClose');
Route::get('/user/my-spheres-joined', 'TaskController@mySpheresJoined');
Route::get('/user/my-spheres-founded', 'TaskController@mySpheresFounded');
Route::get('/user/my-tasks-open', 'TaskController@getMyTasksOpen');


Route::get('/user/my-projects-founded', 'ProjectsController@myProjectsFounded');
Route::get('/user/my-projects-joined', 'ProjectsController@myProjectsJoined');
Route::get('/user/my-projects-all', 'ProjectsController@myProjects');

Route::get('/user/my-spheres', 'SpheresController@mySpheres');
Route::get('/user/my-projects', 'SpheresController@getMyProjects');
Route::get('/view-details-comment-post/{commentId}/{postId}/{userId}/{sphereId}', 'UserController@viewDetailsCommentPostMention');
Route::get('/get-tasks-category/{categoryId}', 'TaskController@getCategoriesTask')->where(['categoryId'=> '[0-9]+']);
Route::get('/get-task-data/{taskId}', 'TaskController@getTaskData')->where(['taskId'=> '[0-9]+']);



/////////////////////////////////////////

//----- process (nortifications , chat , video ,voice)
//notifications
Route::any('/get-all-notifications', 'NotificationsController@getNotifications');
Route::any('/mark-as-read', 'NotificationsController@markAsRead');
//chat
Route::any('/send', 'ChatController@send');
Route::any('/send-messages-user-member', 'ChatController@sendMessagesUserMember');
Route::any('/send-messages-user-sphere', 'ChatController@sendMessagesUserSphere');
Route::post('/save-messages-individual/user/{userid}/member/{memberid}/message/{message}', 'ChatController@savePrivateMessages')->where(['memberid'=> '[0-9]+']);
Route::put('/save-messages-individual/user/{userid}/member/{memberid}/message/{message}', 'ChatController@updatePrivateMessages')->where(['memberid'=> '[0-9]+']);
Route::get('/get-all-messages-individual/user/{userid}/member/{memberid}', 'ChatController@getAllPrivateMessagesUserMember')->where(['userid'=> '[0-9]+','memberid'=>'[0-9]+']);
Route::get('/get-all-messages-individual/member/{memberid}/user/{userid}', 'ChatController@getAllPrivateMessagesMemberUser')->where(['memberid'=> '[0-9]+','userid'=>'[0-9]+']);
Route::get('/get-all-messages-sphere/{sphereId}', 'ChatController@getAllsphereMessages')->where(['sphereid'=> '[0-9]+']);
Route::post('/save-messages-public/user/{userid}/member/{memberid}/message/{message}', 'ChatController@savePublicMessages')->where(['userid'=> '[0-9]+','memberid'=>'[0-9]+']);
Route::any('/delete-messages-sphere/{sphereid}', 'ChatController@deleteMessagesSphere')->where(['sphereid'=> '[0-9]+']);
Route::get('/create-room-video', 'UserController@createRoomVideo');


//chat
Route::any('/chat', 'ChatController@chat');
Route::any('/send', 'ChatController@send');
Route::any('/saveToSession', 'ChatController@saveToSession');
Route::any('/deleteSession', 'ChatController@deleteSession');
Route::get('/create-room-video', 'UserController@createRoomVideo');
//video
Route::get('/create-video-chat/', 'SpheresController@createVideoChat');
//voice
Route::get('/create-voice-call/', 'SpheresController@createVoiceCall');

//notifications
Route::get('/user/notifications/get', 'NotificationsController@getAllNotifications');
Route::any('/user/notifications/read', 'NotificationsController@getMarkAsReadNotifications');
Route::any('/user/notifications/read/{id}', 'NotificationsController@getMarkAsReadNotificationsAndRedirect');


//in profile //

//list post 
Route::get('/posts','PostsSphereController@index');
//list single post
Route::get('/details-post/{id}/{sphere_id}','PostsSphereController@show')->where(['id'=> '[0-9]+','sphereid'=>'[0-9]+']);
Route::get('/comments-post/{id}','PostsSphereController@commentsPost')->where(['id'=> '[0-9]+']);
// create new post
Route::post('/add-post-sphere/{sphereId}/{userId}','PostsSphereController@store')->where(['sphereId'=> '[0-9]+','userId'=>'[0-9]+']);

Route::get('/get-all-posts-home/','PostsSphereController@getAllPostsInHome');

//for posts in member page
//list post 
Route::get('/posts-member/{memberEmail}','PostsProfileMemberController@index');
//my posts in page member
Route::get('/my-posts-in-profile-member/{userId}/{memberEmail}','PostsProfileMemberController@myPostsInProfileMember');
//posts-members-in-this-page-member
Route::get('/posts-members-in-page-this-member/{memberEmail}/{userId}','PostsProfileMemberController@PostsMembersInThisProfileMember')->where(['userId'=>'[0-9]+']);


//in page posts sphere//
//update
Route::put('/edit-post-sphere/{sphereId}/{userId}','PostsSphereController@store')->where(['sphereId'=> '[0-9]+','userId'=>'[0-9]+']);
//delete
Route::delete('/post/{id}/{sphereId}/{userId}','PostsSphereController@destroy')->where(['id'=>'[0-9]+','sphereId'=> '[0-9]+','userId'=>'[0-9]+']);
// create new post
Route::post('/add-post-home/{userId}','PostsHomeController@store');

//in page posts home//


Route::get('/comments-post-dashboard/{post_id}/{sphereid}','PostsHomeController@detailsPostDshboard')->where(['post_id'=> '[0-9]+','sphereid'=>'[0-9]+']);

//update
Route::put('/edit-post-home/{userId}','PostsHomeController@store');
//delete
Route::delete('/post/{id}/{sphereId}/{userId}','PostsHomeController@destroy')->where(['id'=>'[0-9]+','sphereId'=> '[0-9]+','userId'=>'[0-9]+']);

//comments//
//list comments
Route::get('/comments','CommentController@index');
//list single comment
Route::get('/get-comment/{comment_id}/{post_id}/{sphereId}','CommentController@show')->where(['comment_id'=>'[0-9]+','post_id'=> '[0-9]+','sphereId'=>'[0-9]+']);
// create new comment
Route::post('/post-comment/{post_id}/{sphereId}/{userid}','CommentController@storeCommentsSphereAndDasboard');
//update
Route::put('/post-comment/{comment_id}/{post_id}/{sphereId}/{userId}','CommentController@updatePostsSphere')->where(['comment_id'=>'[0-9]+','post_id'=>'[0-9]+','sphereId'=> '[0-9]+','userId'=>'[0-9]+']);
//delete
Route::delete('/delete-comment/{comment_id}/{post_id}/{sphereId}/{userId}','CommentController@destroy')->where(['comment_id'=>'[0-9]+','post_id'=>'[0-9]+','sphereId'=> '[0-9]+','userId'=>'[0-9]+']);

//comments in all posts

//list comments
Route::get('/comments','CommentController@index');
//list single comment
Route::get('/get-comment/{comment_id}/{post_id}/{sphereId}','CommentController@show')->where(['comment_id'=>'[0-9]+','post_id'=>'[0-9]+','sphereId'=> '[0-9]+']);
// create new comment
Route::post('/post-comment/{post_id}/{userid}','CommentController@storeGeneral')->where(['post_id'=>'[0-9]+','userid'=>'[0-9]+']);
//update
Route::put('/post-comment/{comment_id}/{post_id}/','CommentController@updateGeneral')->where(['comment_id'=>'[0-9]+','post_id'=>'[0-9]+']);

//delete
Route::delete('/delete-comment/{comment_id}/{post_id}/','CommentController@destroyGeneral')->where(['comment_id'=>'[0-9]+','post_id'=>'[0-9]+']);

//in page project details//
// Route::put('/post-comment/{comment_id}/{sphereId}/{projectId}','CommentController@updateCommentProject');

//view details project
 //Route::any('/view-details-project/{sphere_id}/{project_id}','ProjectDetailsController@index')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+']);
Route::any('/comments-project/{sphere_id}/{project_id}','ProjectDetailsController@commentsProject')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+']);

Route::post('/add-comment-on-project/{sphere_id}/{project_id}/{user_id}','ProjectDetailsController@addCommentOnProject')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::put('/update-comment-project/{comment_id}/{sphere_id}/{project_id}/{userid}','ProjectDetailsController@updateCommentProject')->where(['comment_id'=>'[0-9]+','sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','userid'=>'[0-9]+']);
Route::delete('/delete-comment-project/{comment_id}/{sphere_id}/{project_id}/{userid}','ProjectDetailsController@deleteCommentProject')->where(['comment_id'=>'[0-9]+','sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','userid'=>'[0-9]+']);


// for category tasks

Route::get('/category/project/{project_id}/sphere/{sphereId}', 'CategoryController@index')->where(['project_id'=>'[0-9]+','sphereId'=> '[0-9]+']);
Route::resource('/category', 'CategoryController');
Route::resource('/task', 'TaskController');
Route::delete('/delete-task/{id}/{user_id}', 'TaskController@destroy')->where(['id'=>'[0-9]+','user_id'=> '[0-9]+']);
Route::put('/update-task/{id}/{category_id}/{user_id}/', 'TaskController@update')->where(['id'=>'[0-9]+','category_id'=>'[0-9]+','user_id'=> '[0-9]+']);
Route::put('/update-category-in-task/{id}/{category_id}', 'TaskController@updateTaskInCategory')->where(['id'=>'[0-9]+','category_id'=>'[0-9]+','user_id'=> '[0-9]+']);
Route::post('/add-task/{category_id}/{user_id}/{sphereId}/{projectId}', 'TaskController@store')->where(['category_id'=>'[0-9]+','user_id'=>'[0-9]+','sphereId'=> '[0-9]+','projectId'=>'[0-9]+']);
Route::get('/category/{category}/tasks/project/{project_id}/sphere/{sphere_id}', 'CategoryController@tasks')->where(['project_id'=>'[0-9]+','sphere_id'=> '[0-9]+']);
Route::get('/check-member/{userId}/category/{category}/task/{taskId}/project/{project_id}/sphere/{sphere_id}', 'CategoryController@checkMemberJoinInTasks')->where(['userId'=>'[0-9]+','task_id'=>'[0-9]+','project_id'=> '[0-9]+','sphere_id'=>'[0-9]+']);
Route::get('get-members/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getMembersSphereProject')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::get('/get-members-to-invitation-join-task/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getMembersSphereProjectToJoinInTask')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::get('get-data-member/sphere/{sphere_id}/project/{project_id}/user/{user_id}','ProjectsController@getdataMemberSphereProject')->where(['sphere_id'=> '[0-9]+','project_id'=>'[0-9]+','user_id'=>'[0-9]+']);
Route::put('/assign-task-for-member/task/{taskId}/member/{member_id}/sphere/{sphere_id}/project/{project_id}/user/{userId}/category/{categoryId}', 'UserController@assignInTask');
Route::get('/get-assign-task-for-member/task/{taskId}/member/{member_id}/sphere/{sphere_id}/project/{project_id}/user/{userId}/category/{categoryId}', 'UserController@getAssignInTask');
Route::put('/cancel-asigned/task/{taskId}/sphere/{sphereId}/project/{projectId}/', 'UserController@cancelAssignTask')->where(['taskId'=> '[0-9]+','sphereId'=>'[0-9]+','projectId'=>'[0-9]+']);
Route::put('/cancel-invit/task/{taskId}/category/{categoryId}/sphere/{sphereId}/project/{projectId}/user/{userId}', 'UserController@cancelInvitTask');

//conversations
Route::get('get-comments/conversation/{conversation_id}/sphere/{sphere_id}','CommentController@getCommentsConversation');
 Route::get('/get-data-user-comments/conversation/{conversation_id}/sphere/{sphere_id}','CommentController@getDataCommentsConversation')->where(['conversation_id'=> '[0-9]+','member_id'=>'[0-9]+','sphere_id'=>'[0-9]+']);
 Route::get('get-info-user/{user_id}/','CommentController@getInfoUser')->where(['user_id'=>'[0-9]+']);
 Route::post('/add-comment-on-conversation/{sphere_id}/{conversationId}/{userId}','ConversationsController@addCommentConversation')->where(['sphere_id'=>'[0-9]+','conversationId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::post('/add-reply-comment-on-conversation/{sphere_id}/{conversationId}/comment/{commentId}/user/{userid}','ConversationsController@addReplyCommentConversation')->where(['sphere_id'=>'[0-9]+','conversationId'=>'[0-9]+','commentId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::put('/update-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{userId}','ConversationsController@updateCommentConversation');
 Route::put('/update-reply-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{reply_id}/{userId}','ConversationsController@updateReplyCommentConversation');
 Route::delete('/delete-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{userid}','ConversationsController@deleteCommentConversation');
 Route::delete('/delete-reply-comment-conversation/{comment_id}/{sphere_id}/{conversationId}/{reply_id}/{userid}','ConversationsController@deleteReplyCommentConversation')->where(['comment_id'=>'[0-9]+','conversationId'=>'[0-9]+','reply_id'=>'[0-9]+','userid'=>'[0-9]+']);
//tasks in project tool manager
 Route::post('/add-description-into-task/{taskId}/{userId}','TaskController@addDescriptionTask')->where(['taskId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::post('/add-didline-into-task/{taskId}/{userId}','TaskController@addDidlineTask')->where(['taskId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::get('get-comments/task/{category_id}/{task_id}/sphere/{sphere_id}/{project_id}','CommentController@getCommentsTask')->where(['category_id'=>'[0-9]+','task_id'=>'[0-9]+','sphere_id'=>'[0-9]+','project_id'=>'[0-9]+']);
 Route::post('/add-comment-on-task/{category_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@addCommentTask')->where(['category_id'=>'[0-9]+','sphere_id'=>'[0-9]+','taskId'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::put('/update-comment-task/{category_id}/{comment_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@updateCommentTask')->where(['category_id'=>'[0-9]+','comment_id'=>'[0-9]+','sphereId'=>'[0-9]+','taskId'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::delete('/delete-comment-task/{category_id}/{comment_id}/{sphere_id}/{taskId}/{projectId}/{userId}','TaskController@deleteCommentTask')->where(['category_id'=>'[0-9]+','comment_id'=>'[0-9]+','sphereId'=>'[0-9]+','taskId'=>'[0-9]+','projectId'=>'[0-9]+','userId'=>'[0-9]+']);
 Route::get('/get-description-task/{task_id}','TaskController@getDescriptionTask');
 Route::get('/get-didline-task/{task_id}','TaskController@getDidlineTask');

 
 //in page dashboard
Route::any('/store-user-in-mention-table/{userid}/comment/{comment_id}/post/{post_id}/sphere/{sphere_id}', 'UserController@storeUserInMentionTable');
Route::get('/get-all-users/', 'UserController@getAllUsers');
Route::get('/show-user/{email}', 'UserController@showUser');
Route::get('/show-user-email/{id}', 'UserController@showEmailUser')->where(['id'=>'[0-9]+']);
Route::get('/get-user-mention-comment/{comment}/post/{id}/sphere', 'UserController@getUserMentionComment')->where(['id'=>'[0-9]+']);
Route::any('/update-user-in-mention-table/{userid}/comment/{comment_id}/post/{post_id}/sphere/{sphere_id}', 'UserController@updateUserInMentionTable');

Route::get('/get-count-posts-home', 'PostsHomeController@countPostsHome');
Route::get('/get-count-posts-sphere/{sphereId}', 'PostsSphereController@countPostsSphere');
Route::get('/get-count-comments-conversation/{sphereId}/{cconvId}', 'PostsSphereController@countCommentsConv');
Route::get('/get-count-comments-project/{sphereId}/{projectId}', 'PostsSphereController@countCommentsPro');
Route::get('/get-count-comments-post/{postId}', 'PostsSphereController@countCommentsPostDashboard');


// Route::get('/get-user-mention/{comment.id}', 'UserController@getUserMentionComment');
Route::post('/store-message','RoomController@store');
Route::get('/room/{roomId}','RoomController@room');
Route::get('/my-rooms/{userId}','RoomController@roomsUser');
Route::get('/rooms/{public_type}','RoomController@roomsPublic');
Route::get('/show-room/{roomId}/public','RoomController@showPublicRoom');
Route::get('show-conversation-me/{userId}/member/{memberId}','RoomController@show');
Route::any('/create-roomm/{user_id}','RoomController@create');

//payment method ->paypal
Route::get('/paypal/return','ProjectsController@paypalReturn')->name('paypal.return');
Route::get('/paypal/cancel','ProjectsController@paypalCancel')->name('paypal.cancel');
Route::get('/paypal/{project_id}','ProjectsController@paypal')->name('paypal');
Route::get('/place-order/{project_id}','ProjectsController@placeOrder');
//payment method ->visa,master,mada
Route::get('/get-checkout-id/','ProjectsController@getCheckoutId')->name('project.checkout');

