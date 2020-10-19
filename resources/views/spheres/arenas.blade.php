@extends ("layout")
@section("content")
    <a href="{{url('/sphere/'.$sphere_id.'/conversations')}}">view conversations</a>
    <a href="{{url('/sphere/'.$sphere_id.'/events')}}">view events</a>
@endsection