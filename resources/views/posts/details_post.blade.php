@extends('layout')
@section('content')
<div id="app">
    <viewdetailspost  post_id="{{$post_id}}" user_id="{{$userId}}" postname="{{$postname}}"   />
</div>
@endsection
