@extends('layout')


@section('content')
<?php
use App\Project;
use App\Sphere;
use App\Survey;
use App\User;
?>
<div class="formobheight">

   @if(!empty($msgFollow))
   {{$msgFollow}}
   <?php
   $member=User::where(['email'=>$memberEmail])->first();
   $user=User::where(['email'=>Session::get('sessionUser')])->first();
   $becomeFollower=DB::table('users_followers')->where(['user_id'=>$user->id,'follower_id'=>$member->id,'status_following'=>'accepted_status'])->first();
   ?>
   <a href="{{url('/decline-follow-member/'.$user->id.'/'.$becomeFollower->follower_id)}}">Decline Follow</a>
   you can send message
   <a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$becomeFollower->follower_id)}}" class="btn btn-info">message</a>
   @endif
   <?php
   $user=User::where(['email'=>Session::get('sessionUser')])->first();
   $member=User::where(['email'=>$memberEmail])->first();
   $FolowCount=DB::table('users_followers')->where(['user_id'=>$userId,'follower_id'=>$member->id,'status_following'=>'pending_status'])->count();
   $follow=DB::table('users_followers')->where(['user_id'=>$userId,'follower_id'=>$member->id,'status_following'=>'pending_status'])->first();?>
   @if($FolowCount!==0)
      <div>
         this member sent a request following for you 
      </div>
      <a href="{{url('/accept-follow/'.$user->id.'/'.$follow->follower_id)}}" class="btn btn-success">Accept Follow</a>
      <a href="{{url('/decline-follow-member/'.$user->id.'/'.$follow->follower_id)}}" class="btn btn-success">Decline Follow</a>
    @else
   <?php
      $folowMemberCount=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id])->count();
      $followMemberPendingCount=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id,'status_following'=>'pending_status'])->count();
      $followMemberAcceptingCount=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id,'status_following'=>'accepted_status'])->count();
   ?>
      @if($folowMemberCount==0)
         <?php
         $followMemberPendingCount=DB::table('users_followers')->where(['user_id'=>$user->id,'follower_id'=>$member->id,'status_following'=>'pending_status'])->count();
         ?>
      @if($followMemberPendingCount==0)
         <?php
         $followMemberAcceptedCount=DB::table('users_followers')->where(['user_id'=>$user->id,'follower_id'=>$member->id,'status_following'=>'accepted_status'])->count();
   
         ?>
      @if($followMemberAcceptedCount==0)
         <a href="{{url('follow-member/'.$userId.'/'.$memberEmail)}}">follow this member</a>
      @endif
      @else
         <h5>you can decline the following from here</h5>
         <?php
            $becomeFollower=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id,'status_following'=>'accepted_status'])->first();
         ?>
         <a href="{{url('/decline-follow/'.$user->id.'/'.$becomeFollower->user_id)}}">Decline Follow</a>
         you can send message
<a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$becomeFollower->follower_id)}}" class="btn btn-info">message</a>
         
         <a href=""></a>
   @endif
         @else
         <?php
            $becomeFollower=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id,'status_following'=>'pending_status'])->first();?>
            @if($followMemberPendingCount!=0)
             <h4>you sent request following into this member , and you cancel it </h4>
            <a href="{{url('/decline-follow/'.$user->id.'/'.$becomeFollower->user_id)}}">Decline Follow</a>
            @else


            <?php
            $becomeFollower=DB::table('users_followers')->where(['user_id'=>$member->id,'follower_id'=>$user->id,'status_following'=>'accepted_status'])->first();
      
      
        ?>
               <h5>you can decline the following from here</h5>
               <a href="{{url('/decline-follow/'.$user->id.'/'.$becomeFollower->user_id)}}">Decline Follow</a>
               you can send a message from here
