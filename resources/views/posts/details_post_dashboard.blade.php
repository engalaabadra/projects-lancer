@extends('layout')
@section('content')
@if($postCount!=0)
<viewdetailspostdashboard  post_id="{{$post_id}}" user_id="{{$userId}}" postname="{{$postname}}" />
@endif
@endsection
