
@extends ("layout")
@section("content")
@if($projectPendingRequestJoinUserCount!==0)
    <h4 style="color: red;">your request joining is pending</h4>
@elseif($projectAcceptedRequestJoinUserCount!==0)
    <h4 style="color: blue;">your request joining accepted , you can view it from 
    <a href="{{url('/view-details-project/'.$sphereId.'/'.$project->id)}}">here</a> </h4>
@else
    <div class="alert alert-danger">
        you can not see this project because you not join in it , so you can request join from
        <a href="{{url('/request-join-into-project/'.$userId.'/'.$sphereId.'/'.$projectId)}}"> here </a>
    </div>
@endif
@endsection
