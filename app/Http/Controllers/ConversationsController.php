<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Project;
use App\Comment;
use App\User;
use App\Conversation;
use Session;
use DB;
use Image;
use App\Http\Resources\CommentResource as CommentsConversation;
use App\Reply;
use App\Sphere;
use App\Topic;
use Facade\FlareClient\View;

class ConversationsController extends Controller
{   
    public function countCommentsConv($sphereId,$convId){
        $countCommentConv= DB::table('comments')->where(['sphere_id'=>$sphereId,'conversation_id'=>$convId])->count();
        return $countCommentConv;
     }
    public function addToArena($sphere_id=null,$project_id=null,$user_id=null)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                $sphereId=$sphere_id;
                $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
                $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
                $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
                if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
                return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
                }else{
                $project=Project::where(['id'=>$project_id])->first();
                if(!empty($project)){
                    $projectName=$project->name;
                    $projectDesc=$project->description;
                    $newProjectConverstaion=new Conversation();
                    $newProjectConverstaion->user_id=$user_id;
                    $newProjectConverstaion->sphere_id=$sphere_id;
                    $newProjectConverstaion->project_id=$project_id;
                    $newProjectConverstaion->type_conversation='in-progress';
                    $newProjectConverstaion->title='convertasion in project'.$projectName;
                    $newProjectConverstaion->description='description project in conversation'.$projectDesc;
                    $newProjectConverstaion->save();
                }else{
                    return redirect('/add-to-arena/'.$sphere_id.'/'.$project_id.'/'.$user_id)->with('flash_message_error','Data in this route is empty'); 
                }
                }

        }else{
            return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page'); 
        }
    }
    public function createRoom(Request $req,$sphere_id)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
                $sphereId=$sphere_id;
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                if($req->isMethod('post')){
                    $data=$req->all();
                    $newRoom=new Conversation();
                    $newRoom->user_id=$user->id;
                    $newRoom->sphere_id=$sphere_id;
                    $newRoom->project_id=0;
                    $newRoom->type_conversation='created-now';
                    $newRoom->title=$data['title'];
                    $newRoom->description=$data['description'];
                $newRoom->save();   
                return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_success','add your room successfully'); 
                }
                return view('conversations.create_room')->with(compact('sphereId'),'flash_message_success','created your room succefully');
   
        }else{
            return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page'); 
    }
    }
    public function getAllConversations($sphere_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userId=$user->id;
            $sphereId=$sphere_id;
            $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
            $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
            $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
                return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
            }else{
                $conversations= Conversation::where(['sphere_id'=>$sphere_id])->get();
                $conversationsCount= Conversation::where(['sphere_id'=>$sphere_id])->count();
                $sphere=Sphere::where(['id'=>$sphere_id])->first();
                $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                return view('conversations.get_all_conversations')->with(compact('userId','conversationsCount','sphere','conversations'));
            }

        }else{
            return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page'); 
        }
}
    public function viewConversation($conversation_id=null,$sphereId=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userId=$user->id;            
            $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
            $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
            $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
            return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
            }else{
            $theLastTopicCount=Topic::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->orderBy('id', 'desc')->count();
            if($theLastTopicCount!==0){
                $theLastTopic=Topic::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->orderBy('id', 'desc')->first();  
            }
            $conversationId=$conversation_id;
            $conversationCount=Conversation::where(['id'=>$conversation_id])->count();
            if($conversationCount!==0){
                $conversation=Conversation::where(['id'=>$conversation_id])->first();    
            }
            
            $sphere_id=$sphereId;
            $sphereCount=Sphere::where(['id'=>$sphere_id])->count();
            if($sphereCount!==0){
                $sphere=Sphere::where(['id'=>$sphere_id])->first();
            }
            $newProjectTheLastCount=DB::table('conversations_projects')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
            if($newProjectTheLastCount!==0){
            $newProjectTheLast=DB::table('conversations_projects')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->orderBy('id', 'desc')->first();
            }
            $newTopicTheLastCount=Topic::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
            if($newTopicTheLastCount!==0){
                $newTopicTheLast=Topic::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->orderBy('id', 'desc')->first();
            }
            if(!empty($newTopicTheLast)){
                $usernewTopicTheLastCount=DB::table('users')->where(['id'=>$newTopicTheLast->user_id])->count();
                if($usernewTopicTheLastCount!==0){
                    $usernewTopicTheLast=DB::table('users')->where(['id'=>$newTopicTheLast->user_id])->first();
                }
                
            }
            $conversationCount=DB::table('conversations_projects')->where(['id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
            if($conversationCount!==0){
                $conversationPCount=DB::table('conversations_projects')->where(['id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
                if($conversationPCount){
                    $conversationP=DB::table('conversations_projects')->where(['id'=>$conversation_id,'sphere_id'=>$sphere_id])->first();
                }
                $commentsConversationCount=DB::table('comments')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
                if($commentsConversationCount!==0){
                    $commentsConversation=DB::table('comments')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->get();
                }
                $projectsCountConversation=DB::table('conversations_projects')->where(['conversation_id'=>$conversationId])->count();
            }
            $numReplies=Reply::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->count();
            $numViews=DB::table('views')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->count();
            $numTopics=DB::table('topics')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->count();
            return view('conversations.view_conversation')->with(compact('numTopics','numViews','numReplies','usernewTopicTheLastCount ','theLastTopicCount','conversationCount','userCount','theLastTopic','newTopicTheLast','ProjectTheLast','newProjectTheLast','newProjectTheLastCount','projectsCountConversation','usernewTopicTheLast','newTopicTheLastCount','newTopicTheLast','sphere','projectsConversation','userId','sphere_id','conversationId','conversation','commentsConversation'));
        }
 
}else{
    return redirect('/user/dashboard')->with('flash_message_error','You have not authorization to access into this page');    
}
    
}

    public function addViewConversation($conversation_id,$sphereId){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            DB::table('views')->insert(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId,'user_id'=>$user->id]);
            return response()->json([
                'status'=>200,
                'data'=>'add success'
            ]);

        }else{
            return response()->json([
            'status'=>401,
            'data'=>'You have not authorization to access into this page'
        ]);
        }
    }
    public function closeMyTopic($topic_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
                $topicCount= Topic::where(['id'=>$topic_id])->count();
                if($topicCount){
                    Topic::where(['id'=>$topic_id])->update(['status_topic'=>'closed']);
                }
                    return redirect()->back()->with('flash_message_success','closed your topic successfully');
               

            }else{
                return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');    
            }
    }
    public function addProjectInConversation(Request $req,$conversation_id=null,$sphere_id=null,$user_id=null,$topic_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $conversationId=$conversation_id;
            $conversationCount=Conversation::where(['id'=>$conversation_id])->count();
            if($conversationCount!==0){
                $conversation=Conversation::where(['id'=>$conversation_id])->first();
            }
            $sphereId=$sphere_id;
            $userId=$user_id;
            $theLastTopicCount=Topic::where(['id'=>$topic_id])->count();
            if($theLastTopicCount!==0){
                $theLastTopic=Topic::where(['id'=>$topic_id])->first();
                if($theLastTopic->status_topic=='open'){
                    if($req->isMethod('post')){
                        $data=$req->all();
                        $newProject=new Project();
                        $newProject->topic_id=$topic_id;
                        $newProject->sphere_id=$sphere_id;
                        $newProject->user_id=$user_id;
                        $newProject->name=$data['name'];
                        $newProject->description=$data['description'];
                        $newProject->status_project='created now';
                        if($req->hasFile('image_project')){
                            $image_tmp=$req->file('image_project');
                            if($image_tmp->isValid()){
                                $extension=$image_tmp->getClientOriginalExtension();
                                $filename=rand(111,9999).'.'.$extension;
                                //save in folder
                                $small_image_path='images/backend_images/projects/small/'.$filename;
                                $medium_image_path='images/backend_images/projects/medium/'.$filename;
                                $large_image_path='images/backend_images/projects/large/'.$filename;
                                //resize, save
                                Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
                                //store in db
                                $newProject->image=$filename;
                            }
                        }
                        $projectConversation=  $newProject->save();   
                        if($projectConversation==true){
                            $project=Project::where(['name'=>$data['name']])->first();
                            $projectId=$project->id;
                            $conversation=DB::table('conversations_projects')->insert(['conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id,'project_id'=>$projectId]);
                            return redirect('/view-conversation/'.$conversation_id.'/sphere/'.$sphere_id);
                        }  
                    }
                    return view('conversations.add_project_into_conversation')->with(compact('conversationCount','conversation','theLastTopic','conversationId','sphereId','userId'));
                    }else{
                        return redirect('/view-conversation/'.$conversation_id.'/sphere/'.$sphere_id)->with('flash_message_error','you can not create project in this conversation , because this conversation not  contains on opening topicto put this project in it ,you can create new topic');
                    }
                }
                
            }else{
                return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page');
            }
        
        }

    public function addNewTopicInConversation(Request $req,$conversation_id=null,$sphere_id=null,$user_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
                $theLastTopicCount=Topic::where(['status_topic'=>'open','conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->orderBy('id', 'desc')->count();
                if($theLastTopicCount==0){
                    $conversationId=$conversation_id;
                    $conversationCount=Conversation::where(['id'=>$conversationId])->count();
                    if($conversationCount!==0){
                        $conversation=Conversation::where(['id'=>$conversationId])->first();
                        $sphereId=$sphere_id;
                        $userId=$user_id;
                        if($req->isMethod('post')){
                            $data=$req->all();
                            $newTopic=new Topic();
                            $newTopic->sphere_id=$sphere_id;
                            $newTopic->conversation_id=$conversation_id;
                            $newTopic->user_id=$user_id;
                            $newTopic->status_topic='open';
                            $newTopic->name=$data['name'];
                            $newTopic->save();   
                        }
                        return view('conversations.add_new_topic_into_conversation')->with(compact('conversation','conversationId','sphereId','userId'));
                    }
                
                }else{
                    return redirect('view-conversation/'.$conversation_id.'/sphere/'.$sphere_id)->with('flash_message_error','you can not create topic in this conversation , because this conversation contains on another topic not close , until now');
                }

        }else{
            return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page');
        }
    }
    public function addNewTopicInSpecificConversation(Request $req,$sphere_id,$user_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $sphereId=$sphere_id;
            $userId=$user_id;
            $conversationsSphereCount=DB::table('conversations')->where(['sphere_id'=>$sphere_id])->count();
            if($conversationsSphereCount!==0){
                $conversationsSphere=DB::table('conversations')->where(['sphere_id'=>$sphere_id])->get();
            }
            
            if($req->isMethod('post')){
                $data=$req->all();
                $theLastTopicCount=Topic::where(['status_topic'=>'open','conversation_id'=>$data['conversation_id'],'sphere_id'=>$sphere_id])->orderBy('id', 'desc')->count();
                if($theLastTopicCount==0){
                $newTopic=new Topic();
                $newTopic->sphere_id=$sphere_id;
                $newTopic->conversation_id=$data['conversation_id'];
                $newTopic->user_id=$user_id;
                $newTopic->name=$data['name'];
                $newTopic->status_topic='open';
                $newTopic->save();   
                return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_success','add yourtopic successfully'); 
                }else{
                    return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_error','you can not create topic in this conversation , because this conversation contains on another topic not close , until now');
                }
            }
            return view('conversations.add_new_topic_into_specific_conversation')->with(compact('conversationsSphereCount','userId','conversationsSphere','sphereId','userId'));
            
        }else{
            return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page');
       
        }
    }

        public function addNewConversation(Request $req,$sphere_id,$user_id){
            $userSession=Session::get('sessionUser');
            if($userSession){
                $sphereId=$sphere_id;
                $userId=$user_id;
                if($req->isMethod('post')){
                    $data=$req->all();
                    $newConversation=new Conversation();
                    $newConversation->sphere_id=$sphere_id;
                    $newConversation->user_id=$user_id;
                    $newConversation->title=$data['title'];
                    $newConversation->description=$data['description'];
                    $newConversation->type_conversation='created_now';
                    $newConversation->save();   
                    return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_success','created conversation successfully'); 
                
                }
                return view('conversations.add_new_conversation')->with(compact('conversationsSphereCount','userId','conversationsSphere','sphereId','userId'));

            }else{
                return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page');
           
            }
        }
        public function addCommentConversation(Request $request,$sphereId=null,$conversationId=null,$userId=null)
        {
             $userSession=Session::get('sessionUser');
             if($userSession){
                $comment=new Comment;
                $comment->sphere_id=$sphereId;
                $comment->conversation_id=$conversationId;
                $comment->user_id=$userId;
                $comment->body=$request->input('body');
                if($comment->save()){

                return response()->json([
                    'status'=>'save successfully',
                    'status'=>200,
                    'data'=>$comment
                ]);
                }

             }else{
                return response()->json([
                 'status'=>401,
                 'data'=>'You have not authorization to access into this page'
             ]);
             }
        }
        public function addReplyCommentConversation(Request $request,$sphereId=null,$conversationId=null,$commentId,$user_id)
        {
            $userSession=Session::get('sessionUser');
            if($userSession){
                $reply=new Reply;
                $reply->comment_id=$commentId;
                $reply->sphere_id=$sphereId;
                $reply->conversation_id=$conversationId;
                $reply->user_id=$user_id;
                
                $reply->body=$request->input('body');
                if($reply->save()){
                return response()->json([
                    'status'=>'save successfully',
                    'status'=>200,
                    'data'=>$reply
                ]);
                }

            }else{
            return response()->json([
                'status'=>401,
                'data'=>'You have not authorization to access into this page'
            ]);
            }
            
        }
        public function updateCommentConversation(Request $request,$commentId=null,$sphereId=null,$conversationId=null,$userId)
        {
            $userSession=Session::get('sessionUser');
            if($userSession){
                    if($request->isMethod('put')){
                        $commentCount=Comment::where(['id'=>$commentId])->first();
                        if($commentCount!==0){
                            $comment=Comment::findOrFail($commentId);
                            $comment->sphere_id=$sphereId;
                            $comment->user_id=$userId;
                            $comment->conversation_id=$conversationId;
                            $comment->id=$commentId;
                            $comment->body=$request->input('body');
                            if($comment->save()){
                                return response()->json([
                                'status'=>'save successfully',
                                'status'=>200,
                                'data'=>$comment
                                ]);
                            }   
                        }else{
                            return response()->json([
                                'status'=>404,
                                'data'=>'Data in this route is empty'
                            ]);
                        }
                    
                    }

            }else{
            return response()->json([
                'status'=>401,
                'data'=>'You have not authorization to access into this page'
            ]);
            }
        }

        public function updateReplyCommentConversation(Request $request,$commentId=null,$sphereId=null,$conversationId=null,$reply_id,$userId)
        {
            $userSession=Session::get('sessionUser');
            if($userSession){
                if($request->isMethod('put')){
                    $replyCount=Reply::where(['id'=>$reply_id])->count();
                        $reply=Reply::where(['id'=>$reply_id])->first();
                        $reply->comment_id=$commentId;
                        $reply->sphere_id=$sphereId;
                        $reply->conversation_id=$conversationId;
                        $reply->user_id=$userId;
                        $reply->id=$reply_id;
                        $reply->body=$request->input('body');
                        $reply->save();
                        return response()->json([
                            'status'=>'save successfully',
                            'status'=>200,
                            'data'=>$reply
                        ]);  
                }
            }else{
            return response()->json([
                'status'=>401,
                'data'=>'You have not authorization to access into this page'
            ]);
            }
        }
        public function deleteCommentConversation($commentId=null,$sphereId=null,$conversationId=null,$userId)
        {
            $userSession=Session::get('sessionUser');
            if($userSession){
                $commentCount=Comment::where(['id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$userId])->count();
                if($commentCount!==0){
                    $comment=Comment::where(['id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$userId])->delete();
                    return response()->json([
                    'status'=>'delete successfully',
                    'status'=>200,
                    'data'=>$comment
                ]); 
                }else{
                    return response()->json([
                        'status'=>404,
                        'data'=>'Data in this route is empty'
                    ]);
                }
               
            }else{
            return response()->json([
                'status'=>401,
                'data'=>'You have not authorization to access into this page'
            ]);
            }
        }
        public function deleteReplyCommentConversation($commentId=null,$sphereId=null,$conversationId,$reply_id,$user_id)
        {
            $userSession=Session::get('sessionUser');
            if($userSession){
                $replyCount=DB::table('replies')->where(['id'=>$reply_id,'comment_id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$user_id])->count();
                if($replyCount!==0){
                $reply=DB::table('replies')->where(['id'=>$reply_id,'comment_id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$user_id])->delete();
                    return response()->json([
                        'status'=>'delete successfully',
                        'status'=>200,
                        'data'=>$reply
                    ]); 
                }else{
                    return response()->json([
                        'status'=>404,
                        'data'=>'Data in this route is empty'
                    ]);
                }

            }else{
            return response()->json([
                'status'=>401,
                'data'=>'You have not authorization to access into this page'
            ]);
            }
        }
}

