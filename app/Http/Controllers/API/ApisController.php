<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use DB;
use Session;
use App\Http\Resources\PostResource as Posts;
use App\Http\Resources\CommentResource as Comments;
use App\Http\Resources\PostsHomePageResource as PostsHome;
use App\Comment;
use App\Project;
use App\Sphere;
class ApisController extends Controller
{
    //**********posts sphere*************//
    public function index()
    {
      $postsCount=Post::orderBy('created_at','desc')->count();
      if($postsCount!==0){
        $posts=Post::orderBy('created_at','desc')->paginate(3);
        return Posts::collection($posts);        
      }
    }
    public function showPostsSphere($id,$sphereId)
    {
        if($id&&$sphereId){
            $postSphereCount=Post::where(['id'=>$id,'sphere_id'=>$sphereId])->count();
            if($postSphereCount!==0){
             $post=Post::where(['id'=>$id,'sphere_id'=>$sphereId])->with(["user","sphere","comments"])->first();
            return new Posts($post); 
            }else{
              return response()->json([
                'status'=>404,
                'data'=>'This post in sphere  not exist'
            ]);
            }
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
          ]);    
        }

  }
  public function commentsPost($post_id){
      if($post_id){
          $commentsCount=Comment::where(['post_id'=>$post_id])->count();
          if($commentsCount!==0){ 
            $comments=Comment::where(['post_id'=>$post_id])->orderBy('created_at','desc')->get();
            return response()->json([
              'status' => 200,
              'message' =>  $comments
          ]);  
          }
      }else{
        return response()->json([
          'status'=>404,
          'data'=>'This page not exsit'
      ]);       
     }

}
public function storePostsSphere(Request $request,$sphereId,$userId)
{//for add and update
    if($sphereId&&$userId){
        $post=$request->isMethod('put')?Post::findOrFail($request->post_id):new Post;
        $post->sphere_id=$sphereId;
        $post->user_id=$userId;
        $post->id=$request->input('post_id');
        $post->body=$request->input('body');
        if($post->save()){
            return new Posts($post);
        }
    }else{
      return response()->json([
        'status'=>404,
        'data'=>'This page not exsit'
    ]);      
    }

}

public function destroyPostsSphere($id,$sphereId,$userId)
{
    if($id&&$userId&&$sphereId){
        $post=Post::where(['id'=>$id,'sphere_id'=>$sphereId,'user_id',$userId])->first();
        if($post->delete()){
          return new Posts($post);
        }
    }else{
      return response()->json([
        'status'=>404,
        'data'=>'This page not exsit'
      ]);    
    }
}
//******posts dashboard****// */
public function getAllPostsInHome(){

      $posts = Post::with('user')->with('comments.user')->get()->sortByDesc(function($post) {
        $post->comments->sortByDesc('created_at');});
      // $post =Post::with('comments.user')->find(238); 
      return response()->json([
        'status'=>'save successfully',
        'status'=>200,
        'data'=>$posts
    ]);

}
public function storePostsDashboard(Request $request,$userId=null)
{

            $post=$request->isMethod('put')?Post::findOrFail($request->post_id):new Post;
            $post->user_id=$userId;
            $post->member_id=0;
            $post->sphere_id=0;
            $post->body=$request->input('body');
            if($post->save()){
                // dump($post);
                return new PostsHome($post);
            }
     
}
public function detailsPostDshboard($postid,$sphere_id){
      if($postid&&$sphere_id){
            $commentsCount=  DB::table('comments')->where(['post_id'=>$postid,'sphere_id'=>0])->count();
            if($commentsCount!==0){
                $comments=  DB::table('comments')->where(['post_id'=>$postid,'sphere_id'=>0])->orderBy('created_at','desc')->get();
                return response()->json([
                    'status' => 200,
                    'message' =>  $comments
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' =>  'there is no comments for this post in this sphere'
                ]);
            }
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
          ]);    
        }


}
public function destroyPostsDashboard($id,$sphere_id,$userId)
{
      if($userId){
            $post=Post::where(['id'=>$id,'sphere_id'=>$sphere_id,'user_id'=>$userId])->delete();
                return response()->json([
                    'status'=>200,
                    'data'=>'deleted successfully'
                  ]);   

        }else{
        return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
        ]);    
        }
}

public function indexCommentsDashboard()
{
    $comments=Comment::orderBy('created_at','desc')->get();
    return Comments::collection($comments);
}



