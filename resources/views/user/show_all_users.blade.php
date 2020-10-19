@extends('layout')


@section('content')

@foreach ($users as $user)
<?php 
    $userInSphereCount=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id])->count();
    ?>
    {{--  if the user is not exist in this sphere: --}}
    @if($userInSphereCount==0)
    {{-- i will show him to invit him --}}
    <h5>{{$user->email}}</h5>
      <a href="{{url('/invit-member/'.$user->id.'/sphere/'.$sphere_id)}}">invit member</a>
    @else
    <?php  
    $userExsitPendingInSphereCount=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'invitation_status'=>'pending_inivitation_status'])->count();
?>
{{--  if the follower is  exist in this sphere , but he pending invitiation , not accepted: --}}
{{--  i will show this :sent the invitation , wait his accepting  --}}
@if($userExsitPendingInSphereCount!==0)
  <h5>{{$user->email}}</h5>
  <h5 style="color:red">sent the invitation , wait his accepting , you can make 
  <a href="{{url('/cancel-my-invitation-into-sphere/'.$user->id.'/'.$sphere_id)}}">cancel</a>  
@else
  <h4>all users in website is exist in this sphere</h4>
@endif
@endif
@endforeach
@endsection
