<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostsHomePageResource as PostsHome;
use DB;
use Session;
class PostsHomeController extends Controller
{
    public function countCommentsPost($postId){
        $countCommentPost= DB::table('posts')->where(['post_id'=>$postId])->count();
        return $countCommentPost;
     }
    public function countPostsHome(){
       $countPostsHome= DB::table('posts')->where(['sphere_id'=>0])->count();
       return $countPostsHome;
    }
    public function detailsPostDshboard($postid,$sphere_id){
         $userSession=Session::get('sessionUser');
         if($userSession){
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
             'status'=>401,
             'data'=>'You have not authorization to access into this page'
           ]);
         }

    }
    public function store(Request $request,$userId=null)
    {
        $post=$request->isMethod('put')?Post::findOrFail($request->input('body')["post_id"]):new Post;
        $post->user_id=$userId;
        $post->member_id=0;
        $post->sphere_id=0;
        $post->body=$request->input('body')["body"];
        $post->save();
        return response()->json([
          'status' => 200,
          'message' =>  $post
      ]);         
    }

    public function destroy($id,$sphere_id,$userId)
    {
         $userSession=Session::get('sessionUser');
         if($userSession){
           if($userId){
                Post::where(['id'=>$id,'sphere_id'=>$sphere_id,'user_id'=>$userId])->delete();
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
         }else{
         return response()->json([
             'status'=>401,
             'data'=>'You have not authorization to access into this page'
         ]);
         }
    }
}
