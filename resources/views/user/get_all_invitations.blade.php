@extends('layout')
@section('content')
@if($userMentionsCount!==0)
    @foreach($userMentions as $userMention)
    <a href="{{url('/view-details-comment-post/'.$userMention->id.'/'.$userMention->post_id.'/'.$userId.'/'.$userMention->sphere_id)}}" class="btn btn-success">View details mention comment </a>
    @endforeach
@endif
    @if($countUserSpheres!==0)
      @foreach($UserSpheres as $userSphere)
      <?php
        $events=  DB::table('events_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
        $eventsCount=  DB::table('events_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
       ?>
        @if($eventsCount!==0)
          @foreach($events as $event)
            <a href="{{url('/view-details-request-join-event/'.$userSphere->sphere_id.'/'.$event->event_id.'/'.$event->user_id)}}" class="btn btn-success">View-details-request-join-evenr for user in sphere</a>
          @endforeach
        @endif



     <?php $spheresCount=  DB::table('sphere_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
     ?>
   @if($spheresCount!==0)
       <?php $spheres=  DB::table('sphere_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
       ?>
     @if($spheresCount!==0)
       @foreach($spheres as $sphere)
         <a href="{{url('/view-details-request-join-sphere/sphere/'.$userSphere->sphere_id.'/'.$sphere->user_id)}}" class="btn btn-success">View-details-request-join-sphere  </a>
       @endforeach
     @endif
   @endif   


          <?php $projectsCount=  DB::table('projects_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
          ?>
        @if($projectsCount!==0)
            <?php $projects=  DB::table('projects_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
            ?>
          @if($projectsCount!==0)
            @foreach($projects as $project)
              <a href="{{url('/view-details-request-join-project/'.$userSphere->sphere_id.'/'.$project->project_id.'/'.$project->user_id)}}" class="btn btn-success">View-details-request-join-project for user in sphere </a>
            @endforeach
          @endif
        @endif                    
                 
                  
          <?php $conversationsCount=  DB::table('conversations_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
          ?>
        @if($conversationsCount!==0)
          <?php  $conversations=  DB::table('conversations_users')->where(['sphere_id'=>$userSphere->sphere_id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
          ?>
          @if($conversationsCount!==0)
            @foreach($conversations as $conversation)
              <a href="{{url('/view-details-request-join-conversation/'.$userSphere->sphere_id.'/'.$conversation->conversation_id.'/'.$conversation->user_id)}}" class="btn btn-success">View-details-request-join-conversation for user in sphere </a>

            @endforeach
          @endif
        @endif
      @endforeach
    @endif
        
{{-- founder that founded sphere --}}
<?php
$countFounderSpheres=  DB::table('spheres')->where(['founder_id'=>$userId])->count();?>
  @if($countFounderSpheres!==0)
    <?php  $FounderSpheres=  DB::table('spheres')->where(['founder_id'=>$userId])->get();?>
      @foreach($FounderSpheres as $founderSphere)
        <?php   $events=  DB::table('events_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
        $eventsCount=  DB::table('events_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
       ?>
        @if($eventsCount!==0)
          @foreach($events as $event)
            <a href="{{url('/view-details-request-join-event/'.$founderSphere->id.'/'.$event->event_id.'/'.$event->user_id)}}" class="btn btn-success">View-details-request-join-evenr for user in sphere</a>
          @endforeach
        @endif
        
          <?php $spheresCount=  DB::table('sphere_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
            ?>
            @if($spheresCount!==0)
            <?php   $spheres=  DB::table('sphere_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
            ?>
              @foreach($spheres as $sphere)
              <?php $user=  DB::table('users')->where(['id'=>$sphere->user_id])->first();
              $userCount=  DB::table('users')->where(['id'=>$sphere->user_id])->count();
              ?>
                @if($userCount!==0)
                    <a href="{{url('/view-details-request-join-sphere/sphere/'.$sphere->sphere_id.'/'.$sphere->user_id)}}" class="btn btn-success">View-details-request-join-sphere for user {{$user->username}}</a>
                @endif
              @endforeach
            @endif
              
              <?php  $projectsCount=  DB::table('projects_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
               ?>
               @if($projectsCount!==0)
                <?php    $projects=  DB::table('projects_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
                ?>
                @if($projectsCount!==0)
                  @foreach($projects as $project)
                    <a href="{{url('/view-details-request-join-project/'.$founderSphere->id.'/'.$project->project_id.'/'.$project->user_id)}}" class="btn btn-success">View-details-request-join-project for user in sphere </a>
                  @endforeach
                @endif 
                <?php
                  $conversationsCount=  DB::table('conversations_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->count();//every sphere that the user join in it
                ?>
                @if($conversationsCount!==0)
                <?php
                  $conversations=  DB::table('conversations_users')->where(['sphere_id'=>$founderSphere->id,'request_joining_status'=>'pending_status_request_joining'])->get();//every sphere that the user join in it
                  ?>
                    @if($conversationsCount!==0)
                        @foreach($conversations as $conversation)
                          
                            <a href="{{url('/view-details-request-join-conversation/'.$founderSphere->id.'/'.$conversation->conversation_id.'/'.$conversation->user_id)}}" class="btn btn-success">View-details-request-join-conversation for user in sphere </a>
    
                        @endforeach
                    @endif
                  @endif
                @endif
  @endforeach                    
  @endif
  
{{-- in profile the leader task--}}
<?php 
$leaderTasksCount=DB::table('tasks')->where(['user_id'=>$userSession->id])->count();?>
@if($leaderTasksCount!==0)
<?php
$leaderTasks=DB::table('tasks')->where(['user_id'=>$userSession->id])->get();//get all tasks that this person lead it (assign many tasks) 
?> 
  @foreach($leaderTasks as $leaderTask){
  <?php $tasks=DB::table('tasks')->where(['sphere_id'=>$leaderTask->sphere_id,'project_id'=>$leaderTask->project_id,'id'=>$leaderTask->id,'start_task'=>$leaderTask->start_task,'end_task'=>$leaderTask->end_task])->get();
  ?>
  @foreach($tasks as $task){
    {{-- will show for this person in his profile : status these tasks that will do it the users that assigned for them these tasks --}}
      @if($task->status_task=='finished_task')
        {{--when the user finished from his task will move this task from in-progress task status(category2) into finished status(category3) , so will appear this button here  --}}
        <a href="{{url('/view-details-task-member-finished/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-warning">View-details-task-member-that-finished</a>
        @elseif($task->status_task=='in-progress_task')
          {{--when the user still in his task  will be this task  in-progress task status(category2)  so will appear this button here  --}}
        <a href="{{url('/view-details-task-member-in-progress/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-danger">View-details-task-member-that-in-progress</a>
          {{--when the leader accept on task the user that he finished from it , will move this task from finished into completed task , so will appear this button here  --}}
        @elseif($task->status_task='completed_task')
        <a href="{{url('/view-details-task-member-in-completed/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-info">View-details-task-member-that-completed</a>
      @endif
                    
    @endforeach
  @endforeach
@endif
  
  {{--in profile user   --}}
<?php  
$user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
$memberTasksCount=DB::table('tasks')->where(['user_id'=>$user->id])->count(); ?>
  @if($memberTasksCount)
  <?php  $memberTasks=DB::table('tasks')->where(['user_id'=>$user->id])->get();?>
    @foreach($memberTasks as $memberTask)
       <?php //get all tasks that i am doing in it
      $tasks=DB::table('tasks')->where(['sphere_id'=>$memberTask->sphere_id,'project_id'=>$memberTask->project_id,'id'=>$memberTask->id,'description_task'=>$memberTask->description_task,'start_task'=>$memberTask->start_task,'end_task'=>$memberTask->end_task])->get();
      ?>
      @foreach($tasks as $task)
          {{-- // when i finished from task will move this task from in-progress status into finished status , so will appear here this button --}}
        @if($task->status_task=='finished_task')
          <a href="{{url('/view-details-my-task-finished/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-warning">View-details-my-task-that-finished</a>
          @elseif($task->status_task=='in-progress_task')
          {{-- // when i am doing in   task will  be in-progress status , so will appear here this button --}}   
            <a href="{{url('/view-details-my-task-in-progress/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-danger">View-details-my-task-that-in-progress</a>
          @elseif($task->status_task='completed_task')
          {{-- // when i finished from task  and this task accepted by the the leader will move this task from finished status into completed status  and , so will appear here this button --}}
            <a href="{{url('/view-details-my-task-in-completed/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$task->member_id)}}" class="btn btn-info">View-details-my-task-that-completed</a>
        @endif
      @endforeach
    @endforeach
  @endif


    

<?php
$userCountStatusFollowing=DB::table('users_followers')->where(['user_id'=>$userId,'status_following'=>'pending_status'])->count();
$userStatusFollowing=DB::table('users_followers')->where(['user_id'=>$userId,'status_following'=>'pending_status'])->get();
?>
@if($userCountStatusFollowing!=0)
    @foreach($userStatusFollowing as $following)
        <?php
        $follower=DB::table('users')->where(['id'=>$following->follower_id])->first();
        ?>
        <a href="{{url('/user/view-profile-member/'.$follower->email)}}" class="btn btn-success">View-profile-this-member</a>
    @endforeach 
@endif  
<?php
$userCountStatusInivitation=DB::table('sphere_users')->where(['user_id'=>$userId,'invitation_status'=>'pending_inivitation_status'])->count();
$userStatusInivitation=DB::table('sphere_users')->where(['user_id'=>$userId,'invitation_status'=>'pending_inivitation_status'])->get();
?>
@if($userCountStatusInivitation!=0)
    @foreach($userStatusInivitation as $invitation)
    <a href="{{url('/view-details-invitation-join-sphere/'.$invitation->sphere_id)}}" class="btn btn-success">View-details-invitation-join-sphere</a>
    @endforeach
@endif
<?php
$userCountStatusInivitationProject=DB::table('projects_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
$userStatusInivitationProject=DB::table('projects_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->get();
?>
@if($userCountStatusInivitationProject!==0)
    @foreach($userStatusInivitationProject as $invitation)
    <a href="{{url('/view-details-invitation-join-project/'.$invitation->project_id.'/sphere/'.$invitation->sphere_id)}}" class="btn btn-success">View-details-invitation-join-project</a>
    @endforeach
@endif
{{-- show invitations tasks for me --}}
<?php
$userCountStatusInivitationtask=DB::table('tasks_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
$userStatusInivitationtask=DB::table('tasks_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->get();
?>
@if($userCountStatusInivitationtask!==0)  
  @foreach($userStatusInivitationtask as $invitation)
    <a href="{{url('/view-details-invitation-join-task/'.$invitation->task_id.'/sphere/'.$invitation->sphere_id.'/project/'.$invitation->project_id.'/category/'.$invitation->category_id)}}" class="btn btn-success">View-details-invitation-join-task</a>
  @endforeach
@endif
<?php
$userCountStatusInivitationConversation=DB::table('conversations_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
$userStatusInivitationConversation=DB::table('conversations_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->get();
?>
@if($userCountStatusInivitationConversation!==0) 
  @foreach($userStatusInivitationConversation as $invitation)

  <a href="{{url('/view-details-invitation-join-conversation/'.$invitation->conversation_id.'/sphere/'.$invitation->sphere_id)}}" class="btn btn-success">View-details-invitation-join-conversation</a>
  @endforeach
@endif

    <?php
$userCountStatusInivitationEvent=DB::table('events_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
$userStatusInivitationEvent=DB::table('events_users')->where(['user_id'=>$userId,'invitation_status'=>'status_pending'])->get();
?>
@if($userCountStatusInivitationEvent!==0)
  @foreach($userStatusInivitationEvent as $invitation)
  <a href="{{url('/view-details-invitation-join-event/'.$invitation->event_id.'/sphere/'.$invitation->sphere_id)}}" class="btn btn-success">View-details-invitation-join-event</a>
  @endforeach
@endif

{{-- show details my tasks that it still pending , which is when i open it , i can aacept on it , or  rejected it --}}

<?php
$userCountStatusTaskPending=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task_invitation'=>'pending_status_task'])->count();
$userStatusTaskPending=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task_invitation'=>'pending_status_task'])->get();
?>
@if($userCountStatusTaskPending!=0)
    @foreach($userStatusTaskPending as $taskInvitation)
    <a href="{{url('/view-details-my-task/'.$taskInvitation->id.'/'.$taskInvitation->sphere_id.'/'.$taskInvitation->project_id.'/'.$taskInvitation->user_id.'/'.$userSession->id)}}" class="btn btn-success">View-details-invitation-task</a>
    @endforeach
@endif
<?php 
$userCountStatusTaskInprogress=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task'=>'in-progress_task'])->count();
$CountStatusTaskInprogress=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task'=>'in-progress_task'])->get();
$userCountStatusTaskFinished=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task'=>'finished_task'])->count();
$userStatusTaskFinished=DB::table('tasks')->where(['user_id'=>$userSession->id,'status_task'=>'finished_task'])->get();

$userCountStatusTaskCompleted=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task'=>'completed_task'])->count();
$userStatusTaskCompleted=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task'=>'completed_task'])->get();

$userCountStatusTaskAccepted=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task_invitation'=>'accepted_status_task'])->count();
$userStatusTaskAccepted=DB::table('tasks')->where(['member_id'=>$userSession->id,'status_task_invitation'=>'accepted_status_task'])->get();
?>

{{-- show details my tasks that i am doing in it  --}}
@if($userCountStatusTaskAccepted!=0)
    @foreach($userStatusTaskAccepted as $taskInvitation)
        @if($userCountStatusTaskInprogress!=0)
        <div class="alert alert-info">
            Your task {{$taskInvitation->name_task}}   in-progress
        </div> 
        @endif

        {{-- show details my tasks that  finished from it , and i am wating the leader accept on it  --}}
        @if($userCountStatusTaskFinished!==0)
            @foreach($userStatusTaskFinished as $taskFinshed)
                <div class="alert alert-warning">
                    Your task {{$taskFinshed->name_task}}  finished , just wait the leader task accept on it
                </div>
            @endforeach
        @endif
        {{-- show details my tasks that it completed , means that this task that accept on it by the leader--}}
            @if($userCountStatusTaskCompleted!==0)
                @foreach($userStatusTaskCompleted as $taskcompleted)
                    <div class="alert alert-warning">
                        Your task {{$taskcompleted->name_task}}  accepted on it by leader the task 
                    </div>
                @endforeach
            @endif
        <a href="{{url('/view-details-my-task/'.$taskInvitation->id.'/'.$taskInvitation->sphere_id.'/'.$taskInvitation->project_id.'/'.$taskInvitation->user_id.'/'.$userSession->id)}}" class="btn btn-success">View-details-my-task</a>
    @endforeach
@endif
<div>
@endsection
