
@extends ("layout")
@section("content")
<?php
use App\Sphere;
$sphere=Sphere::where(['id'=>$sphereId])->first();
$spherePendingRequestJoinUserCount=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining'])->count();
$sphereAcceptedRequestJoinUserCount=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
?>
@if($spherePendingRequestJoinUserCount!==0)
    <h4 style="color: red;">your request joining is pending</h4>
@elseif($sphereAcceptedRequestJoinUserCount!==0)
    <h4 style="color: blue;">your request joining accepted , you can view it from 
    <a href="{{url('/sphere/'.$sphereId.'/'.'posts')}}">here</a> </h4>

@else
    <div class="alert alert-danger">
        you can not see this sphere because you not join in it , so you can request join from
        <a href="{{url('/request-join-into-sphere/'.$userId.'/'.$sphereId)}}"> here </a>
    </div>
@endif
@endsection
