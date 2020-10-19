
@extends('layout')
@section('content')
<h3>invit members into this project</h3>
{{-- get all members this sphere , must be these members accepted status invitation or joining  --}}
@if($usersSphereCount!==0) 
  @foreach($usersSphere as $user)
    <?php
      $memberSphere= DB::table('users')->where(['id'=>$user->user_id])->first();
    ?>
    {{-- are these members is exsit in this project or not --}}
    <?php
    $projectUserCount=DB::table('projects_users')->where(['user_id'=>$user->user_id,'project_id'=>$projectId,'sphere_id'=>$sphereId])->count();
    ?>
    @if($projectUserCount==0)
      {{-- if not exist , will show link for invit member  --}}
      <h6>{{$memberSphere->email}}</h6>
      <a href="{{url('/invit-member/'.$memberSphere->id.'/sphere/'.$sphereId.'/project/'.$projectId)}}">invit member</a>
    @else
      {{-- if exist , but is pending , will show link -> sent the invitation , wait his accepting it  --}}
      <?php
      $projectPendingUserCount=DB::table('projects_users')->where(['user_id'=>$memberSphere->id,'project_id'=>$projectId,'sphere_id'=>$sphereId,'invitation_status'=>'status_pending'])->count();
      ?>
      @if($projectPendingUserCount!==0)
        <h6>{{$memberSphere->email}}</h6>
        <h5 style="color:red">sent the invitation , wait his accepting it</h5>
        <a href="{{url('/cancel-my-invitation-into-project/'.$memberSphere->id.'/'.$projectId.'/'.$sphereId)}}">cancel</a>  

      @endif
    @endif
  @endforeach
  <div class="alert alert-warning">
    you can invit persons to join in sphere <a href="{{url('/sphere/'.$sphereThisProjects->id.'/posts')}}">from here</a> after that to join in this project  
  </div>  
@else
  {{-- if this sphere not contain on any persons , so here not exist any person to join into this project , so show a msg : there is no members in this sphere until now , so you invitation persons to join in this sphere  --}}
  <div class="alert alert-info" role="alert">
    <h4>there is no members in this sphere until now , so you invitation persons to join in this sphere  <a href="{{url('/sphere/'.$sphereThisProjects->id.'/posts')}}">from here</a></h4>
  </div>
@endif

<?php
?>
add my vote on this project: 
<form action="{{url('/main-page-sphere/add-vote-on-project-in-sphere/'.$sphereThisProjects->id.'/'.$projectId)}}" method="post">{{csrf_field()}}

  <button type="submit">Add My Vote</button>
</form>
@if($spheresFounderUserCount!==0)
  @foreach($spheresFounderUser as $sphereFounderUser)
    @if($sphereFounderUser->id==$sphereThisProject->id)
    <a href="{{url('/sphere/'.$sphereId.'/project/'.$projectId.'/tasks')}}">distribute-tasks-project</a>
    @endif
  @endforeach
@endif
@if($spheresLeaderUserCount!==0)
  @foreach($spheresLeaderUser as $sphereLeaderUser)
    @if($sphereLeaderUser->sphere_id==$sphereThisProject->id)
      <a href="{{url('/sphere/'.$sphereId.'/project/'.$projectId.'/tasks')}}">distribute-tasks-project</a>
    @endif
  @endforeach
@endif
  
  <div>
    Title: {{$project->name}}
  </div>
  <div >
    description project : {{$project->description}}
  </div>
  <div>
    From : {{$project->start_project}}
    To : {{$project->end_project}}
  </div>
  <h6 id="price">price: {{$project->price}}</h6>
  <a href="{{url('/place-order/'.$project->id)}}">Place Order</a>
  <a href="{{url('/get-checkout-id/'.$project->price)}}" id="checkout">Pay now</a>
  <div>
    <h3>select your payment method</h3>
    <div id="showPayForm">
      @if(isset($success))
        <div class="alert alert-success text-center">
          payed successfully
        </div>
      @endif
      @if(isset($fail))
      <div class="alert alert-danger text-center">
        payed failur
      </div>
    @endif
    </div>
  </div>

<div id="app">

<viewdetailsproject  project_id={{$projectId}} sphere_id={{$sphereId}} user_id={{$userId}}  />
</div>

@endsection

@section('scripts')
  <script>
    $(document).on('click','#checkout',function(e){
      e.preventDefault();
      $.ajax({
        type:'get',
        url:"{{route('project.checkout')}}",
        data:{
          price:$('#price').text(),
          sphere_id:'{{$project->sphere_id}}',
          project_id:'{{$project->id}}'
        },
        success:function(data){
          if(data.status==true){
            $('#showPayForm').empty().html(data.content);
          }
        }
      })
    })
  </script>
  @stop