public function updateCommentsDashboard(Request $request,$commentId=null,$postId=null,$sphereId=null,$userId)
{
    if($commentId&&$postId&&$sphereId&&$userId){
        if($request->isMethod('put')){
        $comment=Comment::findOrFail($commentId);
            $comment->sphere_id=$sphereId;
            $comment->post_id=$postId;
            $comment->user_id=$userId;
            $comment->id=$commentId;
            $comment->body=$request->input('body');
            if($comment->save()){
                return new comments($comment);
            }
        }
    }else{
      return response()->json([
        'status'=>404,
        'message'=>'commentId , postId , sphereId , userId is null'
    ]);
    }

}

public function showCommentsDashboard($postId,$commentId)
{
    if($commentId&&$postId){
        $comment=Comment::where(['id'=>$commentId,'post_id'=>$postId])->first();
        return new Comments($comment);

    }else{
      return response()->json([
        'status'=>404,
        'message'=>'comment , post is null'
    ]);
    }

}
public function storeCommentsDashboard(Request $request,$postId,$sphereId,$userId)
{
    if($postId&&$sphereId&&$userId){
        $post=DB::table('posts')->where(['id'=>$postId])->first();
        $comment=$request->isMethod('put')?Comment::findOrFail($request->comment.id):new Comment;
        $comment->sphere_id=$sphereId;
        $comment->post_id=$postId;
        $comment->project_id=0;
        $comment->conversation_id=0;
        $comment->task_id=0;
        $comment->category_id=0;
        $comment->user_id=$userId;
        $comment->id=$request->input('comment_id');
        $comment->body=$request->input('body');
        if($comment->save()){
            $data=[
                'comment'=>$request->input('body')
            ];
            $userIdSession=(int) $userId;
            if($userIdSession!==$post->user_id){
            $userOwnPost=User::where(['id'=>$post->user_id])->first();
            $userOwnPost->notify(new NewCommentForPostOwnerNotify($comment));
          }

            return response()->json([
              'status'=>200,
              'data'=>$comment
            ]);                
        }else{
          return response()->json([
            'status'=>404,
            'message'=>'post,sphereId,userId is null'
        ]);
        }

        }
}
public function destroyCommentsDashboard($commentId=null,$postId=null,$sphereId=null,$userId)
{
    if($commentId&&$postId&&$sphereId&&$userId){
        $comment=Comment::where(['id'=>$commentId,'user_id'=>$userId])->delete();
          return response()->json([
            'status' => 200,
            'data' =>  'deleted successfully'
        ]);
  }else{
    return response()->json([
      'status'=>404,
      'message'=>'commentId , postId , sphereId , userId is null'
  ]);
  }


}


//details project
public function indexProjectDetails($sphere_id=null,$project_id=null){
    if($sphere_id&&$project_id){
          $projectDetails=Project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->get();
          return ProjectDetails::collection($projectDetails);
      }else{
      return response()->json([
          'status'=>404,
          'data'=>'This page not exsit'
      ]);    
      }
}

public function addCommentOnProject(Request $request,$sphereId=null,$project_id=null,$user_id)
{

    if($sphereId&&$project_id&&$user_id){
          $comment=Comment::insert(['sphere_id'=>$sphereId,'project_id'=>$project_id,'user_id'=>$user_id,'post_id'=>0,'conversation_id'=>0,'task_id'=>0,'category_id'=>0,'body'=>$request->input('body')]);
              return response()->json([
                  'status'=>'save successfully',
                  'status'=>200,
                  'data'=>$comment
              ]);
      //     }else{
      //     return response()->json([
      //         'status'=>404,
      //         'data'=>'This page not exsit'
      //     ]);    
      //     }
}
}
public function commentsProject($sphere_id=null,$project_id=null){
    if($sphere_id&&$project_id){
          $comments=Comment::where(['sphere_id'=>$sphere_id,'project_id'=>$project_id])->get();
          return response()->json([
              'status'=>'save successfully',
              'status'=>200,
              'data'=>$comments
          ]);
          return CommentsProjects::collection($comments);
            }else{
            return response()->json([
                'status'=>404,
                'data'=>'This page not exsit'
            ]);    
            }
  
}

public function updateCommentProject(Request $request,$commentId=null,$sphereId=null,$projectId=null,$userId=null)
{
    if($commentId&&$sphereId&&$projectId&&$userId){
      if($request->isMethod('put')){
          $comment=Comment::findOrFail($commentId);
          $comment->sphere_id=$sphereId;
          $comment->project_id=$projectId;
          $comment->id=$commentId;
          $comment->user_id=$userId;
          $comment->body=$request->input('body');
          if($comment->save()){
              return response()->json([
                  'status'=>'save successfully',
                  'status'=>200,
                  'data'=>$comment
              ]);
          }
      }
      }else{
      return response()->json([
          'status'=>404,
          'data'=>'This page not exsit'
      ]);    
      }

}
public function deleteCommentProject($commentId=null,$sphereId=null,$conversationId=null,$userId=null)
{

    if($commentId&&$sphereId&&$conversationId&&$userId){
          Comment::where(['id'=>$commentId])->delete();
          return response()->json([
              'status'=>'delete successfully',
              'status'=>200,
          ]);

        }else{
        return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
        ]);    
        }

  
}

