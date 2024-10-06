


@extends ("layout")
@section("content")
@if($conversationCount!==0)
<div class="card">
     <div class="card-header">
        name:   {{$conversation->title}}
     </div>
     <div class="card-body">
         desc: {{$conversation->description}}
         image:     {{$conversation->type_conversation}}
     </div>
      @if($requestJoinconversationCount!==0)
         <a href="{{url('/accept-on-request-join-conversation/'.$userId.'/'.$sphere_id.'/'.$conversation->id)}}" class="btn btn-success">Accept Request Joining</a>
         <a href="{{url('/decline-on-request-join-conversation/'.$userId.'/'.$sphere_id.'/'.$conversation->id)}}" class="btn btn-success">Decline Request Joining</a>
      @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>

@endsection
