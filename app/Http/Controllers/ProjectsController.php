<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Sphere;
use App\Post;
use App\Comment;
use App\Task;
use App\Conversation;
use App\Event;
use App\User;
use App\Survey;
use Session;
use DB;
use Illuminate\Support\Facades\Input;
use Image;
use App\Http\Resources\MembersProjectResource as MembersProject;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaputreRequest;
use Throwable;

class ProjectsController extends Controller
{
  public function myProjects(){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $user=User::where(['email'=>Session::get('sessionUser')])->first();
      $user_id=$user->id;
      return view('projects.my_projects')->with(compact('user_id'));
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
  }
  // 1. init order in paypal and this paypal return a num. order that created in it 
  // 2. return the user into page payment (approve)
  // 3. after that will the paypal , this user pay now from his approving, after this , will excute for the order , and return the final result

  protected function paypal($project_id){
    $total=0;
    $project=Project::where(['id'=>$project_id])->first();
    $total =$total+$project->quantity*$project->price;
    //1. init for client that it will connect with api
    $client=$this->paypalClient($project,$total);
    //2.init order from class OrderCreateRequest ->this come from install sdk
    $request=new OrdersCreateRequest();
    $request->prefer('return=representation');
    //body , contains on info for the order
    $request->body = [
      "intent" => "CAPTURE",
      "purchase_units" => [[
          "reference_id" => $project->id,//this num. order that it for the user(client)
          "amount" => [
              "value" => $total,
              "currency_code" => "USD"
          ]
      ]],
      "application_context" => [//this is after paying the user
           "cancel_url" => url(route('paypal.cancel')),//cancel from user
           "return_url" => url(route('paypal.return'))//after paying the user , will redirect into page (in this page will show if the user pay or not ), which is from this will know status pending or accepted
      ]

  ];
  //after init order i will return for the user to approve(pay)
  try {
    // Call API with your client and get a response for your call
    $response= $client->execute($request); 
    // If call returns body in response, you can get the deserialized version from the result attribute of the response
print_r($response);
if($response->statusCode==201){
  //approve , capture , the link same it
      //when return the response will return the id order that created when approved
      //so will store this id in session , in session because i will need into it in another fun (paypalReturn)
     Session::put('paypal_order_id',$response->result->id);
     Session::put('order_id',$project->id);
  //now will return links for approve
  foreach($response->result->links as $link){
    if($link->rel=='approve'){
      return redirect()->away($link->href);   //this (away) because i will return the user into page external this website
    }
  }
}
}catch (Throwable $ex) {
  return $ex->getMessage();
echo $ex->statusCode;
print_r($ex->getMessage());
}
  return 'Unknown error'.$response->statusCode;


  }
  public function paypalReturn(){
   $paypal_order_id= Session::get('paypal_order_id');
   //capture for the order
   $request=new OrdersCaputreRequest($paypal_order_id);
   $request->prefer('return=representation');
   try{
    $response= $this->paypalClient()->execute($request);
    if($response->statusCode==201){
      //will return info for the payer الدافع
      if(strtoupper($response->result->status)=='COMPLETED'){
        // $idOrder=$response->purchase_units[0]->reference_id;
        $idOrder=Session::get('order_id');
        $project=Project::findOrFail($idOrder);
        $project->status_payment='completed';
        $project->save();
        Session::forget(['paypal_order_id','order_id']);


        return redirect('view_details_project')->with('flash_message_success','created and completed'.$idOrder);
      }

    }
    }catch(Throwable $e ){
      return $e->getMessage();
    }


  }
  protected function paypalClient(){
    $config=config('services.paypal');//araay for config for paypal
    $env= new SandboxEnvironment($config['client_id'],$config['client_secret']);
    $client=new PayPalHttpClient($env);
    return $client;
  }
  public function placeOrder($project_id){
    return view('projects.place_order')->with(compact('project_id'));
  }
  public function getCheckoutId(Request $req){ //pass $req because we use ajax
    $url = "https://test.oppwa.com/v1/checkouts";//in file env the best because will change it from test into live
	$data = "entityId=8a8294174b7ecb28014b9699220015ca" .
                "&amount=".$req->price .
                "&currency=EUR" .
                "&paymentType=DB";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if(curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
   $res=json_decode($responseData,true) ;
  //  dd($res);
   $view=view('ajax.form')->with(['responseData'=>$res,'sphere_id'=>$req->sphere_id,'project_id'=>$req->project_id])->renderSections();
   return response()->json([
     'status'=>true,
     'content'=>$view['main']
   ]);
  }
  public function getPaymentStatus($id,$resourcePath){
    $url = "https://test.oppwa.com/v1/checkouts/${resourcePath}/payment";
    $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                     'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if(curl_errno($ch)) {
      return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData,true);
  
  }
    public function viewDetailsProject($sphere_id,$project_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if(request('id')&&request('resourcePath')){
          $payment_status=$this->getPaymentStatus(request('id'),request('resourcePath'));
          if(isset($payment_status['id'])){//success payment id->transaction bank id
            $showSuccessPaymentMessage=true;
            return view('projects.view_details_project')->with(['success'=>$showSuccessPaymentMessage]);
          }else{
            $showFailPaymentMessage=true;
            return view('projects.view_details_project')->with(['fail'=>$showFailPaymentMessage]);
          }
        }
        
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userId=$user->id;
            $sphereId=$sphere_id;
            $usersSphereCount=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status',['user_id','!=',$userId]])->orWhere(['request_joining_status'=>'accepted_status_request_joining'])->count();//invit member into the project , which is these members must be in this sphere and not send into his invitation before time
            if($usersSphereCount!==0){
              $usersSphere=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status',['user_id','!=',$userId]])->orWhere(['request_joining_status'=>'accepted_status_request_joining'])->get();//invit member into the project , which is these members must be in this sphere and not send into his invitation before time
            }
            $project=Project::where(['id'=>$project_id])->first();
            $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
            $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
            $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            $spheresFounderUserCount=DB::table('spheres')->where(['founder_id'=>$userId])->count();
            if($spheresFounderUserCount!==0){
              $spheresFounderUser=DB::table('spheres')->where(['founder_id'=>$userId])->get();
            }
            $spheresLeaderUserCount=DB::table('sphere_users')->where(['user_id'=>$userId,'is_lead'=>1])->count();
            if($spheresLeaderUserCount!==0){
              $spheresLeaderUser=DB::table('sphere_users')->where(['user_id'=>$userId,'is_lead'=>1])->get();
            }
            $sphereThisProjectCount=DB::table('spheres')->where(['id'=>$sphereId])->count();
            if($sphereThisProjectCount!==0){
              $sphereThisProject=DB::table('spheres')->where(['id'=>$sphereId])->first();
            }
            if($sphereThisProjectCount!==0){
              $sphereThisProject=DB::table('spheres')->where(['id'=>$sphereId])->first();
            }
            
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
            return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
            }else{
              $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
              $usersProjectCount=DB::table('projects_users')->where(['sphere_id'=>$sphere_id,'user_id'=>$user->id,'invitation_status'=>'accepted_inivitation_status','project_id'=>$project_id])->count();//invit member into the project , which is these members must be in this sphere and not send into his invitation before time
              $sphereThisProjects=Sphere::where(['id'=>$sphere_id])->first();
              $nameSphereThisProjects  = $sphereThisProjects->name;
              $user=User::where(['email'=>Session::get('sessionUser')])->first();
              $userId=$user->id;
              $projectId=$project_id;
              $sphereId=$sphere_id;
              $project=project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->first();
              $projectFoundedByUserCount=DB::table('projects_users')->where(['id'=>$project_id,'sphere_id'=>$sphere_id,'user_id'=>$userId])->count();
              $projectAcceptedInvitationUserCount=DB::table('projects_users')->where(['project_id'=>$project_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'invitation_status'=>'accepted_inivitation_status'])->count();
              $projectAcceptedRequestUserCount=DB::table('projects_users')->where(['project_id'=>$project_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
              $projectPendingInvitationUserCount=DB::table('projects_users')->where(['project_id'=>$project_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
              $projectPendingRequestUserCount=DB::table('projects_users')->where(['project_id'=>$project_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining'])->count();
              $project=Project::where(['id'=>$projectId])->first();
              $projectPendingRequestJoinUserCount=DB::table('projects_users')->where(['project_id'=>$projectId,'sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining'])->count();
              $projectAcceptedRequestJoinUserCount=DB::table('projects_users')->where(['project_id'=>$projectId,'sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
              if($projectFoundedByUserCount!==0){
                $project=project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->first();
                return view('projects.view_details_project')->with(compact('spheresLeaderUser','spheresLeaderUserCount','spheresFounderUserCount','spheresFounderUser','sphereThisProjectCount','sphereThisProject','project','usersSphereCount','usersSphere','userId','project','sphereId','projectId','sphereThisProjects'));
              }elseif($projectAcceptedInvitationUserCount!==0){
                $project=project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->first();
                return view('projects.view_details_project')->with(compact('spheresLeaderUser','spheresLeaderUserCount','spheresFounderUserCount','spheresFounderUser','sphereThisProjectCount','sphereThisProject','project','usersSphereCount','usersSphere','userId','project','sphereId','projectId','sphereThisProjects'));
              }elseif($projectAcceptedRequestUserCount!==0){
                $project=project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->first();
                return view('projects.view_details_project')->with(compact('spheresLeaderUser','spheresLeaderUserCount','spheresFounderUserCount','spheresFounderUser','sphereThisProjectCount','sphereThisProject','project','usersSphereCount','usersSphere','userId','project','sphereId','projectId','sphereThisProjects'));
              }elseif($projectPendingInvitationUserCount!==0){
                  return redirect('/user/view-profile/'.$user->email)->with('flash_message_error','Please, go into your invitation to accept on invitation to this project to enter in it ');
              }elseif($projectPendingRequestUserCount!==0){
                return redirect('user/my-projects')->with('flash_message_error','Please, wait until his accepting in this project ');

              }else{
                return view('user.join_into_specific_project')->with(compact('spheresFounderUserCount','spheresFounderUser ','sphereThisProjectCount','project','projectPendingRequestJoinUserCount','userId','projectAcceptedRequestJoinUserCount','projectId','sphereId'));
              }
              $sphereThisProjects=Sphere::where(['id'=>$sphere_id])->first();
              $nameSphereThisProjects  = $sphereThisProjects->name;
              $project=Project::where(['id'=>$project_id])->first();
              $comments=Comment::Where(['project_id'=>$project_id])->get();
              return view('projects.view_details_project')->with(compact('spheresFounderUserCount','spheresFounderUser ','sphereThisProjectCount','project','usersSphereCount','usersSphere','userId','sphereThisProjects','projectId','sphereId','comments','project','sphereThisProjects','sphereThisProjects','nameSphereThisProjects','getPostsSphere'));
          }
        
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }

    public function createPost(Request $req){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($req->isMethod('post')){
          $data=$req->all();
          $newPost=new Post();
          $newPost->title=$data['title'];
          $newPost->body=$data['description'];
          $newPost->save();
        }
        return view('posts.create_post');
      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
    }

    public function addProjectIntoSubSphere($sub_sphere_id=null,Request $req){
      $userSession=Session::get('sessionUser');
      if($userSession){
      $sphere=Sphere::where(['id'=>$sub_sphere_id])->first();
      if(!empty($sphere)){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
          if($req->isMethod('post')){
              $data=$req->all();
              $newProject=new Project();
              $newProject->sphere_id=$sub_sphere_id;
              $newProject->user_id=$user->id;
              $newProject->name=$data['name'];
              $newProject->description=$data['description'];
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
                      // echo 'hghgdhgdhg';die;
                      $newProject->save();
                      return redirect('/sphere/'.$sub_sphere_id.'/projects')->with('flash_message_success','add success');
                 }
             }
              $newProject->save();
          }
          return view('projects.add_project_into_sub_sphere')->with(compact('sphere'));
      }else{
        return redirect('/user/add-project/'.$sub_sphere_id)->with('flash_message_error','The Data in this route is not exist');
      }

  }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
  }
    }
    public function editProject(Request $req, $project_id,$sphere_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            $projectCount=Project::where(['id'=>$project_id])->count();
            if($projectCount!==0){
              $project=Project::where(['id'=>$project_id])->first();
              $data=$req->all();
              if($req->isMethod('post')){
                $projects=Project::where(['id'=>$project_id])->update(['name'=>$data['name'],'description'=>$data['description']]);
                return redirect('/user/edit-project/'.$project->id.'/'.$sphere_id)->with('flash_message_success','edit success');
              }
              return view('projects.edit_project')->with(compact('project','sphere_id'));
            }else{
              return redirect('/user/edit-project/'.$project_id.'/'.$sphere_id)->with('flash_message_error','The Data in this route is not exist');
            }

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
    }
    public function editProjectImage(Request $req,$project_id ,$sphere_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            if($req->isMethod('post')){
              if($req->hasFile('image_project')){
                $image_tmp=$req->file('image_project');
                if($image_tmp->isValid()){
                    $extension=$image_tmp->getClientOriginalExtension();
                  $filename=rand(111,9999).'.'.$extension;
                    //save in folder
                    $small_image_path='images/backend_images/projects/small/'.$filename;
                    //resize, save
                      Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
                    //store in db
                    $editProject=Project::where(['id'=>$project_id])->update(['image'=>$filename]);
                    return redirect('/user/edit-project/'.$project_id.'/'.$sphere_id)->with('flash_message_success','edit image success');
                }
              }   
            }
            return view('projects.edit_image_project')->with(compact('project_id','sphere_id'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
    }
        public function getMembersSphereProject($sphereId=null,$project_id=null,$user_id=null){
          $userSession=Session::get('sessionUser');
          if($userSession){
              $membersProjectSphereCount=Project::where(['id'=>$project_id,'sphere_id'=>$sphereId])->first();
                if(!empty($membersProjectSphereCount)){
                  $member=$membersProjectSphereCount->usersProject()->where(['sphere_id'=>$sphereId,['user_id','!=',$user_id]])->get();
                  return response()->json([
                  'status'=>'fetch data members successfully',
                  'status'=>200,
                  'data'=>$member
              ]);
                
               }else{
                 return response()->json([
                  'status'=>404,
                  'data'=>'data in this route is null'
              ]);
                 
               }

            }else{
              return response()->json([
                'status' => 401,
                'message' =>  'You have not authorization to access into this page'
              ]);   
            } 
      }
        public function getMembersSphereProjectToJoinInTask($sphereId=null,$project_id=null,$user_id=null){
          $userSession=Session::get('sessionUser');
          if($userSession){
                $membersProjectSphereToJoinInTask=DB::table('projects_users')->where(['sphere_id'=>$sphereId,'project_id'=>$project_id,['user_id','!=',$user_id]])->get();
                return response()->json([
                  'status'=>'fetch data members for join in task , successfully',
                  'status'=>200,
                  'data'=>$membersProjectSphereToJoinInTask
              ]);          
        }else{
        return response()->json([
          'status'=>401,
          'data'=>'You have not authorization to access into this page'
        ]);
      }
        }
        public function getdataMemberSphereProject($sphereId=null,$project_id=null,$user_id=null){
          $userSession=Session::get('sessionUser');
          if($userSession){
            if($sphereId&&$project_id&&$user_id){
                $dataMember=DB::table('users')->where(['id'=>$user_id])->first();
                return response()->json([
                  'status'=>'fetch data member successfully',
                  'status'=>200,
                  'data'=>$dataMember
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


        public function myProjectsFounded(){
          $userSession=Session::get('sessionUser');
          if($userSession){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $user_id=$user->id;
             $myProjectsFounded=Project::where(['user_id'=>$user_id])->get(); 
            
            return response()->json([
              'status'=>200,
              'data'=>$myProjectsFounded
            ]);
        }else{
          return response()->json([
            'status'=>401,
            'data'=>'You have not authorization to access into this page'
          ]);
        }
        }

        
    public function myProjectsJoined(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;

         $myProjectssJoined=User::find($user_id)->projectsJoined()->get(); 

        return response()->json([
          'status'=>200,
          'data'=>$myProjectssJoined
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
    }
}