///////category/////////
public function indexCategory($projectId,$sphereId)
    {
      $category=Category::where(['project_id'=>$projectId,'sphere_id'=>$sphereId])->get()->toArray();
      return response()->json($category);

    }
    public function store(Request $request)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
            $category = Category::create($request->only('name'));
            return response()->json([
                'status' => (bool)$category,
                'message' => $category ? 'Category Created' : 'Error Creating Category'
            ]);
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'You have not authurization to access into this page'
            ]);
        }
    }
    public function show(Category $category)
    {
        return response()->json($category);
        
    }
    public function tasks( $category_id,$projectId=null,$sphereId=null)
    {

          if($category_id&&$projectId&&$sphereId){
                $tasksCat=Task::where(['category_id'=>$category_id,'project_id'=>$projectId,'sphere_id'=>$sphereId])->get();
                return response()->json($tasksCat->toArray());
            }else{
            return response()->json([
                'status'=>404,
                'message'=>'categoryId,projectId,sphereId is null'
            ]);
            }
    }
    public function checkMemberJoinInTasksCategory($member_id, $category_id,$taskId,$projectId=null,$sphereId=null)
    {

          if($category_id&&$taskId&&$projectId&&$sphereId){
                $taskMemberJoinStatusAcceptedCount=Task::where(['member_id'=>$member_id,'id'=>$taskId,'category_id'=>$category_id,'project_id'=>$projectId,'sphere_id'=>$sphereId,'status_task_invitation'=>'accepted_status_task'])->orWhere(['user_id'=>$member_id])->count();
                $taskMemberJoinStatusPendingCount=Task::where(['member_id'=>$member_id,'id'=>$taskId,'category_id'=>$category_id,'project_id'=>$projectId,'sphere_id'=>$sphereId,'status_task_invitation'=>'pending_status_task'])->orWhere(['user_id'=>$member_id])->count();
                if($taskMemberJoinStatusAcceptedCount!=0){

                    return response()->json([
                        'status' => 200,
                        'message' =>  'this member allow enter into this task'
                    ]);
                }elseif($taskMemberJoinStatusPendingCount!==0){
                    return response()->json([
                        'status' => 200,
                        'message' =>  'you recived (in your profile) invitation to join in this task , pls , accept on it to can see it'
                    ]);
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' =>  'you cannt see this task because you join in it'
                    ]);
                }

            }else{
            return response()->json([
                'status'=>404,
                'message'=>'categoryId,projectId,sphereId is null'
            ]);
            }

        }

    public function updateCategory(Request $request, Category $category)
    {
          if($category){
            $status = $category->update($request->only('name'));

            return response()->json([
                'status' => $status,
                'message' => $status ? 'Category Updated!' : 'Error Updating Category'
            ]);

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'category is null'
            ]);
        }
}
    public function destroyCategory(Category $category)
    {
          if($category){
            $status = $category->delete();
            return response()->json([
                'status' => $status,
                'message' => $status ? 'Category Deleted' : 'Error Deleting Category'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'category is null'
            ]);
        }

}



    //conversations
    public function addToArena($sphere_id=null,$project_id=null,$user_id=null)
    {
          if($sphere_id&&$project_id&&$user_id){
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
                }

            }else{
            return response()->json([
                'status'=>404,
                'data'=>'This page not exsit'
            ]);    
            }

    }


    public function addViewConversation($conversation_id,$sphereId){
          if($conversation_id&&$sphereId){
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                DB::table('views')->insert(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId,'user_id'=>$user->id]);
                return response()->json([
                    'status'=>200,
                    'data'=>'add success'
                ]);

            }else{
            return response()->json([
                'status'=>404,
                'data'=>'This page not exsit'
            ]);    
            }

    }
  

   
     
        public function addCommentConversation(Request $request,$sphereId=null,$conversationId=null,$userId=null)
        {

              if($conversationId&&$sphereId&&$userId){
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
                    'status'=>404,
                    'data'=>'This page not exsit'
                ]);    
                }
   
        }
        public function addReplyCommentConversation(Request $request,$sphereId=null,$conversationId=null,$commentId,$user_id)
        {
              if($commentId&&$conversationId&&$sphereId&&$user_id){
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
                    'status'=>404,
                    'data'=>'This page not exsit'
                ]);    
                }
   
        }
        public function updateCommentConversation(Request $request,$commentId=null,$sphereId=null,$conversationId=null,$userId)
        {
              if($commentId&&$conversationId&&$sphereId&&$userId){
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
                        }
                    
                    }
                }else{
                return response()->json([
                    'status'=>404,
                    'data'=>'This page not exsit'
                ]);    
                }
        }

        public function updateReplyCommentConversation(Request $request,$commentId=null,$sphereId=null,$conversationId=null,$reply_id,$userId)
        {
              if($commentId&&$conversationId&&$sphereId&&$userId&&$reply_id){
                    if($request->isMethod('put')){
                        $replyCount=Reply::where(['id'=>$reply_id])->count();
                        if($replyCount!==0){
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
              if($commentId&&$conversationId&&$sphereId&&$userId){
                    $commentCount=Comment::where(['id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$userId])->count();
                    if($commentCount!==0){
                        $comment=Comment::where(['id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$userId])->delete();
                        return response()->json([
                        'status'=>'delete successfully',
                        'status'=>200,
                        'data'=>$comment
                    ]); 
                    }
                }else{
                return response()->json([
                    'status'=>404,
                    'data'=>'This page not exsit'
                ]);    
                }
  
        }
        public function deleteReplyCommentConversation($commentId=null,$sphereId=null,$conversationId,$reply_id,$user_id)
        {
              if($commentId&&$conversationId&&$sphereId&&$reply_id&&$user_id){
                    $replyCount=DB::table('replies')->where(['id'=>$reply_id,'comment_id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$user_id])->count();
                    if($replyCount!==0){
                    $reply=DB::table('replies')->where(['id'=>$reply_id,'comment_id'=>$commentId,'sphere_id'=>$sphereId,'conversation_id'=>$conversationId,'user_id'=>$user_id])->delete();
                        return response()->json([
                            'status'=>'delete successfully',
                            'status'=>200,
                            'data'=>$reply
                        ]); 
                    }
               
                }else{
                return response()->json([
                    'status'=>404,
                    'data'=>'This page not exsit'
                ]);    
                }
          
                }
    public function getCommentsConversation($conversation_id=null,$sphereId=null){
          if($conversation_id&&$sphereId){
              $commentsConversation=  Comment::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->with(['user','replies'])->orderBy('created_at','desc')->get();
              return response()->json([
                'status'=>200,
                'data'=>$commentsConversation
            ]);
              return CommentsConversation::collection($commentsConversation);
          }else{
            return response()->json([
              'status'=>404,
              'message'=>'conversationId , sphereId is null'
          ]);
          }
        
      }

      ///tasks////
      
    public function addDescriptionTask(Request $req,$task_id,$user_id){
        if($task_id&&$user_id){
            $taskCount=Task::where(['id'=>$task_id])->count();
            if($taskCount!==0){
              $task=Task::where(['id'=>$task_id])->first();
              $task->description_task=$req->input('body');
              $task->user_id=$user_id;
              $task->save();
              return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$task
              ]);
            }else{
              return response()->json([
                'status'=>404,
                'data'=>'this task'.$task_id.'not exist'
              ]);
            }
            
     
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
          ]);
        }
  
    }
    public function getCommentsTask($category_id,$task_id=null,$sphereId){
      $commentsTask=  Comment::where(['task_id'=>$task_id,'category_id'=>$category_id,'sphere_id'=>$sphereId])->orderBy('created_at','desc')->get();
      return CommentsTask::collection($commentsTask);
    }

    public function addCommentTask(Request $request,$categoryId,$sphereId=null,$taskId=null,$projectId,$userId)
    {
        if($categoryId&&$sphereId&&$taskId&&$projectId&&$userId){
            $comment=new Comment;
            $comment->conversation_id=0;
            $comment->sphere_id=$sphereId;
            $comment->category_id=$categoryId;
            $comment->project_id=$projectId;
            $comment->user_id=$userId;
            $comment->task_id=$taskId;
            $comment->body=$request->input('body');
            $comment->save();
            return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$comment
            ]);
    
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
        ]);
        }
 
    }

    public function updateCommentTask(Request $request,$category_id,$commentId=null,$sphereId=null,$taskId=null,$projectId,$userId)
    {
        if($category_id&&$commentId&&$sphereId&&$taskId&&$projectId&&$userId){
            if($request->isMethod('put')){
              $commentCount=Comment::where(['id'=>$commentId])->count();
              if($commentCount!==0){
                $comment=Comment::findOrFail($commentId);
                $comment->category_id=$category_id;
                $comment->conversation_id=0;
                $comment->sphere_id=$sphereId;
                $comment->task_id=$taskId;
                $comment->user_id=$userId;
                $comment->id=$commentId;
                $comment->project_id=$projectId;
                $comment->body=$request->input('body');
                $comment->save();
                return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$comment
                ]);
              }else{
                return response()->json([
                  'status'=>'this comment'.$commentId,'not exist',
                  'status'=>404
              ]);
              }
             
            }
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'This page not exsit'
        ]);      
      }

    }


    public function deleteCommentTask($category_id,$commentId=null,$sphereId=null,$taskId=null,$projectId,$userId)
    {

        if($category_id&&$commentId&&$sphereId&&$taskId&&$projectId&&$userId){
               $comment=Comment::where(['id'=>$commentId,'category_id'=>$category_id,'sphere_id'=>$sphereId,'task_id'=>$taskId,'project_id'=>$projectId,'user_id'=>$userId])->delete();
              return response()->json([
                  'status'=>'delete successfully',
                  'status'=>200,
                  'data'=>$comment
              ]);
            }else{
              return response()->json([
                'status'=>'this comment'.$commentId,'not exist',
                'status'=>404
            ]);
            }

    }

    public function storeUserInMentionTable(Request $req,$user_id,$comment_id,$post_id,$sphere_id=null){
 
        if($user_id&&$comment_id&&$post_id&&$sphere_id){
            $userMention= new Mention();
            $userMention->sphere_id=$sphere_id;
            $userMention->user_id=$user_id;
            $userMention->comment_id=$comment_id;
            $userMention->post_id=$post_id;
            $userMention->save();
            return response()->json([
              'status' => 200,
              'message' => ' save successfully'
          ]);

        }else{
          return response()->json([
            'status' => 404,
            'message' =>  'This route not exsit'
          ]);  
        }   
    }

    
  public function getAllUsers(){
    $allUsersCount=DB::table('users')->count();
    $allUsers=DB::table('users')->get();
    if($allUsersCount!==0){
      return response()->json([
      'status' => 200,
      'message' =>  $allUsers
      ]);
    }else{
      return response()->json([
        'status' => 404,
        'message' =>  'there is no users in db'
        ]);
    }
  }

  public function showUser($emailUser){

      if($emailUser){
        if(filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
          // valid email
          $user=DB::table('users')->where(['email'=>$emailUser])->first();
            return response()->json([
            'status' => 200,
            'message' => $user
          ]);
        }else {
          // invalid email
          return response()->json([
            'status' => 400,
            'message' => 'invalid email'
        ]);
        }
      }else{
        return response()->json([
          'status' => 404,
          'message' => 'This route not exist'
        ]);
      }


}

