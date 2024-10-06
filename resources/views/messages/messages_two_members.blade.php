@extends ("layout")
@section("content")
userId:{{$user_id}}
memberId:{{$member_id}}
memberName:{{$memberName}}
<div id="app">

    <chattwomembers   userId="{{$user_id}}" memberId="{{$member_id}}" membername="{{$memberName}}"/>
</div>
@endsection