<a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$becomeFollower->user_id)}}" class="btn btn-info">message</a>
               
         @endif      
      @endif        
   @endif       
    
   
   <div>
   
   
   
      <div class="leftbiopanel ">
         <div class="box">
           @if(empty($user->image))
      
           <div><img src="https://getdrawings.com/free-icon/default-avatar-icon-65.png" alt="Avatar" class="avatar avatar-user width-full border bg-white" style="    width: 205px;
      height: 156px;
      border-radius: 29%;"></div>
           @else
           <div><img src="{{asset('/images/backend_images/user/small/'.$member->image)}}" alt="" style="    border-radius: 50%;"/></div>
      
      @endif
           <h2>Username : {{$member->username}}</h2>
         <h3>Bio : </h3>
           <p>{{$member->bio}}</p>
           <h3>{{$member->interests}}</h3>
         </div>
       </div>
       <div class="rightbiopanel">
         <div class="midbox">
           <div class="sglebiosec">
             <p>{{$member->best_sentence}}</p>
           </div>
           <div class="sglebiosec">
             <div class="row items-container">
               <div class="halfcolumn item">
                 <h2>Founded</h2>
                   @if($spheresFoundedCount==0)
                       <div class="alert alert-info">
                       there is no spheres you founded it , until now
                       </div>
                   @else
                       @foreach($spheresFounded as $sphereFounded)
                       <div class="row">
                           <div class="sgleiconcontbio"><img src="{{asset('/images/backend_images/spheres/small/'.$sphereFounded->image)}}" />
                           <h3><a href="{{url('/sphere/'.$sphereFounded->id.'/posts')}}">{{$sphereFounded->name}}</a></h3>
                           </div>
                       </div>
                       @endforeach
                   @endif
      
                   
               </div>
               {{-- <div class="halfcolumn item">
                 <h2>Joined</h2>
                 @if($spheresJoinedCount==0)
                   <div class="alert alert-info">
                   there is no spheres you joined in it , until now
                   </div>
               @else
                   @foreach($spheresJoined as $sphereJoined)
                   <?php
                       $sphere=Sphere::where(['id'=>$sphereJoined->sphere_id])->first();
      
                   ?>
                   <div class="row">
                       <div class="sgleiconcontbio"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" />
                       <h3>{{$sphere->name}}</h3>
                       </div>
                   </div>
                   @endforeach
               @endif
               </div> --}}
      
      
               <div class="halfcolumn item">
                 <h2>Joined</h2>
                 @if($spheresJoinedCount==0)
                 <div class="alert alert-info">
                   there is no spheres this member joined in it , until now
                   </div>
               @else
                 <div class="row">
               @foreach($spheresJoined as $sphereJoined)
                   <?php
                       $sphere=Sphere::where(['id'=>$sphereJoined->sphere_id])->first();
      
                   ?>
                   <div class="sgleiconcontbio"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" />
                     <h3><a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a></h3>
                   </div>
                   
                   @endforeach
                 </div>
               @endif
               </div>
             </div>
           </div>
           <div style="width:100%; overflow:hidden;">
             <div class="row items-container">
               <div class="btmfirstsec item">
                 <div class="sglebiosec">
                   <h2>Projects</h2>
                   {{-- <table class="table table-bordered">
                     <thead>
                       <tr>
                         <th scope="col">Joined</th>
                         <th scope="col">Created</th>
                       </tr>
                     </thead>
                     <tbody>
                      {{-- <?php 
                         // variable that contains both created and joined and we loop through it to check if it is joined or created
                         $projects = [];
                         array_push($projects,$spheresFounded,$spheresJoined);
                         ?>
                             @if(!empty($projects))
       
                               @foreach($projects as $project)
                                 @foreach($project as $pro)
                                 
                                   @if($pro->founder_id)
                                   <tr>
                                     <td class="text-center">created</td>
                                     @else
                                     <td class="text-center">joined</td>
                                     @endif
                             
                                   @endforeach
                               @endforeach
                             @endif --}}
                               
                     </tbody>
                   {{-- </table> --}} 
                   
                   <table  class="table-bordered">
                     <thead>
                     <tr>
                       <th scope="col" class="text-center">Created</th>
                       <th scope="col" class="text-center" >Joined</th>
                     </tr>
                   </thead>
                   <tbody>
                     
                     <tr>
                       @if($projectsJoinedCount!==0)
                  
                   @foreach($projectsJoined as $projectJoined)
                   <?php
                       $project=Project::where(['id'=>$projectJoined->project_id])->first();
      
                   ?>
      
                       <td>
                         <a href="{{url('view-details-project/'.$projectJoined->sphere_id.'/'.$project->id)}}">{{$project->name}}</a>
                         </td>
                   @endforeach
               @endif
               @if($projectsFoundedCount !== 0)
                         
                       @foreach($projectsFounded as $projectFounded)
                       <tr>
      
                         <td>
                           <a href="{{url('view-details-project/'.$projectFounded->sphere_id.'/'.$projectFounded->id)}}">{{$projectFounded->name}}</a>
                         </td>
                       </tr>
                       @endforeach
                   @endif
                       </tbody>
      
                   </table>
                 </div>
               </div>
               <div class="btmmidsec item">
                 <div class="sglebiosec">
                   <h2>Surveys</h2>
                   <p>List surveys use has done</p>
                   <a href="/get-all-surveys-joined-it">See More</a> </div>
               </div>

             </div>
           </div>
         </div>
       </div>
      </div>
{{-- --}}
<?php 
//photos member
$imagesMember=DB::table('backup_user_images')->where(['user_id'=>$member->id])->get();
$imagesMemberCount=DB::table('backup_user_images')->where(['user_id'=>$member->id])->get();

//this member follow this user session
 $memberFollowUserSessionCount=DB::table('users_followers')->where(['follower_id'=>$member->id,'user_id'=>$user->id,'status_following'=>'accepted_status'])->count();
 $memberFollowUserSession =DB::table('users_followers')->where(['follower_id'=>$member->id,'user_id'=>$user->id,'status_following'=>'accepted_status'])->get();

 //usersession follow this member
 $usersessionFollowThisMemberCount=DB::table('users_followers')->where(['follower_id'=>$user->id,'user_id'=>$member->id,'status_following'=>'accepted_status'])->count();
 $usersessionFollowThisMember =DB::table('users_followers')->where(['follower_id'=>$user->id,'user_id'=>$member->id,'status_following'=>'accepted_status'])->get();

 // persons that follow by this member
 $personsFollowThisMemberCount=DB::table('users_followers')->where(['follower_id'=>$member->id,'status_following'=>'accepted_status'])->count();
 $personsFollowThisMember =DB::table('users_followers')->where(['follower_id'=>$member->id,'status_following'=>'accepted_status'])->get();

 // followers this member
 $followersThisMemberCount=DB::table('users_followers')->where(['user_id'=>$member->id,'status_following'=>'accepted_status'])->count();
 $followersThisMember =DB::table('users_followers')->where(['user_id'=>$member->id,'status_following'=>'accepted_status'])->get();
?>

@if($memberFollowUserSessionCount==0)
    @if($usersessionFollowThisMemberCount!==0)
        <h4>you follow this member , so you can see this ssetion </h4>
    @else
      <h4>you can not see this section , because this member not follow you</h4>
    @endif
@else
    {{-- section: photos member --}}
    <h2>Photos </h2>
    @if($imagesMemberCount!==0)
        <div class="btmrytsec item">
        <div class="row sglebiosec" style="height: 74%;">
            @foreach($imagesMember as $memberImage)
            <div class="col-md-3">
                @if(empty($memberImage->image))
                <img src="https://getdrawings.com/free-icon/default-avatar-icon-65.png" alt="Avatar" class="avatar avatar-user width-full border bg-white" >
                @else
                <img src="{{asset('/images/backend_images/user/small/'.$memberImage->image)}}" alt="" />
                @endif
            </div>
            @endforeach
        </div>
        </div>
    @else
        <div >
        there is no images for this user until now
        </div>
    @endif
    {{-- section: persons that follow by this member --}}
    personsFollowThisMember:
    @if($personsFollowThisMemberCount==0)
        @if($memberFollowUserSessionCount!==0)
            <h5>there is not exist persons , this member follow them</h5>
        @endif
    @else
        @foreach($personsFollowThisMember as $personFollowThisMember)
            <?php
            $person=DB::table('users')->where(['id'=>$personFollowThisMember->user_id])->first();
            ?>
               
@if($person->id!==$user->id)

Username: <a href="{{url('/user/view-profile-member/'.$person->email)}}" class="btn btn-success">username: {{$person->email}}</a>

<a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$personFollowThisMember->follower_id)}}" class="btn btn-info">message</a>
@else 
Username: <a href="{{url('/user/view-profile-member/'.$person->email)}}" class="btn btn-success">username: {{$person->email}}</a>

