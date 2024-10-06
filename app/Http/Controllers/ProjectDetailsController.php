<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Project;
use App\Http\Resources\CommentsProjectsResource as CommentsProjects;
use App\Http\Resources\ProjectDetailsResource as ProjectDetails;
use Session;

class ProjectDetailsController extends Controller
{
    public function countCommentsProj($sphereId,$proId){
        $countCommentProj= DB::table('comments')->where(['sphere_id'=>$sphereId,'project_id'=>$proId])->count();
        return $countCommentProj;
     }
    public function index($sphere_id=null,$project_id=null){
         $userSession=Session::get('sessionUser');
         if($userSession){
                $projectDetailsCount=Project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->count();
                if($projectDetailsCount!==0){
                    $projectDetails=Project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->get();
                    return ProjectDetails::collection($projectDetails);
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

    public function addCommentOnProject(Request $request,$sphereId=null,$project_id=null,$user_id)
    {
         $userSession=Session::get('sessionUser');
         if($userSession){
                $comment=Comment::insert(['sphere_id'=>$sphereId,'project_id'=>$project_id,'user_id'=>$user_id,'post_id'=>0,'conversation_id'=>0,'task_id'=>0,'category_id'=>0,'body'=>$request->input('body')]);
                    return response()->json([
                        'status'=>'save successfully',
                        'status'=>200,
                        'data'=>$comment
                    ]);
             }else{
             return response()->json([
                 'status'=>401,
                 'data'=>'You have not authorization to access into this page'
             ]);
             }
    }

    public function commentsProject($sphere_id=null,$project_id=null){
         $userSession=Session::get('sessionUser');
         if($userSession){
                $commentsCount=Comment::where(['sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
                if($commentsCount!==0){
                    $comments=Comment::where(['sphere_id'=>$sphere_id,'project_id'=>$project_id])->with('user')->orderBy('created_at','desc')->paginate(3);
                    // dd($comments);
                    // return response()->json([
                    //     'status'=>404,
                    //     'data'=>$comments
                    // ]);       
                    return CommentsProjects::collection($comments);

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

    public function updateCommentProject(Request $request,$commentId=null,$sphereId=null,$projectId=null,$userId=null)
    {
         $userSession=Session::get('sessionUser');
         if($userSession){
           if($commentId&&$sphereId&&$projectId&&$userId){
               $commentCount=Comment::where(['id'=>$commentId])->count();
               if($commentCount!==0){
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
                    'data'=>'Data in this rote is empty'
                ]);
               }

             }else{
             return response()->json([
                 'status'=>404,
                 'data'=>'This page not exsit'
             ]);    
             }
         }else{
         return response()->json([
             'status'=>401,
             'data'=>'You have not authorization to access into this page'
         ]);
         }
    }
    public function deleteCommentProject($commentId=null,$sphereId=null,$conversationId=null,$userId=null)
    {
         $userSession=Session::get('sessionUser');
         if($userSession){
           if($commentId&&$sphereId&&$conversationId&&$userId){
              $comment=  Comment::where(['id'=>$commentId])->first();
              if(!empty($comment)){
                  Comment::where(['id'=>$commentId])->delete();
                  return response()->json([
                      'status'=>'delete successfully',
                      'status'=>200,
                  ]);
              }else{
                return response()->json([
                    'status'=>404,
                    'data'=>'Data in this route is empty'
                ]);      
              }
             }else{
             return response()->json([
                 'status'=>404,
                 'data'=>'This page not exsit'
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
