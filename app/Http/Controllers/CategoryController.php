<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Task;
use App\Http\Resources\CategoryResource as CategoryResource;
use App\Http\Resources\TasksResource as TasksResource;
use Session;
class CategoryController extends Controller
{
    public function index($projectId,$sphereId)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){

      $category=Category::where(['project_id'=>$projectId,'sphere_id'=>$sphereId])->get()->toArray();
      return response()->json($category);

    }else{
        return response()->json([
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
    }

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
        $userSession=Session::get('sessionUser');
        if($userSession){
                $tasksCat=Task::where(['category_id'=>$category_id,'project_id'=>$projectId,'sphere_id'=>$sphereId])->get();
                return response()->json($tasksCat->toArray());

        }else{
            return response()->json([
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
        }
    }
    public function checkMemberJoinInTasks($member_id, $category_id,$taskId,$projectId=null,$sphereId=null)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
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
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
        }
        }

    public function update(Request $request, Category $category)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
            $status = $category->update($request->only('name'));

            return response()->json([
                'status' => $status,
                'message' => $status ? 'Category Updated!' : 'Error Updating Category'
            ]);

    }else{
        return response()->json([
        'status'=>401,
        'message'=>'You have not authurization to access into this page'
        ]);
    }
}
    public function destroy(Category $category)
    {
        $userSession=Session::get('sessionUser');
        if($userSession){
            $status = $category->delete();
            return response()->json([
                'status' => $status,
                'message' => $status ? 'Category Deleted' : 'Error Deleting Category'
            ]);
    }else{
        return response()->json([
        'status'=>401,
        'message'=>'You have not authurization to access into this page'
        ]);
    }
}
}
