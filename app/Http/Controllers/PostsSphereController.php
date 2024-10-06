<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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

class PostsSphereController extends Controller
{
  public function countPostsSphere($sphereId){
    $countPostsSphere= DB::table('posts')->where(['sphere_id'=>$sphereId])->count();
    return $countPostsSphere;
 }
  public function PostsInSphere($sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      $userId=$user->id;      
    return view('spheres.posts_in_sphere')->with(compact('sphereId','userId'));
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
          
  }
    public function store(Request $request,$sphereId,$userId)
    {//for add and update
      $userSession=Session::get('sessionUser');
      if($userSession){
       // dump($request->input('body'));
            $post=$request->isMethod('put')?Post::findOrFail($request->post_id):new Post;
            $post->sphere_id=$sphereId;
            $post->user_id=$userId;
            $post->body=$request->input('body');
            $post->save();
            return response()->json([
              'data'=>$post,
              'status'=>200
            ]);

      }else{
        return response()->json([
          'status'=>401,
          'data'=>'You have not authorization to access into this page'
      ]);
      } 
}


    public function detailsPost($post_id=null){
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      $userId=$user->id;
      $postCount=DB::table('posts')->where(['id'=>$post_id])->count();
      if($postCount!==0){
        $post=DB::table('posts')->where(['id'=>$post_id])->first();
        $postname=$post->body;
      }
      return view('posts.details_post')->with(compact('post_id','userId','postname'));
    }
    public function detailsPostDashboard($post_id=null,$sphereId){
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;          
      $postCount=DB::table('posts')->where(['id'=>$post_id])->count();
      if($postCount!==0){
        $post=DB::table('posts')->where(['id'=>$post_id])->first();
      $postname=$post->body; 
      }
      return view('posts.details_post_dashboard')->with(compact('post_id','sphereId','userId','postname','postCount'));
    }
    
    public function commentsPost($post_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
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
          'status'=>401,
          'data'=>'You have not authorization to access into this page'
      ]);
    } 
  }
    public function show($id,$sphereId)
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
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
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
  }
    public function destroy($id,$sphereId,$userId)
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
        $post=Post::where(['id'=>$id,'sphere_id'=>$sphereId,'user_id',$userId])->first();
        if($post->delete()){
          return new Posts($post);
        }
      }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
  }
    public function getAllPostsDashboard(){
      $sessionUser=Session::get('sessionUser');
      if($sessionUser){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $spheresThisUserJoinedInItCount=DB::table('sphere_users')->where(['user_id'=>$userId])->count();
        if($spheresThisUserJoinedInItCount!==0){
          $spheresThisUserJoinedInIt=DB::table('sphere_users')->where(['user_id'=>$userId])->get();
        }
        
        $questionForUserCount=DB::table('answers_on_questions')->where(['user_id'=>$user->id,'question_id'=>2])->count();
        if($questionForUserCount!==0){
          $questionForUser=DB::table('answers_on_questions')->where(['user_id'=>$user->id,'question_id'=>2])->get();
          if(!empty($questionForUser[0])){
            $answerUser1=$questionForUser[0]->answer;
            if($answerUser1){
              $sphereForAnswerUser1Count=Sphere::where(['name'=>$answerUser1])->count();
  
              if($sphereForAnswerUser1Count!==0){
                $sphereForAnswerUser1=Sphere::where(['name'=>$answerUser1])->first();
              }
              
            }
            $subSpheresCount1=Sphere::where(['parent_id'=>$sphereForAnswerUser1->id])->count();
            if($subSpheresCount1!==0){
              
              $subSpheres1Count=Sphere::where(['parent_id'=>$sphereForAnswerUser1->id,'status_sphere'=>'activated_status'])->count();
              if($subSpheres1Count!==0){
                $subSpheres1=Sphere::where(['parent_id'=>$sphereForAnswerUser1->id,'status_sphere'=>'activated_status'])->get();
              }
            }
          }
          if(!empty($questionForUser[1])){
            $answerUser2=$questionForUser[1]->answer;
            $answerUser2Count=$questionForUser[1]->answer;
            $sphereForAnswerUser2Count=Sphere::where(['name'=>$answerUser2])->count();
            if($sphereForAnswerUser2Count!==0){
              $sphereForAnswerUser2=Sphere::where(['name'=>$answerUser2])->first();
            }
            
            $subSpheresCount2=Sphere::where(['parent_id'=>$sphereForAnswerUser2->id])->count();
            if($subSpheresCount2!==0){
              $subSpheres2=Sphere::where(['parent_id'=>$sphereForAnswerUser2->id])->get();
            }
    
          }
        }
        $projectsThisUserJoinedInItCount=DB::table('projects_users')->where(['user_id'=>$user->id])->count();
        if($projectsThisUserJoinedInItCount!==0){
          $projectsThisUserJoinedInIt=DB::table('projects_users')->where(['user_id'=>$user->id])->get();
        }
        $eventsThisUserJoinedInItCount=DB::table('events_users')->where(['user_id'=>$user->id])->count();        
        if($eventsThisUserJoinedInItCount!==0){
          $eventsThisUserJoinedInIt=DB::table('events_users')->where(['user_id'=>$user->id])->get();
        }
        
        return view('posts.posts_dashboard')->with(compact('sphereForAnswerUser1Count','eventsThisUserJoinedInItCount','projectsThisUserJoinedInItCount','spheresThisUserJoinedInItCount','questionForUserCount','userId','subSpheres1Count','subSpheresCount2','userId','projectsThisUserJoinedInItCount','suggestionEventsForThisUser','eventsThisUserJoinedInIt','eventsThisUserJoinedInItCount','answerUser2Count','sphereForAnswerUser2Count','spheresThisUserJoinedInItCount','projectsThisUserJoinedInIt','spheresThisUserJoinedInIt','subSpheresCount1','sphereForAnswerUser1','sphereForAnswerUser2','userId','subSpheres1','subSpheres2','sphereForAnswerUser')); 
 
    }else{
      return redirect('user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
    
    public function getAllPostsInHome()
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
        $postsCount = Post::with('user')->with('comments.user')->count();
        if($postsCount!==0){
          $posts = Post::with('user')->with('comments.user')->orderBy('created_at','desc')->paginate(5);
          // dump($posts);
        //             $posts = Post::with('user')->with('comments.user')->paginate(5)->sortByDesc(function($post) {
        //   $post->comments->sortByDesc('id');});
        // // $post =Post::with('comments.user')->find(238); 
        return Posts::collection($posts);

        return response()->json([
          'status'=>'save successfully',
          'status'=>200,
          'data'=>$posts
      ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'Data in this route is empty'
      ]);
    } 
        }else{
          return response()->json([
            'status'=>404,
            'data'=>'You have not authorization to access into this page'
          ]);
        } 
  }
}

