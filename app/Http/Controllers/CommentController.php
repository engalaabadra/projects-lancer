<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CommentResource as Comments;
use App\Http\Resources\CommentsConversationResource as CommentsConversation;
use App\Http\Resources\CommentsConversationResource as CommentsTask;
use App\Http\Resources\RepliesCommentsConversations as RepliesCommentsConversations;
use App\Notifications\NewCommentForPostOwnerNotify;

use DB;

use Session;
use App\Comment;
use App\Events\NewNotification;
use App\Notifications\RepliedToThread;
use App\Reply;
use App\User;

class CommentController extends Controller
{
  public function index()
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comments=Comment::orderBy('created_at','desc')->get();
    return Comments::collection($comments);
}else{
return response()->json([
    'status'=>401,
    'data'=>'You have not authorization to access into this page'
]);
}
  }

  public function storeCommentsSphereAndDasboard(Request $request,$postId=null,$sphereId=null,$userId)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){    
     $post=DB::table('posts')->where(['id'=>$postId])->first();
     if(!empty($post)){
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
      //////
      //to get user that own this post
      if(intval($userId)!==$post->user_id){
        $userOwnPost=User::where(['id'=>$post->user_id])->first();
        $userOwnPost->notify(new NewCommentForPostOwnerNotify($comment));
       }
      return new comments($comment);
     }
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



  public function storeGeneral(Request $request,$postId=null,$userId)
  {
    dd($userId);
    $userSession=Session::get('sessionUser');
    if($userSession){
     $post=DB::table('posts')->where(['id'=>$postId])->first();
     if(!empty($post)){
       $comment=$request->isMethod('put')?Comment::findOrFail($request->comment.id):new Comment;
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
        //////
        dd($post->user_id);
        //to get user that own this post
       if($userId!==$post->user_id){
         $userOwnPost=User::where(['id'=>$post->user_id])->first();
       }
  
      
       $userOwnPost->notify(new NewCommentForPostOwnerNotify($comment));
           return new comments($comment);

     }
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


  public function showPostsSphere($postId,$commentId)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comment=Comment::where(['id'=>$commentId,'post_id'=>$postId])->first();
    if(!empty($comment)){
      return new Comments($comment);
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

  public function updatePostsSphere(Request $request,$commentId=null,$postId=null,$sphereId=null,$userId)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comment=Comment::findOrFail($commentId);
    if(!empty($comment)){
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


  public function updateGeneral(Request $request,$commentId=null,$postId=null)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comment=Comment::findOrFail($commentId);
    if(!empty($comment)){
    if($request->isMethod('put')){
    $comment=Comment::findOrFail($commentId);
        $comment->post_id=$postId;
        $comment->id=$commentId;
        $comment->body=$request->input('body');
        if($comment->save()){
            return new comments($comment);
        }
    }
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
  public function updateCommentProject(Request $request,$commentId=null,$postId=null,$sphereId=null,$projectId=null)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comment=Comment::findOrFail($commentId);
    if(!empty($comment)){
    if($request->isMethod('put')){
      $comment=Comment::findOrFail($commentId);
      $comment->sphere_id=$sphereId;
      $comment->post_id=$postId;
      $comment->id=$commentId;
      $comment->body=$request->input('body');
      if($comment->save()){
          return new comments($comment);
      }
    }

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

  public function destroy($commentId=null,$postId=null,$sphereId=null,$userId)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $commentCount=Comment::where(['id'=>$commentId])->first();
    if($commentCount!==0){
      $comment=Comment::where(['id'=>$commentId])->delete();
            return response()->json([
              'status' => 200,
              'data' =>  'deleted successfully'
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

  public function destroyGeneral($commentId=null,$postId=null)
  {
    $userSession=Session::get('sessionUser');
    if($userSession){
    $comment=Comment::where(['id'=>$commentId,'post_id'=>$postId])->first();
    if($comment->delete()){
      return new Comments($comment);
    }

}else{
return response()->json([
    'status'=>401,
    'data'=>'You have not authorization to access into this page'
]);
}
  }

  public function getCommentsConversation($conversation_id=null,$sphereId=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
    $commentsConversationCount=  Comment::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->with(['user','replies'])->count();
    if($commentsConversationCount!==0){
      $commentsConversation=  Comment::where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->with('user')->with('replies.user')->orderBy('created_at','desc')->paginate(3);
  //     return response()->json([
  //     'status'=>200,
  //     'data'=>$commentsConversation
  // ]);
    return CommentsConversation::collection($commentsConversation);
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

  public function getDataCommentsConversation($conversation_id=null,$sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
    $usersCommentsCount=  DB::table('comments')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->count();
    if($usersCommentsCount!==0){
      $usersComments=  DB::table('comments')->where(['conversation_id'=>$conversation_id,'sphere_id'=>$sphereId])->get();
    return response()->json([
      'status'=>200,
      'data'=>$usersComments
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
  
  public function getInfoUser($user_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
    $usersInfoCount=DB::table('users')->where(['id'=>$user_id])->count();
    if($usersInfoCount!==0){
      $usersInfo=DB::table('users')->where(['id'=>$user_id])->get();
    return response()->json([
      'status'=>200,
      'data'=>$usersInfo
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
  
  public function getCommentsTask($category_id,$task_id=null,$sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
    $commentsTaskCount=  Comment::where(['task_id'=>$task_id,'category_id'=>$category_id,'sphere_id'=>$sphereId])->count();
    if(!empty($commentsTaskCount)){
      $commentsTask=  Comment::where(['task_id'=>$task_id,'category_id'=>$category_id,'sphere_id'=>$sphereId])->orderBy('created_at','desc')->get();
      return CommentsTask::collection($commentsTask);
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