<a  class="btn btn-info">You</a>
@endif
        @endforeach
    @endif
    {{-- section: followers this member --}}
    followersThisMember:
    @if($followersThisMemberCount==0)
        <h5>there is not exist followers for this member</h5>
    @else
        @foreach($followersThisMember as $followerThisMember)
            <?php
            $follower=DB::table('users')->where(['id'=>$followerThisMember->follower_id])->first();
            ?>
              @if($follower->id!==$user->id)
              Username: <a href="{{url('/user/view-profile-member/'.$follower->email)}}" class="btn btn-success">username: {{$follower->email}}</a>
              <a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$hisFollower->user_id)}}" class="btn btn-info">message</a>
            @else 
            Username: <a href="{{url('/user/view-profile-member/'.$follower->email)}}" class="btn btn-success">username: {{$follower->email}}</a>
            <a  class="btn btn-info">You</a>
            @endif
          
        @endforeach
    @endif
@endif

<?php 

 // persons that follow by this member
 $personsFollowThisMemberCount=DB::table('users_followers')->where(['follower_id'=>$member->id,'status_following'=>'accepted_status'])->count();
 $personsFollowThisMember =DB::table('users_followers')->where(['follower_id'=>$member->id,'status_following'=>'accepted_status'])->get();

 // followers this member
 $followersThisMemberCount=DB::table('users_followers')->where(['user_id'=>$member->id,'status_following'=>'accepted_status'])->count();
 $followersThisMember =DB::table('users_followers')->where(['user_id'=>$member->id,'status_following'=>'accepted_status'])->get();
