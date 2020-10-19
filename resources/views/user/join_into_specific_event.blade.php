
@extends ("layout")
@section("content")

@if($eventPendingRequestJoinUserCount!==0)
    <h4 style="color: red;">your request joining is pending</h4>
@elseif($eventAcceptedRequestJoinUserCount!==0)
    <h4 style="color: blue;">your request joining accepted , you can view it from 
<a href="{{url('/view-details-event/'.$sphereId.'/'.$event->id)}}">here</a> </h4>

@else
    <div class="alert alert-danger">
        you can not see this event because you not join in it , so you can request join from
        <a href="{{url('/request-join-into-event/'.$userId.'/'.$sphereId.'/'.$eventId)}}"> here </a>
    </div>
@endif
@endsection