public function showEmailUser($idUser){
    if($idUser){
        $user=DB::table('users')->where(['id'=>$idUser])->first();
          return response()->json([
          'status' => 200,
          'message' => $user
        ]);
    }else{
      return response()->json([
        'status' => 404,
        'message' =>  'This route not exsit'
      ]);  
    }

} 

public function getUserMentionComment($comment_id,$post_id){
    if($comment_id&&$post_id){
        $userMention= DB::table('mentions')->where(['comment_id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>0])->first();
        $userMentionCount= DB::table('mentions')->where(['comment_id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>0])->count();
        return response()->json([
        'status'=>200,
        'message'=>$userMention
        ]);
       
    }else{
      return response()->json([
        'status'=>400,
        'message'=>'comment , post is null'
    ]);
    }
  
}

public function getDataTasksUserForCalender(){

      $userCount=User::where(['email'=>Session::get('sessionUser')])->count();
      if($userCount){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $taskUserAssigned=  Task::where(['member_id'=>$user->id,'status_task_invitation'=>'accepted_status_task'])->with(['category','sphere'])->get();
      //here task time that the user assigned for his
      return response()->json([
        'status' => 200,
        'message' =>  $taskUserAssigned
      ]);
      }else{
        return response()->json([
          'status' => 404,
          'message' =>  'this email is not exist'
        ]);
      }
      

  }

  public function getDataEventsUserForCalender(){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $eventsJoinedUser=User::find($userId)->events()->get();//get all events that it for this user
        //here task time that the user assigned for his
        return response()->json([
          'status' => 200,
          'message' =>  $eventsJoinedUser
        ]);

  
  }
}