?>

@if($usersessionFollowThisMemberCount==0)
        @if($memberFollowUserSessionCount!==0)
            <h4>you can see this section because this member follow you</h4>
        @else
        <h4>you can not see this section , because you not follow his </h4>
        @endif
    
@else
    {{-- section: photos member --}}
    <h2>Photos </h2>
    @if($imagesMemberCount!==0)
        <div class="btmrytsec item">
        <div class="row sglebiosec" style="height: 74%;">
            @foreach($imagesMember as $memberImage)
            <div class="col-md-3">
                @if(empty($memberImage->image))
                <img src="https://getdrawings.com/free-icon/default-avatar-icon-65.png" alt="Avatar" class="avatar avatar-user width-full border bg-white" >
                @else
                <img src="{{asset('/images/backend_images/user/small/'.$memberImage->image)}}" alt="" />
                @endif
            </div>
            @endforeach
        </div>
        </div>
    @else
        <div >
        there is no images for this user until now
        </div>
    @endif
    {{-- section: persons that follow by this member --}}
    personsFollowThisMember: 
    @if($personsFollowThisMemberCount==0)
        <h5>there is not exist persons , this member follow them</h5>
    @else
        @foreach($personsFollowThisMember as $personFollowThisMember)
            <?php
            $person=DB::table('users')->where(['id'=>$personFollowThisMember->user_id])->first();
            ?>
            

@if($person->id!==$user->id)

              Username: <a href="{{url('/user/view-profile-member/'.$person->email)}}" class="btn btn-success">username: {{$person->email}}</a>

  <a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$personFollowThisMember->follower_id)}}" class="btn btn-info">message</a>
  @else 
  Username: <a href="{{url('/user/view-profile-member/'.$person->email)}}" class="btn btn-success">username: {{$person->email}}</a>

  <a  class="btn btn-info">You</a>
  @endif

        @endforeach
    @endif
    {{-- section: followers this member --}}
    followersThisMember:
    @if($followersThisMemberCount==0)
        <h5>there is not exist followers for this member</h5>
    @else
        @foreach($followersThisMember as $followerThisMember)
            <?php
            $follower=DB::table('users')->where(['id'=>$followerThisMember->follower_id])->first();
            ?>
            @if($follower->id!==$user->id)

            Username: <a href="{{url('/user/view-profile-member/'.$follower->email)}}" class="btn btn-success">username: {{$follower->email}}</a>

<a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$followerThisMember->follower_id)}}" class="btn btn-info">message</a>
@else 
Username: <a href="{{url('/user/view-profile-member/'.$follower->email)}}" class="btn btn-success">username: {{$follower->email}}</a>

<a  class="btn btn-info">You</a>
@endif
        @endforeach
    @endif
@endif


{{-- <div id="app">
    <Profile user_id={{$user->id}} />
</div> --}}
<style>
   .formobheight{
  background-image:url("{{asset('/images/backend_images/user/small/'.$member->cover_image)}}") 
 }
 </style>
@endsection
